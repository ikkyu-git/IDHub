<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SamlController extends BaseController
{
    // Serve basic IdP metadata generated from the example hosted metadata
    public function metadata(Request $request)
    {
        $example = null;
        $metaFile = base_path('deploy/simplesamlphp/examples/saml20-idp-hosted.php');

        if (file_exists($metaFile)) {
            $metadata = [];
            include $metaFile; // populates $metadata
            if (!empty($metadata) && is_array($metadata)) {
                // pick first entry
                $example = reset($metadata);
            }
        }

        $entityId = url('/');
        $ssoLocation = url('/saml/login');
        $sloLocation = url('/saml/login');
        $cert = null;

        $certPath = base_path('deploy/simplesamlphp/cert/server.crt');
        if (file_exists($certPath)) {
            $pem = file_get_contents($certPath);
            // strip PEM headers
            $cert = preg_replace('/-----BEGIN CERTIFICATE-----/', '', $pem);
            $cert = preg_replace('/-----END CERTIFICATE-----/', '', $cert);
            $cert = trim(str_replace(["\r", "\n"], '', $cert));
        }

        // Basic metadata XML
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<EntityDescriptor xmlns="urn:oasis:names:tc:SAML:2.0:metadata" entityID="' . e($entityId) . '">' . "\n";
        $xml .= '  <IDPSSODescriptor protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">' . "\n";
        if ($cert) {
            $xml .= '    <KeyDescriptor use="signing">' . "\n";
            $xml .= '      <KeyInfo xmlns="http://www.w3.org/2000/09/xmldsig#">' . "\n";
            $xml .= '        <X509Data><X509Certificate>' . $cert . '</X509Certificate></X509Data>' . "\n";
            $xml .= '      </KeyInfo>' . "\n";
            $xml .= '    </KeyDescriptor>' . "\n";
        }
        $xml .= '    <SingleSignOnService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="' . e($ssoLocation) . '"/>' . "\n";
        $xml .= '    <SingleLogoutService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="' . e($sloLocation) . '"/>' . "\n";
        $xml .= '  </IDPSSODescriptor>' . "\n";
        $xml .= '</EntityDescriptor>' . "\n";

        return response($xml, 200)->header('Content-Type', 'application/samlmetadata+xml');
    }

    // Minimal login endpoint - redirect to Laravel login if not authenticated
    public function login(Request $request)
    {
        if (!auth()->check()) {
            // store intended and redirect to normal login
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login.page');
        }

        // If authenticated, show a simple selection/consent page for SPs (placeholder)
        return view('saml.loggedin', ['user' => auth()->user()]);
    }

    // Assertion Consumer Service (ACS) - accepts AuthnRequest and returns SAMLResponse
    public function acs(Request $request)
    {
        // In a full implementation we would parse the AuthnRequest XML, validate signature,
        // and use the requested ACS URL. For this dev-friendly scaffold we accept a POST
        // param `acs` for the SP's ACS URL (or use RelayState if provided).

        $acsUrl = $request->input('acs') ?: $request->input('RelayState') ?: null;
        if (!$acsUrl) {
            return response('Missing ACS URL (provide `acs` param)', 400);
        }

        if (!auth()->check()) {
            // Not authenticated - redirect to login flow
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login.page');
        }

        $user = auth()->user();

        $assertionId = 'a' . bin2hex(random_bytes(12));
        $issueInstant = gmdate('Y-m-d\TH:i:s\Z');
        $notOnOrAfter = gmdate('Y-m-d\TH:i:s\Z', time() + 300);

        $nameId = htmlspecialchars($user->email ?? $user->username ?? (string)$user->id, ENT_QUOTES, 'UTF-8');

        $attributesXml = '';
        $attributesXml .= '<AttributeStatement>';
        $attributesXml .= '<Attribute Name="uid"><AttributeValue>' . htmlspecialchars($user->id) . '</AttributeValue></Attribute>';
        $attributesXml .= '<Attribute Name="email"><AttributeValue>' . htmlspecialchars($user->email) . '</AttributeValue></Attribute>';
        $attributesXml .= '<Attribute Name="displayName"><AttributeValue>' . htmlspecialchars($user->name ?? '') . '</AttributeValue></Attribute>';
        $attributesXml .= '</AttributeStatement>';

        $responseXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $responseXml .= '<samlp:Response xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" ID="r' . $assertionId . '" Version="2.0" IssueInstant="' . $issueInstant . '">';
        $responseXml .= '<saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . e(url('/')) . '</saml:Issuer>';
        $responseXml .= '<samlp:Status><samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Success"/></samlp:Status>';

        $responseXml .= '<saml:Assertion xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion" ID="' . $assertionId . '" IssueInstant="' . $issueInstant . '" Version="2.0">';
        $responseXml .= '<saml:Issuer>' . e(url('/')) . '</saml:Issuer>';
        $responseXml .= '<saml:Subject><saml:NameID Format="urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress">' . $nameId . '</saml:NameID>';
        $responseXml .= '<saml:SubjectConfirmation Method="urn:oasis:names:tc:SAML:2.0:cm:bearer"><saml:SubjectConfirmationData NotOnOrAfter="' . $notOnOrAfter . '"/></saml:SubjectConfirmation></saml:Subject>';

        $responseXml .= '<saml:Conditions NotBefore="' . $issueInstant . '" NotOnOrAfter="' . $notOnOrAfter . '"></saml:Conditions>';

        $responseXml .= '<saml:AuthnStatement AuthnInstant="' . $issueInstant . '"><saml:AuthnContext><saml:AuthnContextClassRef>urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport</saml:AuthnContextClassRef></saml:AuthnContext></saml:AuthnStatement>';

        $responseXml .= $attributesXml;
        $responseXml .= '</saml:Assertion>';
        $responseXml .= '</samlp:Response>';

        $b64 = base64_encode($responseXml);

        // Return an auto-posting form to the SP ACS URL
        $form = '<html><body onload="document.forms[0].submit()">';
        $form .= '<form method="post" action="' . htmlspecialchars($acsUrl, ENT_QUOTES, 'UTF-8') . '">';
        $form .= '<input type="hidden" name="SAMLResponse" value="' . htmlspecialchars($b64, ENT_QUOTES, 'UTF-8') . '" />';
        if ($request->has('RelayState')) {
            $form .= '<input type="hidden" name="RelayState" value="' . htmlspecialchars($request->input('RelayState'), ENT_QUOTES, 'UTF-8') . '" />';
        }
        $form .= '<noscript><button type="submit">Continue</button></noscript>';
        $form .= '</form></body></html>';

        return response($form, 200)->header('Content-Type', 'text/html');
    }
}

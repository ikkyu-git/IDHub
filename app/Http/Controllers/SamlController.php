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
}

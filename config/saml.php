<?php
return [
    // Avoid calling url() here because config files are loaded in console
    // contexts where the request may be null. Use APP_URL as fallback.
    'entity_id' => env('SAML_ENTITY_ID', rtrim(env('APP_URL', ''), '/')),
    'assertion_consumer_service' => env('SAML_ACS_URL', rtrim(env('APP_URL', ''), '/') . '/saml/acs'),
    'certificate_path' => storage_path('saml/public.crt'),
    'private_key_path' => storage_path('saml/private.key'),
    'signature_algorithm' => env('SAML_SIGNATURE_ALG', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256'),
    'digest_algorithm' => env('SAML_DIGEST_ALG', 'http://www.w3.org/2001/04/xmlenc#sha256'),
];

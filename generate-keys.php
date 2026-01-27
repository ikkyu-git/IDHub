<?php

$opensslConfigPath = __DIR__ . '/openssl.cnf';

if (!file_exists($opensslConfigPath)) {
    die("Error: openssl.cnf not found at $opensslConfigPath\n");
}

$config = [
    'digest_alg' => 'sha256',
    'private_key_bits' => 4096,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'config' => $opensslConfigPath,
];

// Create the private and public key
$res = openssl_pkey_new($config);

if (!$res) {
    die("Error: openssl_pkey_new failed. " . openssl_error_string() . "\n");
}

// Extract the private key
if (!openssl_pkey_export($res, $privKey, null, $config)) {
    die("Error: openssl_pkey_export failed. " . openssl_error_string() . "\n");
}

// Extract the public key
$keyDetails = openssl_pkey_get_details($res);
if (!$keyDetails) {
    die("Error: openssl_pkey_get_details failed. " . openssl_error_string() . "\n");
}
$pubKey = $keyDetails['key'];

// Save the keys to files
$privPath = __DIR__ . '/storage/oauth-private.key';
$pubPath = __DIR__ . '/storage/oauth-public.key';

if (file_put_contents($privPath, $privKey) === false) {
    die("Error: Failed to write private key to $privPath\n");
}

if (file_put_contents($pubPath, $pubKey) === false) {
    die("Error: Failed to write public key to $pubPath\n");
}

echo "RSA Keys generated successfully!\n";
echo "Private Key: $privPath (" . strlen($privKey) . " bytes)\n";
echo "Public Key: $pubPath (" . strlen($pubKey) . " bytes)\n";

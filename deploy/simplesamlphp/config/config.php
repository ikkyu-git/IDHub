<?php
// SimpleSAMLphp minimal config for IdP hosted inside this repo (development/testing)
// IMPORTANT: Do not use these settings as-is in production. Store secrets outside the repo.

$config = array(
    'baseurlpath' => '/saml/',
    'certdir' => __DIR__ . '/../cert/',
    'loggingdir' => __DIR__ . '/../logs/',
    'secretsalt' => 'CHANGE_THIS_SECRET_SALT',
    'technicalcontact_name' => 'IdHub Admin',
    'technicalcontact_email' => 'ops@example.com',
    'auth.adminpassword' => 'changeme',
);

return $config;

<?php
// Define authentication sources for SimpleSAMLphp
$authsources = array(
    'laravel-userpass' => array(
        'laravelauth:Laravel',
    ),
);

return $authsources;

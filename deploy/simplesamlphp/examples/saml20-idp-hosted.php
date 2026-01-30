<?php
// ตัวอย่าง metadata ของ IdP (development/testing only)
// อย่าเก็บคีย์จริงใน repo สำหรับ production
$metadata['__DUMMY__'] = array(
    'host' => '__DUMMY__',
    'privatekey' => 'server.key',
    'certificate' => 'server.crt',
    'auth' => 'example-userpass',
    'name' => array('en' => 'IdHub SimpleSAMLphp IdP (dev)'),
    'SingleSignOnService' => 'https://saml-idp.example.com/simplesaml/module.php/saml/sp/sso.php',
    'SingleLogoutService' => 'https://saml-idp.example.com/simplesaml/module.php/saml/sp/slo.php',
);

?>

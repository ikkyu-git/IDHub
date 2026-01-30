<?php
/**
 * SimpleSAMLphp auth source that bootstraps the Laravel app and validates
 * credentials against the `users` table.
 *
 * NOTE: This implementation is intended for controlled environments and
 * development/testing. Bootstrapping a full Laravel app from within
 * SimpleSAMLphp has operational and security implications. Store secrets
 * and keys outside the repository in production.
 */

class SimpleSAML_Auth_Source_Laravel extends SimpleSAML_Auth_Source
{
    private $info;
    private $config;

    public function __construct($info, $config)
    {
        parent::__construct($info, $config);
        $this->info = $info;
        $this->config = $config;
    }

    public function authenticate(&$state)
    {
        // Expect POSTed username/password from SimpleSAML login form.
        $username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : null;
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;

        if (!$username || !$password) {
            $this->renderLoginForm(isset($state['AuthStateId']) ? $state['AuthStateId'] : null);
            exit();
        }

        // For production: call internal Laravel REST endpoint instead of bootstrapping
        $authUrl = $this->config['auth_url'] ?? 'http://localhost/internal/saml/auth';
        $internalToken = $this->config['internal_token'] ?? 'CHANGE_ME_INTERNAL_TOKEN';

        $payload = json_encode([
            'username' => $username,
            'password' => $password,
        ]);

        $ch = curl_init($authUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Internal-Token: ' . $internalToken,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err || $code !== 200) {
            $this->renderLoginFormWithError('Authentication service unavailable');
            exit();
        }

        $data = json_decode($resp, true);
        if (!is_array($data) || empty($data['ok']) || empty($data['attributes'])) {
            $this->renderLoginFormWithError('Invalid credentials');
            exit();
        }

        $state['Attributes'] = $data['attributes'];
        return;
    }

    private function findLaravelBase($start)
    {
        $dir = $start;
        for ($i = 0; $i < 8; $i++) {
            if (file_exists($dir . '/vendor/autoload.php') && file_exists($dir . '/bootstrap/app.php')) {
                return $dir;
            }
            $parent = dirname($dir);
            if ($parent === $dir) break;
            $dir = $parent;
        }
        return false;
    }

    private function renderLoginForm($stateId = null)
    {
        $action = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
        echo '<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>';
        echo '<h1>Login (IdHub)</h1>';
        echo '<form method="post" action="' . $action . '">';
        echo '<label>Username or email: <input name="username" type="text" /></label><br/>';
        echo '<label>Password: <input name="password" type="password" /></label><br/>';
        if ($stateId) {
            echo '<input type="hidden" name="AuthStateId" value="' . htmlspecialchars($stateId, ENT_QUOTES, 'UTF-8') . '" />';
        }
        echo '<button type="submit">Login</button>';
        echo '</form></body></html>';
    }

    private function renderLoginFormWithError($error)
    {
        echo '<p style="color:red;">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
        $this->renderLoginForm();
    }

    private function error($msg)
    {
        throw new Exception($msg);
    }
}

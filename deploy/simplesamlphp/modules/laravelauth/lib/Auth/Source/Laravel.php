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

        // Bootstrap Laravel by locating vendor/autoload.php and bootstrap/app.php
        $laravelBase = $this->findLaravelBase(dirname(__DIR__, 6));
        if (!$laravelBase) {
            $this->error('Could not find Laravel application to bootstrap (vendor/autoload.php).');
        }

        require $laravelBase . '/vendor/autoload.php';
        $app = require $laravelBase . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Use the User model to find and validate the user
        try {
            $userModel = \App\Models\User::where('email', $username)->orWhere('username', $username)->first();
            if (!$userModel) {
                $this->renderLoginFormWithError('Invalid credentials');
                exit();
            }

            // Laravel hashes are compatible with password_verify for bcrypt
            if (!password_verify($password, $userModel->password)) {
                $this->renderLoginFormWithError('Invalid credentials');
                exit();
            }

            // Build attributes to return to SP
            $attributes = array();
            $attributes['uid'] = array((string) $userModel->id);
            $attributes['email'] = array($userModel->email);
            $attributes['displayName'] = array($userModel->name ?? '');
            $attributes['givenName'] = array($userModel->first_name ?? '');
            $attributes['sn'] = array($userModel->last_name ?? '');

            // Roles (if relationship exists)
            try {
                if (method_exists($userModel, 'roles')) {
                    $roles = $userModel->roles->pluck('slug')->toArray();
                    $attributes['roles'] = $roles;
                }
            } catch (\Exception $e) {
                // ignore roles mapping failures
            }

            // Successful authentication - set attributes in state
            $state['Attributes'] = $attributes;
            return;
        } catch (\Exception $e) {
            $this->error('Authentication error: ' . $e->getMessage());
        }
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

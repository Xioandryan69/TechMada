# Login en admin

- Sur la page /home, cliquer sur le lien "Administartion"

## Donnés pour se loger en admin

```sql
INSERT INTO users (nom, prenom, email, password, genre, date_naissance, role) VALUES
('Admin',        'Super',    'admin@regime.mg',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'H', '1985-01-01', 'admin'); -- le mot de pass hashé est "password"
```
## Controller d'authentification admin

```php
namespace App\Controllers;

class AdminAuth extends BaseController
{
    public function login()
    {
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/dashboard'); 
        }

        return view('admin/login');
    }

    public function loginCheck()
    {
        $session = session();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new \App\Models\UserModel();
        $admin = $userModel->where('email', $username)
                           ->where('role', 'admin')
                           ->first();

        // On vérifie avec password_verify car le mot de passe est haché dans la BDD (password)
        if ($admin && password_verify($password, $admin['password'])) {
            
            $ses_data = [
                'id'              => $admin['id'],
                'username'        => $admin['nom'] . ' ' . $admin['prenom'],
                'email'           => $admin['email'],
                'isAdminLoggedIn' => true
            ];
            $session->set($ses_data);
            
            return redirect()->to('/admin/dashboard');

        } else {
            $session->setFlashdata('error', 'Email ou mot de passe incorrect, ou accès non autorisé.');
            return redirect()->to('/admin/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        
        return redirect()->to('/admin/login');
    }
}
```


## Filtre admin

```php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // On ne fait rien après
    }
}
```

## Routes relatifs aux fonctions

```php 
$routes->get('/', 'Home::index');
// --- Routes pour l'authentification Admin ---

// Afficher le formulaire
$routes->get('admin/login', 'AdminAuth::login');

// Traiter le formulaire
$routes->post('admin/loginCheck', 'AdminAuth::loginCheck');

// Se déconnecter
$routes->get('admin/logout', 'AdminAuth::logout');

$routes->get('admin/dashboard', 'AdminDashboard::index', ['filter' => 'adminAuth']);
```
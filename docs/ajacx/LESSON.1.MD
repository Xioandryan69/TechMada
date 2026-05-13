# CodeIgniter — Routes → Controller → Model → JSON → View

Architecture complète et circulation des données

---

# 1. Vue globale architecture MVC

Dans [CodeIgniter](https://codeigniter.com?utm_source=chatgpt.com) :

```text id="g9e7ll"
Navigateur
    ↓
routes.php
    ↓
Controller
    ↓
Model
    ↓
Base de données
    ↓
Controller
    ↓
JSON ou View
    ↓
Navigateur
```

---

# 2. Rôle de chaque composant

| Élément    | Rôle                   |
| ---------- | ---------------------- |
| routes.php | Router URL             |
| Controller | Chef d’orchestre       |
| Model      | Accès base             |
| View       | Affichage              |
| JSON       | Communication API/AJAX |

---

# 3. Contexte réel

Exemple :

```text id="j9gxsa"
URL :
/users/wallet/check
```

Flow :

```text id="9n3m4q"
Route → WalletController → WalletModel → DB → JSON
```

---

# 4. routes.php

Fichier :

```text id="4nspzn"
app/Config/Routes.php
```

---

# 5. Exemple routes GET

```php id="xrz7h8"
$routes->get('/', 'Home::index');

$routes->get(
    '/users',
    'UserController::index'
);

$routes->get(
    '/wallet',
    'WalletController::index'
);
```

---

# Explication

```text id="i79g70"
GET /wallet
↓
WalletController::index()
```

---

# 6. Exemple routes POST

```php id="wk65s6"
$routes->post(
    '/wallet/checkCode',
    'WalletController::checkCode'
);

$routes->post(
    '/login',
    'AuthController::login'
);
```

---

# Pourquoi POST ?

POST sert à :

* envoyer données
* formulaire
* connexion
* paiement
* wallet

---

# 7. Route avec paramètre

```php id="v6x4vq"
$routes->get(
    '/user/(:num)',
    'UserController::show/$1'
);
```

---

# Explication

```text id="t9zg08"
URL :
/user/5

↓

show(5)
```

---

# 8. Structure projet CodeIgniter

```text id="g0tq1q"
app/
│
├── Controllers/
│   ├── UserController.php
│   └── WalletController.php
│
├── Models/
│   ├── UserModel.php
│   └── WalletModel.php
│
├── Views/
│   ├── users/
│   └── wallet/
│
└── Config/
    └── Routes.php
```

---

# 9. Controller simple

---

## UserController.php

```php id="7a3qk3"
<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        return view('users/index');
    }
}
```

---

# Explication

```text id="f6vr6o"
Route appelle Controller
Controller appelle View
```

---

# 10. Controller avec données

---

## Controller

```php id="hcxm8k"
<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        $data = [

            'nom' => 'Rakoto',

            'age' => 20
        ];

        return view(
            'users/index',
            $data
        );
    }
}
```

---

## View

```php id="30m5vm"
<h1>
    <?= $nom ?>
</h1>

<p>
    <?= $age ?>
</p>
```

---

# Flux des données

```text id="4h1g5j"
Controller
    ↓
$data
    ↓
View
```

---

# 11. Controller + Model

---

## Model

```php id="vq4m1v"
<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
}
```

---

## Controller

```php id="n35lh4"
<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $users = $userModel->findAll();

        return view(
            'users/index',
            [
                'users' => $users
            ]
        );
    }
}
```

---

## View

```php id="f8m3mm"
<?php foreach($users as $user): ?>

    <p>
        <?= $user['nom'] ?>
    </p>

<?php endforeach; ?>
```

---

# 12. Controller JSON API

Très important pour AJAX.

---

## WalletController.php

```php id="ahh5vv"
<?php

namespace App\Controllers;

class WalletController extends BaseController
{
    public function checkCode()
    {
        return $this->response->setJSON([

            'success' => true,

            'message' => 'Code valide',

            'amount' => 5000
        ]);
    }
}
```

---

# Résultat JSON

```json id="o2vq8u"
{
    "success": true,
    "message": "Code valide",
    "amount": 5000
}
```

---

# 13. JavaScript AJAX vers Controller

---

## JS

```javascript id="2xttq0"
fetch('/wallet/checkCode', {

    method: 'POST'

})
.then(response => response.json())

.then(data => {

    console.log(data);

});
```

---

# Flux AJAX

```text id="2xh6r5"
JavaScript
    ↓
Route
    ↓
Controller
    ↓
JSON
    ↓
JavaScript
```

---

# 14. Récupérer POST dans Controller

---

## JSON Input

```php id="k7u80k"
$data = $this->request->getJSON(true);

$code = $data['code'];
```

---

# Exemple complet

```php id="q5j7zc"
public function checkCode()
{
    $data =
        $this->request->getJSON(true);

    $code = $data['code'];

    if($code == "GOLD123")
    {
        return $this->response->setJSON([

            'success' => true,

            'message' => 'Code valide'
        ]);
    }

    return $this->response->setJSON([

        'success' => false,

        'message' => 'Code invalide'
    ]);
}
```

---

# 15. Formulaire classique vers Controller

---

## HTML

```html id="0w2l8x"
<form action="/login" method="POST">

    <input type="email" name="email">

    <button>
        Envoyer
    </button>

</form>
```

---

## Controller

```php id="9zxk36"
$email =
    $this->request->getPost('email');
```

---

# 16. Différence JSON vs View

| JSON    | View      |
| ------- | --------- |
| API     | HTML      |
| AJAX    | Interface |
| Mobile  | Site web  |
| Données | Affichage |

---

# 17. Controller REST API

---

## Exemple API

```php id="z9gn7p"
public function users()
{
    $users = [

        [
            'nom' => 'Rakoto'
        ],

        [
            'nom' => 'Rabe'
        ]
    ];

    return $this->response
        ->setJSON($users);
}
```

---

# API URL

```text id="rltc6j"
GET /api/users
```

---

# Réponse

```json id="w9sm9r"
[
    {
        "nom": "Rakoto"
    },
    {
        "nom": "Rabe"
    }
]
```

---

# 18. Filtrage dans Controller

Très utilisé.

---

# Exemple filtre recherche

```php id="0a6w8u"
public function search()
{
    $keyword =
        $this->request->getGet('keyword');

    $userModel = new UserModel();

    $users = $userModel
        ->like('nom', $keyword)
        ->findAll();

    return $this->response
        ->setJSON($users);
}
```

---

# URL

```text id="25xq2z"
GET /users/search?keyword=ra
```

---

# 19. JavaScript filtre temps réel

```javascript id="5hnlta"
fetch('/users/search?keyword=' + mot)

.then(response => response.json())

.then(data => {

    console.log(data);

});
```

---

# 20. Routes filtre

```php id="d73nqg"
$routes->get(
    '/users/search',
    'UserController::search'
);
```

---

# 21. MVC complet réel

```text id="nsqqjj"
1. User tape recherche
2. JS fetch()
3. Route appelée
4. Controller reçoit
5. Model SQL
6. DB retourne données
7. Controller JSON
8. JS affiche
```

---

# 22. Exemple SQL réel

---

## Model

```php id="4lr9xv"
$users = $this
    ->where('status', 1)
    ->findAll();
```

---

# SQL généré

```sql id="q4yqoq"
SELECT *
FROM users
WHERE status = 1
```

---

# 23. Bonnes pratiques Controller

---

## Controller doit :

* recevoir requête
* valider
* appeler model
* retourner réponse

---

## Controller NE doit PAS :

* écrire SQL complexe
* écrire HTML énorme
* logique métier énorme

---

# 24. Séparation logique

| Couche     | Travail    |
| ---------- | ---------- |
| View       | afficher   |
| Controller | coordonner |
| Model      | données    |

---

# 25. Exemple architecture Wallet Gold

```text id="2n8fkr"
Wallet Page
    ↓
AJAX POST
    ↓
/wallet/checkCode
    ↓
WalletController
    ↓
WalletModel
    ↓
wallet_codes table
    ↓
JSON response
    ↓
Interface update
```

---

# 26. Controller professionnel

---

## Mauvais

```php id="j39yk1"
echo json_encode($data);
die();
```

---

## Bon

```php id="o6ks5y"
return $this->response
    ->setJSON($data);
```

---

# 27. Validation Controller

---

## Exemple

```php id="l7ydyy"
if(empty($code))
{
    return $this->response
        ->setJSON([

            'success' => false,

            'message' => 'Code requis'
        ]);
}
```

---

# 28. Status HTTP

```php id="hxy1dv"
return $this->response
    ->setStatusCode(404)
    ->setJSON([
        'message' => 'Introuvable'
    ]);
```

---

# 29. Diagramme professionnel

```text id="tnm7lj"
+------------+
| Browser    |
+------------+
       |
       v
+------------+
| Routes.php |
+------------+
       |
       v
+------------+
| Controller |
+------------+
       |
       v
+------------+
| Model      |
+------------+
       |
       v
+------------+
| Database   |
+------------+
       |
       v
+------------+
| JSON/View  |
+------------+
```

---

# 30. Philosophie MVC

> “Le Model connaît les données.
> Le View connaît l’apparence.
> Le Controller connaît le chemin.”

---

# 31. Ressources officielles

## Documentation officielle CodeIgniter

[CodeIgniter User Guide](https://codeigniter4.github.io/userguide/?utm_source=chatgpt.com)

---

## Controllers

[CodeIgniter Controllers](https://codeigniter4.github.io/userguide/incoming/controllers.html?utm_source=chatgpt.com)

---

## Routing

[CodeIgniter Routing](https://codeigniter4.github.io/userguide/incoming/routing.html?utm_source=chatgpt.com)

---

## Models

[CodeIgniter Models](https://codeigniter4.github.io/userguide/models/model.html?utm_source=chatgpt.com)

---

## Responses JSON

[CodeIgniter API Response Trait](https://codeigniter4.github.io/userguide/outgoing/api_responses.html?utm_source=chatgpt.com)

---

# 32. Résumé ultra-court

```text id="ltcl9z"
Route dirige
Controller contrôle
Model récupère
JSON transporte
View affiche
```

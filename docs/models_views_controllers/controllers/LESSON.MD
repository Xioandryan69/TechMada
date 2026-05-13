Voici une **documentation propre et professionnelle CodeIgniter 4** pour comprendre clairement :

* 👉 Model (rôle)
* 👉 Requêtes (Query Builder)
* 👉 Controller (rôle + logique)
* 👉 Exemple complet MVC réel

---

# 1. ARCHITECTURE MVC (CodeIgniter)

```text id="mvc1"
Controller  → reçoit la requête utilisateur
    ↓
Model       → parle à la base de données
    ↓
Database    → stocke les données
    ↓
View        → affiche les résultats
```

---

# 2. RÔLE DE CHAQUE PARTIE

## 🔵 MODEL

👉 Sert à gérer les données

Il fait :

* SELECT
* INSERT
* UPDATE
* DELETE
* JOINTURES

❌ Il ne doit PAS gérer HTML
❌ Il ne doit PAS gérer les formulaires

---

## 🟢 CONTROLLER

👉 C’est le chef d’orchestre

Il fait :

* reçoit la requête HTTP
* appelle le Model
* traite les données
* envoie vers View ou JSON

---

## 🟣 VIEW

👉 Affichage (HTML)

---

# 3. EXEMPLE MODEL (Users)

```php id="m1"
<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'genre'
    ];
}
```

---

# 4. EXEMPLE REQUÊTES MODEL

## SELECT

```php id="m2"
public function getAllUsers()
{
    return $this->findAll();
}
```

---

## SELECT WHERE

```php id="m3"
public function getUserByEmail($email)
{
    return $this->where('email', $email)->first();
}
```

---

## INSERT

```php id="m4"
public function createUser($data)
{
    return $this->insert($data);
}
```

---

## UPDATE

```php id="m5"
public function updateUser($id, $data)
{
    return $this->update($id, $data);
}
```

---

## DELETE

```php id="m6"
public function deleteUser($id)
{
    return $this->delete($id);
}
```

---

# 5. EXEMPLE JOINTURE MODEL

```php id="m7"
public function getUsersWithHealth()
{
    return $this->db
        ->table('users u')
        ->select('u.*, h.poids, h.taille, h.imc')
        ->join('user_health h', 'h.user_id = u.id')
        ->get()
        ->getResultArray();
}
```

---

# 6. EXEMPLE CONTROLLER

```php id="c1"
<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        $data['users'] = $model->getAllUsers();

        return view('users/list', $data);
    }
}
```

---

# 7. CONTROLLER + INSERT (FORMULAIRE)

```php id="c2"
public function create()
{
    $model = new UserModel();

    $data = [
        'nom' => $this->request->getPost('nom'),
        'prenom' => $this->request->getPost('prenom'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash(
            $this->request->getPost('password'),
            PASSWORD_DEFAULT
        )
    ];

    $model->insert($data);

    return redirect()->to('/users');
}
```

---

# 8. CONTROLLER + REQUÊTE COMPLEXE

```php id="c3"
public function profil($id)
{
    $model = new UserModel();

    $user = $model
        ->where('id', $id)
        ->first();

    return view('users/profil', [
        'user' => $user
    ]);
}
```

---

# 9. FLUX COMPLET (IMPORTANT)

## Exemple : afficher utilisateurs avec IMC

```text id="flow1"
Browser → Controller → Model → Database → Model → Controller → View
```

---

# 10. EXEMPLE PROJET RÉEL

## Controller IMC

```php id="c4"
public function imc($user_id)
{
    $model = new UserHealthModel();

    $health = $model->where('user_id', $user_id)->first();

    if (!$health) {
        return redirect()->back();
    }

    return view('imc/result', [
        'imc' => $health['imc'],
        'categorie' => $model->categorieIMC($health['imc'])
    ]);
}
```

---

# 11. BONNES PRATIQUES PRO

## ✔ Controller doit :

* rester simple
* appeler Model
* ne pas contenir SQL
* ne pas contenir logique complexe

---

## ✔ Model doit :

* contenir SQL
* gérer relations
* logique métier simple

---

## ❌ À éviter

```php id="bad1"
$sql = "SELECT * FROM users"; // dans controller ❌
```

---

# 12. Résumé professionnel

| Couche     | Rôle                |
| ---------- | ------------------- |
| Controller | logique application |
| Model      | données + SQL       |
| View       | affichage           |

---

# 13. Ton niveau actuel

Ton projet IMC + régime :

✔ déjà niveau intermédiaire avancé
✔ relationnel correct
✔ jointures possibles
✔ architecture MVC respectée

---

# 14. Si tu veux passer niveau pro

Je peux te générer :

🔥 API REST complète (JSON)
🔥 Service layer (business logic propre)
🔥 Architecture propre Clean Architecture
🔥 Diagramme UML de ton projet
🔥 Auth JWT CodeIgniter

Dis-moi 👍

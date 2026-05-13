Oui. Le framework PHP moderne est : [CodeIgniter 4 Official Documentation](https://codeigniter4.github.io/userguide/?utm_source=chatgpt.com)

Et l’ancien framework historique :

[CodeIgniter 3 Documentation](https://codeigniter.com/userguide3/?utm_source=chatgpt.com)

---

# Différence importante

| Version       | Architecture                          |
| ------------- | ------------------------------------- |
| CodeIgniter 3 | ancien style PHP                      |
| CodeIgniter 4 | moderne (MVC + Composer + namespaces) |

Aujourd’hui il faut apprendre surtout :

* CodeIgniter 4
* MVC
* Routes
* Models
* Controllers
* Migration
* Seeder
* Authentication

---

# Installation

## Avec Composer

```bash
composer create-project codeigniter4/appstarter projetCI4
```

---

# Lancer serveur

```bash
php spark serve
```

Puis :

```text
http://localhost:8080
```

---

# Structure MVC

```text
app/
├── Controllers/
├── Models/
├── Views/
├── Config/
├── Database/
```

---

# Architecture

```text
Navigateur
    ↓
Route
    ↓
Controller
    ↓
Model
    ↓
Database
    ↓
View
    ↓
HTML
```

---

# Documentation ultra utile

## Tutoriel officiel

[CodeIgniter 4 Tutorial](https://codeigniter4.github.io/userguide/tutorial/index.html?utm_source=chatgpt.com)

---

# Database

[CodeIgniter Database Guide](https://codeigniter4.github.io/userguide/database/index.html?utm_source=chatgpt.com)

---

# Models

[CodeIgniter Models](https://codeigniter4.github.io/userguide/models/model.html?utm_source=chatgpt.com)

---

# Controllers

[CodeIgniter Controllers](https://codeigniter4.github.io/userguide/incoming/controllers.html?utm_source=chatgpt.com)

---

# Routing

[CodeIgniter Routing](https://codeigniter4.github.io/userguide/incoming/routing.html?utm_source=chatgpt.com)

---

# Query Builder

[CodeIgniter Query Builder](https://codeigniter4.github.io/userguide/database/query_builder.html?utm_source=chatgpt.com)

---

# Authentication Shield

[CodeIgniter Shield Authentication](https://shield.codeigniter.com/?utm_source=chatgpt.com)

---

# Migration

[CodeIgniter Migrations](https://codeigniter4.github.io/userguide/dbmgmt/migration.html?utm_source=chatgpt.com)

---

# Seeder

[CodeIgniter Seeder](https://codeigniter4.github.io/userguide/dbmgmt/seeds.html?utm_source=chatgpt.com)

---

# Validation

[CodeIgniter Validation](https://codeigniter4.github.io/userguide/libraries/validation.html?utm_source=chatgpt.com)

---

# Session

[CodeIgniter Session](https://codeigniter4.github.io/userguide/libraries/sessions.html?utm_source=chatgpt.com)

---

# Upload fichiers

[CodeIgniter File Upload](https://codeigniter4.github.io/userguide/libraries/uploaded_files.html?utm_source=chatgpt.com)

---

# CRUD complet

```text
CREATE
READ
UPDATE
DELETE
```

Le cœur de presque toutes les applications :

* banque
* réseau social
* gestion école
* régime alimentaire
* stock
* e-commerce

---

# Exemple MVC simple

## Route

```php
$routes->get('/users', 'UserController::index');
```

---

## Controller

```php
namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        $data['users'] = $model->findAll();

        return view('users/index', $data);
    }
}
```

---

## Model

```php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';

    protected $allowedFields = [
        'nom',
        'prenom',
        'email'
    ];
}
```

---

## View

```php
<h1>Liste Users</h1>

<?php foreach($users as $u): ?>

<p><?= $u['nom'] ?></p>

<?php endforeach; ?>
```

---

# Pourquoi CodeIgniter est apprécié

| Avantage           | Explication                |
| ------------------ | -------------------------- |
| léger              | rapide                     |
| simple             | facile débutant            |
| MVC                | architecture propre        |
| rapide à apprendre | moins complexe que Laravel |
| bon pour CRUD      | développement rapide       |

---

# Limites

| Limite                    | Explication            |
| ------------------------- | ---------------------- |
| moins moderne que Laravel | écosystème plus petit  |
| moins de packages         | communauté plus petite |
| moins “enterprise”        | que Symfony            |

---

# Pour ton projet régime alimentaire

CodeIgniter est très adapté :

* login/register
* IMC
* recommandations
* wallet
* abonnements
* dashboard admin
* CRUD

---

# Conseils architecture projet

```text
app/
├── Controllers/
│   ├── AuthController.php
│   ├── RegimeController.php
│   ├── RecommendationController.php
│
├── Models/
│   ├── UserModel.php
│   ├── RegimeModel.php
│
├── Views/
│   ├── auth/
│   ├── regimes/
│   ├── dashboard/
```

---

# Commandes utiles

## Créer controller

```bash
php spark make:controller UserController
```

---

## Créer model

```bash
php spark make:model UserModel
```

---

## Créer migration

```bash
php spark make:migration CreateUsers
```

---

# Documentation GitHub

[CodeIgniter 4 GitHub](https://github.com/codeigniter4/CodeIgniter4?utm_source=chatgpt.com)

---

# Tutoriels YouTube

## Français

[Grafikart CodeIgniter Playlist](https://www.youtube.com/results?search_query=grafikart+codeigniter+4&utm_source=chatgpt.com)

---

## Anglais

[CodeIgniter 4 Tutorials YouTube](https://www.youtube.com/results?search_query=codeigniter+4+tutorial&utm_source=chatgpt.com)

---

# Livres gratuits

[CodeIgniter User Guide PDF Style](https://codeigniter4.github.io/userguide/intro/index.html?utm_source=chatgpt.com)

---

# Citation développeur

> “Frameworks don’t replace understanding.
> They organize it.”

Un framework :

* ne remplace pas PHP
* ne remplace pas SQL
* ne remplace pas MVC

Il structure la complexité.

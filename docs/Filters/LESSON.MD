# Les `Filters` dans CodeIgniter

Dans CodeIgniter, un **Filter** est un mécanisme qui intercepte une requête HTTP **avant** ou **après** l’exécution d’un contrôleur.

C’est comparable à :

* un **gardien**
* un **middleware**
* un **sas de sécurité**
* une **couche transversale**

---

# Pourquoi utiliser un Filter ?

Un Filter sert à centraliser des comportements globaux :

| Besoin             | Exemple                  |
| ------------------ | ------------------------ |
| Vérifier connexion | utilisateur connecté ?   |
| Vérifier rôle      | admin uniquement         |
| Protection CSRF    | anti piratage formulaire |
| Logs               | enregistrer requêtes     |
| JWT/API            | vérifier token           |
| Maintenance        | site fermé               |
| Limiter accès      | IP bannie                |
| Compression        | optimisation réponse     |

---

# Architecture générale

Dans :

```bash
app/
 ├── Filters/
 │    ├── AuthFilter.php
 │    ├── AdminFilter.php
 │    └── ApiFilter.php
```

Chaque filtre :

```php
implements FilterInterface
```

---

# Namespace classique

```php
<?php

namespace App\Filters;
```

---

# Import obligatoire

```php
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
```

---

# Structure complète d’un Filter

```php
<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        
    }
}
```

---

# `implements FilterInterface`

## Signification

```php
class AuthFilter implements FilterInterface
```

Cela veut dire :

> “Cette classe est obligée de respecter le contrat FilterInterface.”

Donc elle DOIT avoir :

* `before()`
* `after()`

Sinon erreur PHP.

---

# Concept Interface

Une interface en PHP :

```php
interface Animal
{
    public function manger();
}
```

Toute classe :

```php
class Chien implements Animal
```

doit définir :

```php
public function manger()
```

---

# Dans CodeIgniter

Le framework impose :

```php
before()
after()
```

pour garantir un comportement standard.

---

# Méthode `before()`

Exécutée AVANT le contrôleur.

---

## Cycle HTTP

```text
Client
   ↓
before()
   ↓
Controller
   ↓
after()
   ↓
Response
```

---

# Exemple : vérification connexion

```php
public function before(RequestInterface $request, $arguments = null)
{
    if (!session()->get('user')) {
        return redirect()->to('/login');
    }
}
```

---

# Explication

## Si utilisateur NON connecté :

```php
return redirect()->to('/login');
```

Le contrôleur NE sera PAS exécuté.

Le filter coupe la requête.

---

# Diagramme logique

```text
Utilisateur demande /dashboard
            ↓
       AuthFilter
            ↓
 connecté ? ------ NON -----> redirect login
      |
     OUI
      ↓
DashboardController
```

---

# Méthode `after()`

Exécutée APRÈS le contrôleur.

---

# Exemple

```php
public function after(
    RequestInterface $request,
    ResponseInterface $response,
    $arguments = null
) {
    $response->setHeader('X-App', 'MonProjet');
}
```

---

# Utilité de `after()`

| Usage       | Exemple       |
| ----------- | ------------- |
| Logs        | temps requête |
| Debug       | toolbar       |
| Headers     | sécurité      |
| Compression | gzip          |
| Cache       | optimisation  |

---

# Paramètres

---

# `RequestInterface $request`

Contient :

* URL
* méthode GET/POST
* headers
* body
* IP
* cookies

---

## Exemple

```php
$request->getMethod();
```

```php
$request->getIPAddress();
```

```php
$request->getPost();
```

---

# `ResponseInterface $response`

Manipule la réponse HTTP.

---

## Exemple

```php
$response->setStatusCode(403);
```

```php
$response->setHeader('X-Test', 'ok');
```

---

# `$arguments = null`

Permet de passer des paramètres au filter.

---

# Exemple

Dans routes :

```php
'filter' => 'role:admin'
```

Dans filter :

```php
public function before(RequestInterface $request, $arguments = null)
{
    print_r($arguments);
}
```

Résultat :

```php
['admin']
```

---

# Création complète d’un Filter

---

# 1. Créer fichier

```bash
app/Filters/AuthFilter.php
```

---

# 2. Code

```php
<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('user')) {
            return redirect()->to('/login');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        
    }
}
```

---

# 3. Enregistrer dans Filters.php

Fichier :

```bash
app/Config/Filters.php
```

---

## Alias

```php
public array $aliases = [
    'auth' => \App\Filters\AuthFilter::class,
];
```

---

# Pourquoi alias ?

Au lieu d’écrire :

```php
\App\Filters\AuthFilter::class
```

on écrit simplement :

```php
auth
```

---

# Utilisation dans Routes

---

# Route unique

```php
$routes->get('/dashboard', 'Dashboard::index', [
    'filter' => 'auth'
]);
```

---

# Groupe de routes

```php
$routes->group('admin', ['filter' => 'auth'], function($routes) {

    $routes->get('dashboard', 'Admin::dashboard');

    $routes->get('users', 'Admin::users');

});
```

---

# Multiple filters

```php
'filter' => 'auth,admin'
```

---

# Ordre exécution

```text
auth → admin → controller
```

---

# Filters globaux

Dans :

```php
public array $globals
```

---

# Exemple

```php
public array $globals = [
    'before' => [
        'csrf',
    ],
    'after' => [
        'toolbar',
    ],
];
```

---

# `csrf`

Protection formulaires.

---

# `toolbar`

Debug bar de CodeIgniter.

---

# Filters par méthode HTTP

```php
public array $methods = [
    'POST' => ['csrf']
];
```

---

# Filters par URI

```php
public array $filters = [
    'auth' => [
        'before' => [
            'admin/*'
        ]
    ]
];
```

---

# Exclure certaines routes

```php
'auth' => [
    'except' => [
        '/',
        'login',
    ]
]
```

---

# Exemple avancé : RoleFilter

---

## Route

```php
'filter' => 'role:admin'
```

---

## Filter

```php
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');

        if (!in_array($role, $arguments)) {

            return redirect()->to('/forbidden');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {

    }
}
```

---

# Ce que fait `in_array`

```php
in_array($role, $arguments)
```

Vérifie si :

```php
admin
```

existe dans :

```php
['admin']
```

---

# Filter API JWT

Très utilisé REST API.

---

# Exemple logique

```text
Client API
    ↓
JWT Filter
    ↓
token valide ?
   ↓        ↓
 oui       non
 ↓          ↓
API      erreur 401
```

---

# Exemple simplifié

```php
public function before(RequestInterface $request, $arguments = null)
{
    $token = $request->getHeaderLine('Authorization');

    if (!$token) {
        return service('response')
            ->setJSON([
                'error' => 'Token manquant'
            ])
            ->setStatusCode(401);
    }
}
```

---

# Différence Filter vs Controller

| Filter      | Controller          |
| ----------- | ------------------- |
| transversal | métier              |
| sécurité    | logique application |
| global      | spécifique          |
| avant/après | pendant             |

---

# Différence Filter vs Helper

| Filter             | Helper                |
| ------------------ | --------------------- |
| intercepte requête | fonctions utilitaires |
| sécurité           | outils                |
| HTTP lifecycle     | fonctions simples     |

---

# Différence Filter vs Middleware Laravel

Très similaire.

| Laravel    | CodeIgniter |
| ---------- | ----------- |
| Middleware | Filter      |

---

# Cycle complet réel

```text
HTTP Request
    ↓
index.php
    ↓
Routes
    ↓
Before Filters
    ↓
Controller
    ↓
Model
    ↓
View
    ↓
After Filters
    ↓
HTTP Response
```

---

# Bonnes pratiques

---

## 1. Filter léger

Ne pas mettre logique métier lourde.

Mauvais :

```php
calcul IA
traitement image
requêtes énormes
```

---

## 2. Utiliser pour transversal

Parfait pour :

* auth
* logs
* csrf
* headers
* api

---

## 3. Retourner Response

Toujours :

```php
return redirect()->to(...)
```

ou :

```php
return service('response')
```

---

# Erreurs fréquentes

---

# Oublier `implements FilterInterface`

Erreur :

```text
Class must implement methods...
```

---

# Oublier `before()`

Erreur fatale.

---

# Mauvais namespace

```php
namespace App\Filter;
```

au lieu de :

```php
namespace App\Filters;
```

---

# Oublier alias

```text
Filter not found
```

---

# Philosophie

Le Filter applique le principe :

> “Séparer les préoccupations.”

Le contrôleur :

* gère le métier

Le filter :

* gère l’infrastructure HTTP

---

# Analogie réelle

```text
Filter = agent sécurité entrée immeuble
Controller = employé bureau
Model = base archives
View = écran affichage
```

---

# Citation ingénierie logicielle

> “Cross-cutting concerns should be centralized.”
>
> — Aspect Oriented Programming

Traduction :

> Les préoccupations transversales doivent être centralisées.

Les Filters sont exactement cela.

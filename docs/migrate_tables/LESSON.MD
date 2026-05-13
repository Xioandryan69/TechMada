# MVC dans [CodeIgniter 4 Official Documentation](https://codeigniter4.github.io/userguide/?utm_source=chatgpt.com)

MVC signifie :

```text id="s0o5dg"
M = Model
V = View
C = Controller
```

---

# 1. Vision globale

```text id="zq4j43"
Utilisateur
    ↓
Route
    ↓
Controller
    ↓
Model
    ↓
Base de données
    ↓
Controller
    ↓
View
    ↓
HTML envoyé au navigateur
```

---

# 2. MODEL

Le **Model** gère :

* SQL
* données
* table
* insert/update/delete/select

---

## Exemple

Table :

```sql id="zgvnwf"
users
```

---

## UserModel.php

```php id="ay01ef"
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'genre',
        'date_naissance',
        'role'
    ];
}
```

---

# Explication

| Attribut         | Rôle             |
| ---------------- | ---------------- |
| `$table`         | nom table SQL    |
| `$primaryKey`    | clé primaire     |
| `$allowedFields` | champs autorisés |

---

# Pourquoi `$allowedFields`

Protection sécurité.

Sans ça :

```php id="24p9w5"
$model->save($_POST);
```

peut injecter :

* colonnes interdites
* admin
* données sensibles

---

# 3. VIEW

La View :

* affiche HTML
* CSS
* données utilisateur

---

## Exemple

```php id="jlwm1f"
<h1>Liste utilisateurs</h1>

<?php foreach($users as $u): ?>

<p><?= $u['nom'] ?></p>

<?php endforeach; ?>
```

---

# La View ne doit PAS

❌ faire SQL
❌ logique métier complexe

---

# 4. CONTROLLER

Le cerveau.

Il :

* reçoit requête
* appelle model
* envoie données à view

---

# Exemple

```php id="a6m4mt"
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

# Étapes exactes

## Utilisateur ouvre :

```text id="8yfm5l"
/users
```

---

## Route :

```php id="3rbw8p"
$routes->get('/users', 'UserController::index');
```

---

## Controller :

```php id="cuw3dv"
UserController::index()
```

---

## Model :

```php id="x09zpv"
$model->findAll()
```

fait :

```sql id="o0a5qo"
SELECT * FROM users
```

---

## View :

reçoit :

```php id="o7mvgf"
$data['users']
```

et affiche HTML.

---

# 5. MIGRATION

Très important.

Migration =
versionner la base de données.

---

# Sans migration

Tu fais :

```sql id="4x0f35"
CREATE TABLE ...
```

manuellement.

---

# Avec migration

PHP génère les tables.

---

# Avantages

| Avantage   | Explication    |
| ---------- | -------------- |
| historique | version DB     |
| équipe     | même structure |
| rollback   | annuler        |
| DevOps     | déploiement    |

---

# Commande migration

```bash id="fy8hx1"
php spark make:migration CreateUsers
```

---

# Fichier créé

```text id="2fe7x0"
app/Database/Migrations/
```

---

# Exemple migration USERS

```php id="l1dx1v"
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],

            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],

            'prenom' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],

            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'unique' => true
            ],

            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],

            'genre' => [
                'type' => 'ENUM',
                'constraint' => ['H','F']
            ],

            'date_naissance' => [
                'type' => 'DATE'
            ],

            'role' => [
                'type' => 'ENUM',
                'constraint' => ['user','admin'],
                'default' => 'user'
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]

        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
```

---

# Comprendre `up()` et `down()`

| Fonction | Rôle     |
| -------- | -------- |
| `up()`   | crée     |
| `down()` | supprime |

---

# Exécution

```bash id="3jlwmx"
php spark migrate
```

---

# Rollback

```bash id="s4x8uj"
php spark migrate:rollback
```

---

# 6. Comment nommer tables

Convention professionnelle :

---

# Tables

## Toujours :

```text id="90pdkt"
plural
snake_case
```

---

# Bons noms

| Correct         | Mauvais        |
| --------------- | -------------- |
| users           | user           |
| user_health     | UserHealth     |
| type_regimes    | TypeRegime     |
| recommendations | recommendation |

---

# 7. Colonnes

Convention :

```text id="f9ur84"
snake_case
```

---

# Bons exemples

| Bon            | Mauvais       |
| -------------- | ------------- |
| user_id        | idUser        |
| created_at     | createdAt     |
| date_naissance | DateNaissance |

---

# 8. Foreign Keys

Convention importante :

```text id="6p6j0g"
nomtable_singulier_id
```

---

# Exemple

| Table référencée | FK          |
| ---------------- | ----------- |
| users            | user_id     |
| regimes          | regime_id   |
| activites        | activite_id |

---

# Ton projet corrigé

---

# Table users

```text id="s78g4g"
users
```

Model :

```text id="jzjlwm"
UserModel
```

Controller :

```text id="n1q7eb"
UserController
```

---

# Table regimes

```text id="7wl9fh"
regimes
```

Model :

```text id="9s1kqj"
RegimeModel
```

Controller :

```text id="awfkq8"
RegimeController
```

---

# Table recommendations

```text id="1pj0kg"
recommendations
```

Model :

```text id="lajzyx"
RecommendationModel
```

---

# 9. Convention fichiers

```text id="9mbgkk"
app/
├── Controllers/
│   ├── UserController.php
│
├── Models/
│   ├── UserModel.php
│
├── Views/
│   ├── users/
│       ├── index.php
```

---

# 10. Exemple CRUD complet

---

# CREATE

```php id="u50b42"
$model->insert($data);
```

---

# READ

```php id="z2fjlwm"
$model->findAll();
```

---

# UPDATE

```php id="e9cdq4"
$model->update($id, $data);
```

---

# DELETE

```php id="jlwmxm"
$model->delete($id);
```

---

# 11. Relation SQL

Ton projet :

```text id="wjlwmz"
users
    ↓
user_health

users
    ↓
wallet

users
    ↓
recommendations
```

---

# 12. Architecture propre projet régime

```text id="8w5qik"
app/
├── Controllers/
│   ├── AuthController.php
│   ├── RegimeController.php
│   ├── WalletController.php
│   ├── RecommendationController.php
│
├── Models/
│   ├── UserModel.php
│   ├── RegimeModel.php
│   ├── WalletModel.php
│
├── Views/
│   ├── auth/
│   ├── regimes/
│   ├── wallet/
```

---

# 13. Seeder

Seeder = données automatiques.

---

# Exemple

```bash id="e8mjlwm"
php spark make:seeder UserSeeder
```

---

# Exemple

```php id="6ggz1h"
$data = [
    'nom' => 'admin',
    'email' => 'admin@gmail.com'
];

$this->db->table('users')->insert($data);
```

---

# Lancer

```bash id="3e7h2n"
php spark db:seed UserSeeder
```

---

# 14. Différence Migration / Seeder

| Migration    | Seeder  |
| ------------ | ------- |
| structure    | données |
| CREATE TABLE | INSERT  |
| architecture | contenu |

---

# Citation architecture logicielle

> “Fat models, skinny controllers.”

Le controller doit rester léger.
La logique métier :

* calcul IMC
* recommandations
* validation

doit vivre :

* dans Model
* ou Service Layer.


## 
Ce code est une **Migration CodeIgniter 4**.

Elle sert à :

* créer une table SQL automatiquement
* versionner la base de données
* pouvoir recréer/supprimer la structure facilement

---

# Vue globale

```text id="fxnrw0"
Migration PHP
    ↓
CodeIgniter Forge
    ↓
SQL généré automatiquement
    ↓
CREATE TABLE factories(...)
```

---

# Le fichier

```php id="k9d54e"
class ExampleMigration extends Migration
```

hérite de :

```php id="hjlwm5"
CodeIgniter\Database\Migration
```

Donc :

* cette classe devient une migration officielle CI4.

---

# 1. Namespace

```php id="3x2jlwm"
namespace Tests\Support\Database\Migrations;
```

---

# Rôle

Organise les classes.

---

# Signification

| Partie     | Rôle              |
| ---------- | ----------------- |
| Tests      | dossier tests     |
| Support    | support technique |
| Database   | base de données   |
| Migrations | migrations        |

---

# Donc cette migration

sert probablement :

* aux tests automatisés
* PHPUnit
* environnement test

et non production.

---

# 2. DBGroup

```php id="jlwm3f"
protected $DBGroup = 'tests';
```

---

# Très important

CodeIgniter peut avoir plusieurs connexions :

```text id="2u4qls"
default
tests
production
dev
```

---

# Ici

la migration utilise :

```text id="qk6jlwm"
connexion "tests"
```

définie dans :

```text id="jlwm6k"
app/Config/Database.php
```

---

# Exemple

```php id="jlwm7l"
public array $tests = [
    'DSN'      => '',
    'hostname' => 'localhost',
    'username' => 'root',
];
```

---

# 3. Fonction up()

```php id="jlwm8m"
public function up(): void
```

---

# Rôle

Créer la structure.

Equivalent SQL :

```sql id="jlwm9n"
CREATE TABLE ...
```

---

# 4. addField('id')

```php id="jlwm0p"
$this->forge->addField('id');
```

---

# Raccourci magique CI4

CodeIgniter génère automatiquement :

```sql id="jlwm1q"
id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY
```

---

# Equivalent SQL complet

```sql id="jlwm2r"
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
```

---

# 5. Colonnes

```php id="jlwm3s"
'name' => ['type' => 'varchar', 'constraint' => 31]
```

---

# Traduction SQL

```sql id="jlwm4t"
name VARCHAR(31)
```

---

# Chaque champ

| Champ      | SQL          |
| ---------- | ------------ |
| name       | VARCHAR(31)  |
| uid        | VARCHAR(31)  |
| class      | VARCHAR(63)  |
| icon       | VARCHAR(31)  |
| summary    | VARCHAR(255) |
| created_at | DATETIME     |
| updated_at | DATETIME     |
| deleted_at | DATETIME     |

---

# 6. null => true

```php id="jlwm5u"
'deleted_at' => [
    'type' => 'datetime',
    'null' => true
]
```

---

# Signifie

```sql id="jlwm6v"
deleted_at DATETIME NULL
```

---

# Donc

la colonne peut contenir :

```text id="jlwm7w"
NULL
```

---

# 7. Soft Delete

Très important.

```php id="jlwm8x"
deleted_at
```

sert souvent au :

```text id="jlwm9y"
SOFT DELETE
```

---

# Différence

## DELETE classique

```sql id="jlwm0z"
DELETE FROM factories
```

supprime vraiment.

---

# Soft delete

On met :

```sql id="jlwm11"
deleted_at = NOW()
```

Donc :

* donnée conservée
* cachée

---

# Très utilisé :

* réseaux sociaux
* e-commerce
* logs
* archives

---

# 8. addKey()

```php id="jlwm22"
$this->forge->addKey('name');
```

---

# Rôle

Créer un INDEX SQL.

Equivalent :

```sql id="jlwm33"
INDEX(name)
```

---

# Pourquoi index ?

Accélère recherches.

---

# Exemple

```sql id="jlwm44"
SELECT * FROM factories
WHERE uid='abc'
```

plus rapide avec index.

---

# 9. Clé composite

```php id="jlwm55"
$this->forge->addKey(['deleted_at', 'id']);
```

---

# SQL

```sql id="jlwm66"
INDEX(deleted_at, id)
```

---

# Utilité

Optimisation requêtes :

```sql id="jlwm77"
WHERE deleted_at IS NULL
ORDER BY id
```

---

# 10. createTable()

```php id="jlwm88"
$this->forge->createTable('factories');
```

---

# SQL généré

Approximativement :

```sql id="jlwm99"
CREATE TABLE factories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(31),
    uid VARCHAR(31),
    class VARCHAR(63),
    icon VARCHAR(31),
    summary VARCHAR(255),
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL
);
```

---

# 11. down()

```php id="jlwmab"
public function down(): void
```

---

# Sert à rollback.

---

# SQL équivalent

```sql id="jlwmbc"
DROP TABLE factories;
```

---

# Donc :

| Fonction | Effet    |
| -------- | -------- |
| up()     | crée     |
| down()   | supprime |

---

# 12. Cycle migration

---

# Exécution

```bash id="jlwmcd"
php spark migrate
```

CI4 exécute :

```php id="jlmwde"
up()
```

---

# Rollback

```bash id="jlwmef"
php spark migrate:rollback
```

CI4 exécute :

```php id="jlmwfg"
down()
```

---

# 13. Forge

```php id="jlmwhg"
$this->forge
```

est un générateur SQL.

---

# Forge traduit :

```php
addField()
addKey()
createTable()
```

en SQL réel.

---

# 14. Pourquoi c’est puissant

Sans migration :

```sql id="jlmwih"
CREATE TABLE ...
```

manuel.

---

# Avec migration :

* versionnement
* équipe synchronisée
* historique DB
* rollback
* CI/CD
* DevOps

---

# 15. Table factories

Cette table semble stocker :

```text id="jlmwjj"
des "factory objects"
```

probablement :

* composants
* classes
* plugins
* objets dynamiques

---

# Exemple possible

| id | name        | class                     |
| -- | ----------- | ------------------------- |
| 1  | userFactory | App\Factories\UserFactory |

---

# 16. Analyse architecture

```text id="jlmwkk"
Migration
    ↓
Forge
    ↓
Schema SQL
    ↓
MySQL/PostgreSQL
```

---

# 17. Convention timestamps

```php id="jlmwll"
created_at
updated_at
deleted_at
```

sont standards modernes.

---

# Utilisés automatiquement par :

```php id="jlmwmm"
protected $useTimestamps = true;
protected $useSoftDeletes = true;
```

dans Model CI4.

---

# Exemple Model lié

```php id="jlmwnn"
class FactoryModel extends Model
{
    protected $table = 'factories';

    protected $useTimestamps = true;

    protected $useSoftDeletes = true;
}
```

---

# Alors CI4 remplit automatiquement :

| Champ      | Automatique |
| ---------- | ----------- |
| created_at | INSERT      |
| updated_at | UPDATE      |
| deleted_at | DELETE      |

---

# Citation architecture

> “Schema evolves. Migrations remember.”

Une base de données change constamment.
Les migrations gardent l’historique de cette évolution.

## Mis en place 
Tu ne dois **jamais modifier `system/`** dans [CodeIgniter 4](https://codeigniter.com/user_guide/?utm_source=chatgpt.com).
`system/` = le noyau du framework.
Quand tu fais `composer update`, tout peut être écrasé.

La règle :

> `app/ = TON code`
>
> `system/ = le moteur CodeIgniter`

---

# Structure correcte de ton projet

Dans ton projet :

```txt
app/
├── Config
├── Controllers
├── Database
│   ├── Migrations
│   └── Seeds
├── Models
├── Views
```

---

# Où mettre les migrations ?

Dans :

```txt
app/Database/Migrations/
```

Exemple :

```txt
app/Database/Migrations/2026-05-07-000001_CreateUsers.php
```

---

# Où mettre les seeders (insert data) ?

Dans :

```txt
app/Database/Seeds/
```

Exemple :

```txt
app/Database/Seeds/UserSeeder.php
```

---

# Où mettre reset database ?

Tu as 3 possibilités.

---

# 1. Seeder RESET

Créer un seeder :

```txt
app/Database/Seeds/ResetSeeder.php
```

Exemple :

```php
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResetSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'recommendations',
            'transactions',
            'wallet',
            'abonnements',
            'user_health',
            'user_objectifs',
            'regime_user',
            'recommendations_weight',
            'regimes',
            'activites',
            'codes',
            'objectifs',
            'type_regimes',
            'health_rules',
            'imc_categories',
            'age_ranges',
            'users'
        ];

        foreach ($tables as $table) {
            $this->db->table($table)->truncate();
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
```

---

# Exécuter

```bash
php spark db:seed ResetSeeder
```

---

# 2. Migration = CREATE TABLE

Migration sert à :

* créer tables
* modifier tables
* ajouter colonnes
* supprimer colonnes

PAS pour insérer données métier.

---

# Exemple architecture complète

```txt
app/
├── Controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   └── RegimeController.php
│
├── Models/
│   ├── UserModel.php
│   ├── RegimeModel.php
│   ├── WalletModel.php
│   └── RecommendationModel.php
│
├── Views/
│   ├── auth/
│   ├── dashboard/
│   └── regimes/
│
├── Database/
│   ├── Migrations/
│   │   ├── 2026-05-07-000001_CreateUsers.php
│   │   ├── 2026-05-07-000002_CreateWallet.php
│   │   └── 2026-05-07-000003_CreateRegimes.php
│   │
│   └── Seeds/
│       ├── UserSeeder.php
│       ├── RegimeSeeder.php
│       ├── AdminSeeder.php
│       └── ResetSeeder.php
```

---

# Cycle professionnel réel

## Étape 1 — créer migration

Commande :

```bash
php spark make:migration CreateUsers
```

CodeIgniter génère :

```txt
app/Database/Migrations/xxxx_CreateUsers.php
```

---

# Étape 2 — écrire structure SQL

Dans migration :

```php
public function up()
{
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'auto_increment' => true
        ],

        'nom' => [
            'type' => 'VARCHAR',
            'constraint' => 100
        ]
    ]);

    $this->forge->addKey('id', true);

    $this->forge->createTable('users');
}
```

---

# Étape 3 — migrer

```bash
php spark migrate
```

Effet :

```sql
CREATE TABLE users (...)
```

---

# Étape 4 — insérer données fake

Créer seeder :

```bash
php spark make:seeder UserSeeder
```

---

# Étape 5 — remplir

```php
$this->db->table('users')->insert([
    'nom' => 'Jean'
]);
```

---

# Étape 6 — exécuter

```bash
php spark db:seed UserSeeder
```

---

# Migration vs Seeder

| Élément    | Rôle         |
| ---------- | ------------ |
| Migration  | Structure BD |
| Seeder     | Données      |
| Model      | Requêtes SQL |
| Controller | Logique      |
| View       | HTML         |

---

# Ton SQL actuel

Ton énorme SQL doit être séparé en :

## Migrations

Une migration par table :

```txt
CreateUsers
CreateWallet
CreateTransactions
CreateRegimes
```

---

# Seeders

```txt
AdminSeeder
FakeUserSeeder
ImcSeeder
HealthRulesSeeder
```

---

# Très important

Ordre des foreign keys :

```txt
users
↓
wallet
↓
transactions
```

Sinon erreur :

```txt
Cannot add foreign key constraint
```

---

# Commandes importantes

## Migrer

```bash
php spark migrate
```

---

## Voir status

```bash
php spark migrate:status
```

---

## Reset complet

```bash
php spark migrate:refresh
```

Effet :

```txt
down()
up()
```

donc :

```sql
DROP TABLE
CREATE TABLE
```

---

# Seeder après refresh

```bash
php spark db:seed MainSeeder
```

---

# MainSeeder professionnel

```php
class MainSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdminSeeder');
        $this->call('ImcSeeder');
        $this->call('HealthSeeder');
    }
}
```

Puis :

```bash
php spark db:seed MainSeeder
```

---

# En résumé

## Ne touche jamais :

```txt
system/
vendor/
node_modules/
```

---

# Toi tu travailles surtout dans :

```txt
app/
public/
writable/
```

---

# Philosophie MVC

```txt
Utilisateur
    ↓
Route
    ↓
Controller
    ↓
Model
    ↓
Database
    ↓
Retour View
```

---

# Exemple concret

Utilisateur :

```txt
/login
```

↓

Route :

```php
$routes->get('/login', 'AuthController::index');
```

↓

Controller :

```php
return view('auth/login');
```

↓

View :

```html
<form>
```

---

Proverbe développeur :

> “Un bon développeur sépare la structure, les données et la logique.”
>
> Sinon le projet devient un “plat de spaghetti cosmique”. 🍝

# Documentation — Models, Jointures et Validation dans [CodeIgniter 4](https://codeigniter.com?utm_source=chatgpt.com)

---

# 1. Qu’est-ce qu’un Model ?

Un **Model** représente :

* une table SQL
* les opérations base de données
* la logique métier liée aux données

Architecture MVC :

```text id="wzkjv0"
Controller
    ↓
Model
    ↓
Database
```

---

# 2. Création d’un Model

Structure :

```php id="jx8ogj"
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
        'email'
    ];
}
```

---

# 3. Explication des propriétés

| Propriété        | Rôle                           |
| ---------------- | ------------------------------ |
| `$table`         | table SQL                      |
| `$primaryKey`    | clé primaire                   |
| `$returnType`    | array ou object                |
| `$allowedFields` | champs autorisés insert/update |

---

# 4. CRUD de base

## INSERT

```php id="c0ehlj"
$model->insert([
    'nom' => 'Rakoto',
    'email' => 'rakoto@test.mg'
]);
```

---

## UPDATE

```php id="v9f2g9"
$model->update(1, [
    'nom' => 'Jean'
]);
```

---

## DELETE

```php id="0xblv5"
$model->delete(1);
```

---

## FIND

```php id="5qls8o"
$user = $model->find(1);
```

---

## FIND ALL

```php id="5p9k1h"
$users = $model->findAll();
```

---

# 5. WHERE

```php id="f1mm6n"
$model
    ->where('genre', 'H')
    ->findAll();
```

SQL :

```sql id="jv8yru"
SELECT * FROM users
WHERE genre = 'H'
```

---

# 6. ORDER BY

```php id="c90d6n"
$model
    ->orderBy('nom', 'ASC')
    ->findAll();
```

---

# 7. LIMIT

```php id="n9bqwy"
$model
    ->limit(5)
    ->findAll();
```

---

# 8. Jointures (JOIN)

Très important.

---

# INNER JOIN

```php id="ux8m1z"
return $this->db
    ->table('recommendations r')

    ->join(
        'users u',
        'u.id = r.user_id'
    )

    ->get()

    ->getResultArray();
```

SQL :

```sql id="v3b0ft"
SELECT *
FROM recommendations r
INNER JOIN users u
ON u.id = r.user_id
```

---

# LEFT JOIN

```php id="l1szzg"
->join(
    'activites a',
    'a.id = r.activite_id',
    'left'
)
```

Même si activité absente → résultat affiché.

---

# Plusieurs JOIN

```php id="9jlcc5"
return $this->db
    ->table('recommendations r')

    ->select('
        r.*,

        u.nom,
        u.prenom,

        reg.nom AS regime_nom,

        a.nom AS activite_nom
    ')

    ->join(
        'users u',
        'u.id = r.user_id'
    )

    ->join(
        'regimes reg',
        'reg.id = r.regime_id'
    )

    ->join(
        'activites a',
        'a.id = r.activite_id',
        'left'
    )

    ->get()

    ->getResultArray();
```

---

# 9. Alias SQL

Très utilisé.

```php id="q7qlsp"
users u
```

signifie :

```text id="pmym3u"
table users renommée u
```

---

# 10. SELECT personnalisé

```php id="5v54b6"
->select('
    users.nom,
    users.email
')
```

---

# 11. COUNT

```php id="8sbn4h"
$model->countAll();
```

---

# COUNT avec WHERE

```php id="jlwm7z"
$model
    ->where('genre', 'F')
    ->countAllResults();
```

---

# 12. ValidationRules

Très important dans [CodeIgniter 4](https://codeigniter.com?utm_source=chatgpt.com).

Permet :

* sécuriser données
* empêcher valeurs invalides
* valider automatiquement

---

# Structure

```php id="jlwm1x"
protected $validationRules = [
    'nom' => 'required|min_length[3]',
    'email' => 'required|valid_email'
];
```

---

# 13. Règles principales

| Règle           | Description    |
| --------------- | -------------- |
| required        | obligatoire    |
| valid_email     | email valide   |
| integer         | entier         |
| decimal         | nombre décimal |
| min_length[x]   | taille minimum |
| max_length[x]   | taille maximum |
| greater_than[x] | supérieur      |
| less_than[x]    | inférieur      |
| is_unique       | unique en DB   |

---

# Exemple complet

```php id="m2t9vl"
protected $validationRules = [

    'nom' => 'required|min_length[2]|max_length[100]',

    'email' => '
        required|
        valid_email|
        is_unique[users.email]
    ',

    'poids' => '
        required|
        decimal|
        greater_than[0]
    ',

    'taille' => '
        required|
        decimal|
        greater_than[0]
    '
];
```

---

# 14. Messages personnalisés

```php id="5q7d1f"
protected $validationMessages = [

    'email' => [

        'valid_email' =>
            'Email invalide',

        'is_unique' =>
            'Email déjà utilisé'
    ]
];
```

---

# 15. Vérifier erreurs validation

```php id="jlwm1a"
if (!$model->insert($data)) {

    $errors = $model->errors();

    print_r($errors);
}
```

---

# 16. Exemple Model professionnel

```php id="e5m4bn"
<?php

namespace App\Models;

use CodeIgniter\Model;

class UserHealthModel extends Model
{
    protected $table = 'user_health';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id',
        'taille',
        'poids',
        'imc'
    ];

    protected $validationRules = [

        'taille' => '
            required|
            decimal|
            greater_than[0]
        ',

        'poids' => '
            required|
            decimal|
            greater_than[0]
        '
    ];

    protected $validationMessages = [

        'taille' => [

            'required' =>
                'La taille est obligatoire',

            'greater_than' =>
                'La taille doit être positive'
        ]
    ];
}
```

---

# 17. Relations SQL importantes

## One To Many

```text id="wwt2al"
1 user
→ plusieurs recommendations
```

SQL :

```text id="jlwmzq"
recommendations.user_id
```

---

## Many To Many

```text id="jlwm2f"
users
↔
regimes
```

via table pivot :

```text id="o6x8t8"
user_regimes
```

---

# 18. Builder vs Model

## Builder

```php id="ljwmk9"
$this->db->table(...)
```

Permet :

* joins
* select complexe
* SQL avancé

---

## Model

Permet :

* CRUD rapide
* validation
* logique métier

Souvent utilisés ensemble.

---

# 19. Bonnes pratiques

## Toujours

✔ validationRules
✔ allowedFields
✔ primaryKey
✔ returnType

---

## Éviter

❌ `SELECT *`

Préférer :

```php id="jlwmq2"
->select('id, nom')
```

---

## Utiliser alias

```php id="2h4sja"
users u
```

plus lisible.

---

# 20. Exemple jointure complète

```php id="jlwm5d"
public function getRecommendations(): array
{
    return $this->db

        ->table('recommendations r')

        ->select('
            r.id,

            u.nom,
            u.prenom,

            reg.nom AS regime,

            a.nom AS activite
        ')

        ->join(
            'users u',
            'u.id = r.user_id'
        )

        ->join(
            'regimes reg',
            'reg.id = r.regime_id'
        )

        ->join(
            'activites a',
            'a.id = r.activite_id',
            'left'
        )

        ->get()

        ->getResultArray();
}
```

---

# 21. Requête générée

```sql id="jlwm65"
SELECT
    r.id,
    u.nom,
    u.prenom,
    reg.nom AS regime,
    a.nom AS activite

FROM recommendations r

INNER JOIN users u
ON u.id = r.user_id

INNER JOIN regimes reg
ON reg.id = r.regime_id

LEFT JOIN activites a
ON a.id = r.activite_id
```

---

# Documentation officielle

## Models

[CodeIgniter Models Documentation](https://codeigniter.com/user_guide/models/model.html?utm_source=chatgpt.com)

---

## Query Builder

[CodeIgniter Query Builder](https://codeigniter.com/user_guide/database/query_builder.html?utm_source=chatgpt.com)

---

## Validation

[CodeIgniter Validation](https://codeigniter.com/user_guide/libraries/validation.html?utm_source=chatgpt.com)

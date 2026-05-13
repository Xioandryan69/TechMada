# Documentation AJAX + JavaScript + PHP + JSON

Guide pratique pour projet web (`HTML + JavaScript + PHP`)

---

# 1. Contexte

Quand un utilisateur clique sur un bouton :

* sans AJAX → la page recharge
* avec AJAX → échange de données en arrière-plan

Exemple :

```text
Utilisateur clique → JS envoie requête → PHP traite → JSON retourne → HTML se met à jour
```

Architecture :

```text
HTML (interface)
    ↓
JavaScript AJAX
    ↓
PHP Controller/API
    ↓
Base de données
    ↓
JSON Response
    ↓
JavaScript met à jour la page
```

---

# 2. Pourquoi AJAX ?

## Sans AJAX

```text
Clique → Rechargement total
```

Problèmes :

* lent
* mauvaise UX
* perte du scroll
* formulaire rechargé

---

## Avec AJAX

```text
Clique → données seulement
```

Avantages :

* rapide
* fluide
* moderne
* temps réel
* validation instantanée

---

# 3. Technologies utilisées

| Technologie | Rôle            |
| ----------- | --------------- |
| HTML        | Interface       |
| CSS         | Style           |
| JavaScript  | Dynamique       |
| AJAX        | Communication   |
| JSON        | Format données  |
| PHP         | Backend         |
| MySQL       | Base de données |

---

# 4. Qu'est-ce que JSON ?

JSON = JavaScript Object Notation

Format léger d’échange de données.

---

## Objet JSON

```json
{
    "nom": "Rakoto",
    "age": 20
}
```

---

## Tableau JSON

```json
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

# 5. Méthodes HTTP

| Méthode | Utilisation     |
| ------- | --------------- |
| GET     | Lire données    |
| POST    | Envoyer données |
| PUT     | Modifier        |
| DELETE  | Supprimer       |

---

# 6. AJAX avec XMLHttpRequest

Ancienne méthode mais importante.

---

## HTML

```html
<button onclick="charger()">Charger</button>

<div id="resultat"></div>
```

---

## JavaScript

```javascript
function charger() {

    let xhr = new XMLHttpRequest();

    xhr.open("GET", "data.php", true);

    xhr.onload = function () {

        if (xhr.status == 200) {

            document.getElementById("resultat").innerHTML =
                xhr.responseText;
        }
    };

    xhr.send();
}
```

---

## PHP

```php
<?php

echo "Bonjour AJAX";
```

---

# 7. AJAX moderne avec fetch()

Aujourd’hui `fetch()` est recommandé.

---

# 8. GET avec fetch()

---

## HTML

```html
<button id="btn">Voir utilisateur</button>

<div id="zone"></div>
```

---

## JavaScript

```javascript
document.getElementById("btn").addEventListener("click", function () {

    fetch("user.php")
        .then(response => response.json())
        .then(data => {

            document.getElementById("zone").innerHTML =
                data.nom + " - " + data.email;
        })
        .catch(error => console.log(error));

});
```

---

## PHP

```php
<?php

header("Content-Type: application/json");

$user = [
    "nom" => "Rakoto",
    "email" => "rakoto@gmail.com"
];

echo json_encode($user);
```

---

# 9. POST avec fetch()

Très utilisé pour :

* connexion
* inscription
* wallet
* paiement
* commentaires

---

## HTML

```html
<input type="text" id="nom">

<button id="envoyer">Envoyer</button>
```

---

## JavaScript

```javascript
document.getElementById("envoyer").addEventListener("click", function () {

    let nom = document.getElementById("nom").value;

    fetch("save.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({
            nom: nom
        })
    })

    .then(response => response.json())

    .then(data => {

        alert(data.message);

    })

    .catch(error => console.log(error));

});
```

---

## PHP

```php
<?php

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$nom = $data["nom"];

echo json_encode([
    "message" => "Bonjour " . $nom
]);
```

---

# 10. Différence GET vs POST

| GET           | POST          |
| ------------- | ------------- |
| Visible URL   | Caché         |
| Lecture       | Envoi données |
| Peu sécurisé  | Plus sécurisé |
| Petite taille | Grande taille |

---

# 11. AJAX + Base de données MySQL

---

## PHP Connexion

```php
<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "test"
);
```

---

## SELECT avec JSON

```php
<?php

header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "test");

$sql = "SELECT * FROM users";

$result = $conn->query($sql);

$users = [];

while($row = $result->fetch_assoc()) {

    $users[] = $row;
}

echo json_encode($users);
```

---

## JavaScript afficher liste

```javascript
fetch("users.php")
    .then(response => response.json())
    .then(data => {

        let html = "";

        data.forEach(user => {

            html += `
                <p>
                    ${user.nom}
                </p>
            `;
        });

        document.getElementById("zone").innerHTML = html;
    });
```

---

# 12. Validation AJAX en temps réel

Très utilisé :

* code promo
* wallet code
* email disponible
* username unique

---

## HTML

```html
<input type="text" id="code">

<div id="message"></div>
```

---

## JavaScript

```javascript
document.getElementById("code").addEventListener("keyup", function () {

    let code = this.value;

    fetch("verification.php?code=" + code)

    .then(response => response.json())

    .then(data => {

        document.getElementById("message").innerHTML =
            data.message;
    });

});
```

---

## PHP

```php
<?php

header("Content-Type: application/json");

$code = $_GET["code"];

if($code == "GOLD123") {

    echo json_encode([
        "status" => true,
        "message" => "Code valide"
    ]);

} else {

    echo json_encode([
        "status" => false,
        "message" => "Code invalide"
    ]);
}
```

---

# 13. Réponse JSON standard professionnelle

Bonne pratique :

```json
{
    "success": true,
    "message": "Utilisateur ajouté",
    "data": {}
}
```

---

# 14. Structure recommandée projet

```text
project/
│
├── index.php
├── js/
│   └── app.js
│
├── api/
│   ├── users.php
│   ├── login.php
│   └── wallet.php
│
├── models/
│   └── UserModel.php
│
├── controllers/
│   └── UserController.php
│
└── database/
    └── connexion.php
```

---

# 15. Exemple complet LOGIN AJAX

---

# HTML

```html
<input type="email" id="email">
<input type="password" id="password">

<button id="login">
    Connexion
</button>

<div id="message"></div>
```

---

# JavaScript

```javascript
document.getElementById("login")
.addEventListener("click", function () {

    let email =
        document.getElementById("email").value;

    let password =
        document.getElementById("password").value;

    fetch("login.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({
            email: email,
            password: password
        })
    })

    .then(response => response.json())

    .then(data => {

        document.getElementById("message")
            .innerHTML = data.message;

    });

});
```

---

# PHP

```php
<?php

header("Content-Type: application/json");

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$email = $data["email"];
$password = $data["password"];

if(
    $email == "admin@gmail.com"
    &&
    $password == "1234"
) {

    echo json_encode([
        "success" => true,
        "message" => "Connexion réussie"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Email ou mot de passe incorrect"
    ]);
}
```

---

# 16. Status HTTP importants

| Code | Signification  |
| ---- | -------------- |
| 200  | Succès         |
| 404  | Introuvable    |
| 500  | Erreur serveur |
| 401  | Non autorisé   |
| 403  | Interdit       |

---

# 17. Gestion des erreurs AJAX

```javascript
fetch("api.php")

.then(response => {

    if(!response.ok) {
        throw new Error("Erreur serveur");
    }

    return response.json();
})

.then(data => console.log(data))

.catch(error => {

    console.log(error);

});
```

---

# 18. Async Await

Version moderne.

---

## JavaScript

```javascript
async function charger() {

    try {

        let response =
            await fetch("users.php");

        let data =
            await response.json();

        console.log(data);

    } catch(error) {

        console.log(error);
    }
}
```

---

# 19. Sécurité importante

---

## Toujours :

### 1. Valider backend PHP

Jamais confiance au JS.

---

### 2. Utiliser requêtes préparées

```php
$stmt = $conn->prepare(
    "SELECT * FROM users WHERE email=?"
);
```

---

### 3. Protéger contre XSS

```php
htmlspecialchars()
```

---

### 4. Hasher mots de passe

```php
password_hash()
password_verify()
```

---

# 20. Cycle complet AJAX

```text
1. User clique
2. JavaScript récupère données
3. fetch() envoie requête
4. PHP reçoit
5. PHP traite DB
6. PHP retourne JSON
7. JS reçoit réponse
8. HTML mis à jour
```

---

# 21. Diagramme logique

```text
+-------------+
| HTML FORM   |
+-------------+
        |
        v
+-------------+
| JavaScript  |
| fetch AJAX  |
+-------------+
        |
        v
+-------------+
| PHP API     |
+-------------+
        |
        v
+-------------+
| MySQL DB    |
+-------------+
        |
        v
+-------------+
| JSON Return |
+-------------+
        |
        v
+-------------+
| HTML Update |
+-------------+
```

---

# 22. Exemple réel Wallet Gold

---

## Front

```javascript
fetch("/wallet/checkCode", {

    method: "POST",

    headers: {
        "Content-Type": "application/json"
    },

    body: JSON.stringify({
        code: code
    })
})
```

---

## Backend

```php
$wallet =
    $walletModel->findByCode($code);
```

---

## Réponse

```json
{
    "success": true,
    "amount": 5000
}
```

---

# 23. AJAX vs Formulaire classique

| Critère       | AJAX | Formulaire |
| ------------- | ---- | ---------- |
| Rapide        | Oui  | Non        |
| Temps réel    | Oui  | Non        |
| Recharge page | Non  | Oui        |
| UX moderne    | Oui  | Moyen      |

---

# 24. Erreurs fréquentes

---

## 1. Oublier json_encode

Mauvais :

```php
echo $data;
```

Bon :

```php
echo json_encode($data);
```

---

## 2. Oublier Content-Type

```php
header("Content-Type: application/json");
```

---

## 3. Mauvais chemin fichier

```javascript
fetch("/api/users.php")
```

---

## 4. JSON invalide

Mauvais :

```json
{
nom: "Rakoto"
}
```

Bon :

```json
{
    "nom": "Rakoto"
}
```

---

# 25. Citation développeur

> “Le frontend parle.
> Le backend réfléchit.
> La base de données se souvient.”

---

# 26. Philosophie architecture web

```text
HTML = corps
CSS = vêtements
JavaScript = muscles
PHP = cerveau
MySQL = mémoire
AJAX = système nerveux
```

---

# 27. Ressources officielles

## JavaScript Fetch API

[MDN Fetch API](https://developer.mozilla.org/fr/docs/Web/API/Fetch_API?utm_source=chatgpt.com)

---

## AJAX XMLHttpRequest

[MDN XMLHttpRequest](https://developer.mozilla.org/fr/docs/Web/API/XMLHttpRequest?utm_source=chatgpt.com)

---

## JSON

[JSON Official](https://www.json.org/json-fr.html?utm_source=chatgpt.com)

---

## PHP JSON

[PHP json_encode](https://www.php.net/manual/fr/function.json-encode.php?utm_source=chatgpt.com)

---

## PHP Fetch Input

[PHP php://input](https://www.php.net/manual/fr/wrappers.php.php?utm_source=chatgpt.com)

---

## MySQL Prepared Statement

[PHP Prepared Statements](https://www.php.net/manual/fr/mysqli.quickstart.prepared-statements.php?utm_source=chatgpt.com)

---

# 28. Conclusion

AJAX est devenu essentiel dans :

* React
* Vue
* Angular
* CodeIgniter
* Laravel
* Spring Boot
* applications temps réel
* wallet
* paiement
* réseaux sociaux

Concept clé :

```text
Frontend envoie
Backend traite
JSON transporte
Interface réagit
```

---

# Petit proverbe développeur

> “Un bon développeur résout les problèmes.
> Un excellent développeur évite qu’ils apparaissent.”

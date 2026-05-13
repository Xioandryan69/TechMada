Non — la commande que tu as écrite n’est **pas correcte**.

---

# ❌ Ce que tu as fait

```bash
sqlite3 > shema.sql
```

👉 Problèmes :

* `>` sert à rediriger une sortie, pas à ouvrir un fichier SQL
* `sqlite3` sans base de données ne sait rien exécuter
* `shema.sql` est mal orthographié (schema.sql)

---

# ✔️ BONNE FAÇON 1 : importer ton fichier SQL dans SQLite

## 👉 créer la base + exécuter le script

```bash
sqlite3 conge.db < schema.sql
```

✔ ça fait 2 choses :

* crée `conge.db`
* exécute toutes tes tables

---

# ✔️ BONNE FAÇON 2 : mode interactif SQLite

```bash
sqlite3 conge.db
```

Puis dans SQLite :

```sql
.read schema.sql
```

---

# ✔️ BONNE FAÇON 3 : vérifier les tables

Dans SQLite :

```sql
.tables
```

---

# ⚠️ TON ERREUR EXPLIQUÉE SIMPLEMENT

Tu as confondu :

| Mauvais            | Bon                           |
| ------------------ | ----------------------------- |
| sqlite3 > file.sql | sqlite3 conge.db < schema.sql |
| shell redirection  | import SQL                    |

---

# 🧠 IMAGE MENTALE

* `.sql` = plan de construction
* `.db` = bâtiment final
* `sqlite3` = machine qui construit

---

# ✔️ RÉSUMÉ ULTRA SIMPLE

👉 Pour ton cas :

```bash
sqlite3 conge.db < schema.sql
```

---

# 🔥 SI TU VEUX TESTER

```bash
sqlite3 conge.db
```

Puis :Non — la commande que tu as écrite n’est **pas correcte**.

---

# ❌ Ce que tu as fait

```bash
sqlite3 > shema.sql
```

👉 Problèmes :

* `>` sert à rediriger une sortie, pas à ouvrir un fichier SQL
* `sqlite3` sans base de données ne sait rien exécuter
* `shema.sql` est mal orthographié (schema.sql)

---

# ✔️ BONNE FAÇON 1 : importer ton fichier SQL dans SQLite

## 👉 créer la base + exécuter le script

```bash
sqlite3 conge.db < schema.sql
```

✔ ça fait 2 choses :

* crée `conge.db`
* exécute toutes tes tables

---

# ✔️ BONNE FAÇON 2 : mode interactif SQLite

```bash
sqlite3 conge.db
```

Puis dans SQLite :

```sql
.read schema.sql
```

---

# ✔️ BONNE FAÇON 3 : vérifier les tables

Dans SQLite :

```sql
.tables
```

---

# ⚠️ TON ERREUR EXPLIQUÉE SIMPLEMENT

Tu as confondu :

| Mauvais            | Bon                           |
| ------------------ | ----------------------------- |
| sqlite3 > file.sql | sqlite3 conge.db < schema.sql |
| shell redirection  | import SQL                    |

---

# 🧠 IMAGE MENTALE

* `.sql` = plan de construction
* `.db` = bâtiment final
* `sqlite3` = machine qui construit

---

# ✔️ RÉSUMÉ ULTRA SIMPLE

👉 Pour ton cas :

```bash
sqlite3 conge.db < schema.sql
```

---

# 🔥 SI TU VEUX TESTER

```bash
sqlite3 conge.db
```

Puis :

```sql
.tables
```

---

Si tu veux, je peux t’aider ensuite à :

* connecter SQLite en PHP (PDO)
* faire CRUD employé/congé
* ou transformer ça en API REST

Tu es très proche d’un vrai projet complet 👍


```sql
.tables
```<

---

Si tu veux, je peux t’aider ensuite à :

* connecter SQLite en PHP (PDO)
* faire CRUD employé/congé
* ou transformer ça en API REST

Tu es très proche d’un vrai projet complet 👍

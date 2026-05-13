Table users {
  id int [pk, increment]
  nom varchar
  prenom varchar
  email varchar [unique]
  password varchar
  genre varchar
  date_naissance date
  role varchar
}

Table imc_categories {
  id int [pk, increment]
  min_value decimal
  max_value decimal
  label varchar
}

Table age_ranges {
  id int [pk, increment]
  age_min int
  age_max int
}

Table objectifs {
  id int [pk, increment]
  nom varchar
}

Table type_regimes {
  id int [pk, increment]
  nom varchar
  pourcentage decimal
}

Table health_rules {
  id int [pk, increment]
  type varchar
  age_min int
  age_max int
  genre varchar
  valeur_min decimal
  valeur_max decimal
}

Table recommendations_weight {
  id int [pk, increment]
  age_range_id int
  genre varchar
  imc_min decimal
  imc_max decimal
  poids_min decimal
  poids_max decimal
}

Table user_health {
  id int [pk, increment]
  user_id int
  taille decimal
  poids decimal
  imc decimal
}

Table user_objectifs {
  id int [pk, increment]
  user_id int
  objectif_id int
}

Table regime_user {
  id int [pk, increment]
  id_user int
  type_regime_id int
}

Table regimes {
  id int [pk, increment]
  nom varchar
  description text
  regime_user_id int
  calories int
  prix decimal
  duree_jours int
}

Table activites {
  id int [pk, increment]
  nom varchar
  description text
  calories_brulees int
  duree_minutes int
}

Table recommendations {
  id int [pk, increment]
  user_id int
  regime_id int
  activite_id int
  date_debut date
  date_fin date
}

Table wallet {
  id int [pk, increment]
  user_id int
  solde decimal
}

Table codes {
  id int [pk, increment]
  code varchar
  valeur decimal
  utilise boolean
}

Table transactions {
  id int [pk, increment]
  user_id int
  code_id int
  montant decimal
  date_transaction datetime
}

Table abonnements {
  id int [pk, increment]
  user_id int
  type varchar
  date_debut date
  date_fin date
}

/* ================= RELATIONS ================= */

Ref: user_health.user_id > users.id
Ref: user_objectifs.user_id > users.id
Ref: user_objectifs.objectif_id > objectifs.id

Ref: regime_user.id_user > users.id
Ref: regime_user.type_regime_id > type_regimes.id

Ref: regimes.regime_user_id > regime_user.id

Ref: recommendations.user_id > users.id
Ref: recommendations.regime_id > regimes.id
Ref: recommendations.activite_id > activites.id

Ref: wallet.user_id > users.id

Ref: transactions.user_id > users.id
Ref: transactions.code_id > codes.id

Ref: abonnements.user_id > users.id

Ref: recommendations_weight.age_range_id > age_ranges.id
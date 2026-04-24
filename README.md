# 🏥 Cabinet Médical — Laravel

Application web de gestion d'un cabinet médical développée avec **Laravel 13**, **Bootstrap 5** et **MySQL**.

> Projet académique — Licence Informatique S6 | Faculté des Sciences Semlalia, UCA Marrakech

🌐 **Application en ligne : [cabinet-medical-production-7b99.up.railway.app](https://cabinet-medical-production-7b99.up.railway.app/)**

##  Fonctionnalités

- 🔐 Authentification avec 4 rôles : Administrateur, Médecin, Secrétaire, Patient
- 📅 Prise de rendez-vous en ligne avec créneaux dynamiques (AJAX) et détection de conflits
- 📄 Dossiers médicaux électroniques et historique des consultations
- 🖨️ Génération d'ordonnances en PDF (DomPDF)
- 📧 Notifications email automatiques (confirmation, acceptation, refus, rappel)
- 📊 Tableau de bord administrateur avec graphiques Chart.js

## 🛠 Stack

`Laravel 13` · `PHP 8.4` · `MySQL 8` · `Bootstrap 5` · `Eloquent ORM` · `Laravel Breeze` · `DomPDF` · `PHPUnit`

## 🚀 Installation

```bash
git clone https://github.com/votre-username/cabinet-medical.git
cd cabinet-medical
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## 🗄️ Base de données & Seeders

Les migrations et seeders sont inclus dans le projet.  
Une seule commande suffit pour créer toutes les tables ET insérer les données de démonstration :

```bash
php artisan migrate --seed
```

Pour réinitialiser complètement la base de données :

```bash
php artisan migrate:fresh --seed
```

**Ce qui est créé automatiquement :**
- ✅ 10 tables (users, patients, medecins, rendezvous, consultations, ordonnances...)
- ✅ 6 comptes de démonstration (admin, 2 médecins, secrétaire, 2 patients)
- ✅ Données de test (rendez-vous, dossiers médicaux, disponibilités)

> ⚠️ Ne pas oublier de configurer `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD` dans le fichier `.env` avant de lancer les migrations.

## 🔑 Comptes de test — mot de passe : `password`

| Rôle | Email |
|------|-------|
| Administrateur | admin@cabinet.com |
| Médecin (Cardiologie) | medecin1@cabinet.com |
| Médecin (Pédiatrie) | medecin2@cabinet.com |
| Secrétaire | secretaire@cabinet.com |
| Patient | patient1@cabinet.com |
| Patient | patient2@cabinet.com |

## 👥 Réalisé par

Zouhair AJARIF · Hamza BELAKBIR · Mohammed ANFLOUS  
Encadré par Pr. JABIR Somaya & Pr. BABA Naima — 2025/2026

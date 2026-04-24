# 🏥 Cabinet Médical — Laravel

Application web de gestion d'un cabinet médical développée avec **Laravel 13**, **Bootstrap 5** et **MySQL**.

> Projet académique — Licence Informatique S6 | Faculté des Sciences Semlalia, UCA Marrakech

##  Fonctionnalités

- 🔐 Authentification avec 4 rôles : Administrateur, Médecin, Secrétaire, Patient
- 📅 Prise de rendez-vous en ligne avec créneaux dynamiques (AJAX) et détection de conflits
- 📄 Dossiers médicaux électroniques et historique des consultations
- 🖨️ Génération d'ordonnances en PDF (DomPDF)
- 📧 Notifications email automatiques (confirmation, acceptation, refus, rappel)
- 📊 Tableau de bord administrateur avec graphiques Chart.js

## 🛠 Stack

`Laravel 13` · `PHP 8.4` · `MySQL 8` · `Bootstrap 5` · `Eloquent ORM` · `Laravel Breeze` · `DomPDF` · `PHPUnit`

##  Installation

```bash
git clone https://github.com/votre-username/cabinet-medical.git
cd cabinet-medical
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## 🔑 Comptes de test — mot de passe : `password`

| Rôle | Email |
|------|-------|
| Administrateur | admin@cabinet.com |
| Médecin | medecin1@cabinet.com |
| Secrétaire | secretaire@cabinet.com |
| Patient | patient1@cabinet.com |

## 👥 Réalisé par

Zouhair AJARIF · Hamza BELAKBIR · Mohammed ANFLOUS  
Encadré par Pr. JABIR Somaya & Pr. BABA Naima — 2025/2026

# 🏥 Cabinet Médical — Laravel

Application web de gestion d'un cabinet médical développée avec **Laravel 13**, **Bootstrap 5** et **MySQL**.

> Projet académique — Licence Informatique S6 | Faculté des Sciences Semlalia, UCA Marrakech

## 🌐 Démonstration

L'application est exposée via **ngrok** (tunneling local) — le lien fonctionne uniquement quand le PC de développement est allumé.

> ⚠️ Pour tester l'application, contacter l'équipe pour planifier une démonstration en direct.

Le déploiement sur **Railway** a été tenté mais a rencontré des incompatibilités entre la version PHP disponible sur Railway (8.2/8.3) et les dépendances du projet qui nécessitent **PHP 8.4**. La solution adoptée est **ngrok** comme alternative.

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
php artisan migrate --seed
php artisan serve
```

## 🌍 Lancer la démonstration publique (ngrok)

```bash
# Terminal 1
php artisan serve

# Terminal 2
ngrok http 8000
```

Le lien public généré sera de la forme : `https://xxxx.ngrok-free.app`

## 🗄️ Base de données & Seeders

```bash
# Créer les tables + insérer les données de démonstration
php artisan migrate --seed

# Réinitialiser complètement
php artisan migrate:fresh --seed
```

- ✅ 10 tables créées automatiquement
- ✅ 6 comptes de démonstration insérés
- ✅ Données de test (rendez-vous, dossiers médicaux, disponibilités)

> ⚠️ Configurer `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD` dans `.env` avant de lancer les migrations.

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

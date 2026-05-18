# SludinājumuPortāls

Latvijas sludinājumu portāls, izstrādāts ar Laravel 12 + Bootstrap 5.
Lietotāji var publicēt sludinājumus, sazināties ar pārdevējiem un
pārvaldīt savu kontu.

## Galvenās funkcijas

- Lietotāju reģistrācija un autentifikācija (ar 2FA atbalstu)
- Sludinājumu publicēšana ar daudzpakāpju asistentu (5 soļi)
- Pārlūkošana un meklēšana ar filtriem
- Reāllaika ziņojumu sistēma starp pircēju un pārdevēju
- Profila pārvaldība ar privātuma iestatījumiem
- PDF eksports sludinājumiem un administratora pārskatiem
- Administratora panelis (lietotāju un sludinājumu pārvaldība, bloķēšana)
- GDPR atbilstošs datu eksports
- Telefona OTP verifikācija (sākotnējā setup)

## Tehnoloģiju steks

| Daļa | Tehnoloģija |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Datubāze | MySQL 8.0+ / MariaDB 10.3+ |
| Frontend | Bootstrap 5, vanilla JavaScript |
| Autentifikācija | Laravel Fortify |
| API tokeni | Laravel Sanctum |
| Kartes | Leaflet.js + OpenStreetMap |
| PDF | barryvdh/laravel-dompdf |

## Instalācija lokāli

```bash
# Klonē projektu
git clone https://github.com/MarisBa/SludinajumuPortals.git
cd SludinajumuPortals

# Instalē atkarības
composer install
npm install

# Konfigurē environment
cp .env.example .env
php artisan key:generate

# Konfigurē DB savienojumu .env failā
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sludinajumi
# DB_USERNAME=root
# DB_PASSWORD=

# Palaid migrācijas un seederus
php artisan migrate
php artisan db:seed
php artisan db:seed --class=AdminUserSeeder

# Izveido storage symlink (attēlu augšupielādei)
php artisan storage:link

# Palaid serveri
php artisan serve

# Atver pārlūkprogrammā: http://127.0.0.1:8000
```

## Noklusētie pieslēgšanās dati (testēšanai)

**Administratora konts:**
- E-pasts: `admin@portals.lv`
- Parole: `admin123`

⚠️ **SVARĪGI:** Produkcijā maini administratora paroli uz drošāku!

## Galvenās URL adreses

| Lapa | URL |
|---|---|
| Mājaslapa | `/home` |
| Sludinājumu pārlūkošana | `/browse` |
| Pieslēgšanās | `/login` |
| Reģistrācija | `/register` |
| Mani sludinājumi | `/ads` |
| Jauns sludinājums | `/ads/create` |
| Ziņas | `/messages` |
| Profila iestatījumi | `/profile` |
| Administratora panelis | `/admin` |

## Datubāzes struktūra

Projekta datubāze sastāv no šādām galvenajām tabulām:
- `users` — lietotāji ar lomām un bloķēšanas statusu
- `advertisements` — sludinājumi
- `categories`, `subcategories`, `childcategories` — kategoriju hierarhija
- `countries`, `states`, `cities` — ģeogrāfijas hierarhija
- `conversations` — sarunas starp lietotājiem
- `messages` — atsevišķas ziņas

## Testēšana

```bash
# Palaid Laravel testus (ja ir)
php artisan test

# Pārbaudi maršrutus
php artisan route:list

# Iztīri cache (problēmu gadījumā)
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

## Autors

Māris Balčūns
Rīgas Valsts Tehnikums, Datorikas nodaļa
Kvalifikācijas darbs 2026

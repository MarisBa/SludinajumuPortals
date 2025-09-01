
# SludinājumuPortāls

**SludinājumuPortāls** ir Laravel ietvarā izstrādāta tīmekļa lietotne, kas nodrošina lietotājiem ērtu platformu sludinājumu publicēšanai, meklēšanai un pārvaldīšanai. Projekts ir veidots ar mērķi piedāvāt lietotājiem intuitīvu un funkcionālu pieredzi, vienlaikus nodrošinot stabilu un drošu tehnoloģisko pamatu.

## Galvenās funkcijas

- **Sludinājumu publicēšana un pārvaldība**: Lietotāji var viegli pievienot, rediģēt un dzēst savus sludinājumus.
- **Meklēšanas un filtrēšanas iespējas**: Efektīva sludinājumu meklēšana pēc dažādiem kritērijiem.
- **Lietotāju autentifikācija**: Droša lietotāju pieteikšanās un reģistrācija.
- **Dizains un lietojamība**: Ērts un mūsdienīgs lietotāja interfeiss, kas nodrošina patīkamu lietošanas pieredzi.

## Tehnoloģijas

- **Laravel** – PHP ietvars tīras un uzturamas koda bāzes izstrādei
- **Redis** – kešatmiņas un sesiju pārvaldībai
- **Vite** – mūsdienīgs JavaScript bundler ātrai resursu ielādei
- **Vue.js** – interaktīvu lietotāja saskarnes elementu izveidei
- **Tailwind CSS** – ātrai un pielāgojamai dizaina izstrādei

## Instalācija

1. Klonē repozitoriju:

```bash
git clone https://github.com/MarisBa/SludinajumuPortals.git
cd SludinajumuPortals
Instalē atkarības:

bash
Kopēt kodu
composer install
npm install
Konfigurē .env failu:

bash
Kopēt kodu
cp .env.example .env
php artisan key:generate
Palaid datu bāzes migrācijas:

bash
Kopēt kodu
php artisan migrate
Palaid vietējo serveri:

bash
Kopēt kodu
php artisan serve
npm run dev

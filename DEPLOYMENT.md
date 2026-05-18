# Deployment instrukcijas

## Sistēmas prasības

| Komponents | Versija |
|---|---|
| PHP | 8.2 vai jaunāka |
| MySQL | 8.0+ vai MariaDB 10.3+ |
| Composer | 2.x |
| Node.js | 18+ (tikai build laikā) |
| Web serveris | Apache 2.4+ vai Nginx 1.18+ |

### PHP paplašinājumi

Nepieciešami: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL,
PDO, Tokenizer, XML, GD (PDF ģenerēšanai)

Pārbaudīt: `php -m`

## Soļi servera setup

1. **Augšupielādēt kodu uz serveri**
   ```bash
   git clone https://github.com/MarisBa/SludinajumuPortals.git
   cd SludinajumuPortals
   ```

2. **Instalēt PHP atkarības**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Build frontend assetus**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurēt .env**
   ```bash
   cp .env.example .env
   nano .env
   ```

   Iestatīt:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.lv

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=sludinajumi_prod
   DB_USERNAME=...
   DB_PASSWORD=...
   ```

5. **Ģenerēt aplikācijas atslēgu**
   ```bash
   php artisan key:generate
   ```

6. **Palaist migrācijas un seederus**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan db:seed --class=AdminUserSeeder --force
   ```

   ⚠️ Pēc seedera palaišanas nekavējoties pieslēdzies kā `admin@portals.lv`
   un nomaini paroli no `admin123` uz drošu.

7. **Izveidot storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Iestatīt failu atļaujas**
   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

9. **Optimizēt produkcijai**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

## Web servera konfigurācija

### Apache

Document root jāvērš uz `public/` direktoriju. Projektā jau ir
[public/.htaccess](public/.htaccess), kas nodrošina pareizu URL pārrakstīšanu.

Apache virtual host paraugs:
```apache
<VirtualHost *:443>
    ServerName yourdomain.lv
    DocumentRoot /var/www/SludinajumuPortals/public

    <Directory /var/www/SludinajumuPortals/public>
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/yourdomain.lv/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/yourdomain.lv/privkey.pem

    ErrorLog ${APACHE_LOG_DIR}/sludinajumi-error.log
    CustomLog ${APACHE_LOG_DIR}/sludinajumi-access.log combined
</VirtualHost>
```

Nepieciešamie Apache moduļi: `mod_rewrite`, `mod_ssl`, `mod_headers`.

### Nginx

```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.lv;
    root /var/www/SludinajumuPortals/public;

    index index.php;

    ssl_certificate /etc/letsencrypt/live/yourdomain.lv/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.lv/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

server {
    listen 80;
    server_name yourdomain.lv;
    return 301 https://$host$request_uri;
}
```

### SSL sertifikāts (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-apache  # vai python3-certbot-nginx
sudo certbot --apache -d yourdomain.lv           # vai --nginx
```

Sertifikāts atjaunosies automātiski caur `certbot.timer`.

## Pēc-deploy pārbaudes

```bash
# Pārbaudi, ka migrācijas ir izpildītas
php artisan migrate:status

# Pārbaudi, ka maršruti darbojas
php artisan route:list

# Pārbaudi, ka cache ir uzbūvēts
ls -la bootstrap/cache/

# Pārbaudi web servera atbildi
curl -I https://yourdomain.lv/home
```

Manuāla pārbaude pārlūkprogrammā:
- Atver `https://yourdomain.lv/home` — sākumlapai jāielādējas
- `https://yourdomain.lv/login` — pieslēgšanās formai jāparādās
- Pieslēdzies kā admin un atver `/admin` — administratora panelim jāielādējas

## Atjaunināšana (deploy jaunai versijai)

```bash
cd /var/www/SludinajumuPortals

# Aktivizē maintenance režīmu
php artisan down

# Velc jaunāko kodu
git pull origin main

# Atjaunini atkarības un assetus
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# Palaid jaunās migrācijas
php artisan migrate --force

# Pārbūvē cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Atslēdz maintenance režīmu
php artisan up
```

## Dublēšana (backup)

Datubāze:
```bash
mysqldump -u root -p sludinajumi_prod > backup-$(date +%Y%m%d).sql
```

Augšupielādētie faili:
```bash
tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/app
```

Ieteicams ieplānot kā cron darbu, kas glabā vismaz pēdējās 7 dienu kopijas.

## Problēmu novēršana

| Simptoms | Risinājums |
|---|---|
| 500 kļūda visās lapās | Pārbaudi `storage/logs/laravel.log`. Bieži — atļaujas vai trūkstošs `.env` |
| Attēli neparādās | `php artisan storage:link` netika izpildīts vai `storage/app/public/` atļaujas nav 775 |
| "419 Page Expired" | Sesijas vai CSRF konfigurācija (`SESSION_DOMAIN`, `APP_URL` neatbilst domēnam) |
| Mainīgs CSS/JS | `npm run build` nav palaists vai web serveris kešo vecos failus |
| Lēna lapa | Palaid `php artisan config:cache` un `route:cache` |
| Maršruti ar 404 pēc deploy | `php artisan route:clear && php artisan route:cache` |

Detalizētāks logs (tikai īslaicīgi, debug nolūkos):
```bash
# .env: APP_DEBUG=true (TIKAI uz mirkli, tad atgriez uz false)
tail -f storage/logs/laravel.log
```

## Drošība

- Nodrošini, ka `APP_DEBUG=false` un `APP_ENV=production`
- Maini noklusēto `admin@portals.lv` paroli
- Iestatīt HTTPS un HSTS headerus
- Aktivizē `SESSION_SECURE_COOKIE=true` un `SESSION_HTTP_ONLY=true`
- Regulāri palaid `composer audit` un `npm audit`
- Veido datubāzes lietotāju ar minimālām tiesībām (tikai konkrētajai DB)
- Konfigurē `.env` failu ar atļaujām `600` (`chmod 600 .env`)

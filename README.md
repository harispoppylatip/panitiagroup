# Deployment dengan Docker

Docker Compose menjalankan aplikasi, worker queue, dan scheduler. Port publik, URL aplikasi, dan koneksi database diatur dari `.env`. Database tidak dibuat oleh Compose.

```bash
cp .env.example .env
docker compose build
# Salin hasil perintah ini ke APP_KEY di .env host.
docker compose run --rm app php artisan key:generate --show
docker compose run --rm app php artisan migrate --force
docker compose up -d
```

Perintah `key:generate --force` tidak dipakai di container karena `.env` tidak disalin ke image. `env_file` hanya mengirim nilai environment ke proses, bukan membuat file `/var/www/html/.env`. Setelah mengisi `APP_KEY` di `.env` host, jalankan ulang perintah Compose agar semua service membaca nilai terbaru.

Untuk MySQL di host server yang sama, gunakan `DB_HOST=host.docker.internal`. Untuk database di server lain, isi `DB_HOST` dengan alamat IP atau hostname database tersebut. `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` wajib disesuaikan. Compose tidak menyediakan database lokal.

## Setup MySQL Server

Jika database belum dibuat di server:

```bash
sudo mysql
```

```sql
CREATE DATABASE laravel;
CREATE USER 'panitia'@'%' IDENTIFIED BY '<password-aman>';
GRANT ALL PRIVILEGES ON laravel.* TO 'panitia'@'%';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'panitia'@'%';
```

Jika user hanya tersedia sebagai `panitia@localhost`, buat juga user `panitia@'%'` dengan grant yang sama. Agar container dapat terhubung, edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
bind-address = 0.0.0.0
```

Lalu restart MySQL:

```bash
sudo systemctl restart mysql
```

Isi `.env` container dengan database dan user yang sama, lalu uji:

```bash
docker compose run --rm app php artisan migrate:status
```

Jika muncul `Connection refused`, periksa MySQL aktif, `DB_HOST`/`DB_PORT`, bind address, firewall, dan izin user database.

`APP_URL` adalah URL publik aplikasi, misalnya `https://paz.example.com`. `APP_PORT=2005` adalah port host Docker dan dapat diganti tanpa mengubah `docker-compose.yml`. Jika memakai domain dengan reverse proxy HTTPS, `APP_URL` tetap memakai `https://` dan port internal Docker tetap `80`.

Contoh mengganti port menjadi `9000`:

```dotenv
APP_PORT=9000
APP_URL=http://alamat-server-anda:9000
```

Kemudian jalankan ulang container:

```bash
docker compose up -d
```

Aplikasi dapat dibuka di `http://alamat-server-anda:9000`. Jika memakai domain dan reverse proxy HTTPS, gunakan `APP_URL=https://domain-anda.com`; port publik reverse proxy diarahkan ke `9000`.

`migrate --force` hanya perlu dijalankan saat ada migration baru dan sengaja tidak dijalankan otomatis oleh container. File upload dan hasil sinkronisasi galeri disimpan pada volume Docker agar tetap ada saat container dibuat ulang.

Perintah operasional:

```bash
docker compose ps
docker compose logs -f app
docker compose logs -f scheduler
docker compose down
```

# Deployment dengan Docker

Docker Compose menjalankan aplikasi, worker queue, dan scheduler. Port publik, URL aplikasi, dan koneksi database diatur dari `.env`. Database tidak dibuat oleh Compose.

```bash
cp .env.example .env
docker compose build
docker compose run --rm app php artisan key:generate --force
docker compose run --rm app php artisan migrate --force
docker compose up -d
```

Untuk MySQL di host server yang sama, gunakan `DB_HOST=host.docker.internal`. Untuk database di server lain, isi `DB_HOST` dengan alamat IP atau hostname database tersebut. `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` wajib disesuaikan.

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

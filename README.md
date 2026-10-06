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

## Catatan AI lokal

`CLAUDE.md`, `AGENTS.md`, dan `.ai/project-memory.md` berisi konteks lokal project untuk AI. Ketiganya sengaja masuk `.gitignore`, sehingga tidak dikirim ke GitHub. Saat membuat fitur baru, periksa juga catatan project di vault Haris, terutama pola Docker di `[[pola-fitur]]`.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

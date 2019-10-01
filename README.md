### 1. Buat database dengan nama database (lihat file .env)

### 2. Jalankan migrasi

```
php artisan migrate --force
```

### 3. Jalankan server

```
php -S localhost:{port} -t ./public/
```

### 4. Jalankan test

```
./vendor/bin/phpunit
```

### Kekurangan

```
1. endpoint untuk 'history', 'templates' belum selesai
2. GET request untuk 'checklist' dan 'items' hanya bisa paginasi
   itupun bawaan dari laravel (belum bisa filter, sorting)
```

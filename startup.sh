#!/bin/bash

# 1. Pastikan folder storage ada (Solusi Gambar Hilang)
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views

# 2. Atur izin (Permissions)
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# 3. Buat Symlink Storage
php artisan storage:link

# 4. Jalankan Migrasi Database (AMAN)
# --force diperlukan karena kita di environment 'production'
php artisan migrate --force

# CATATAN: db:seed TIDAK dimasukkan di sini agar data tidak ganda.
# Jika database kosong melompong dan Anda INGIN otomatis seed,
# hapus tanda pagar (#) di baris bawah ini (tapi hati-hati duplikat):
# php artisan db:seed --force

# 5. Jalankan SSH (Agar tidak Conn Close)
service ssh start

# 6. Jalankan Apache (Server Website)
apache2-foreground

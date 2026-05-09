# Smart Exam Tutor

Platform tryout online adaptif berbasis Laravel untuk membantu siswa belajar lebih terarah melalui analisis hasil ujian dan rekomendasi belajar personal.

## ✨ Highlight

- 🧑‍🏫 Role-based system: `Admin` dan `Student`
- 📝 Tryout end-to-end: mulai, kerjakan, submit, lihat hasil
- 📊 Analisis performa per topik (weakness & mastery)
- 🧠 Klasifikasi level siswa: `Pemula`, `Menengah`, `Mahir` (KNN)
- 🎯 Rekomendasi materi dan soal lanjutan berbasis `cosine similarity`
- 🤖 Integrasi Google Gemini untuk feedback belajar otomatis
- 🗂️ Sinkronisasi bank soal eksternal

## 🧩 Fitur Utama

### Untuk Admin
- Kelola mata pelajaran (`subjects`)
- Kelola topik (`topics`)
- Kelola materi belajar (`learning materials`)
- Kelola soal + opsi jawaban + tingkat kesulitan
- Sinkronisasi soal dari provider eksternal
- Kelola paket tryout
- Lihat performa siswa

### Untuk Student
- Akses dashboard pembelajaran
- Ikut tryout berdasarkan paket tersedia
- Dapatkan skor, statistik benar/salah, dan feedback otomatis
- Lihat analisis kelemahan topik
- Dapat rekomendasi materi, soal lanjutan, dan resource video belajar

## ⚙️ Tech Stack

- **Backend:** Laravel 13, PHP 8.3+
- **Frontend:** Blade, TailwindCSS, Alpine.js, Vite
- **Database:** MySQL/MariaDB (disarankan)
- **AI Integration:** Google Generative Language API (Gemini)

## 🚀 Instalasi Lokal

### 1. Clone repository

```bash
git clone https://github.com/USERNAME/Smart-Exam-Tutor.git
cd Smart-Exam-Tutor
```

### 2. Install dependency backend & frontend

```bash
composer install
npm install
```

### 3. Buat file environment

```bash
cp .env.example .env
```

Jika di Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 4. Konfigurasi `.env`

Ubah bagian penting berikut:

```env
APP_NAME="Smart Exam Tutor"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_exam_tutor
DB_USERNAME=root
DB_PASSWORD=
```

Jika ingin aktifkan fitur AI Gemini:

```env
GOOGLE_AI_API_KEY=your_api_key_here
GOOGLE_AI_MODEL=gemini-1.5-flash
GOOGLE_AI_TIMEOUT=20
```

Jika ingin aktifkan sinkronisasi bank soal eksternal:

```env
QUESTION_BANK_PROVIDER=opentdb
QUESTION_BANK_BASE_URL=https://opentdb.com
QUESTION_BANK_ENDPOINT=/api.php
QUESTION_BANK_TIMEOUT=15
QUESTION_BANK_TOKEN=
```

### 5. Generate app key

```bash
php artisan key:generate
```

### 6. Migrasi database

```bash
php artisan migrate
```

### 7. (Opsional) Seed data awal

```bash
php artisan db:seed
```

### 8. Jalankan aplikasi

Jalankan backend:

```bash
php artisan serve
```

Jalankan Vite (terminal terpisah):

```bash
npm run dev
```

Akses aplikasi di `http://127.0.0.1:8000`.

## 🛠️ Perintah Penting

```bash
# Menjalankan test
php artisan test

# Build asset production
npm run build

# Sinkronisasi soal eksternal (jika tersedia command)
php artisan app:sync-external-questions
```

## 📁 Struktur Singkat

```text
app/
  Http/Controllers/Admin      -> fitur manajemen admin
  Http/Controllers/Student    -> dashboard, tryout, insight siswa
  Services/                   -> evaluasi tryout, klasifikasi, rekomendasi
database/migrations/          -> skema tabel inti sistem
resources/views/              -> tampilan Blade admin & student
routes/web.php                -> seluruh routing web
```

## 🔐 Catatan Akses

- Middleware `role:admin` untuk area admin
- Middleware `role:student` untuk area student
- Auth menggunakan Laravel Breeze

## 📄 License

Project ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).

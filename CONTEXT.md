# ScholarGO — Project Context

Platform manajemen beasiswa berbasis web. Admin mengelola data beasiswa; pengguna mencari dan mendaftar.

---

## Quick Start

```bash
# Prasyarat: PHP 8.3+, Composer, Node.js, MySQL (XAMPP)

composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Jalankan (2 terminal)
php artisan serve   # → http://localhost:8000
npm run dev         # → Vite HMR

# Atau satu perintah:
composer dev
```

**Akses form beasiswa:** `http://localhost:8000/admin/scholarships/create`

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Blade, Tailwind CSS 4, Vite 8 |
| Database | MySQL (`scholargo_db`), port 3306 |
| Storage | Disk `public` (`storage/app/public/`) |
| Dev Server | XAMPP (Apache + MySQL) |

**.env penting:**
```
DB_DATABASE=scholargo_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## Struktur Database

### `users`
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| name | string |
| email | string unique |
| password | string (hashed) |
| timestamps | — |

### `scholarships`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | — |
| name | string | Nama beasiswa |
| organizer_name | string | Penyelenggara |
| description | text | Deskripsi lengkap |
| level | string | D3 / D4 / S1 / S2 / S3 |
| category | string(100) nullable | Jenis beasiswa |
| amount | unsignedBigInteger nullable | Nominal (Rp) |
| quota | unsignedInteger nullable | Kuota penerima |
| start_date | date nullable | Tanggal buka pendaftaran |
| end_date | date nullable | Deadline pendaftaran |
| source_url | string nullable | URL resmi |
| poster | string nullable | Path file di storage public |
| status | enum draft/published | Default: draft |
| timestamps + deleted_at | — | SoftDeletes aktif |

---

## Arsitektur Folder

```
app/
  Http/
    Controllers/Admin/ScholarshipController.php
    Requests/StoreScholarshipRequest.php
  Models/
    Scholarship.php
    User.php

database/migrations/
  2026_10_28_032755_create_scholarships_table.php

resources/views/admin/scholarships/
  create.blade.php

routes/web.php
```

## Routes

| Method | URI | Name | Keterangan |
|---|---|---|---|
| GET | `/admin/scholarships/create` | `admin.scholarships.create` | Form tambah beasiswa |
| POST | `/admin/scholarships` | `admin.scholarships.store` | Simpan beasiswa |

---

## Product Backlog

### ✅ PBI-10 — Tambah Beasiswa (Admin)
Form tambah beasiswa dengan validasi lengkap, Xupload poster, soft delete, dan auto-cleanup file.

### 🔲 Planned
- **PBI-11-  Sebagai admin, saya ingin melihat daftar dan detail beasiswa yang sudah ada  agar dapat memantau data yang tersimpan ** 
- **PBI-12- Sebagai admin, saya ingin mengubah dan menghapus data beasiswa agar informasi tetap akurat dan terbaru 
---

## Konvensi Tim

- **Model:** PascalCase singular — `Scholarship`
- **Controller:** Grouped di `Admin/`, suffix `Controller`
- **Request:** `{Aksi}{Model}Request` — `StoreScholarshipRequest`
- **View:** `admin/scholarships/create.blade.php`
- **Route name:** `admin.scholarships.store`
- **Validasi:** Selalu pakai Form Request, bukan inline `validate()`
- **Upload:** Disk `public`, path disimpan di DB
- **Soft delete:** Wajib untuk semua model data utama
- **Bahasa UI:** Bahasa Indonesia

---

## File Panduan di Root

| File | Status | Keterangan |
|---|---|---|
| `CONTEXT.md` | ✅ Aktif | Satu-satunya file panduan — update ini |
| `AGENTS.md` | ⚠️ Jangan dihapus | Dibaca otomatis oleh AI agent (Antigravity, Claude Code, dll.) |

---

*Last updated: 2026-10-28 · PBI-10 selesai*

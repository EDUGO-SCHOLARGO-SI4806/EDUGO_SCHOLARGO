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

### `beasiswas`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | — |
| nama | string | Nama beasiswa |
| penyelenggara | string | Penyelenggara |
| deskripsi | text nullable | Deskripsi lengkap |
| jenjang | string | D3 / D4 / S1 / S2 / S3 |
| lokasi | string nullable | Lokasi beasiswa (diselaraskan dgn tim) |
| jenis_pendanaan | string nullable | Penuh / Parsial (diselaraskan dgn tim) |
| kategori | string(100) nullable | Jenis beasiswa |
| nominal | unsignedBigInteger nullable | Nominal (Rp) |
| kuota | unsignedInteger nullable | Kuota penerima |
| tanggal_buka | date nullable | Tanggal buka pendaftaran |
| tanggal_tutup | date nullable | Deadline pendaftaran |
| link_sumber | string nullable | URL resmi |
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
    Requests/UpdateScholarshipRequest.php
  Models/
    Beasiswa.php
    Scholarship.php (alias/subclass)
    User.php

database/migrations/
  2026_10_28_032755_create_scholarships_table.php

resources/views/admin/scholarships/
  create.blade.php
  edit.blade.php
  index.blade.php
  show.blade.php
  trashed.blade.php

routes/web.php
```

## Routes

| Method | URI | Name | Keterangan |
|---|---|---|---|
| GET | `/` | — | Redirect ke `admin.scholarships.index` |
| GET | `/admin/scholarships` | `admin.scholarships.index` | Daftar beasiswa |
| GET | `/admin/scholarships/create` | `admin.scholarships.create` | Form tambah beasiswa |
| POST | `/admin/scholarships` | `admin.scholarships.store` | Simpan beasiswa |
| GET | `/admin/scholarships/{scholarship}` | `admin.scholarships.show` | Detail beasiswa |
| GET | `/admin/scholarships/{scholarship}/edit` | `admin.scholarships.edit` | Form edit beasiswa |
| PUT/PATCH | `/admin/scholarships/{scholarship}` | `admin.scholarships.update` | Simpan perubahan beasiswa |
| DELETE | `/admin/scholarships/{scholarship}` | `admin.scholarships.destroy` | Hapus beasiswa (soft delete) |
| GET | `/admin/scholarships/trashed` | `admin.scholarships.trashed` | Arsip beasiswa |
| POST | `/admin/scholarships/{id}/restore` | `admin.scholarships.restore` | Pulihkan beasiswa dari arsip |
| DELETE | `/admin/scholarships/{id}/force-delete` | `admin.scholarships.forceDelete` | Hapus permanen beasiswa & file poster |

---

## Product Backlog

### ✅ PBI-10 — Tambah Beasiswa (Admin)
Form tambah beasiswa dengan validasi lengkap, upload poster, soft delete, dan auto-cleanup file.

### ✅ PBI-11 — Daftar dan Detail Beasiswa (Admin)
Halaman daftar beasiswa (`/admin/scholarships`) dengan pagination, badge status, thumbnail poster, dan halaman detail beasiswa (`/admin/scholarships/{scholarship}`) yang responsif dan aman terhadap data null.

### ✅ PBI-12 — Edit, Hapus, dan Restore Beasiswa (Admin)
Method `edit`, `update`, `destroy`, `trashed`, `restore`, `forceDelete` pada `ScholarshipController`. Form `edit.blade.php` dengan data pre-filled dan tombol hapus berkonfirmasi. Halaman `trashed.blade.php` (Arsip Beasiswa) dengan fitur Pulihkan dan Hapus Permanen. Tombol Edit & Hapus (soft delete, konfirmasi JS) di `show.blade.php` dan `index.blade.php`.

### 🔲 Planned
---

## Konvensi Tim

- **Model:** PascalCase singular — `Scholarship`
- **Controller:** Grouped di `Admin/`, suffix `Controller`
- **Request:** `{Aksi}{Model}Request` — `StoreScholarshipRequest`
- **View:** `admin/scholarships/create.blade.php`, `index.blade.php`, `show.blade.php`
- **Route name:** `admin.scholarships.store`, `admin.scholarships.index`, dll.
- **Validasi:** Selalu pakai Form Request, bukan inline `validate()`
- **Upload:** Disk `public`, path disimpan di DB
- **Soft delete:** Wajib untuk semua model data utama
- **Bahasa UI:** Bahasa Indonesia

---

## Fil

---

*Last updated: 2026-11-06 · PBI-12 selesai*

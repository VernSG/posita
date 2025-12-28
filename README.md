# 🛒 Posita - Sistem Point of Sales

Aplikasi **Point of Sales (POS)** berbasis web untuk manajemen penjualan harian, stok konsinyasi, dan pemesanan box catering. Dibangun menggunakan arsitektur modern dengan Laravel + Vue.js + Inertia.js.

---

## 📋 Project Overview

Posita adalah sistem kasir digital yang dirancang untuk:
- **Manajemen Sesi Toko** - Buka/tutup toko dengan pencatatan kas awal & akhir
- **Sistem Konsinyasi** - Pelacakan stok titipan dari mitra/supplier
- **Pemesanan Box Catering** - Kelola pesanan nasi box dan snack box dengan countdown timer
- **Dashboard Admin** - Analitik penjualan, tren revenue, dan laporan harian

---

## 🛠️ Tech Stack

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **Laravel** | 11.x | Backend Framework & API |
| **Vue.js** | 3.x | Frontend Framework (Composition API) |
| **Inertia.js** | 2.x | SPA Bridge (tanpa API terpisah) |
| **Tailwind CSS** | 3.x | Utility-first CSS Framework |
| **MySQL** | 8.x | Database |
| **Vite** | 5.x | Build Tool & Dev Server |

---

## 🚀 Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL 8.x

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/posita.git
cd posita

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node.js
npm install

# 4. Copy file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
# DB_DATABASE=posita
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migrasi dan seeder
php artisan migrate:fresh --seed

# 8. Jalankan development server
php artisan serve
npm run dev
```

### Akun Default
| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@posita.com` | `password` |
| Kasir | `rivaldi@posita.com` | `password` |

---

## 👥 Pembagian Tugas Tim (Feature Breakdown)

### Nurita — Fitur: Open Shop
> Menangani proses pembukaan sesi toko harian dan input saldo awal kas.

| Layer | File |
|-------|------|
| **Controller** | `app/Http/Controllers/Pos/ShopSessionController.php` → `create()`, `store()` |
| **Service** | `app/Services/ShopSessionService.php` → `startSession()` |
| **View** | `resources/js/Pages/Pos/OpenShop.vue` |
| **Model** | `app/Models/ShopSession.php` |

---

### Amar — Fitur: Close Shop
> Menangani penutupan toko, rekapitulasi uang fisik, dan generate laporan akhir sesi.

| Layer | File |
|-------|------|
| **Controller** | `app/Http/Controllers/Pos/ShopSessionController.php` → `showClose()`, `close()` |
| **Service** | `app/Services/ShopSessionService.php` → `closeSession()`, `calculateClosingSummary()` |
| **Request** | `app/Http/Requests/CloseDailyShopRequest.php` |
| **View** | `resources/js/Pages/Pos/CloseShop.vue` |
| **Component** | `resources/js/Pages/Pos/Partials/CloseShopItem.vue` |

---

### Rivaldi — Fitur: Box Order
> Menangani transaksi penjualan box catering, pemilihan template, dan kalkulasi harga.

| Layer | File |
|-------|------|
| **Controller** | `app/Http/Controllers/Pos/BoxOrderController.php` |
| **Service** | `app/Services/BoxOrderService.php` |
| **View** | `resources/js/Pages/Pos/Box/Index.vue`, `resources/js/Pages/Pos/Box/Create.vue` |
| **Model** | `app/Models/BoxOrder.php`, `app/Models/BoxOrderItem.php`, `app/Models/BoxTemplate.php` |

---

### Belva — System Setup & Admin Dashboard
> Setup instalasi awal, konfigurasi database, arsitektur backend, dan dashboard admin.

| Layer | File |
|-------|------|
| **Routes** | `routes/web.php` |
| **Seeders** | `database/seeders/DatabaseSeeder.php`, `UserSeeder.php`, `PartnerSeeder.php`, dll. |
| **Config** | `composer.json`, `package.json`, `tailwind.config.js` |
| **Admin Controller** | `app/Http/Controllers/Admin/DashboardController.php` |
| **Admin Service** | `app/Services/AdminDataService.php`, `app/Services/DashboardService.php` |
| **Admin View** | `resources/js/Layouts/AdminLayout.vue`, `resources/js/Pages/Admin/Dashboard.vue` |

---

## 📁 Struktur Folder Utama

```
posita/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controller untuk admin panel
│   │   └── Pos/            # Controller untuk kasir/POS
│   ├── Models/             # Eloquent Models
│   └── Services/           # Business Logic Layer
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/            # Data dummy untuk development
├── resources/js/
│   ├── Components/         # Reusable Vue components
│   ├── Layouts/            # AdminLayout, EmployeeLayout
│   └── Pages/              # Halaman Inertia (Pos/, Admin/)
└── routes/
    └── web.php             # Route definitions
```

---

## 📄 License

MIT License - Silakan gunakan dan modifikasi sesuai kebutuhan.

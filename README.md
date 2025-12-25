# POSITA - Point of Sales & Consignment Management System

---

## 1. 📋 Project Overview

**Posita** adalah sistem aplikasi Point of Sale (POS) berbasis web yang dirancang khusus untuk manajemen usaha dengan model konsinyasi (titip jual) dan penyewaan box (box rental). 

### Fitur Utama

| Modul | Deskripsi |
| :--- | :--- |
| **Buka Toko (Open Shop)** | Memulai sesi kasir dengan input kas awal dan pemilihan barang konsinyasi dari mitra |
| **Tutup Toko (Close Shop)** | Menutup sesi dengan input sisa stok, kalkulasi revenue/profit, dan rekonsiliasi kas |
| **Order Box** | Manajemen pemesanan box dengan countdown pengambilan, upload bukti bayar, dan kwitansi |
| **Admin Dashboard** | Statistik penjualan dengan grafik tren (harian/mingguan/bulanan), riwayat sesi, dan order |
| **Manajemen Partner** | Pengelolaan data mitra konsinyasi beserta produk template mereka |
| **Laporan** | Generate PDF laporan harian dan per-sesi dengan detail konsinyasi |

---

## 2. 💻 Technology Stack Requirements

Project ini dibangun menggunakan teknologi modern:

| Kategori | Teknologi | Versi |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel | 11.x |
| **Frontend Framework** | Vue.js (Composition API) | 3.x |
| **Routing/Glue** | Inertia.js | 2.x |
| **Styling** | Tailwind CSS | 3.x |
| **Database** | MySQL / MariaDB | 8.x / 10.x |
| **Build Tool** | Vite | 5.x |
| **Package Manager** | Composer, NPM | - |
| **PDF Generator** | barryvdh/laravel-dompdf | - |
| **Activity Logging** | spatie/laravel-activitylog | - |

### Prasyarat Sistem
- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL 8.x / MariaDB 10.x
- Git

---

## 3. 📦 Step by Step Installation

### Langkah 1: Clone Repository
```bash
git clone https://github.com/username/posita.git
cd posita
```

### Langkah 2: Install Backend Dependencies
```bash
composer install
```

### Langkah 3: Install Frontend Dependencies
```bash
npm install
```

### Langkah 4: Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi database:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=posita_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Langkah 5: Generate App Key
```bash
php artisan key:generate
```

### Langkah 6: Migrasi Database
```bash
php artisan migrate
```

### Langkah 7: Seed Data Demo (Opsional)
Untuk mengisi database dengan data demo:
```bash
php artisan db:seed
```

Atau untuk reset dan seed ulang:
```bash
php artisan migrate:fresh --seed
```

Untuk data tren 30 hari (untuk testing dashboard):
```bash
php artisan db:seed --class=TrendSeeder
```

### Langkah 8: Link Storage
```bash
php artisan storage:link
```

### Langkah 9: Jalankan Aplikasi
Buka dua terminal terpisah:

**Terminal 1 (Backend Server):**
```bash
php artisan serve
```

**Terminal 2 (Frontend Hot-Reload):**
```bash
npm run dev
```

### Langkah 10: Akses Aplikasi
Buka browser dan kunjungi: `http://localhost:8000`

### Demo Account
| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@posita.com` | `password` |
| **Kasir (Staff)** | `kasir1@posita.com` | `password` |

---

## 4. 👥 Task Allocation

### Nurita — Open Shop Feature (Buka Toko)
Semua item yang terkait fitur **Buka Toko**:

**Controllers:**
- `app/Http/Controllers/Pos/ShopSessionController.php` → method `open()`, `storeOpen()`
- `app/Http/Controllers/Pos/PosController.php` → method `index()` untuk redirect logic

**Services:**
- `app/Services/ShopSessionService.php` → method `startSession()`, `getActiveSession()`, `hasActiveSession()`

**Actions:**
- `app/Actions/StartDailyShopAction.php` → logic memulai sesi dengan barang konsinyasi

**Models:**
- `app/Models/ShopSession.php` → status 'open', relasi ke user dan consignments
- `app/Models/DailyConsignment.php` → menyimpan barang konsinyasi per sesi
- `app/Models/Partner.php` → data mitra
- `app/Models/ProductTemplate.php` → template produk mitra

**Frontend:**
- `resources/js/Pages/Pos/OpenShop.vue` → form input kas awal dan pilih barang konsinyasi
- `resources/js/Pages/Pos/Index.vue` → halaman utama POS setelah sesi aktif

**Migrations:**
- `database/migrations/*_create_shop_sessions_table.php`
- `database/migrations/*_create_daily_consignments_table.php`
- `database/migrations/*_create_partners_table.php`
- `database/migrations/*_create_product_templates_table.php`

---

### Amar — Close Shop Feature (Tutup Toko)
Semua item yang terkait fitur **Tutup Toko**:

**Controllers:**
- `app/Http/Controllers/Pos/ShopSessionController.php` → method `close()`, `storeClose()`

**Services:**
- `app/Services/ShopSessionService.php` → method `closeSession()`, `calculateClosingSummary()`
- `app/Services/ConsignmentService.php` → method `updateSoldQuantity()`

**Actions:**
- `app/Actions/CloseDailyShopAction.php` → logic kalkulasi akhir dan tutup sesi

**Models:**
- `app/Models/ShopSession.php` → status 'closed', field `closing_cash_system`, `closing_cash_actual`, `notes`
- `app/Models/DailyConsignment.php` → field `qty_sold`, `qty_remaining`, `subtotal_income`

**Frontend:**
- `resources/js/Pages/Pos/CloseShop.vue` → form input sisa stok, kas aktual, kalkulasi selisih

**Logic Kalkulasi:**
- Revenue: `qty_sold × selling_price`
- Profit: `qty_sold × (selling_price - base_price)`
- Cash Discrepancy: `expected_cash - actual_cash` (positif = kurang, negatif = lebih)

---

### Rivaldi — Box Order Feature (Order Box)
Semua item yang terkait fitur **Order Box**:

**Controllers:**
- `app/Http/Controllers/Pos/BoxOrderController.php` → method `index()`, `create()`, `store()`, `uploadProof()`, `updateStatus()`, `downloadReceipt()`

**Services:**
- `app/Services/BoxOrderService.php` → method `createOrder()`, `uploadPaymentProof()`, `updateOrderStatus()`, `cancelOrderWithReason()`, `generateReceipt()`

**Models:**
- `app/Models/BoxOrder.php` → status enum ['pending', 'paid', 'completed', 'cancelled'], `cancellation_reason`
- `app/Models/BoxOrderItem.php` → line items per order
- `app/Models/BoxTemplate.php` → template box (heavy_meal, snack_box)

**Frontend:**
- `resources/js/Pages/Pos/Box/Index.vue` → list order, countdown timer, status modal, notifikasi otomatis
- `resources/js/Pages/Pos/Box/Create.vue` → form pembuatan order baru

**Views (PDF):**
- `resources/views/reports/box-receipt.blade.php` → template kwitansi

**Migrations:**
- `database/migrations/*_create_box_orders_table.php`
- `database/migrations/*_create_box_order_items_table.php`
- `database/migrations/*_create_box_templates_table.php`
- `database/migrations/*_add_cancellation_reason_to_box_orders_table.php`

**Routes:**
- `/pos/box` → index
- `/pos/box/create` → create form
- `/pos/box` (POST) → store
- `/pos/box/{id}/status` (PATCH) → update status
- `/pos/box/{id}/receipt` → download kwitansi

---

### Belva — Remaining Project Implementation
Implementasi sisa proyek meliputi:

**Admin Dashboard:**
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Services/DashboardService.php` → `getSalesTrend()`, `getSalesComparison()`, `getGlobalProfit()`, `getSessionHistory()`, `getBoxOrderHistory()`
- `resources/js/Pages/Admin/Dashboard.vue` → line chart, filter tabs, riwayat sesi/order, detail modal

**Partner & User Management:**
- `app/Http/Controllers/Admin/PartnerController.php`
- `app/Http/Controllers/Admin/UserManagementController.php`
- `app/Http/Controllers/Admin/BoxTemplateController.php`

**Reporting System:**
- `app/Services/ReportService.php` → generate PDF laporan
- `resources/views/reports/*.blade.php` → templates PDF

**Database Optimization:**
- Migrasi dengan indexing: `database/migrations/*_add_performance_indexes.php`
- Seeder demo: `database/seeders/DatabaseSeeder.php`, `TrendSeeder.php`

**UI/Layouts:**
- `resources/js/Layouts/AdminLayout.vue`
- `resources/js/Layouts/EmployeeLayout.vue`
- `resources/js/Components/*.vue`
- `resources/js/utils/formatMoney.js`

**Authentication & Middleware:**
- Role-based routing di `bootstrap/app.php`
- Middleware logic untuk redirect berdasarkan role

---

## 📁 Project Structure

```
posita/
├── app/
│   ├── Actions/          # Business actions (StartDailyShopAction, CloseDailyShopAction)
│   ├── Http/Controllers/ # Admin & Pos controllers
│   ├── Models/           # Eloquent models
│   └── Services/         # Business logic services
├── database/
│   ├── migrations/       # Database schema
│   └── seeders/          # Demo data
├── resources/
│   ├── js/
│   │   ├── Layouts/      # Admin & Employee layouts
│   │   ├── Pages/        # Vue pages (Admin/, Pos/)
│   │   └── utils/        # Helper functions
│   └── views/
│       └── reports/      # PDF templates
└── routes/
    └── web.php           # Application routes
```

---

*© 2024/2025 Posita Development Team.*
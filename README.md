## 📁 Project Structure

```
etech_care/
├── 📂 app/
│   ├── 📂 Http/Controllers/
│   │   ├── CustomerAuthController.php     (Login/Logout)
│   │   ├── CustomerTicketController.php   (CRUD operations)
│   │   └── CustomerTicketEditController.php (Update logic)
│   ├── 📂 Models/
│   └── 📂 Requests/ (Form validation)
│
├── 📂 resources/
│   ├── 📂 views/
│   │   ├── 📂 customer/tickets/
│   │   │   ├── dashboard.blade.php    (Main CRUD interface)
│   │   │   ├── show.blade.php         (View details)
│   │   │   └── edit.blade.php         (Edit form)
│   │   └── 📂 auth/
│   │       └── login.blade.php
│   └── 📂 css/ & 📂 js/
│
├── 📂 public/
│   ├── 📂 css/
│   │   ├── customer-dashboard.css     (Main styles)
│   │   └── ticket-wizard.css          (Modal styles)
│   ├── 📂 js/
│   │   ├── customer-dashboard.js      (Form wizard)
│   │   ├── customer-ticket-filters.js (Search/filter)
│   │   └── customer-ticket-tabs.js    (Tabs)
│   ├── 📂 images/
│   │   ├── HARDWARE.jpg
│   │   ├── SOFTWARE.jpg
│   │   ├── NETWORK.png
│   │   └── ACCOUNT.png
│
├── 📂 routes/
│   └── web.php (All routes)
│
├── 📂 database/
│   ├── 📂 migrations/
│   └── seeders/
│
├── 📂 tests/
│   ├── 📂 Unit/
│   └── 📂 Feature/
│
├── vite.config.js
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## 🚀 Installation & Setup

### Requirements
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Node.js 16+
- XAMPP/WAMP or local MySQL server

### Step 1: Clone Repository
```bash
git clone https://github.com/yourusername/bu-care-system.git
cd bu-care-system/etech_care
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bu_care_system
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Create Database & Run Migrations
```bash
# Create database
mysql -u root -p
CREATE DATABASE bu_care_system;
EXIT;

# Run migrations
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### Step 5: Install Frontend Dependencies
```bash
npm install
```

### Step 6: Build Assets
```bash
npm run build
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

### Test Credentials
```
Email:    student@bicol.edu
Password: password123
```

---

## 🧪 Testing

### Run Tests
```bash
# All tests
php artisan test

# Specific test class
php artisan test --filter=TicketCreationTest

# With detailed output
php artisan test --verbose
```

### Manual Testing Checklist
- [ ] Create ticket with all 5 wizard steps
- [ ] Edit ticket description and location
- [ ] Search tickets (ID, category, description)
- [ ] Filter by status tabs
- [ ] Close ticket and verify soft-delete
- [ ] View closed tickets in "Closed" tab
- [ ] Test form validation
- [ ] Test responsive design (mobile)
- [ ] Logout and verify session cleared

---




## 📚 References & Resources

- [Laravel 11 Documentation](https://laravel.com/docs/11)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Font Awesome Icons](https://fontawesome.com/search)
- [PHP Manual](https://www.php.net/manual/)
- [MySQL 8.0 Reference](https://dev.mysql.com/doc/)

---

## 📞 Support

For questions or issues with this project:
1. Check the [GitHub Issues](https://github.com/yourusername/bu-care-system/issues)
2. Review the [Laravel Documentation](https://laravel.com/docs)
3. Contact the development team

---

**Last Updated:** May 25, 2026  
**Version:** 1.0.0  
**Status:** ✅ Complete

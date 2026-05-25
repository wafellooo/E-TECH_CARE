# E-TECH CUSTOMER SUPPORT CARE (Computer And Response Emergency)
## Web Systems and Technologies - Final Project/Exam

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?style=for-the-badge)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Complete-green?style=for-the-badge)

A functional **ticket management module** for Bicol University's CARE (Customer Assistance & Resolution for E-Support) System. This project is a modern, full-stack implementation of a student/faculty IT helpdesk portal with complete **CRUD operations**.

---

## 📋 Project Overview

**Module Title:** Customer Ticket Submission and Tracking Module 
**Institution:** Bicol University  
**Subject:** Web Systems and Technologies (Final Project)  
**Submission Date:** May 25, 2026

### Module Scope
This CRUD module enables students and faculty to:
- ✅ **Create** support tickets through an interactive 5-step wizard
- ✅ **Read** ticket history with real-time search and filtering
- ✅ **Update** ticket descriptions and location information
- ✅ **Delete** tickets safely using soft-delete pattern

### Key Features
- Multi-step form wizard with validation
- Live search and filtering (no page reload)
- Responsive dashboard with statistics widgets
- Soft-delete pattern for audit trails
- Session-based authentication
- Toast notification system
- Category-based ticket organization
- Device and location tracking

---

## 🛠️ Technology Stack

### Frontend
- **Framework:** Bootstrap 5.3.2 (CSS)
- **Icons:** Font Awesome 6.4.0
- **JavaScript:** Vanilla ES6+ (no jQuery)
- **Build Tool:** Vite.js for asset compilation
- **Styling:** Custom CSS with CSS variables

### Backend
- **Framework:** Laravel 11 (PHP 8.1+)
- **Database:** MySQL 8.0
- **ORM:** Eloquent & Query Builder
- **Authentication:** Custom session-based auth
- **Templating:** Blade Template Engine

### Architecture
- **Pattern:** MVC (Model-View-Controller)
- **Session:** File-based session driver
- **Routes:** RESTful API design
- **Middleware:** CSRF protection

---

## 🗄️ Database Schema

### Core Tables

**customer** - User accounts
```
customer_id (PK) → Integer, Auto-increment
c_fname → VARCHAR(100)
c_lname → VARCHAR(100)
c_email → VARCHAR(150), UNIQUE
c_password → VARCHAR(255)
cust_type → VARCHAR(50) [student|faculty|staff]
created_at → TIMESTAMP
```

**ticket** - Support requests (MAIN CRUD TABLE)
```
ticket_id (PK) → Integer, Auto-increment
ticket_number → VARCHAR(20), UNIQUE [TKT001, TKT002...]
customer_id (FK) → Integer
category → VARCHAR(50) [HARDWARE|SOFTWARE|NETWORK|ACCOUNT]
description → TEXT, 10-500 chars
status → VARCHAR(20) [pending|resolved|closed]
device_id (FK) → Integer (nullable)
location_id (FK) → Integer (nullable)
date_created → TIMESTAMP
date_resolved → TIMESTAMP (nullable)
```

**device_model** - Device inventory
```
dm_id (PK) → Integer
model_name → VARCHAR(100)
manufacturer → VARCHAR(50)
device_type → VARCHAR(50) [laptop|desktop|tablet|mobile]
```

**location** - Campus locations
```
loc_id (PK) → Integer
building_id (FK) → Integer
room_no → VARCHAR(20)
floor → Integer
campus_id → Integer (nullable)
```

---

## ✨ CRUD Operations

### 1️⃣ CREATE - Add New Ticket

**Endpoint:** `POST /customer/tickets`

**Form:** 5-Step Interactive Wizard
```
Step 1: Category Selection
   → HARDWARE | SOFTWARE | NETWORK | ACCOUNT
   → Images displayed for each category

Step 2: Problem Description
   → Text area (10-500 characters)
   → Real-time character counter
   → Validation feedback

Step 3: Device Information
   → Option 1: Select from registered devices
   → Option 2: Enter unregistered device details

Step 4: Location Details
   → Campus selection (dropdown)
   → Building selection (dropdown)
   → Room number (text)
   → Floor number (number)

Step 5: Review & Confirm
   → Display all entered information
   → Back button to edit
   → Submit button to create
```

**Validation Rules:**
- Category: Required (must select one)
- Description: 10-500 characters, required
- Device: Required (registered or unregistered)
- Location: All fields required

**Success Response:**
```
✅ Ticket created successfully!
   Ticket ID: TKT051
   Category: Hardware Support
   Status: Pending
   [Modal closes, dashboard refreshes]
```

---

### 2️⃣ READ - View & Search Tickets

**Endpoint:** `GET /customer/tickets`

**Dashboard Display:**
```
┌─────────────────────────────────────────┐
│ Welcome back, [Customer Name]!           │
│ You have X total ticket(s) on record    │
└─────────────────────────────────────────┘

[Statistics Cards]
├─ Total Issues: X
├─ Issues Pending: Y
└─ Resolved Issues: Z

[Filter Tabs]
All | Pending | Resolved | Closed

[Search Box]
🔍 Search by ID, category, or description…

[Ticket Table]
├─ Ticket ID
├─ Category
├─ Description
├─ Device
├─ Status
├─ Created Date
└─ Actions (View/Edit)
```

**Filter Features:**
- **All:** Shows pending + resolved (excludes closed)
- **Pending:** status = 'pending'
- **Resolved:** status = 'resolved'
- **Closed:** status = 'closed' (soft-deleted)

**Search Features:**
- Real-time (no page reload)
- Searches: ID, Category, Description
- Case-insensitive matching
- Instant result highlighting

---

### 3️⃣ UPDATE - Edit Ticket

**Endpoint:** `POST /customer/tickets/{id}/update`

**Editable Fields:**
```
Description (10-500 chars)
└─ Textarea with character counter
└─ Validation feedback

Location
├─ Campus (dropdown)
├─ Building (dropdown)
├─ Room Number (text)
└─ Floor (number)
```

**Read-only Fields:**
- Ticket ID
- Category
- Status
- Created Date

**Success Response:**
```
✅ Ticket updated successfully
   Updated fields: Description, Location
```

---

### 4️⃣ DELETE - Close/Archive Ticket

**Endpoint:** `DELETE /customer/tickets/{id}`

**Pattern:** Soft Delete (status update, not removal)

**Confirmation Modal:**
```
⚠️  Close This Ticket?

Are you sure you want to close this support ticket?
You can review closed tickets anytime in the "Closed" tab.

Ticket ID: TKT051
Category: Hardware Support

[Keep Ticket] [Close Ticket]
 (Blue)       (Red)
```

**Safety Measures:**
- Confirmation required
- Destructive button visually distinct
- Data preserved in database
- Recoverable in "Closed" tab
- `date_resolved` timestamp recorded

**Success Response:**
```
✅ Ticket closed successfully
   Ticket: TKT051 is now archived
   View in "Closed" tab
```

---

## 🎨 User Interface Features

### Live Filtering (No Reload)
- Searches across multiple fields simultaneously
- Instant visual feedback
- Updates visible row count
- Preserves table state

### Toast Notifications
```
Success (Green):
   ✅ Success! Ticket created successfully

Error (Red):
   ❌ Error. Please fill all required fields

Info (Blue):
   ℹ️  Information. Ticket TKT051 updated
```

### Responsive Design
- Mobile-first approach
- Collapsible sidebar navigation
- Touch-friendly form inputs
- Optimized table layout
- Responsive modal dialogs

### Accessibility
- ARIA labels on interactive elements
- Keyboard navigation support
- Color contrast compliant
- Screen reader friendly

---

## 🔐 Authentication & Authorization

### Login Flow
```
1. User enters email + password
2. Validated against customer table
3. Session created with:
   - customer_id (authorization)
   - customer_name (personalization)
   - customer_type (role-based features)
4. Redirected to dashboard
```

### Session Security
- CSRF tokens on all forms
- Session timeout on browser close
- No persistent login tokens
- Server-side session validation

### Authorization
- User can only view own tickets
- Checks `customer_id` on every request
- No cross-customer data access

---

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

## 📊 Comparison with Original

| Aspect | Original | Modern Implementation |
|--------|----------|----------------------|
| Framework | Basic PHP | Laravel 11 MVC |
| Frontend | jQuery + Bootstrap 3 | ES6 + Bootstrap 5 |
| Form Submission | Full page reload | AJAX with toasts |
| Delete Pattern | Hard DELETE | Soft DELETE |
| Search/Filter | Server-side reload | Client-side live |
| Mobile Support | Limited | Fully responsive |
| Notifications | Alert boxes | Toast system |
| Code Organization | Mixed concerns | Separated MVC |
| Validation | Server-only | Client + server |

---

## 💡 Key Technical Insights

### 1. Multi-Step Wizard Implementation
```javascript
// Tracks step progress
let activeStep = 1;
btnNext.onclick = () => { activeStep++; updateVisuals(); }
btnBack.onclick = () => { activeStep--; updateVisuals(); }
```

### 2. Soft Delete Pattern
```php
// Instead of DELETE, UPDATE status
DB::table('ticket')->update(['status' => 'closed', 'date_resolved' => now()]);
```

### 3. Live Search Filter
```javascript
// Real-time filtering without page reload
rows.forEach(row => {
  const matches = searchTerm.test(row.dataset.search);
  const statusMatch = filterStatus(row.dataset.status);
  row.style.display = (matches && statusMatch) ? '' : 'none';
});
```

### 4. Session-Based Authentication
```php
// Custom auth using sessions (not Laravel Fortify)
Session::put('customer_name', $customer->c_fname . ' ' . $customer->c_lname);
// In template: {{ session('customer_name') ?? 'Guest' }}
```

---

## 🎓 Individual Reflection

### Challenges Faced
1. **Form State Management:** Persisting multi-step form data required careful JavaScript state tracking
2. **Filter Logic:** "All" tab excluding closed tickets needed special boolean logic
3. **Soft Delete:** Distinguishing between closed/deleted vs. permanently removed data
4. **Responsive Tables:** Making table readable on mobile while preserving desktop layout
5. **Session Authentication:** Implementing custom auth instead of Laravel's built-in system

### Solutions Implemented
- Created state object for wizard form persistence
- Implemented explicit exclusion logic in filter conditions
- Used `status` field instead of hard DELETE
- Applied CSS media queries and table scrolling
- Leveraged Laravel Session facade directly

### Technical Achievements
✅ Full CRUD operations with validation  
✅ Real-time search without page reload  
✅ Responsive mobile-first design  
✅ Soft-delete audit trail  
✅ Multi-step form wizard  
✅ Toast notification system  
✅ Custom session authentication  
✅ Database relationship management  

### Future Improvements
- Implement ticket assignment to IT staff
- Add email notifications for ticket updates
- Create admin dashboard for support staff
- Add ticket priority levels
- Implement ticket categories with custom fields
- Add PDF export for tickets
- Implement full-text search with MySQL MATCH
- Add real-time updates using WebSockets

---

## 📚 References & Resources

- [Laravel 11 Documentation](https://laravel.com/docs/11)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Font Awesome Icons](https://fontawesome.com/search)
- [PHP Manual](https://www.php.net/manual/)
- [MySQL 8.0 Reference](https://dev.mysql.com/doc/)

---

## 📄 License & Submission

**Project Type:** Academic Final Examination  
**Subject:** Web Systems and Technologies  
**Submission Date:** May 25, 2026  
**Status:** ✅ Complete & Production-Ready

This project is submitted as coursework and demonstration of CRUD functionality in modern web development.

---

## 👨‍💻 How to Submit to GitHub

```bash
# Initialize git (if not already done)
git init

# Add all files
git add .

# Create initial commit
git commit -m "feat: BU CARE System - Ticket Management CRUD Module"

# Add remote repository
git remote add origin https://github.com/yourusername/bu-care-system.git

# Push to GitHub
git branch -M main
git push -u origin main
```

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

# MarineMonks - PHP/MySQL Website

## 🚀 Complete Conversion to PHP & MySQL

This is the fully converted MarineMonks website using **PHP** for the backend and **MySQL** for the database, as requested.

## 📁 Project Structure

```
/
├── admin/                  # Admin dashboard files
│   ├── index.php          # Admin dashboard
│   ├── upload-probables.php
│   ├── upload-study-material.php
│   ├── upload-mock-test.php
│   └── review-submission.php
├── backend/               # API endpoints
│   └── api.php           # RESTful API for frontend
├── config/               # Configuration files
│   └── db.php           # Database connection
├── css/                 # Stylesheets (unchanged)
├── js/                  # JavaScript files (unchanged)
├── uploads/             # File upload directories
│   ├── probables/
│   ├── study_materials/
│   ├── mock_tests/
│   └── answersheets/
├── index.php           # Homepage
├── login.php           # User login
├── logout.php          # User logout
├── signup.php          # User registration
├── probables.php       # Probables page
├── study-material.php  # Study materials page
├── mock-tests.php      # Mock tests page (premium locked)
├── pricing.php         # Pricing page
├── database_schema.sql # Database structure
└── insert_sample_data.php # Sample data script
```

## 🗄️ Database Tables

| Table | Description |
|-------|-------------|
| `users` | User accounts with premium membership flags |
| `probables` | PDF probables categorized by year |
| `study_materials` | Study materials (Written/Orals) with filters |
| `mock_tests` | Mock test definitions |
| `answersheets` | User submissions with feedback |

## 🔧 Installation Instructions

### 1. Prerequisites
- PHP 7.4+ with MySQL extension
- MySQL 5.7+ or MariaDB
- Web server (Apache/Nginx)

### 2. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE marine_monks;"

# Import schema
mysql -u root -p marine_monks < database_schema.sql

# Insert sample data
php insert_sample_data.php
```

### 3. Configuration
Edit `config/db.php` with your database credentials:
```php
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "marine_monks";
```

### 4. File Permissions
```bash
chmod 755 uploads/
chmod 755 uploads/probables/
chmod 755 uploads/study_materials/
chmod 755 uploads/mock_tests/
chmod 755 uploads/answersheets/
```

## 👥 Test User Accounts

| Username | Password | Type | Premium |
|----------|----------|------|---------|
| `admin` | `admin123` | Admin | Yes |
| `premiumuser` | `premium123` | User | Yes |
| `testuser` | `user123` | User | No |

## ✨ Features Implemented

### 🟦 Probables Page
- ✅ Categorized by year (2024, 2023, 2022, 2021)
- ✅ Search functionality
- ✅ Year filter dropdown
- ✅ View/download counters
- ✅ "Coming Soon" toggle for admin
- ✅ Preview & download buttons

### 🟨 Study Material Page
- ✅ Two tabs: Written | Orals
- ✅ **Written filters**: Subject, Topic, Author
- ✅ **Orals filters**: Function, Topic, Author
- ✅ Real-time search across all content
- ✅ View/download analytics
- ✅ "Coming Soon" toggle

### 🔒 Mock Test Page (Premium Locked)
- ✅ Premium membership required
- ✅ Three difficulty levels: Easy, Medium, Hard
- ✅ 3-hour countdown timer (JavaScript)
- ✅ Answer sheet upload (PDF/JPG)
- ✅ Confirmation: "Your answer sheet is being checked by a certified surveyor"
- ✅ Admin review dashboard

### 💳 Pricing Page
- ✅ **Basic Plan**: Free
- ✅ **Premium Plan**: ₹199/month
- ✅ Dummy "Become Premium" button
- ✅ FAQ section
- ✅ Easy content editing

## 🛠️ Backend Features (PHP)

### User Authentication
- ✅ Login/logout with sessions
- ✅ Password hashing (PHP `password_hash()`)
- ✅ Premium membership validation

### Admin Dashboard
- ✅ File upload for all content types
- ✅ Toggle "Coming Soon" status
- ✅ View analytics (views/downloads)
- ✅ Review mock test submissions
- ✅ Manage user premium status

### API Endpoints (`backend/api.php`)
- `GET /api.php?action=get_probables` - Fetch probables
- `GET /api.php?action=get_study_materials&type=written|orals` - Fetch study materials
- `POST /api.php?action=increment_views` - Update view count
- `POST /api.php?action=increment_downloads` - Update download count

## 🎨 Frontend (Unchanged)
- ✅ **HTML/CSS/JS**: Vanilla, responsive design
- ✅ **PDF.js**: PDF preview functionality
- ✅ **Mobile responsive**: Touch-friendly interface
- ✅ **Original styling**: All colors, fonts, animations preserved

## 🔐 Security Features
- Password hashing with `password_hash()`
- SQL injection prevention with prepared statements
- Session-based authentication
- File upload validation
- CSRF protection ready (can be added)

## 📊 Analytics & Tracking
- View counters for all content
- Download tracking
- User engagement metrics
- Admin analytics dashboard

## 🚀 Deployment Notes
- Ensure PHP MySQL extension is enabled
- Set proper file permissions for uploads
- Configure web server to handle PHP files
- Update database credentials in `config/db.php`
- Test all functionality after deployment

## 🆘 Troubleshooting

### Common Issues:
1. **Database connection failed**: Check credentials in `config/db.php`
2. **File upload errors**: Verify upload directory permissions
3. **Session issues**: Ensure PHP sessions are enabled
4. **Premium features not working**: Check user `is_premium_member` flag in database

## 📞 Support
For any issues or questions, refer to the code comments or database schema for implementation details.

---

**✅ All requested features have been successfully implemented and tested!**


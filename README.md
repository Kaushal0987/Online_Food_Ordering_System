# Online Food Ordering System - MongoDB Edition

A complete PHP-based online food ordering system migrated from MySQL to MongoDB.

## 🚀 Features

- **User Management**: Registration, login, and profile management
- **Admin Dashboard**: Complete admin panel for managing the system
- **Food Management**: Add, update, delete, and search food items
- **Order Management**: Place orders, track status, and manage deliveries
- **Revenue Tracking**: Real-time revenue calculation
- **Image Upload**: Support for food images
- **Search Functionality**: Search foods by title or description

## 📋 Requirements

- PHP 7.4 or higher
- MongoDB 4.0 or higher
- MongoDB Compass (recommended for database management)
- Composer (for dependency management)
- Apache/XAMPP server

## 🔧 Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup MongoDB

1. Install MongoDB from [mongodb.com](https://www.mongodb.com/try/download/community)
2. Install MongoDB Compass for GUI management
3. Start MongoDB service

### 3. Create Database

Open MongoDB Compass and create:
- Database: `online_food_ordering_system`
- Collections: `admins`, `users`, `foods`, `orders`

### 4. Add Initial Admin

In MongoDB Compass, insert into `admins` collection:

```json
{
  "username": "admin",
  "password": "21232f297a57a5a743894a0e4a801fc3"
}
```

Login credentials: **admin** / **admin**

### 5. Configure Site URL

Edit `config/constants.php` and update:
```php
define('SITEURL', 'http://localhost/Online_Food_Ordering_System/');
```

## 🎯 Usage

### Admin Panel
Access: `http://localhost/Online_Food_Ordering_System/admin/login.php`

Features:
- Dashboard with statistics
- Manage admins, users, foods, and orders
- Upload food images
- Track orders and revenue

### User Site
Access: `http://localhost/Online_Food_Ordering_System/`

Features:
- User registration and login
- Browse food menu
- Search for food items
- Place orders

## 📁 Project Structure

```
Online_Food_Ordering_System/
├── admin/                  # Admin panel files
│   ├── add-*.php          # Add operations
│   ├── update-*.php       # Update operations
│   ├── delete-*.php       # Delete operations
│   ├── manage-*.php       # List/view operations
│   └── partials/          # Reusable components
├── config/
│   └── constants.php      # MongoDB configuration
├── CSS/                   # Stylesheets
├── images/                # Uploaded images
│   ├── food/             # Food images
│   └── category/         # Category images
├── JS/                    # JavaScript files
├── particle-front/        # Frontend components
├── vendor/                # Composer dependencies
├── composer.json          # Dependency configuration
├── QUICK_START.txt        # Quick setup guide
├── MONGODB_SETUP.txt      # Detailed setup instructions
└── MIGRATION_SUMMARY.txt  # Migration details
```

## 🗄️ Database Schema

### Collections

#### admins
- `_id`: ObjectId (auto-generated)
- `username`: String
- `password`: String (MD5 hashed)

#### users
- `_id`: ObjectId (auto-generated)
- `username`: String
- `email`: String (unique)
- `address`: String
- `password`: String (MD5 hashed)

#### foods
- `_id`: ObjectId (auto-generated)
- `title`: String
- `description`: String
- `price`: Number (Float)
- `image_name`: String
- `active`: String ("Yes" or "No")

#### orders
- `_id`: ObjectId (auto-generated)
- `foodID`: String (ObjectId reference)
- `quantity`: Number (Integer)
- `total`: Number (Float)
- `order_date`: String (DateTime)
- `status`: String ("Ordered", "On Delivery", "Delivered", "Cancelled")
- `uID`: String (ObjectId reference)

## 🔐 Security Notes

⚠️ **Important for Production:**
1. Change default admin password immediately
2. Upgrade from MD5 to bcrypt for password hashing
3. Enable MongoDB authentication
4. Use environment variables for credentials
5. Implement input validation and sanitization
6. Use SSL/TLS for database connections

## 📚 Documentation Files

- **QUICK_START.txt** - Get started in 5 minutes
- **MONGODB_SETUP.txt** - Detailed setup instructions
- **MIGRATION_SUMMARY.txt** - Complete list of changes

## 🐛 Troubleshooting

### MongoDB Connection Issues
```
Error: "Class 'MongoDB\Client' not found"
Solution: Run composer install
```

```
Error: "Connection refused"
Solution: Ensure MongoDB is running (check MongoDB Compass)
```

### Composer Issues
```
Error: "vendor/autoload.php not found"
Solution: Run composer install in project root
```

## 🔄 Migration from MySQL

This project was successfully migrated from MySQL to MongoDB. Key changes:

- All MySQL queries converted to MongoDB operations
- Numeric auto-increment IDs replaced with MongoDB ObjectIds
- Support for flexible schema and document-based storage
- Improved scalability and performance

See `MIGRATION_SUMMARY.txt` for complete details.

## 📝 License

This project is open source and available for educational purposes.

## 🤝 Contributing

Feel free to fork this project and submit pull requests for improvements.

## 📧 Support

For issues and questions:
1. Check troubleshooting section
2. Review documentation files
3. Verify MongoDB connection and setup

---

**Version**: 2.0 (MongoDB Edition)  
**Last Updated**: January 2026  
**Database**: MongoDB  
**Language**: PHP 7.4+

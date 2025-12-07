# Chaitanya Resort - PHP Website Setup Guide

## Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB
- Apache/Nginx web server
- phpMyAdmin (for database management)

## Installation Steps

### 1. Database Setup

1. Open phpMyAdmin at `http://localhost/phpmyadmin/`
2. Import the database schema:
   - Click on "Import" tab
   - Select the file `database/schema.sql`
   - Click "Go" to import
   
   OR manually run the SQL commands from `database/schema.sql` in phpMyAdmin SQL tab

3. The database `chaitanya` will be created with:
   - Users table (for customers and admin)
   - Rooms table (6 rooms pre-configured)
   - Bookings table
   - Default admin user:
     - Email: `admin@chaitanyaresort.com`
     - Password: `admin123`

### 2. Database Configuration

Edit `config/database.php` if your MySQL credentials are different:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Change if needed
define('DB_PASS', '');            // Change if needed
define('DB_NAME', 'chaitanya');
```

### 3. File Permissions

Ensure the web server has read permissions for all files.

### 4. Access the Website

- Main Website: `http://localhost/chaitanyaresort.com/`
- Admin Panel: `http://localhost/chaitanyaresort.com/admin/`
  - Login with: `admin@chaitanyaresort.com` / `admin123`

## Features

### Customer Features
- Sign Up / Sign In
- View Gallery
- Book Rooms with:
  - Date selection
  - Room selection
  - Extra bed option
  - Availability checking
  - Price calculation
- Contact page with Google Maps
- Floating WhatsApp and Maps buttons

### Admin Features
- Dashboard with statistics
- View all bookings
- Update booking status
- Update payment status
- Manage rooms
- View users

## Room Configuration

- 6 rooms available
- Check-in: 2:00 PM
- Check-out: 12:00 PM
- Price: ₹2,000/day (double occupancy)
- Extra bed: ₹300/day
- All rooms have attached bathroom
- No AC in any room

## Payment Integration

Payment link is integrated: `https://u.payu.in/PAYUMN/Nr1i3MjpIYCb`

After booking, customers are redirected to this payment link.

## Restaurant Information

- Restaurant bill is separate from room booking
- Payable at hotel
- Fish preparation available (customers need to bring fish from Dapoli fish market)

## File Structure

```
chaitanyaresort.com/
├── admin/              # Admin panel
│   ├── index.php       # Dashboard
│   ├── bookings.php    # All bookings
│   ├── booking_details.php
│   ├── rooms.php       # Manage rooms
│   └── users.php       # View users
├── api/                # API endpoints
│   ├── auth.php        # Authentication
│   ├── booking.php     # Booking operations
│   ├── check_availability.php
│   └── rooms.php       # Room data
├── assets/             # Images and media
├── config/             # Configuration
│   └── database.php    # Database connection
├── css/                # Stylesheets
├── database/           # Database schema
│   └── schema.sql      # SQL schema file
├── includes/           # PHP includes
│   ├── auth.php        # Authentication functions
│   ├── header.php      # Page header
│   └── footer.php      # Page footer
├── js/                 # JavaScript files
│   ├── auth.js         # Authentication JS
│   ├── booking.js      # Booking JS
│   └── script.js       # General JS
├── index.php           # Home page (with booking functionality)
└── gallery.php         # Gallery page
```

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify credentials in `config/database.php`
- Ensure database `chaitanya` exists

### Session Issues
- Check PHP session directory is writable
- Verify `session_start()` is called in auth.php

### Permission Denied
- Check file permissions (should be readable by web server)
- Ensure PHP has write access to session directory

## Security Notes

- Change default admin password after first login
- Use strong passwords for production
- Keep PHP and MySQL updated
- Consider using prepared statements (already implemented)
- Enable HTTPS in production

## Support

For issues or questions, contact: info@chaitanyaresort.com


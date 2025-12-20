# Web Server Setup Guide

## Problem
You're getting "Not Found" error because your files are not in Apache's document root directory.

## Solution Options

### Option 1: Move Files to Apache Document Root (Recommended)

**For XAMPP:**
1. Copy all files from `C:\Project\chaitanyaresort.com\` to `C:\xampp\htdocs\chaitanyaresort.com\`
2. Access via: `http://localhost/chaitanyaresort.com/admin.html`

**For WAMP:**
1. Copy all files from `C:\Project\chaitanyaresort.com\` to `C:\wamp64\www\chaitanyaresort.com\`
2. Access via: `http://localhost/chaitanyaresort.com/admin.html`

**For MAMP (Windows):**
1. Copy all files from `C:\Project\chaitanyaresort.com\` to `C:\MAMP\htdocs\chaitanyaresort.com\`
2. Access via: `http://localhost:8888/chaitanyaresort.com/admin.html`

### Option 2: Configure Apache Virtual Host (Advanced)

Edit Apache's `httpd.conf` file and add:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/Project/chaitanyaresort.com"
    ServerName localhost
    <Directory "C:/Project/chaitanyaresort.com">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Then restart Apache.

### Option 3: Use PHP Built-in Server (Quick Test)

Open PowerShell in your project directory and run:
```powershell
cd C:\Project\chaitanyaresort.com
php -S localhost:8000
```

Then access: `http://localhost:8000/admin.html`

## Finding Your Apache Document Root

Check your Apache configuration file (`httpd.conf`) for the `DocumentRoot` directive. Common locations:
- XAMPP: `C:\xampp\apache\conf\httpd.conf`
- WAMP: `C:\wamp64\bin\apache\apache[version]\conf\httpd.conf`

## Quick Test

After moving files or configuring, test by accessing:
- `http://localhost/chaitanyaresort.com/test-db-connection.php`
- `http://localhost/chaitanyaresort.com/get-visitors.php`
- `http://localhost/chaitanyaresort.com/admin.html`


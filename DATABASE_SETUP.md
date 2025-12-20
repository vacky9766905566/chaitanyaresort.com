# Database Setup Guide

This guide will help you migrate from JSON file storage to MySQL database using phpMyAdmin.

## Step 1: Create Database and Table

1. Open phpMyAdmin in your browser (usually `http://localhost/phpmyadmin`)
2. Click on the **SQL** tab
3. Open the `database.sql` file in a text editor and copy its contents
4. Paste the SQL code into the SQL tab in phpMyAdmin
5. Click **Go** to execute the SQL
6. You should see a success message confirming that the database and table were created

The database will be named `chaitanya_resort` and will contain a table called `visitors`.

## Step 2: Configure Database Connection

1. Open the `config.php` file in a text editor
2. Update the following values according to your MySQL setup:

```php
define('DB_HOST', 'localhost');        // Usually 'localhost' for local development
define('DB_NAME', 'chaitanya_resort'); // Database name (should match what you created)
define('DB_USER', 'root');             // Your MySQL username
define('DB_PASS', '');                 // Your MySQL password (leave empty if no password)
```

3. Save the file

## Step 3: (Optional) Migrate Existing JSON Data

If you have existing visitor data in `data/visitors.json`, you can migrate it to the database:

1. Open `migrate-json-to-db.php` in your browser (e.g., `http://localhost/chaitanyaresort.com/migrate-json-to-db.php`)
2. Review the information displayed
3. Click **Start Migration** to import all JSON data into the database
4. The script will skip duplicate entries (based on timestamp)

**Note:** After migration, you can optionally delete the `data` folder if you no longer need the JSON files. However, it's recommended to keep a backup first.

## Step 4: Test the Setup

1. Test saving a visitor:
   - Open your website
   - Fill out the visitor form
   - Check phpMyAdmin → `chaitanya_resort` database → `visitors` table to see if the record was saved

2. Test retrieving visitors:
   - Visit `get-visitors.php` in your browser
   - You should see JSON output with all visitors

## Database Structure

The `visitors` table has the following structure:

| Column | Type | Description |
|--------|------|-------------|
| id | INT(11) | Primary key, auto-increment |
| timestamp | VARCHAR(50) | ISO timestamp string |
| name | VARCHAR(255) | Visitor name (NULL for WhatsApp clicks) |
| contact | VARCHAR(20) | Contact number (NULL for WhatsApp clicks) |
| whatsapp_number | VARCHAR(20) | WhatsApp number (NULL for regular visitors) |
| type | VARCHAR(20) | 'visitor' or 'whatsapp' |
| date | VARCHAR(20) | Formatted date string |
| time | VARCHAR(20) | Formatted time string |
| created_at | TIMESTAMP | Database timestamp (auto-set) |

## Troubleshooting

### Connection Error
- Verify your MySQL credentials in `config.php`
- Make sure MySQL/MariaDB is running
- Check that the database `chaitanya_resort` exists

### Table Not Found
- Run `database.sql` in phpMyAdmin again
- Make sure you're using the correct database

### Permission Errors
- Ensure your MySQL user has INSERT and SELECT permissions on the database
- For local development with XAMPP/WAMP, the default 'root' user usually has all permissions

## API Endpoints

### Save Visitor
- **URL:** `save-visitor.php`
- **Method:** POST
- **Content-Type:** application/json
- **Body:** 
  ```json
  {
    "timestamp": "2025-12-20T07:25:35.875Z",
    "name": "John Doe",
    "contact": "1234567890",
    "date": "20/12/2025",
    "time": "12:55:35"
  }
  ```

### Get Visitors
- **URL:** `get-visitors.php`
- **Method:** GET
- **Query Parameters (optional):**
  - `type`: Filter by type ('visitor' or 'whatsapp')
  - `limit`: Limit number of results
  - `order`: Sort order ('asc' or 'desc', default: 'desc')
- **Example:** `get-visitors.php?type=visitor&limit=10&order=desc`


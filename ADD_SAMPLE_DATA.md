# Adding Sample Data to Database

This guide explains how to add sample/test data to the `chaitanya_resort` database.

## Steps to Add Sample Data

### Option 1: Using phpMyAdmin (Recommended)

1. **Open phpMyAdmin** in your browser (usually `http://localhost/phpmyadmin`)

2. **Select the database:**
   - Click on `chaitanya_resort` in the left sidebar

3. **Go to SQL tab:**
   - Click on the **SQL** tab at the top

4. **Run the sample data script:**
   - Open `sample-data.sql` file in a text editor
   - Copy the entire contents
   - Paste it into the SQL query box in phpMyAdmin
   - Click **Go** to execute

5. **Verify the data:**
   - After execution, you should see a success message
   - Click on the `visitors` table to view the inserted records
   - You should see 31 records total (15 visitors + 16 WhatsApp clicks)

### Option 2: Using Command Line

If you prefer using MySQL command line:

```bash
mysql -u root -p chaitanya_resort < sample-data.sql
```

Or if you don't have a password:

```bash
mysql -u root chaitanya_resort < sample-data.sql
```

## What Data is Included?

The sample data includes:

### Regular Visitors (15 records)
- Names and contact numbers
- Type: `visitor`
- Dates: Dec 20-22, 2025
- Various Indian names for realistic testing

### WhatsApp Clicks (16 records)
- WhatsApp numbers only
- Type: `whatsapp`
- Dates: Dec 20-22, 2025
- Multiple clicks from different numbers

### Sample WhatsApp Numbers Used:
- `919421297851`
- `918390347209`
- `919112680201`

## Verification

After running the script, you can verify by running:

```sql
SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN type = 'visitor' THEN 1 ELSE 0 END) as visitor_count,
    SUM(CASE WHEN type = 'whatsapp' THEN 1 ELSE 0 END) as whatsapp_count
FROM visitors;
```

Expected result:
- total_records: 31
- visitor_count: 15
- whatsapp_count: 16

## Viewing the Data

You can view all records in phpMyAdmin:
1. Select `chaitanya_resort` database
2. Click on `visitors` table
3. Click **Browse** tab

Or use SQL query:
```sql
SELECT * FROM visitors ORDER BY created_at DESC;
```

## Testing Your Admin Panel

After adding the sample data:
1. Open `admin.html` via web server: `http://localhost/chaitanyaresort.com/admin.html`
2. Login with credentials (default: username: `chaitanya`, password: `Password@2025`)
3. You should see:
   - Total Clicks: 31 (or more if you have additional data)
   - Statistics showing visitors and WhatsApp clicks
   - Table displaying all entries

## Notes

- The timestamps are set to recent dates (Dec 20-22, 2025) for testing
- Contact numbers are 10-digit Indian mobile numbers
- WhatsApp numbers include country code (91 for India)
- You can modify the data in `sample-data.sql` to match your requirements


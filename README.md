# Chaitanya Resort - PHP Website

A complete PHP-based booking system for Chaitanya Resort with MySQL database integration.

## Quick Start

1. **Import Database**
   - Open phpMyAdmin at `http://localhost/phpmyadmin/`
   - Import `database/schema.sql`
   - Database `chaitanya` will be created with all tables

2. **Configure Database**
   - Edit `config/database.php` with your MySQL credentials if needed

3. **Access Website**
   - Main site: `http://localhost/chaitanyaresort.com/`
   - Admin: `http://localhost/chaitanyaresort.com/admin/`
   - Admin login: `admin@chaitanyaresort.com` / `admin123`

## Features

✅ User authentication (Sign Up/Sign In)  
✅ Room booking system with availability checking  
✅ 6 rooms with pricing (₹2,000/day + ₹300/extra bed)  
✅ Admin panel for managing bookings  
✅ Payment integration (PayU)  
✅ Responsive design  
✅ Floating WhatsApp and Maps buttons  
✅ Gallery page  
✅ Contact page with Google Maps  

## Database Structure

- **users**: Customer and admin accounts
- **rooms**: Room information (6 rooms)
- **bookings**: All booking records

## Admin Panel Features

- Dashboard with statistics
- View and manage all bookings
- Update booking and payment status
- Manage room details
- View user list

## Room Details

- Check-in: 2:00 PM
- Check-out: 12:00 PM
- Price: ₹2,000/day (double occupancy)
- Extra bed: ₹300/day
- All rooms: Attached bathroom, No AC

For detailed setup instructions, see [SETUP.md](SETUP.md)

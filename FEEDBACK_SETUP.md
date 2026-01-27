# Feedback/Testimonial System Setup Guide

## Overview
This document explains the changes made to implement:
1. Visitor modal opens only when WhatsApp button is clicked (not on page load)
2. Form submission redirects to WhatsApp after saving visitor info
3. New feedback/testimonial section with pagination

## Changes Made

### 1. Visitor Modal Changes
- **File**: `js/script.js`
- **Changes**:
  - Removed automatic modal display on page load
  - Modal now opens when WhatsApp floating button is clicked
  - After form submission, visitor info is saved and user is redirected to WhatsApp

### 2. Feedback/Testimonial System

#### Database Setup
- **File**: `feedback.sql`
- **Action Required**: Run this SQL script in your MySQL database to create the `feedbacks` table

```sql
-- Execute feedback.sql in your database
mysql -u root -p chaitanya_resort < feedback.sql
```

#### PHP Endpoints Created
1. **save-feedback.php** - Saves feedback submissions
2. **get-feedbacks.php** - Retrieves feedbacks with pagination

#### Frontend Changes
- **index.php**: Added feedback section with form and display area
- **includes/navigation.php**: Added "अभिप्राय" (Feedback) link to navigation
- **css/styles.css**: Added styles for feedback form, cards, and pagination
- **js/script.js**: Added JavaScript for form submission and pagination

## Database Table Structure

The `feedbacks` table includes:
- `id` - Primary key
- `name` - Visitor name (required)
- `email` - Email address (optional)
- `contact` - Contact number (optional, 10 digits)
- `rating` - Rating from 1 to 5 (optional)
- `message` - Feedback message (required)
- `status` - Moderation status: 'pending', 'approved', 'rejected' (default: 'pending')
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Setup Instructions

### Step 1: Create Database Table
```bash
mysql -u root -p chaitanya_resort < feedback.sql
```

Or manually execute the SQL in `feedback.sql` using phpMyAdmin or MySQL client.

### Step 2: Verify Files
Ensure these files exist:
- `feedback.sql`
- `save-feedback.php`
- `get-feedbacks.php`
- Updated `index.php` (with feedback section)
- Updated `js/script.js` (with feedback functionality)
- Updated `css/styles.css` (with feedback styles)

### Step 3: Test the System
1. Visit the website
2. Scroll to the feedback section (#feedback)
3. Submit a test feedback
4. Verify it appears in the database
5. Check pagination if you have more than 6 feedbacks

## Features

### Feedback Form
- Name (required)
- Email (optional)
- Contact Number (optional, 10 digits)
- Rating (optional, 1-5 stars)
- Message (required)

### Display
- Shows only approved feedbacks
- Pagination (6 feedbacks per page)
- Responsive design
- Star ratings display
- Date formatting in Marathi locale

### Moderation
- All feedbacks are saved with status 'pending'
- Only approved feedbacks are displayed
- Admin can change status in database to 'approved' or 'rejected'

## Admin Actions

To approve/reject feedbacks, update the database:
```sql
-- Approve a feedback
UPDATE feedbacks SET status = 'approved' WHERE id = 1;

-- Reject a feedback
UPDATE feedbacks SET status = 'rejected' WHERE id = 1;

-- View all pending feedbacks
SELECT * FROM feedbacks WHERE status = 'pending';
```

## Notes

- The visitor modal no longer appears automatically on page load
- Clicking WhatsApp buttons now shows the visitor modal first
- After submitting visitor info, users are redirected to WhatsApp
- Feedback system is fully functional with pagination
- All feedbacks require moderation before display

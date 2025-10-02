# Email Notifications Setup

## ✅ What's Been Added

### 1. Contact Form Submissions
- Get Involved form now saves to database
- Admin receives email notification for each submission
- View all submissions in admin dashboard

### 2. Report Notifications  
- Reports now send email to admin when submitted
- Email includes all report details

### 3. Database Table
- New `contact_submissions` table created
- Stores: name, email, interest, message, status, response

## 📋 Setup Instructions

### Step 1: Import Database
```bash
mysql -u root -p odpm_db < c:\AppServ\www\ODPM\db_contact_submissions.sql
```

### Step 2: Configure Email Settings
Edit `config.php` and update email settings:

```php
'email' => [
    'admin_email' => 'info@odpmnigeria.org',  // Where notifications are sent
    'from_email' => 'noreply@odpmnigeria.org',
    'from_name' => 'ODPM Nigeria',
    'smtp_host' => 'localhost',  // Your SMTP server
    'smtp_port' => 25,
    'smtp_username' => '',  // If needed
    'smtp_password' => '',  // If needed
    'smtp_secure' => '',  // 'tls' or 'ssl' or leave empty
],
```

### Step 3: Test Email (Optional)
For local testing, you can use:
- **MailHog** (recommended for development)
- **XAMPP Mercury Mail** (if using XAMPP)
- **Gmail SMTP** (for production)

#### Using Gmail SMTP:
```php
'smtp_host' => 'smtp.gmail.com',
'smtp_port' => 587,
'smtp_username' => 'your-email@gmail.com',
'smtp_password' => 'your-app-password',  // Generate from Google Account
'smtp_secure' => 'tls',
```

## 🎯 Features

### Admin Dashboard
- **New Card**: "Contact Submissions" - View all Get Involved form submissions
- **Email Notifications**: Automatic emails sent to admin for:
  - New contact form submissions
  - New report submissions

### Contact Submissions Page
**URL**: `http://localhost/ODPM/admin/manage-contacts.php`

Features:
- View all submissions
- Filter by status (New, Read, Responded)
- Update status
- Add response notes
- Delete submissions
- Click email to send reply

### Email Templates
Beautiful HTML emails with:
- ODPM branding colors
- All submission details
- Direct link to admin dashboard

## 📧 Email Flow

1. **User submits Get Involved form** →
2. **Saved to database** →
3. **Email sent to admin** →
4. **Admin views in dashboard** →
5. **Admin updates status/responds**

## 🔧 Troubleshooting

### Emails not sending?
1. Check PHP `mail()` is enabled
2. Verify SMTP settings in config.php
3. Check spam folder
4. Test with: `php -r "mail('test@example.com', 'Test', 'Test message');"`

### For Development (No Real Emails):
Comment out the `send_email()` calls in:
- `public/contact-submit.php` (line 51)
- `public/reports.php` (line 80)

## 📁 Files Created/Modified

**New Files:**
- `db_contact_submissions.sql` - Database table
- `public/contact-submit.php` - Form handler
- `admin/manage-contacts.php` - Admin page
- `EMAIL_SETUP.md` - This file

**Modified Files:**
- `config.php` - Added email settings
- `includes/functions.php` - Added `send_email()` function
- `public/index.php` - Updated form action
- `public/reports.php` - Added email notification
- `admin/dashboard.php` - Added contact submissions card

## ✨ Ready to Use!

1. Import the database
2. Configure email settings
3. Test the Get Involved form
4. Check admin dashboard for submissions

All done! 🎉

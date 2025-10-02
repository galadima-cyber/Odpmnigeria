# ODPM Nigeria - Quick Setup Guide

## ✅ Step 1: Database Setup

### 1.1 Create Database
Open phpMyAdmin or MySQL CLI and run:
```sql
CREATE DATABASE odpm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 1.2 Import Schema
Import the `db.sql` file:
- **phpMyAdmin**: Select `odpm_db` → Import → Choose `db.sql` → Go
- **CLI**: `mysql -u root -p odpm_db < c:\AppServ\www\ODPM\db.sql`

## ✅ Step 2: Create Admin User

### 2.1 Generate Password Hash
Visit: `http://localhost/ODPM/hash.php`

You should see a hash like: `$2y$10$abcdef...`

Copy this hash.

### 2.2 Insert Admin User
In phpMyAdmin, run this SQL (replace `YOUR_HASH_HERE` with the hash from step 2.1):

```sql
INSERT INTO users (email, name, password_hash, role) 
VALUES ('admin@odpm.com', 'Admin', 'YOUR_HASH_HERE', 'admin');
```

Example with actual hash:
```sql
INSERT INTO users (email, name, password_hash, role) 
VALUES ('admin@odpm.com', 'Admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

## ✅ Step 3: Seed Initial Content

Run these SQL commands to create pages for About, Founder, and Executives:

```sql
-- About page (already seeded by db.sql)
UPDATE pages SET content = 'ODPMNIGERIA is a dynamic consortium of young activists united by a shared commitment to driving positive change across Nigeria. We focus on community development, humanitarian initiatives, and political activism to create a more inclusive, equitable, and empowered society.' WHERE slug = 'about';

-- Founder page
INSERT INTO pages (slug, title, content) VALUES 
('founder', 'Founder - Umar Bello Galadima', 
'Umar Bello Galadima is the visionary founder and president of ODPM Nigeria. With over 10 years of experience in political activism, community development, and social work, Umar has dedicated his life to empowering young Nigerians and creating positive change across the country.

His journey began as a young activist in Kano State, where he witnessed firsthand the challenges facing Nigerian communities. This experience ignited his passion for social change and led to the establishment of ODPM Nigeria in 2015.

Education: BSc Biotechnology, Federal University Dutse
Experience: 10+ Years in Leadership & Activism

"The future of Nigeria lies in the hands of our youth. We must empower them to be agents of positive change, not just beneficiaries of development, but active participants in shaping our nation''s destiny."');

-- Executives page
INSERT INTO pages (slug, title, content) VALUES 
('executives', 'Executive Board', 
'Our executive board comprises dedicated leaders from diverse backgrounds, united in their commitment to driving positive change across Nigeria. Each member brings unique expertise and passion to advance our mission of community development, humanitarian service, and political activism.');
```

## ✅ Step 4: Access Your Site

### Public Site
- **Home**: http://localhost/ODPM/public/index.php
- **About**: http://localhost/ODPM/public/about.php
- **Founder**: http://localhost/ODPM/public/founder.php
- **Executives**: http://localhost/ODPM/public/executives.php
- **News**: http://localhost/ODPM/public/news.php
- **Gallery**: http://localhost/ODPM/public/gallery.php
- **Reports**: http://localhost/ODPM/public/reports.php

### Admin Portal
- **Login**: http://localhost/ODPM/admin/index.php
- **Credentials**:
  - Email: `admin@odpm.com`
  - Password: `ODPMPassword123!` (from your hash.php)

## ✅ Step 5: Add Content via Admin

### 5.1 Login to Admin
1. Go to http://localhost/ODPM/admin/index.php
2. Enter your credentials
3. You'll see the dashboard with 4 management sections

### 5.2 Manage Pages
- Edit About, Founder, and Executives content
- Use the slug field to create new pages

### 5.3 Manage News
- Create news articles with cover images
- Set publish date (leave blank for draft)
- Slug is auto-generated from title

### 5.4 Manage Gallery
- Upload images with captions
- Images appear on public gallery page

### 5.5 Manage Reports
- View public submissions
- Filter by category/status
- Add responses and update status

## 🎨 Customization

### Colors (Tailwind Config)
Edit `includes/header.php` line 13:
```javascript
'odpm-green': '#118B50',  // Main green
'odpm-light': '#FBF6E9',  // Light background
'odpm-lemon': '#E3F0AF',  // Accent yellow
'odpm-mint': '#5DB996',   // Secondary green
```

### Upload Limits
Edit `config.php`:
```php
'max_upload_bytes' => 5 * 1024 * 1024, // 5MB
'allowed_image_types' => ['image/jpeg','image/png','image/gif','image/webp'],
```

## 🔒 Security Checklist

- ✅ All DB queries use prepared statements
- ✅ CSRF tokens on all forms
- ✅ Password hashing with bcrypt
- ✅ File upload validation (MIME, size, sanitized names)
- ✅ Output escaping via `e()` helper
- ✅ Session-based authentication

## 🚀 Next Steps

1. **Delete hash.php** after creating your admin user (security)
2. **Change default password** immediately after first login
3. **Add more pages** via Manage Pages (use slugs like `contact`, `team`, etc.)
4. **Upload news** with images to populate the homepage
5. **Test report submission** from the public form

## 📝 Notes

- **Database**: All tables use `utf8mb4` for full Unicode support
- **Sessions**: PHP sessions handle authentication
- **Uploads**: Files stored in `/uploads/` with randomized names
- **Images**: Existing images in `/images/` are static assets

## ❓ Troubleshooting

### Can't login?
- Verify admin user exists: `SELECT * FROM users;`
- Check password hash was copied correctly
- Ensure database connection in `config.php` is correct

### Images not showing?
- Check `/uploads/` folder exists and is writable
- Verify image paths in database match uploaded filenames
- Check PHP `upload_max_filesize` and `post_max_size` in php.ini

### Database connection error?
- Verify credentials in `config.php`
- Ensure MySQL service is running
- Check database name is `odpm_db`

---

**🎉 You're all set! Visit http://localhost/ODPM/public/index.php to see your site.**

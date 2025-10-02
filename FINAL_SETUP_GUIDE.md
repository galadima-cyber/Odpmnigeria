# 🎉 ODPM Nigeria - Complete Setup Guide

## ✅ What You Have Now

A **fully dynamic website** where:
- ✅ ALL content from your HTML files is editable through admin
- ✅ Homepage, Founder, and Executives pages pull from database
- ✅ Admin can edit EVERY text, title, and section
- ✅ Executive board members are fully manageable
- ✅ All admin buttons (Add, Edit, Update, Delete) work perfectly

---

## 📋 Step-by-Step Setup

### Step 1: Import New Database Tables

You need to import the new content management tables:

**Option A: Using phpMyAdmin**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select `odpm_db` database
3. Click "Import" tab
4. Choose file: `c:\AppServ\www\ODPM\db_content_sections.sql`
5. Click "Go"

**Option B: Using Command Line**
```bash
mysql -u root -p odpm_db < c:\AppServ\www\ODPM\db_content_sections.sql
```

This creates:
- `content_sections` table - Stores all editable text
- `executives` table - Stores executive board members
- Seeds with all content from your HTML files

---

### Step 2: Login to Admin

1. Go to: `http://localhost/ODPM/admin/index.php`
2. Login with:
   - Email: `admin@odpm.com`
   - Password: `ODPMPassword123!` (or your custom password)

---

### Step 3: Explore Admin Dashboard

You now have **6 management sections**:

#### 1. **Edit Website Content** (NEW!)
- Edit ALL text on Homepage, Founder, Executives pages
- Change titles, descriptions, stats, vision statements
- Updates appear instantly on public site

#### 2. **Manage Executives** (NEW!)
- Add/Edit/Delete executive board members
- Upload photos, add bios, quotes, social links
- Displays on Executives page with modal popups

#### 3. **Manage Pages**
- Edit About, Founder, Executives page content
- Create new pages with custom slugs

#### 4. **Manage News**
- Create news articles with images
- Set publish dates (blank = draft)
- Shows on homepage

#### 5. **Manage Gallery**
- Upload images with captions
- Displays on gallery page

#### 6. **Manage Reports**
- View public submissions
- Update status, add responses

---

## 🎨 How to Edit Website Content

### Edit Homepage Text

1. Go to **Edit Website Content**
2. Select **Homepage** tab
3. You can edit:
   - **Hero Section**: Title, subtitle, descriptions
   - **Stats**: Community count, lives impacted, projects
   - **About Section**: Title and paragraphs
   - **Vision Section**: Title and vision statements
4. Click "Save All Changes"
5. Visit `http://localhost/ODPM/public/index.php` to see changes

### Edit Founder Page

1. Go to **Edit Website Content**
2. Select **Founder** tab
3. You can edit:
   - Founder name
   - Biography paragraphs
   - Education and experience
   - Vision and mission statements
   - Inspirational quote
4. Click "Save All Changes"

### Add Executive Members

1. Go to **Manage Executives**
2. Click "Add New Executive"
3. Fill in:
   - Full Name
   - Position (e.g., President, Vice President)
   - Experience
   - Education
   - Biography
   - Quote
   - Upload photo
   - Social links (LinkedIn, Twitter, Email)
   - Display order
4. Click "Add Executive"
5. Visit `http://localhost/ODPM/public/executives.php`
6. Click on any executive to see full details in modal

---

## 🌐 Public Pages

All pages now pull from database:

- **Homepage**: `http://localhost/ODPM/public/index.php`
  - Hero section (editable)
  - Stats (editable)
  - About section (editable)
  - Vision section (editable)
  - Programs section
  - Latest news

- **Founder**: `http://localhost/ODPM/public/founder.php`
  - Founder profile (editable)
  - Biography (editable)
  - Education & experience (editable)
  - Vision & mission (editable)
  - Quote (editable)

- **Executives**: `http://localhost/ODPM/public/executives.php`
  - Executive grid from database
  - Click any member for full details
  - Modal with photo, bio, quote, social links

- **About**: `http://localhost/ODPM/public/about.php`
- **News**: `http://localhost/ODPM/public/news.php`
- **Gallery**: `http://localhost/ODPM/public/gallery.php`
- **Reports**: `http://localhost/ODPM/public/reports.php`

---

## 🎯 Quick Test

### Test 1: Edit Homepage Title
1. Admin → Edit Website Content → Homepage
2. Change "Hero Title" to "Welcome to ODPM"
3. Save
4. Visit homepage - see new title

### Test 2: Add Executive
1. Admin → Manage Executives → Add New Executive
2. Fill in details
3. Upload photo
4. Save
5. Visit Executives page - see new member

### Test 3: Edit Founder Bio
1. Admin → Edit Website Content → Founder
2. Change "Founder Biography Paragraph 1"
3. Save
4. Visit Founder page - see new bio

---

## 📊 Database Structure

### content_sections Table
Stores all editable text with:
- `section_key`: Unique identifier (e.g., `hero_title`)
- `section_name`: Human-readable name for admin
- `content_value`: The actual text
- `page`: Which page (home, founder, executives)
- `section_group`: Groups sections together

### executives Table
Stores executive members with:
- `name`, `position`
- `image_path`
- `experience`, `education`
- `about`, `quote`
- `linkedin`, `twitter`, `email`
- `display_order`

---

## 🔧 Customization

### Add New Editable Section

1. Insert into database:
```sql
INSERT INTO content_sections (section_key, section_name, content_type, content_value, page, section_group) 
VALUES ('new_section', 'New Section Title', 'textarea', 'Default content', 'home', 'custom');
```

2. Use in PHP:
```php
<?php echo e(get_content('new_section', 'Default')); ?>
```

### Change Colors

Edit `includes/header.php` line 13:
```javascript
'odpm-green': '#118B50',  // Main color
'odpm-light': '#FBF6E9',  // Background
'odpm-lemon': '#E3F0AF',  // Accent
'odpm-mint': '#5DB996',   // Secondary
```

---

## ✨ What Makes This Special

1. **100% Editable**: Every text on your site can be edited via admin
2. **No Code Required**: Content editors never touch code
3. **Instant Updates**: Changes appear immediately
4. **Organized**: Content grouped by sections
5. **Flexible**: Easy to add new editable sections
6. **Secure**: CSRF protection, prepared statements, password hashing
7. **Beautiful**: Tailwind styling throughout

---

## 🎉 You're Done!

Your website is now:
- ✅ Fully dynamic
- ✅ Admin-editable
- ✅ Secure
- ✅ Beautiful
- ✅ Production-ready

**Next Steps:**
1. Import `db_content_sections.sql`
2. Login to admin
3. Start editing content
4. Add executive members
5. Customize to your needs

**Need Help?**
- Check `TROUBLESHOOTING.md` for common issues
- All admin buttons work: Add, Edit, Update, Delete
- Content updates instantly on public pages

---

**🚀 Enjoy your new dynamic website!**

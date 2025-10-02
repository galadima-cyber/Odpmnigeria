# Troubleshooting Guide - ODPM Nigeria

## Issue: Content Not Showing on Public Pages After Creating in Admin

### Problem
When you create pages, news, or other content in the admin panel, it doesn't appear on the public website.

### Solutions

#### 1. **Check Database Connection**
Make sure the database is properly configured:
- Open `config.php`
- Verify credentials match your MySQL setup:
  ```php
  'host' => '127.0.0.1',
  'name' => 'odpm_db',
  'user' => 'root',
  'pass' => 'YOUR_PASSWORD',
  ```

#### 2. **Verify Content Was Saved**
Check if content actually saved to database:
1. Open phpMyAdmin
2. Select `odpm_db` database
3. Browse these tables:
   - `pages` - Should show your About/Founder/Executives content
   - `news` - Should show your articles
   - `gallery_images` - Should show uploaded images
   - `reports` - Should show submitted reports

#### 3. **Check Page Slugs**
For pages to display correctly, slugs must match:

**For About Page** (`public/about.php`):
- Admin: Create page with slug = `about`
- Public URL: `http://localhost/ODPM/public/about.php`

**For Founder Page** (`public/founder.php`):
- Admin: Create page with slug = `founder`
- Public URL: `http://localhost/ODPM/public/founder.php`

**For Executives Page** (`public/executives.php`):
- Admin: Create page with slug = `executives`
- Public URL: `http://localhost/ODPM/public/executives.php`

#### 4. **Check News Publish Status**
News articles need a publish date to appear:
- In admin, when creating/editing news
- Set "Publish Date & Time" field
- Leave blank = Draft (won't show on public site)
- Set date = Published (will show on public site)

#### 5. **Clear Browser Cache**
Sometimes browsers cache old content:
- Press `Ctrl + F5` (Windows) to hard refresh
- Or clear browser cache completely

#### 6. **Check File Paths**
All public pages should be accessed via:
- ✅ `http://localhost/ODPM/public/index.php`
- ✅ `http://localhost/ODPM/public/about.php`
- ✅ `http://localhost/ODPM/public/news.php`
- ❌ NOT `http://localhost/ODPM/about.php`

#### 7. **Verify PHP Errors**
Enable error display to see issues:
1. Open `c:\AppServ\www\ODPM\includes\db.php`
2. Check if database connection succeeds
3. Look for error messages on the page

---

## Common Issues & Fixes

### Issue: "No pages yet" message on About page
**Cause**: No content in database with slug 'about'

**Fix**:
1. Login to admin: `http://localhost/ODPM/admin/index.php`
2. Go to "Manage Pages"
3. Click "Add New Page"
4. Fill in:
   - Title: `About ODPM Nigeria`
   - Slug: `about`
   - Content: Your about text
5. Click "Create Page"
6. Visit `http://localhost/ODPM/public/about.php`

### Issue: News not showing on homepage
**Cause**: No published news articles

**Fix**:
1. Go to admin → "Manage News"
2. Click "Add New Article"
3. Fill in all fields including:
   - Title
   - Excerpt
   - Content
   - Upload cover image
   - **Set Publish Date** (important!)
4. Click "Create Article"
5. Visit homepage to see it

### Issue: Gallery images not showing
**Cause**: Images not uploaded or upload folder missing

**Fix**:
1. Check if `c:\AppServ\www\ODPM\uploads\` folder exists
2. Make sure folder is writable
3. Go to admin → "Manage Gallery"
4. Upload images with captions
5. Visit `http://localhost/ODPM/public/gallery.php`

### Issue: Reports not submitting
**Cause**: Database table missing or CSRF token issue

**Fix**:
1. Make sure `reports` table exists in database
2. Check `db.sql` was imported correctly
3. Try clearing browser cookies
4. Refresh the reports page

---

## Step-by-Step: Creating Your First Page

### 1. Login to Admin
- URL: `http://localhost/ODPM/admin/index.php`
- Email: `admin@odpm.com`
- Password: `ODPMPassword123!` (or whatever you set)

### 2. Create About Page
1. Click "Manage Pages" card
2. Click green "Add New Page" button
3. Fill in form:
   ```
   Title: About ODPM Nigeria
   Slug: about
   Content: 
   ODPMNIGERIA is a dynamic consortium of young activists united by 
   a shared commitment to driving positive change across Nigeria. 
   
   We focus on community development, humanitarian initiatives, and 
   political activism to create a more inclusive, equitable, and 
   empowered society.
   ```
4. Click "Create Page"
5. Visit: `http://localhost/ODPM/public/about.php`

### 3. Create First News Article
1. Click "Manage News" card
2. Click green "Add New Article" button
3. Fill in form:
   ```
   Title: ODPM Launches Community Development Initiative
   Excerpt: ODPM Nigeria announces new program to empower local communities
   Content: [Full article text]
   Upload Image: [Select cover image]
   Publish Date: [Click and select current date/time]
   ```
4. Click "Create Article"
5. Visit: `http://localhost/ODPM/public/index.php` (homepage)

### 4. Upload Gallery Image
1. Click "Manage Gallery" card
2. Fill in upload form:
   ```
   Caption: Annual Meeting 2024
   Image: [Select image file]
   ```
3. Click "Upload Image"
4. Visit: `http://localhost/ODPM/public/gallery.php`

---

## Testing Checklist

Use this checklist to verify everything works:

- [ ] Can login to admin panel
- [ ] Can create a page with slug 'about'
- [ ] About page shows content at `/public/about.php`
- [ ] Can create news article with publish date
- [ ] News shows on homepage
- [ ] Can upload gallery image
- [ ] Image shows in gallery page
- [ ] Can submit public report
- [ ] Report appears in admin "Manage Reports"
- [ ] Can update report status in admin
- [ ] All navigation links work

---

## Database Quick Reference

### Check if content exists:
```sql
-- Check pages
SELECT * FROM pages;

-- Check news
SELECT * FROM news;

-- Check gallery
SELECT * FROM gallery_images;

-- Check reports
SELECT * FROM reports;
```

### Manually insert test page:
```sql
INSERT INTO pages (slug, title, content) VALUES 
('about', 'About ODPM Nigeria', 'This is test content for the about page.');
```

### Manually insert test news:
```sql
INSERT INTO news (title, slug, excerpt, content, published_at) VALUES 
('Test Article', 'test-article', 'This is a test', 'Full article content here', NOW());
```

---

## Still Having Issues?

### Check PHP Error Log
Look for errors in:
- `c:\AppServ\Apache24\logs\error.log`

### Check MySQL is Running
1. Open Services (Windows + R → `services.msc`)
2. Look for "MySQL" service
3. Make sure it's running

### Verify Apache is Running
1. Check Services for "Apache2.4"
2. Make sure it's running
3. Try accessing: `http://localhost/`

### Test Database Connection
Create `test.php` in ODPM folder:
```php
<?php
require_once 'includes/db.php';
try {
    $pdo = db();
    echo "Database connected successfully!";
    $result = $pdo->query("SELECT COUNT(*) FROM pages");
    echo "<br>Pages in database: " . $result->fetchColumn();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```
Visit: `http://localhost/ODPM/test.php`

---

## Contact & Support

If you're still stuck:
1. Check the error message carefully
2. Look in browser console (F12) for JavaScript errors
3. Check PHP error log for server-side issues
4. Verify all files were created correctly
5. Make sure database was imported

**Remember**: 
- Admin panel: `/ODPM/admin/index.php`
- Public pages: `/ODPM/public/*.php`
- Always use full paths starting with `/ODPM/`

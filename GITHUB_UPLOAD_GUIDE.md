# 📤 GitHub Upload Preparation Guide

## ⚠️ BEFORE UPLOADING - CRITICAL STEPS

### 🔴 Files to DELETE (Security Risk)
Delete these files completely - they contain sensitive information:

```bash
# Delete these files:
rm config.php                    # Contains database password
rm uploads/*                     # Contains user-uploaded files
rm hash.php                      # Optional - password hashing tool
rm admin/check-setup.php         # Optional - diagnostic tool
```

### ✅ Files to KEEP
These are safe to upload:
- ✅ `config.sample.php` - Template without credentials
- ✅ `.gitignore` - Prevents sensitive files from being uploaded
- ✅ All `.sql` files - Database structure (no sensitive data)
- ✅ All PHP source files
- ✅ All HTML/CSS/JS files
- ✅ Documentation files (*.md)
- ✅ `images/` folder - Static website images

---

## 📋 Pre-Upload Checklist

### 1. Remove Sensitive Data
- [ ] Delete `config.php`
- [ ] Keep `config.sample.php`
- [ ] Clear `uploads/` folder
- [ ] Remove any backup files (*.backup, *.bak)
- [ ] Remove error logs

### 2. Create Empty Folders
```bash
# Create placeholder files for empty directories
echo "" > uploads/.gitkeep
```

### 3. Update Documentation
- [ ] Update `README.md` with GitHub repo URL
- [ ] Add installation instructions
- [ ] Add demo credentials (if any)
- [ ] Add screenshots (optional)

### 4. Review Code
- [ ] Remove any hardcoded passwords
- [ ] Remove any API keys
- [ ] Remove debug code (`var_dump`, `print_r`)
- [ ] Remove `ini_set('display_errors', 1)` from production files

---

## 🚀 Upload to GitHub

### Method 1: Using Git Command Line

```bash
# Navigate to project folder
cd c:\AppServ\www\ODPM

# Initialize git repository
git init

# Add all files (respects .gitignore)
git add .

# Commit
git commit -m "Initial commit: ODPM Nigeria website"

# Add remote repository
git remote add origin https://github.com/YOUR_USERNAME/odpm-nigeria.git

# Push to GitHub
git push -u origin main
```

### Method 2: Using GitHub Desktop
1. Open GitHub Desktop
2. Click "Add" → "Add Existing Repository"
3. Choose `c:\AppServ\www\ODPM`
4. Click "Publish repository"
5. Choose public/private
6. Click "Publish"

### Method 3: Using VS Code
1. Open folder in VS Code
2. Click Source Control icon (left sidebar)
3. Click "Initialize Repository"
4. Stage all changes (click +)
5. Enter commit message
6. Click "Publish to GitHub"

---

## 📝 Recommended README.md Updates

Add this section to your README.md:

```markdown
## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/odpm-nigeria.git
   cd odpm-nigeria
   ```

2. **Configure database**
   ```bash
   cp config.sample.php config.php
   # Edit config.php with your database credentials
   ```

3. **Import database**
   ```bash
   mysql -u root -p odpm_db < db.sql
   mysql -u root -p odpm_db < db_content_sections.sql
   mysql -u root -p odpm_db < db_contact_submissions.sql
   ```

4. **Create uploads folder**
   ```bash
   mkdir uploads
   chmod 755 uploads
   ```

5. **Create admin user**
   - Visit: `http://localhost/ODPM/hash.php`
   - Generate password hash
   - Insert into users table

6. **Access the site**
   - Website: `http://localhost/ODPM/public/index.php`
   - Admin: `http://localhost/ODPM/admin/index.php`

## 🔐 Security Notes

- Never commit `config.php` to version control
- Change default admin password immediately
- Use strong passwords for production
- Enable HTTPS in production
- Keep PHP and MySQL updated
```

---

## 🗂️ Final File Structure (What Gets Uploaded)

```
ODPM/
├── .gitignore                    ✅ Upload
├── config.sample.php             ✅ Upload (template)
├── config.php                    ❌ DO NOT UPLOAD
├── README.md                     ✅ Upload
├── FINAL_SETUP_CHECKLIST.md     ✅ Upload
├── EMAIL_SETUP.md               ✅ Upload
├── GITHUB_UPLOAD_GUIDE.md       ✅ Upload
├── MIGRATION_SUMMARY.md         ✅ Upload
├── db.sql                        ✅ Upload
├── db_content_sections.sql      ✅ Upload
├── db_contact_submissions.sql   ✅ Upload
├── hash.php                      ⚠️ Optional (useful tool)
├── admin/                        ✅ Upload all
│   ├── index.php
│   ├── dashboard.php
│   ├── manage-*.php
│   └── check-setup.php          ⚠️ Optional
├── includes/                     ✅ Upload all
│   ├── auth.php
│   ├── db.php
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── public/                       ✅ Upload all
│   ├── index.php
│   ├── founder.php
│   ├── executives.php
│   ├── news.php
│   ├── gallery.php
│   ├── reports.php
│   └── contact-submit.php
├── images/                       ✅ Upload (static images)
│   └── *.jpg, *.png
└── uploads/                      ❌ Don't upload contents
    └── .gitkeep                  ✅ Upload (placeholder)
```

---

## 🔒 Security Best Practices

### What NOT to Upload:
1. ❌ `config.php` - Contains database password
2. ❌ `uploads/*` - User-generated content
3. ❌ `.env` files - Environment variables
4. ❌ Backup files - `*.backup`, `*.bak`
5. ❌ Log files - `*.log`, `error_log`
6. ❌ IDE folders - `.vscode/`, `.idea/`

### What TO Upload:
1. ✅ Source code (PHP, HTML, CSS, JS)
2. ✅ Database structure (SQL files)
3. ✅ Documentation (MD files)
4. ✅ Sample configuration (`config.sample.php`)
5. ✅ `.gitignore` file
6. ✅ Static assets (images, fonts)

---

## 🎯 Quick Commands

### Delete sensitive files:
```bash
cd c:\AppServ\www\ODPM
del config.php
del /Q uploads\*.*
```

### Create placeholder:
```bash
echo. > uploads\.gitkeep
```

### Check what will be uploaded:
```bash
git status
git diff --cached
```

---

## ✅ Final Verification

Before pushing to GitHub, verify:

1. [ ] `config.php` is NOT in the repository
2. [ ] `.gitignore` is present and working
3. [ ] `uploads/` folder is empty (except .gitkeep)
4. [ ] No passwords in any files
5. [ ] README.md has installation instructions
6. [ ] All SQL files are included
7. [ ] `config.sample.php` exists

---

## 🆘 If You Accidentally Uploaded Sensitive Data

If you already pushed sensitive data:

```bash
# Remove file from git history
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch config.php" \
  --prune-empty --tag-name-filter cat -- --all

# Force push
git push origin --force --all

# Change all passwords immediately!
```

**Better solution:** Delete the repository and create a new one.

---

## 📞 Support

After uploading, update your repository with:
- Demo URL (if deployed)
- Screenshots
- Contributing guidelines
- License file (MIT, GPL, etc.)

---

## 🎉 You're Ready!

Once you've completed this checklist, your code is safe to upload to GitHub!

**Remember:** 
- Public repos are visible to everyone
- Private repos require GitHub Pro (or free for students)
- Never commit sensitive data
- Always use `.gitignore`

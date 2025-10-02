<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();
$u = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - ODPM Nigeria</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-odpm-green">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-xs text-gray-500">Management Portal</p>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-gray-600">
            <i class="fas fa-user-circle mr-2 text-odpm-green"></i>
            <?php echo e($u['name'] ?? $u['email'] ?? 'Admin'); ?>
          </span>
          <a href="/ODPM/public/index.php" class="text-gray-600 hover:text-odpm-green transition-colors" title="View Site">
            <i class="fas fa-external-link-alt"></i>
          </a>
          <a href="/ODPM/admin/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-7xl mx-auto p-6">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back!</h2>
      <p class="text-gray-600">Manage your website content and settings from here.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <a href="/ODPM/admin/manage-content.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-edit text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Edit Website Content</h3>
        <p class="text-sm opacity-90">Edit all text & sections on your website</p>
      </a>

      <a href="/ODPM/admin/manage-executives.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-users text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Manage Executives</h3>
        <p class="text-sm opacity-90">Add & edit executive board members</p>
      </a>

      <a href="/ODPM/admin/manage-pages.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-file-alt text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Manage Pages</h3>
        <p class="text-sm opacity-90">Edit About, Founder & Executives pages</p>
      </a>

      <a href="/ODPM/admin/manage-news.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-newspaper text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Manage News</h3>
        <p class="text-sm opacity-90">Create & publish news articles</p>
      </a>

      <a href="/ODPM/admin/manage-gallery.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-images text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Manage Gallery</h3>
        <p class="text-sm opacity-90">Upload & organize images</p>
      </a>

      <a href="/ODPM/admin/manage-contacts.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-envelope text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Contact Submissions</h3>
        <p class="text-sm opacity-90">View Get Involved form submissions</p>
      </a>

      <a href="/ODPM/admin/manage-reports.php" class="group block p-6 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
          <i class="fas fa-flag text-4xl opacity-80"></i>
          <i class="fas fa-arrow-right text-xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Manage Reports</h3>
        <p class="text-sm opacity-90">Review & respond to submissions</p>
      </a>
    </div>

    <!-- Quick Stats -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-2xl shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Total Pages</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">
              <?php 
              try { echo db()->query("SELECT COUNT(*) FROM pages")->fetchColumn(); } catch(Throwable $e) { echo '0'; }
              ?>
            </p>
          </div>
          <i class="fas fa-file-alt text-4xl text-blue-500 opacity-20"></i>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Published News</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">
              <?php 
              try { echo db()->query("SELECT COUNT(*) FROM news WHERE published_at IS NOT NULL")->fetchColumn(); } catch(Throwable $e) { echo '0'; }
              ?>
            </p>
          </div>
          <i class="fas fa-newspaper text-4xl text-green-500 opacity-20"></i>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm font-medium">Pending Reports</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">
              <?php 
              try { echo db()->query("SELECT COUNT(*) FROM reports WHERE status='new'")->fetchColumn(); } catch(Throwable $e) { echo '0'; }
              ?>
            </p>
          </div>
          <i class="fas fa-flag text-4xl text-orange-500 opacity-20"></i>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

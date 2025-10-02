<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();

$msg = '';
$page_filter = $_GET['page'] ?? 'home';

// Handle content update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  
  foreach ($_POST as $key => $value) {
    if (strpos($key, 'content_') === 0) {
      $section_id = str_replace('content_', '', $key);
      $content_value = trim((string)$value);
      
      $stmt = db()->prepare('UPDATE content_sections SET content_value = ? WHERE id = ?');
      $stmt->execute([$content_value, (int)$section_id]);
    }
  }
  
  $msg = 'Content updated successfully!';
}

// Fetch content sections for selected page
$stmt = db()->prepare('SELECT * FROM content_sections WHERE page = ? ORDER BY section_group, display_order');
$stmt->execute([$page_filter]);
$sections = $stmt->fetchAll();

// Group sections
$grouped = [];
foreach ($sections as $section) {
  $group = $section['section_group'] ?: 'general';
  if (!isset($grouped[$group])) {
    $grouped[$group] = [];
  }
  $grouped[$group][] = $section;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Content - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-indigo-600 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Manage Website Content</h1>
            <p class="text-xs text-gray-500">Edit all text and sections</p>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <a href="/ODPM/admin/dashboard.php" class="text-gray-600 hover:text-odpm-green transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Dashboard
          </a>
          <a href="/ODPM/admin/logout.php" class="text-red-600 hover:text-red-700">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto p-6">
    <!-- Page Selector -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Content Editor</h2>
        <p class="text-gray-600 mt-1">Edit text, titles, and descriptions for each page</p>
      </div>
      <div class="flex gap-2">
        <a href="?page=home" class="px-4 py-2 rounded-lg <?php echo $page_filter === 'home' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
          <i class="fas fa-home mr-2"></i>Homepage
        </a>
        <a href="?page=founder" class="px-4 py-2 rounded-lg <?php echo $page_filter === 'founder' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
          <i class="fas fa-user mr-2"></i>Founder
        </a>
        <a href="?page=executives" class="px-4 py-2 rounded-lg <?php echo $page_filter === 'executives' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
          <i class="fas fa-users mr-2"></i>Executives
        </a>
      </div>
    </div>

    <?php if ($msg): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded flex items-start">
        <i class="fas fa-check-circle mt-0.5 mr-3 text-xl"></i>
        <span><?php echo e($msg); ?></span>
      </div>
    <?php endif; ?>

    <!-- Content Form -->
    <form method="post" class="space-y-6">
      <?php echo csrf_field(); ?>
      
      <?php foreach ($grouped as $group_name => $group_sections): ?>
        <div class="bg-white rounded-2xl shadow-lg p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b flex items-center">
            <i class="fas fa-layer-group text-indigo-600 mr-3"></i>
            <?php echo e(ucwords(str_replace('_', ' ', $group_name))); ?> Section
          </h3>
          
          <div class="space-y-6">
            <?php foreach ($group_sections as $section): ?>
              <div>
                <label class="block mb-2 font-semibold text-gray-700">
                  <i class="fas fa-edit text-indigo-600 mr-2"></i>
                  <?php echo e($section['section_name']); ?>
                </label>
                
                <?php if ($section['content_type'] === 'text'): ?>
                  <input 
                    type="text" 
                    name="content_<?php echo (int)$section['id']; ?>" 
                    value="<?php echo e($section['content_value']); ?>" 
                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-all"
                  />
                <?php elseif ($section['content_type'] === 'number'): ?>
                  <input 
                    type="number" 
                    name="content_<?php echo (int)$section['id']; ?>" 
                    value="<?php echo e($section['content_value']); ?>" 
                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-all"
                  />
                <?php else: ?>
                  <textarea 
                    name="content_<?php echo (int)$section['id']; ?>" 
                    rows="4" 
                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-all"
                  ><?php echo e($section['content_value']); ?></textarea>
                <?php endif; ?>
                
                <p class="text-xs text-gray-500 mt-1">
                  <i class="fas fa-info-circle mr-1"></i>
                  Key: <code class="bg-gray-100 px-2 py-1 rounded"><?php echo e($section['section_key']); ?></code>
                </p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Save Button -->
      <div class="flex items-center justify-between pt-6 border-t bg-white rounded-2xl shadow-lg p-6">
        <p class="text-sm text-gray-500">
          <i class="fas fa-lightbulb mr-2"></i>
          Changes will be visible immediately on the public website
        </p>
        <button class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-8 py-4 rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all shadow-lg font-semibold text-lg">
          <i class="fas fa-save mr-2"></i>Save All Changes
        </button>
      </div>
    </form>
  </div>
</body>
</html>

<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();

global $config;

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $postAction = $_POST['action'] ?? '';
  if ($postAction === 'save') {
    $pid = (int)($_POST['id'] ?? 0);
    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $content = (string)($_POST['content'] ?? '');
    if ($slug === '') $slug = slugify($title ?: bin2hex(random_bytes(3)));

    if ($pid > 0) {
      $stmt = db()->prepare('UPDATE pages SET title=?, slug=?, content=? WHERE id=?');
      $stmt->execute([$title, $slug, $content, $pid]);
      $msg = 'Page updated';
      $action = 'edit';
      $id = $pid;
    } else {
      $stmt = db()->prepare('INSERT INTO pages (slug, title, content) VALUES (?,?,?)');
      $stmt->execute([$slug, $title, $content]);
      $msg = 'Page created';
      $action = 'list';
    }
  } elseif ($postAction === 'delete') {
    $pid = (int)($_POST['id'] ?? 0);
    if ($pid) {
      $stmt = db()->prepare('DELETE FROM pages WHERE id=?');
      $stmt->execute([$pid]);
      $msg = 'Page deleted';
      $action = 'list';
    }
  }
}

function fetch_page($id) {
  $stmt = db()->prepare('SELECT * FROM pages WHERE id=?');
  $stmt->execute([$id]);
  return $stmt->fetch();
}
$pages = [];
if ($action === 'list') {
  $pages = db()->query('SELECT id, slug, title, updated_at FROM pages ORDER BY updated_at DESC')->fetchAll();
}
$page = $id ? fetch_page($id) : ['id'=>0,'title'=>'','slug'=>'','content'=>''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Pages - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-odpm-green mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Manage Pages</h1>
            <p class="text-xs text-gray-500">Edit site content</p>
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

  <div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Pages Management</h2>
        <p class="text-gray-600 mt-1">Create and edit website pages (About, Founder, Executives, etc.)</p>
      </div>
      <?php if ($action === 'list'): ?>
        <a href="/ODPM/admin/manage-pages.php?action=create" class="bg-odpm-green text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors shadow-lg font-semibold">
          <i class="fas fa-plus mr-2"></i>Add New Page
        </a>
      <?php else: ?>
        <a href="/ODPM/admin/manage-pages.php" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
          <i class="fas fa-list mr-2"></i>View All Pages
        </a>
      <?php endif; ?>
    </div>
    <?php if ($msg): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded flex items-start">
        <i class="fas fa-check-circle mt-0.5 mr-3 text-xl"></i>
        <span><?php echo e($msg); ?></span>
      </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <table class="min-w-full">
          <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <tr>
              <th class="text-left p-4 font-semibold">
                <i class="fas fa-heading mr-2"></i>Title
              </th>
              <th class="text-left p-4 font-semibold">
                <i class="fas fa-link mr-2"></i>Slug
              </th>
              <th class="text-left p-4 font-semibold">
                <i class="fas fa-clock mr-2"></i>Last Updated
              </th>
              <th class="p-4 font-semibold text-right">
                <i class="fas fa-cog mr-2"></i>Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pages as $p): ?>
              <tr class="border-t hover:bg-gray-50 transition-colors">
                <td class="p-4">
                  <span class="font-semibold text-gray-900"><?php echo e($p['title']); ?></span>
                </td>
                <td class="p-4">
                  <code class="bg-gray-100 px-3 py-1 rounded text-sm text-blue-600"><?php echo e($p['slug']); ?></code>
                </td>
                <td class="p-4 text-gray-600">
                  <?php echo date('M d, Y g:i A', strtotime($p['updated_at'])); ?>
                </td>
                <td class="p-4 text-right">
                  <a href="?action=edit&id=<?php echo (int)$p['id']; ?>" class="inline-flex items-center bg-odpm-green text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors mr-2">
                    <i class="fas fa-edit mr-2"></i>Edit
                  </a>
                  <form method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this page?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                    <button class="inline-flex items-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                      <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; if (!$pages): ?>
              <tr>
                <td colspan="4" class="p-12 text-center">
                  <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                  <p class="text-gray-500 text-lg">No pages yet. Click "Add New Page" to create one.</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="mb-6">
          <h3 class="text-xl font-bold text-gray-900 mb-2">
            <?php echo $page['id'] ? 'Edit Page' : 'Create New Page'; ?>
          </h3>
          <p class="text-gray-600">Fill in all the details below to <?php echo $page['id'] ? 'update' : 'create'; ?> the page.</p>
        </div>

        <form method="post" class="space-y-6">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="save" />
          <input type="hidden" name="id" value="<?php echo (int)$page['id']; ?>" />
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-heading text-odpm-green mr-2"></i>Page Title *
              </label>
              <input 
                type="text" 
                name="title" 
                value="<?php echo e($page['title']); ?>" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" 
                placeholder="e.g., About Us, Founder Profile"
                required 
              />
              <p class="text-xs text-gray-500 mt-1">This will be displayed as the page heading</p>
            </div>

            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-link text-odpm-green mr-2"></i>Page Slug
              </label>
              <input 
                type="text" 
                name="slug" 
                value="<?php echo e($page['slug']); ?>" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" 
                placeholder="e.g., about, founder, executives"
              />
              <p class="text-xs text-gray-500 mt-1">URL-friendly name (auto-generated if left blank)</p>
            </div>
          </div>

          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-align-left text-odpm-green mr-2"></i>Page Content *
            </label>
            <textarea 
              name="content" 
              rows="16" 
              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all font-mono text-sm"
              placeholder="Enter the full content for this page. You can use multiple paragraphs.&#10;&#10;Example:&#10;ODPM Nigeria is a dynamic organization...&#10;&#10;Our mission is to create positive change..."
              required
            ><?php echo e($page['content']); ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Write the full page content. Line breaks will be preserved.</p>
          </div>

          <div class="flex items-center justify-between pt-4 border-t">
            <a href="/ODPM/admin/manage-pages.php" class="text-gray-600 hover:text-gray-900 transition-colors">
              <i class="fas fa-arrow-left mr-2"></i>Cancel
            </a>
            <button class="bg-odpm-green text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors shadow-lg font-semibold">
              <i class="fas fa-save mr-2"></i><?php echo $page['id'] ? 'Update Page' : 'Create Page'; ?>
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

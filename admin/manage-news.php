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
    $nid = (int)($_POST['id'] ?? 0);
    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $excerpt = trim((string)($_POST['excerpt'] ?? ''));
    $content = (string)($_POST['content'] ?? '');
    $published_at = trim((string)($_POST['published_at'] ?? ''));
    
    // Convert datetime-local format to MySQL datetime
    if ($published_at && strpos($published_at, 'T') !== false) {
      $published_at = str_replace('T', ' ', $published_at) . ':00';
    }

    if ($slug === '') $slug = slugify($title ?: bin2hex(random_bytes(3)));

    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
      $res = handle_upload($_FILES['image'], $config['site']['allowed_image_types'], $config['site']['max_upload_bytes']);
      if ($res['ok']) { $image_path = $res['filename']; } else { $msg = 'Image upload failed: ' . e($res['error']); }
    }

    if (!isset($msg) || $msg === '') {
      if ($nid > 0) {
        if ($image_path) {
          $stmt = db()->prepare('UPDATE news SET title=?, slug=?, excerpt=?, content=?, image_path=?, published_at=? WHERE id=?');
          $stmt->execute([$title,$slug,$excerpt,$content,$image_path,($published_at ?: null),$nid]);
        } else {
          $stmt = db()->prepare('UPDATE news SET title=?, slug=?, excerpt=?, content=?, published_at=? WHERE id=?');
          $stmt->execute([$title,$slug,$excerpt,$content,($published_at ?: null),$nid]);
        }
        $msg = 'News updated';
        $action = 'edit';
        $id = $nid;
      } else {
        $stmt = db()->prepare('INSERT INTO news (title, slug, excerpt, content, image_path, published_at) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$title,$slug,$excerpt,$content,$image_path,($published_at ?: null)]);
        $msg = 'News created';
        $action = 'list';
      }
    }
  } elseif ($postAction === 'delete') {
    $nid = (int)($_POST['id'] ?? 0);
    if ($nid) {
      $stmt = db()->prepare('DELETE FROM news WHERE id=?');
      $stmt->execute([$nid]);
      $msg = 'News deleted';
      $action = 'list';
    }
  }
}

function fetch_news($id){ $s=db()->prepare('SELECT * FROM news WHERE id=?'); $s->execute([$id]); return $s->fetch(); }
$items = [];
if ($action === 'list') {
  $items = db()->query('SELECT id,title,slug,image_path,published_at,updated_at FROM news ORDER BY COALESCE(published_at, created_at) DESC')->fetchAll();
}
$item = $id ? fetch_news($id) : ['id'=>0,'title'=>'','slug'=>'','excerpt'=>'','content'=>'','image_path'=>'','published_at'=>''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage News - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-green-600 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Manage News</h1>
            <p class="text-xs text-gray-500">Create and publish articles</p>
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
        <h2 class="text-2xl font-bold text-gray-900">News Management</h2>
        <p class="text-gray-600 mt-1">Create, edit, and publish news articles with images</p>
      </div>
      <?php if ($action === 'list'): ?>
        <a href="/ODPM/admin/manage-news.php?action=create" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-lg font-semibold">
          <i class="fas fa-plus mr-2"></i>Add New Article
        </a>
      <?php else: ?>
        <a href="/ODPM/admin/manage-news.php" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
          <i class="fas fa-list mr-2"></i>View All Articles
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
          <thead class="bg-gradient-to-r from-green-500 to-green-600 text-white">
            <tr>
              <th class="text-left p-4 font-semibold">
                <i class="fas fa-newspaper mr-2"></i>Title
              </th>
              <th class="text-left p-4 font-semibold">
                <i class="fas fa-link mr-2"></i>Slug
              </th>
              <th class="text-left p-4 font-semibold">
                <i class="fas fa-calendar mr-2"></i>Status
              </th>
              <th class="p-4 font-semibold text-right">
                <i class="fas fa-cog mr-2"></i>Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $n): ?>
              <tr class="border-t hover:bg-gray-50 transition-colors">
                <td class="p-4">
                  <div class="flex items-center space-x-3">
                    <?php if(!empty($n['image_path'])): ?>
                      <div class="w-16 h-16 bg-cover bg-center rounded-lg" style="background-image:url('<?php echo e($config['site']['upload_base_url'].'/'.$n['image_path']); ?>')"></div>
                    <?php else: ?>
                      <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                      </div>
                    <?php endif; ?>
                    <span class="font-semibold text-gray-900"><?php echo e($n['title']); ?></span>
                  </div>
                </td>
                <td class="p-4">
                  <code class="bg-gray-100 px-3 py-1 rounded text-sm text-green-600"><?php echo e($n['slug']); ?></code>
                </td>
                <td class="p-4">
                  <?php if($n['published_at']): ?>
                    <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                      <i class="fas fa-check-circle mr-2"></i>Published
                    </span>
                    <p class="text-xs text-gray-500 mt-1"><?php echo date('M d, Y', strtotime($n['published_at'])); ?></p>
                  <?php else: ?>
                    <span class="inline-flex items-center bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                      <i class="fas fa-clock mr-2"></i>Draft
                    </span>
                  <?php endif; ?>
                </td>
                <td class="p-4 text-right">
                  <a href="?action=edit&id=<?php echo (int)$n['id']; ?>" class="inline-flex items-center bg-odpm-green text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors mr-2">
                    <i class="fas fa-edit mr-2"></i>Edit
                  </a>
                  <form method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
                    <button class="inline-flex items-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                      <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; if (!$items): ?>
              <tr>
                <td colspan="4" class="p-12 text-center">
                  <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                  <p class="text-gray-500 text-lg">No articles yet. Click "Add New Article" to create one.</p>
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
            <?php echo $item['id'] ? 'Edit Article' : 'Create New Article'; ?>
          </h3>
          <p class="text-gray-600">Fill in all the details below to <?php echo $item['id'] ? 'update' : 'create'; ?> the news article.</p>
        </div>

        <form method="post" enctype="multipart/form-data" class="space-y-6">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="save" />
          <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>" />
          
          <!-- Title & Slug -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-heading text-green-600 mr-2"></i>Article Title *
              </label>
              <input 
                type="text" 
                name="title" 
                value="<?php echo e($item['title']); ?>" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all" 
                placeholder="e.g., ODPM Launches New Community Program"
                required 
              />
              <p class="text-xs text-gray-500 mt-1">Main headline for the article</p>
            </div>

            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-link text-green-600 mr-2"></i>URL Slug
              </label>
              <input 
                type="text" 
                name="slug" 
                value="<?php echo e($item['slug']); ?>" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all" 
                placeholder="e.g., odpm-launches-new-program"
              />
              <p class="text-xs text-gray-500 mt-1">Auto-generated if left blank</p>
            </div>
          </div>

          <!-- Excerpt -->
          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-align-center text-green-600 mr-2"></i>Excerpt / Summary *
            </label>
            <textarea 
              name="excerpt" 
              rows="3" 
              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all"
              placeholder="Write a short summary (1-2 sentences) that will appear in news listings..."
              required
            ><?php echo e($item['excerpt']); ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Brief summary shown on news cards (recommended: 100-150 characters)</p>
          </div>

          <!-- Content -->
          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-file-alt text-green-600 mr-2"></i>Full Article Content *
            </label>
            <textarea 
              name="content" 
              rows="16" 
              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all font-mono text-sm"
              placeholder="Write the complete article content here. You can use multiple paragraphs.&#10;&#10;Example:&#10;&#10;ODPM Nigeria has launched a groundbreaking community development initiative...&#10;&#10;The program aims to empower local communities..."
              required
            ><?php echo e($item['content']); ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Full article text. Line breaks will be preserved.</p>
          </div>

          <!-- Image Upload -->
          <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-image text-green-600 mr-2"></i>Cover Image
            </label>
            <input 
              type="file" 
              name="image" 
              accept="image/*"
              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 bg-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all" 
            />
            <?php if(!empty($item['image_path'])): ?>
              <div class="mt-4">
                <p class="text-sm text-gray-600 mb-2">Current image:</p>
                <img src="<?php echo e($config['site']['upload_base_url'].'/'.$item['image_path']); ?>" alt="Current" class="w-48 h-32 object-cover rounded-lg shadow" />
                <p class="text-xs text-gray-500 mt-1"><?php echo e($item['image_path']); ?></p>
              </div>
            <?php endif; ?>
            <p class="text-xs text-gray-500 mt-2">
              <i class="fas fa-info-circle mr-1"></i>
              Recommended: 1200x630px, max 5MB (JPG, PNG, GIF, WebP)
            </p>
          </div>

          <!-- Publish Date -->
          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-calendar-alt text-green-600 mr-2"></i>Publish Date & Time
            </label>
            <input 
              type="datetime-local" 
              name="published_at" 
              value="<?php echo $item['published_at'] ? date('Y-m-d\TH:i', strtotime($item['published_at'])) : ''; ?>" 
              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all" 
            />
            <p class="text-xs text-gray-500 mt-1">
              <i class="fas fa-lightbulb mr-1"></i>
              Leave blank to save as draft. Set a date to publish immediately or schedule for later.
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-between pt-6 border-t">
            <a href="/ODPM/admin/manage-news.php" class="text-gray-600 hover:text-gray-900 transition-colors">
              <i class="fas fa-arrow-left mr-2"></i>Cancel
            </a>
            <div class="space-x-3">
              <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-lg font-semibold">
                <i class="fas fa-save mr-2"></i><?php echo $item['id'] ? 'Update Article' : 'Create Article'; ?>
              </button>
            </div>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

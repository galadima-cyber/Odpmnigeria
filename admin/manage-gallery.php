<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();

global $config;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $action = $_POST['action'] ?? '';
  if ($action === 'upload') {
    $caption = trim((string)($_POST['caption'] ?? ''));
    if (!empty($_FILES['image']['name'])) {
      $res = handle_upload($_FILES['image'], $config['site']['allowed_image_types'], $config['site']['max_upload_bytes']);
      if ($res['ok']) {
        $stmt = db()->prepare('INSERT INTO gallery_images (filename, caption) VALUES (?, ?)');
        $stmt->execute([$res['filename'], $caption]);
        $msg = 'Image uploaded';
      } else {
        $msg = 'Upload failed: ' . e($res['error']);
      }
    } else {
      $msg = 'Please select an image';
    }
  } elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
      $row = db()->prepare('SELECT filename FROM gallery_images WHERE id=?');
      $row->execute([$id]);
      if ($file = $row->fetch()) {
        $path = rtrim($config['site']['upload_dir'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file['filename'];
        @unlink($path);
      }
      $stmt = db()->prepare('DELETE FROM gallery_images WHERE id=?');
      $stmt->execute([$id]);
      $msg = 'Image deleted';
    }
  }
}

$images = db()->query('SELECT id, filename, caption, created_at FROM gallery_images ORDER BY id DESC')->fetchAll();
$base = $config['site']['upload_base_url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Gallery - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-purple-600 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Manage Gallery</h1>
            <p class="text-xs text-gray-500">Upload and organize images</p>
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
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Gallery Management</h2>
      <p class="text-gray-600 mt-1">Upload photos with captions for the public gallery</p>
    </div>

    <?php if ($msg): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded flex items-start">
        <i class="fas fa-check-circle mt-0.5 mr-3 text-xl"></i>
        <span><?php echo e($msg); ?></span>
      </div>
    <?php endif; ?>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
      <h2 class="text-xl font-bold mb-4 flex items-center">
        <i class="fas fa-cloud-upload-alt text-3xl mr-3"></i>
        Upload New Image
      </h2>
      <form method="post" enctype="multipart/form-data" class="space-y-4">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="upload" />
        
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 font-semibold">
              <i class="fas fa-comment mr-2"></i>Image Caption
            </label>
            <input 
              type="text" 
              name="caption" 
              class="w-full border-2 border-white/30 bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-white placeholder-white/60 focus:border-white focus:ring-2 focus:ring-white/30 transition-all" 
              placeholder="e.g., Annual Meeting 2024"
            />
            <p class="text-xs text-white/80 mt-1">Optional description for the image</p>
          </div>

          <div>
            <label class="block mb-2 font-semibold">
              <i class="fas fa-image mr-2"></i>Select Image *
            </label>
            <input 
              type="file" 
              name="image" 
              accept="image/*"
              class="w-full border-2 border-white/30 bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-white file:text-purple-600 file:font-semibold hover:file:bg-gray-100 transition-all" 
              required 
            />
            <p class="text-xs text-white/80 mt-1">JPG, PNG, GIF, WebP - Max 5MB</p>
          </div>
        </div>

        <button class="bg-white text-purple-600 px-8 py-3 rounded-lg hover:bg-gray-100 transition-colors shadow-lg font-semibold">
          <i class="fas fa-upload mr-2"></i>Upload Image
        </button>
      </form>
    </div>

    <div class="mb-6">
      <h3 class="text-xl font-bold text-gray-900 mb-2">Gallery Images</h3>
      <p class="text-gray-600">All uploaded images (<?php echo count($images); ?> total)</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ($images as $img): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all">
          <div class="relative overflow-hidden">
            <img src="<?php echo e($base . '/' . $img['filename']); ?>" alt="<?php echo e($img['caption']); ?>" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          </div>
          <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-2 flex items-start">
              <i class="fas fa-image text-purple-600 mr-2 mt-1"></i>
              <span><?php echo e($img['caption'] ?: 'No caption'); ?></span>
            </h3>
            <p class="text-xs text-gray-500 mb-3">
              <i class="fas fa-clock mr-1"></i>
              Uploaded: <?php echo date('M d, Y', strtotime($img['created_at'])); ?>
            </p>
            <form method="post" onsubmit="return confirm('Are you sure you want to delete this image?');">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?php echo (int)$img['id']; ?>" />
              <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors font-semibold">
                <i class="fas fa-trash mr-2"></i>Delete Image
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; if (!$images): ?>
        <div class="col-span-3 p-12 text-center bg-white rounded-2xl shadow">
          <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg">No images yet. Upload your first image above.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

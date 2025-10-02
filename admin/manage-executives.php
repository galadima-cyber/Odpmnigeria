<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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
    $eid = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $position = trim((string)($_POST['position'] ?? ''));
    $experience = trim((string)($_POST['experience'] ?? ''));
    $education = trim((string)($_POST['education'] ?? ''));
    $about = trim((string)($_POST['about'] ?? ''));
    $quote = trim((string)($_POST['quote'] ?? ''));
    $linkedin = trim((string)($_POST['linkedin'] ?? ''));
    $twitter = trim((string)($_POST['twitter'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $display_order = (int)($_POST['display_order'] ?? 0);

    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
      $res = handle_upload($_FILES['image'], $config['site']['allowed_image_types'], $config['site']['max_upload_bytes']);
      if ($res['ok']) { $image_path = $res['filename']; }
    }

    if ($eid > 0) {
      if ($image_path) {
        $stmt = db()->prepare('UPDATE executives SET name=?, position=?, image_path=?, experience=?, education=?, about=?, quote=?, linkedin=?, twitter=?, email=?, display_order=? WHERE id=?');
        $stmt->execute([$name, $position, $image_path, $experience, $education, $about, $quote, $linkedin, $twitter, $email, $display_order, $eid]);
      } else {
        $stmt = db()->prepare('UPDATE executives SET name=?, position=?, experience=?, education=?, about=?, quote=?, linkedin=?, twitter=?, email=?, display_order=? WHERE id=?');
        $stmt->execute([$name, $position, $experience, $education, $about, $quote, $linkedin, $twitter, $email, $display_order, $eid]);
      }
      $msg = 'Executive updated';
      $action = 'list';
    } else {
      $stmt = db()->prepare('INSERT INTO executives (name, position, image_path, experience, education, about, quote, linkedin, twitter, email, display_order) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
      $stmt->execute([$name, $position, $image_path, $experience, $education, $about, $quote, $linkedin, $twitter, $email, $display_order]);
      $msg = 'Executive added';
      $action = 'list';
    }
  } elseif ($postAction === 'delete') {
    $eid = (int)($_POST['id'] ?? 0);
    if ($eid) {
      $stmt = db()->prepare('DELETE FROM executives WHERE id=?');
      $stmt->execute([$eid]);
      $msg = 'Executive deleted';
      $action = 'list';
    }
  }
}

function fetch_executive($id) {
  $stmt = db()->prepare('SELECT * FROM executives WHERE id=?');
  $stmt->execute([$id]);
  return $stmt->fetch();
}

$executives = [];
if ($action === 'list') {
  $executives = db()->query('SELECT * FROM executives ORDER BY display_order, name')->fetchAll();
}
$exec = $id ? fetch_executive($id) : ['id'=>0,'name'=>'','position'=>'','image_path'=>'','experience'=>'','education'=>'','about'=>'','quote'=>'','linkedin'=>'','twitter'=>'','email'=>'','display_order'=>0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Executives - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-teal-600 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Manage Executives</h1>
            <p class="text-xs text-gray-500">Executive board members</p>
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
        <h2 class="text-2xl font-bold text-gray-900">Executive Board Management</h2>
        <p class="text-gray-600 mt-1">Add, edit, and manage executive members</p>
      </div>
      <?php if ($action === 'list'): ?>
        <a href="?action=create" class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg font-semibold">
          <i class="fas fa-plus mr-2"></i>Add New Executive
        </a>
      <?php else: ?>
        <a href="/ODPM/admin/manage-executives.php" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
          <i class="fas fa-list mr-2"></i>View All Executives
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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($executives as $e): ?>
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all">
            <div class="h-48 bg-cover bg-center" style="background-image:url('<?php echo e($config['site']['upload_base_url'].'/'.($e['image_path'] ?: 'default.jpg')); ?>')"></div>
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($e['name']); ?></h3>
              <p class="text-teal-600 font-semibold mb-3"><?php echo e($e['position']); ?></p>
              <p class="text-sm text-gray-600 mb-4"><?php echo e(mb_strimwidth($e['about'] ?: '', 0, 100, '...')); ?></p>
              <div class="flex gap-2">
                <a href="?action=edit&id=<?php echo (int)$e['id']; ?>" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors text-center">
                  <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form method="post" class="flex-1" onsubmit="return confirm('Delete this executive?');">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?php echo (int)$e['id']; ?>">
                  <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; if (!$executives): ?>
          <div class="col-span-3 p-12 text-center bg-white rounded-2xl shadow">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No executives yet. Click "Add New Executive" to create one.</p>
          </div>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="mb-6">
          <h3 class="text-xl font-bold text-gray-900 mb-2">
            <?php echo $exec['id'] ? 'Edit Executive' : 'Add New Executive'; ?>
          </h3>
          <p class="text-gray-600">Fill in all the details for the executive member</p>
        </div>

        <form method="post" enctype="multipart/form-data" class="space-y-6">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="action" value="save" />
          <input type="hidden" name="id" value="<?php echo (int)$exec['id']; ?>" />
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-user text-teal-600 mr-2"></i>Full Name *
              </label>
              <input type="text" name="name" value="<?php echo e($exec['name']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="e.g., Umar Bello Galadima" required />
            </div>

            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-briefcase text-teal-600 mr-2"></i>Position *
              </label>
              <input type="text" name="position" value="<?php echo e($exec['position']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="e.g., President, Vice President" required />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-award text-teal-600 mr-2"></i>Experience
              </label>
              <input type="text" name="experience" value="<?php echo e($exec['experience']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="e.g., 10+ Years in Leadership" />
            </div>

            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-graduation-cap text-teal-600 mr-2"></i>Education
              </label>
              <input type="text" name="education" value="<?php echo e($exec['education']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="e.g., BSc Biotechnology" />
            </div>
          </div>

          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-align-left text-teal-600 mr-2"></i>About / Biography *
            </label>
            <textarea name="about" rows="5" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="Write a brief biography..." required><?php echo e($exec['about']); ?></textarea>
          </div>

          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-quote-left text-teal-600 mr-2"></i>Inspirational Quote
            </label>
            <textarea name="quote" rows="3" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="A quote from this executive..."><?php echo e($exec['quote']); ?></textarea>
          </div>

          <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-image text-teal-600 mr-2"></i>Profile Photo
            </label>
            <input type="file" name="image" accept="image/*" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 bg-white focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" />
            <?php if(!empty($exec['image_path'])): ?>
              <div class="mt-4">
                <p class="text-sm text-gray-600 mb-2">Current photo:</p>
                <img src="<?php echo e($config['site']['upload_base_url'].'/'.$exec['image_path']); ?>" alt="Current" class="w-32 h-32 object-cover rounded-lg shadow" />
              </div>
            <?php endif; ?>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fab fa-linkedin text-teal-600 mr-2"></i>LinkedIn URL
              </label>
              <input type="url" name="linkedin" value="<?php echo e($exec['linkedin']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="https://linkedin.com/in/..." />
            </div>

            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fab fa-twitter text-teal-600 mr-2"></i>Twitter Handle
              </label>
              <input type="text" name="twitter" value="<?php echo e($exec['twitter']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="@username" />
            </div>

            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-envelope text-teal-600 mr-2"></i>Email
              </label>
              <input type="email" name="email" value="<?php echo e($exec['email']); ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="email@example.com" />
            </div>
          </div>

          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-sort text-teal-600 mr-2"></i>Display Order
            </label>
            <input type="number" name="display_order" value="<?php echo (int)$exec['display_order']; ?>" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/20 transition-all" placeholder="0" />
            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
          </div>

          <div class="flex items-center justify-between pt-6 border-t">
            <a href="/ODPM/admin/manage-executives.php" class="text-gray-600 hover:text-gray-900 transition-colors">
              <i class="fas fa-arrow-left mr-2"></i>Cancel
            </a>
            <button class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-8 py-3 rounded-lg hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg font-semibold">
              <i class="fas fa-save mr-2"></i><?php echo $exec['id'] ? 'Update Executive' : 'Add Executive'; ?>
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

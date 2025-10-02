<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();

global $config;

$msg = '';

// Handle POST actions: update status / respond / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $action = $_POST['action'] ?? '';
  if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'new';
    $response_text = trim((string)($_POST['response_text'] ?? ''));
    $stmt = db()->prepare("UPDATE reports SET status=?, response_text=?, responded_at = CASE WHEN ? <> '' THEN NOW() ELSE responded_at END WHERE id=?");
    $stmt->execute([$status, $response_text, $response_text, $id]);
    $msg = 'Report updated';
  } elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
      $stmt = db()->prepare('DELETE FROM reports WHERE id=?');
      $stmt->execute([$id]);
      $msg = 'Report deleted';
    }
  }
}

// Filters
$category = trim((string)($_GET['category'] ?? ''));
$status = trim((string)($_GET['status'] ?? ''));

$sql = 'SELECT * FROM reports WHERE 1';
$params = [];
if ($category !== '') { $sql .= ' AND category = ?'; $params[] = $category; }
if ($status !== '') { $sql .= ' AND status = ?'; $params[] = $status; }
$sql .= ' ORDER BY created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll();

$cats = ['Abuse','Corruption','Infrastructure','Health','Education','Other'];
$statuses = ['new','in_progress','resolved','rejected'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Reports - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-orange-600 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Manage Reports</h1>
            <p class="text-xs text-gray-500">Review and respond to submissions</p>
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
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Reports Management</h2>
      <p class="text-gray-600 mt-1">Review public submissions and update their status</p>
    </div>

    <?php if ($msg): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded flex items-start">
        <i class="fas fa-check-circle mt-0.5 mr-3 text-xl"></i>
        <span><?php echo e($msg); ?></span>
      </div>
    <?php endif; ?>

    <form method="get" class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 mb-6 text-white">
      <h3 class="text-lg font-bold mb-4 flex items-center">
        <i class="fas fa-filter mr-2"></i>Filter Reports
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-2">
            <i class="fas fa-tag mr-2"></i>Category
          </label>
          <select name="category" class="w-full border-2 border-white/30 bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-white focus:border-white focus:ring-2 focus:ring-white/30 transition-all">
            <option value="" class="text-gray-900">All Categories</option>
            <?php foreach ($cats as $c): ?>
              <option value="<?php echo e($c); ?>" <?php echo $category===$c?'selected':''; ?> class="text-gray-900"><?php echo e($c); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-2">
            <i class="fas fa-tasks mr-2"></i>Status
          </label>
          <select name="status" class="w-full border-2 border-white/30 bg-white/10 backdrop-blur rounded-lg px-4 py-3 text-white focus:border-white focus:ring-2 focus:ring-white/30 transition-all">
            <option value="" class="text-gray-900">All Statuses</option>
            <?php foreach ($statuses as $s): ?>
              <option value="<?php echo e($s); ?>" <?php echo $status===$s?'selected':''; ?> class="text-gray-900"><?php echo e(ucfirst(str_replace('_',' ',$s))); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="flex items-end">
          <button class="w-full bg-white text-orange-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold shadow-lg">
            <i class="fas fa-search mr-2"></i>Apply Filters
          </button>
        </div>
      </div>
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3 text-left">Title</th>
            <th class="p-3 text-left">Category</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Submitted</th>
            <th class="p-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reports as $r): ?>
          <tr class="border-t align-top">
            <td class="p-3">
              <div class="font-semibold"><?php echo e($r['title']); ?></div>
              <div class="text-gray-600 whitespace-pre-line mt-1"><?php echo nl2br(e(mb_strimwidth($r['description'], 0, 200, '…'))); ?></div>
              <?php if ($r['file_path']): ?>
                <div class="mt-2"><a class="text-odpm-green" target="_blank" href="<?php echo e($config['site']['upload_base_url'].'/'.$r['file_path']); ?>">Attachment</a></div>
              <?php endif; ?>
              <div class="text-xs text-gray-500 mt-2">
                Contact: <?php echo e(trim(($r['contact_name']?:'').' '.($r['contact_phone']?:''))); ?>
                <?php if ($r['contact_email']): ?> | <?php echo e($r['contact_email']); ?><?php endif; ?>
              </div>
            </td>
            <td class="p-3"><?php echo e($r['category']); ?></td>
            <td class="p-3"><?php echo e(ucfirst($r['status'])); ?></td>
            <td class="p-3"><?php echo e($r['created_at']); ?></td>
            <td class="p-3 w-80">
              <form method="post" class="space-y-3">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
                <div>
                  <label class="block text-sm font-medium mb-1">Status</label>
                  <select name="status" class="w-full border rounded px-3 py-2">
                    <?php foreach ($statuses as $s): ?>
                      <option value="<?php echo e($s); ?>" <?php echo $r['status']===$s?'selected':''; ?>><?php echo e(ucfirst($s)); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1">Response</label>
                  <textarea name="response_text" rows="3" class="w-full border rounded px-3 py-2"><?php echo e($r['response_text'] ?: ''); ?></textarea>
                </div>
                <div class="flex gap-3">
                  <button name="action" value="update" class="bg-odpm-green text-white px-4 py-2 rounded">Save</button>
                  <button name="action" value="delete" class="text-red-600" onclick="return confirm('Delete this report?')">Delete</button>
                </div>
              </form>
            </td>
          </tr>
          <?php endforeach; if (!$reports): ?>
            <tr><td colspan="5" class="p-4 text-center text-gray-500">No reports found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

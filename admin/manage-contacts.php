<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_login();

global $config;

$msg = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $action = $_POST['action'] ?? '';
  
  if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'new';
    $response_text = trim((string)($_POST['response_text'] ?? ''));
    
    $stmt = db()->prepare("UPDATE contact_submissions SET status=?, response_text=?, responded_at = CASE WHEN ? <> '' THEN NOW() ELSE responded_at END WHERE id=?");
    $stmt->execute([$status, $response_text, $response_text, $id]);
    $msg = 'Contact updated';
    
  } elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM contact_submissions WHERE id=?');
    $stmt->execute([$id]);
    $msg = 'Contact deleted';
  }
}

// Filters
$status_filter = $_GET['status'] ?? 'all';
$where = $status_filter !== 'all' ? "WHERE status = ?" : "";
$params = $status_filter !== 'all' ? [$status_filter] : [];

$stmt = db()->prepare("SELECT * FROM contact_submissions $where ORDER BY created_at DESC");
$stmt->execute($params);
$contacts = $stmt->fetchAll();

// Count by status
$counts = db()->query("SELECT status, COUNT(*) as cnt FROM contact_submissions GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
$total = array_sum($counts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Contacts - ODPM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <nav class="bg-white shadow-lg border-b-4 border-blue-600 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center space-x-4">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-12 w-auto" />
          <div>
            <h1 class="text-xl font-bold text-gray-900">Contact Submissions</h1>
            <p class="text-xs text-gray-500">Get Involved form submissions</p>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <a href="/ODPM/admin/index.php" class="text-gray-600 hover:text-odpm-green transition-colors">
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
    <?php if ($msg): ?>
      <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded">
        <i class="fas fa-check-circle mr-2"></i><?php echo e($msg); ?>
      </div>
    <?php endif; ?>

    <!-- Filter Tabs -->
    <div class="mb-6 flex gap-2">
      <a href="?status=all" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
        All (<?php echo $total; ?>)
      </a>
      <a href="?status=new" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'new' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
        New (<?php echo $counts['new'] ?? 0; ?>)
      </a>
      <a href="?status=read" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'read' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
        Read (<?php echo $counts['read'] ?? 0; ?>)
      </a>
      <a href="?status=responded" class="px-4 py-2 rounded-lg <?php echo $status_filter === 'responded' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
        Responded (<?php echo $counts['responded'] ?? 0; ?>)
      </a>
    </div>

    <!-- Contacts List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interest</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php foreach ($contacts as $contact): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="font-medium text-gray-900"><?php echo e($contact['first_name'] . ' ' . $contact['last_name']); ?></div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              <a href="mailto:<?php echo e($contact['email']); ?>" class="text-blue-600 hover:underline">
                <?php echo e($contact['email']); ?>
              </a>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                <?php echo ucfirst(e($contact['interest'])); ?>
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              <?php echo date('M j, Y', strtotime($contact['created_at'])); ?>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <?php
              $statusColors = ['new' => 'bg-yellow-100 text-yellow-800', 'read' => 'bg-blue-100 text-blue-800', 'responded' => 'bg-green-100 text-green-800'];
              $statusColor = $statusColors[$contact['status']] ?? 'bg-gray-100 text-gray-800';
              ?>
              <span class="px-2 py-1 text-xs rounded-full <?php echo $statusColor; ?>">
                <?php echo ucfirst(e($contact['status'])); ?>
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <button onclick="viewContact(<?php echo $contact['id']; ?>)" class="text-blue-600 hover:text-blue-800 mr-3">
                <i class="fas fa-eye"></i> View
              </button>
              <form method="POST" class="inline" onsubmit="return confirm('Delete this contact?')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                <button type="submit" class="text-red-600 hover:text-red-800">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          
          <!-- Expandable Details Row -->
          <tr id="details-<?php echo $contact['id']; ?>" class="hidden bg-gray-50">
            <td colspan="6" class="px-6 py-4">
              <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold mb-4">Message Details</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <p class="text-sm text-gray-600">Full Name</p>
                    <p class="font-medium"><?php echo e($contact['first_name'] . ' ' . $contact['last_name']); ?></p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium"><?php echo e($contact['email']); ?></p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Interest</p>
                    <p class="font-medium"><?php echo ucfirst(e($contact['interest'])); ?></p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Submitted</p>
                    <p class="font-medium"><?php echo date('F j, Y g:i A', strtotime($contact['created_at'])); ?></p>
                  </div>
                </div>
                
                <div class="mb-4">
                  <p class="text-sm text-gray-600 mb-2">Message</p>
                  <div class="bg-gray-50 p-4 rounded border">
                    <?php echo nl2br(e($contact['message'])); ?>
                  </div>
                </div>

                <form method="POST" class="space-y-4">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg">
                      <option value="new" <?php echo $contact['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                      <option value="read" <?php echo $contact['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                      <option value="responded" <?php echo $contact['status'] === 'responded' ? 'selected' : ''; ?>>Responded</option>
                    </select>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Response Notes (Optional)</label>
                    <textarea name="response_text" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo e($contact['response_text'] ?? ''); ?></textarea>
                  </div>
                  
                  <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          
          <?php if (empty($contacts)): ?>
          <tr>
            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
              <i class="fas fa-inbox text-4xl mb-2"></i>
              <p>No contact submissions yet</p>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function viewContact(id) {
      const row = document.getElementById('details-' + id);
      row.classList.toggle('hidden');
    }
  </script>
</body>
</html>

<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $category = trim((string)($_POST['category'] ?? ''));
  $title = trim((string)($_POST['title'] ?? ''));
  $description = trim((string)($_POST['description'] ?? ''));
  $contact_name = trim((string)($_POST['contact_name'] ?? ''));
  $contact_email = trim((string)($_POST['contact_email'] ?? ''));
  $contact_phone = trim((string)($_POST['contact_phone'] ?? ''));

  $file_path = null;
  if (!empty($_FILES['attachment']['name'])) {
    $res = handle_upload(
      $_FILES['attachment'],
      $config['site']['allowed_file_types'],
      $config['site']['max_upload_bytes']
    );
    if ($res['ok']) {
      $file_path = $res['filename'];
    } else {
      $err = $res['error'];
      $msg = 'Upload failed: ' . e($err);
    }
  }

  if (!isset($msg)) {
    $stmt = db()->prepare("INSERT INTO reports (category, title, description, file_path, contact_name, contact_email, contact_phone, status) VALUES (?,?,?,?,?,?,?, 'new')");
    $stmt->execute([$category, $title, $description, $file_path, $contact_name, $contact_email, $contact_phone]);
    $success = true;
    
    // Send email notification to admin
    global $config;
    $adminEmail = $config['email']['admin_email'];
    $subject = 'New Report Submitted - ODPM Nigeria';
    
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #118B50; color: white; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #118B50; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Report Submitted</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <span class='label'>Category:</span> " . ucfirst($category) . "
                </div>
                <div class='field'>
                    <span class='label'>Title:</span> " . htmlspecialchars($title) . "
                </div>
                <div class='field'>
                    <span class='label'>Description:</span><br>
                    " . nl2br(htmlspecialchars($description)) . "
                </div>
                <div class='field'>
                    <span class='label'>Contact:</span> {$contact_name} ({$contact_email})
                </div>
            </div>
            <div style='text-align: center; padding: 20px;'>
                <p><a href='http://localhost/ODPM/admin/manage-reports.php'>View in Admin Dashboard</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    send_email($adminEmail, $subject, $emailBody);
  }
}
?>

<!-- Hero Section -->
<section class="py-16 bg-gradient-to-br from-odpm-green to-green-700 text-white">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <div class="inline-block mb-4">
      <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-semibold">
        <i class="fas fa-flag mr-2"></i>Report an Issue
      </span>
    </div>
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Submit a Report or Complaint</h1>
    <p class="text-xl opacity-90 max-w-2xl mx-auto">
      Help us improve our communities by reporting issues, concerns, or complaints. Your voice matters.
    </p>
  </div>
</section>

<!-- Report Form Section -->
<section class="py-16 bg-gray-50">
  <div class="max-w-4xl mx-auto px-4">
    <?php if (!empty($success)): ?>
      <div class="mb-8 bg-green-50 border-l-4 border-green-500 text-green-800 p-6 rounded-lg flex items-start">
        <i class="fas fa-check-circle text-3xl mr-4 mt-1"></i>
        <div>
          <h3 class="font-bold text-lg mb-2">Report Submitted Successfully!</h3>
          <p>Thank you for your submission. Our team will review it and take appropriate action.</p>
          <a href="/ODPM/public/reports.php" class="inline-block mt-3 text-green-700 hover:text-green-900 font-semibold">
            <i class="fas fa-plus mr-2"></i>Submit Another Report
          </a>
        </div>
      </div>
    <?php elseif (!empty($msg)): ?>
      <div class="mb-8 bg-red-50 border-l-4 border-red-500 text-red-800 p-6 rounded-lg flex items-start">
        <i class="fas fa-exclamation-circle text-3xl mr-4 mt-1"></i>
        <div>
          <h3 class="font-bold text-lg mb-2">Error</h3>
          <p><?php echo $msg; ?></p>
        </div>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-xl p-8">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Report Details</h2>
        <p class="text-gray-600">Fill in the form below. You can submit anonymously or provide contact information.</p>
      </div>

      <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        
        <!-- Category & Title -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-tag text-odpm-green mr-2"></i>Category *
            </label>
            <select name="category" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" required>
              <option value="">Select a category</option>
              <option value="Abuse">Abuse</option>
              <option value="Corruption">Corruption</option>
              <option value="Infrastructure">Infrastructure</option>
              <option value="Health">Health</option>
              <option value="Education">Education</option>
              <option value="Environment">Environment</option>
              <option value="Other">Other</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Choose the most relevant category</p>
          </div>

          <div>
            <label class="block mb-2 font-semibold text-gray-700">
              <i class="fas fa-heading text-odpm-green mr-2"></i>Report Title *
            </label>
            <input 
              type="text" 
              name="title" 
              class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" 
              placeholder="e.g., Poor Road Condition on Main Street"
              required 
            />
            <p class="text-xs text-gray-500 mt-1">Brief title summarizing the issue</p>
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block mb-2 font-semibold text-gray-700">
            <i class="fas fa-align-left text-odpm-green mr-2"></i>Detailed Description *
          </label>
          <textarea 
            name="description" 
            rows="8" 
            class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all"
            placeholder="Please provide as much detail as possible:&#10;- What is the issue?&#10;- Where is it located?&#10;- When did it occur?&#10;- Who is affected?&#10;- Any other relevant information..."
            required
          ></textarea>
          <p class="text-xs text-gray-500 mt-1">Provide detailed information to help us understand and address the issue</p>
        </div>

        <!-- Attachment -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
          <label class="block mb-2 font-semibold text-gray-700">
            <i class="fas fa-paperclip text-odpm-green mr-2"></i>Attachment (Optional)
          </label>
          <input 
            type="file" 
            name="attachment" 
            accept="image/*,.pdf"
            class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 bg-white focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" 
          />
          <p class="text-xs text-gray-500 mt-2">
            <i class="fas fa-info-circle mr-1"></i>
            Upload supporting documents or images (JPG, PNG, PDF - Max 5MB)
          </p>
        </div>

        <!-- Contact Information (Optional) -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
          <h3 class="font-bold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-user-circle text-blue-600 mr-2"></i>
            Contact Information (Optional)
          </h3>
          <p class="text-sm text-gray-600 mb-4">
            You can submit anonymously, but providing contact info helps us follow up with you.
          </p>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-user text-blue-600 mr-2"></i>Your Name
              </label>
              <input 
                type="text" 
                name="contact_name" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" 
                placeholder="John Doe"
              />
            </div>
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-envelope text-blue-600 mr-2"></i>Email Address
              </label>
              <input 
                type="email" 
                name="contact_email" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" 
                placeholder="john@example.com"
              />
            </div>
            <div>
              <label class="block mb-2 font-semibold text-gray-700">
                <i class="fas fa-phone text-blue-600 mr-2"></i>Phone Number
              </label>
              <input 
                type="text" 
                name="contact_phone" 
                class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" 
                placeholder="+234..."
              />
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between pt-6 border-t">
          <p class="text-sm text-gray-500">
            <i class="fas fa-lock mr-2"></i>Your information is secure and confidential
          </p>
          <button class="bg-odpm-green text-white px-8 py-4 rounded-lg hover:bg-green-700 transition-colors shadow-lg font-semibold text-lg">
            <i class="fas fa-paper-plane mr-2"></i>Submit Report
          </button>
        </div>
      </form>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <i class="fas fa-shield-alt text-4xl text-odpm-green mb-3"></i>
        <h3 class="font-bold text-gray-900 mb-2">Confidential</h3>
        <p class="text-sm text-gray-600">Your report is handled with strict confidentiality</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <i class="fas fa-clock text-4xl text-odpm-green mb-3"></i>
        <h3 class="font-bold text-gray-900 mb-2">Quick Response</h3>
        <p class="text-sm text-gray-600">We review all reports within 48 hours</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6 text-center">
        <i class="fas fa-user-secret text-4xl text-odpm-green mb-3"></i>
        <h3 class="font-bold text-gray-900 mb-2">Anonymous Option</h3>
        <p class="text-sm text-gray-600">Submit without providing personal details</p>
      </div>
    </div>
  </div>
</section>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

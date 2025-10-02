<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/auth.php';

if (is_logged_in()) { redirect('/ODPM/admin/dashboard.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf_or_die();
  $email = trim((string)($_POST['email'] ?? ''));
  $password = (string)($_POST['password'] ?? '');

  try {
    if (login($email, $password)) {
      redirect('/ODPM/admin/dashboard.php');
    } else {
      $error = 'Invalid credentials';
    }
  } catch (Throwable $e) {
    $error = 'Login error';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - ODPM Nigeria</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-gradient-to-br from-odpm-light to-white min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-20 w-auto mx-auto mb-4" />
      <h1 class="text-3xl font-bold text-gray-900">Admin Portal</h1>
      <p class="text-gray-600 mt-2">ODPM Nigeria Management System</p>
    </div>
    
    <form method="post" class="bg-white p-8 rounded-2xl shadow-xl">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Sign In</h2>
      
      <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 flex items-start">
          <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
          <span><?php echo e($error); ?></span>
        </div>
      <?php endif; ?>
      
      <?php echo csrf_field(); ?>
      
      <div class="mb-5">
        <label class="block mb-2 text-sm font-semibold text-gray-700">
          <i class="fas fa-envelope mr-2 text-odpm-green"></i>Email Address
        </label>
        <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" placeholder="admin@odpm.com" required>
      </div>
      
      <div class="mb-6">
        <label class="block mb-2 text-sm font-semibold text-gray-700">
          <i class="fas fa-lock mr-2 text-odpm-green"></i>Password
        </label>
        <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-odpm-green focus:ring-2 focus:ring-odpm-green/20 transition-all" placeholder="••••••••" required>
      </div>
      
      <button class="w-full bg-odpm-green text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
      </button>
      
      <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-xs text-blue-800 flex items-start">
          <i class="fas fa-info-circle mt-0.5 mr-2"></i>
          <span><strong>First time?</strong> Create an admin user using the instructions in README.md or run hash.php to generate a password hash.</span>
        </p>
      </div>
    </form>
    
    <div class="text-center mt-6">
      <a href="/ODPM/public/index.php" class="text-gray-600 hover:text-odpm-green transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back to Website
      </a>
    </div>
  </div>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$config = require dirname(__DIR__) . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo e($config['site']['name']); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
</head>
<body class="bg-white font-sans">
  <nav class="bg-odpm-light shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <div class="flex items-center">
          <img src="/ODPM/images/logo1.jpg" alt="ODPM Logo" class="h-16 w-auto" />
        </div>
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-8">
            <a href="/ODPM/public/index.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">Home</a>
            <a href="/ODPM/public/about.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">About</a>
            <a href="/ODPM/public/news.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">News</a>
            <a href="/ODPM/public/executives.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">Executives</a>
            <a href="/ODPM/public/founder.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">Founder</a>
            <a href="/ODPM/public/gallery.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">Gallery</a>
            <button class="bg-odpm-green text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium">
              <a href="/ODPM/public/reports.php" class="text-white">Join Us</a>
            </button>
          </div>
        </div>
        <div class="md:hidden">
          <button type="button" class="text-gray-900 hover:text-odpm-green focus:outline-none" id="mobile-menu-button">
            <i class="fas fa-bars text-2xl"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="md:hidden hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-odpm-light border-t">
        <a href="/ODPM/public/index.php" class="block px-3 py-2">Home</a>
        <a href="/ODPM/public/about.php" class="block px-3 py-2">About</a>
        <a href="/ODPM/public/news.php" class="block px-3 py-2">News</a>
        <a href="/ODPM/public/executives.php" class="block px-3 py-2">Executives</a>
        <a href="/ODPM/public/founder.php" class="block px-3 py-2">Founder</a>
        <a href="/ODPM/public/gallery.php" class="block px-3 py-2">Gallery</a>
        <a href="/ODPM/public/reports.php" class="block px-3 py-2">Join Us</a>
      </div>
    </div>
  </nav>
  <script>
    document.getElementById('mobile-menu-button')?.addEventListener('click', function(){
      document.getElementById('mobile-menu').classList.toggle('hidden');
    });
  </script>

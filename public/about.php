<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

$page = ['title' => 'About ODPM Nigeria', 'content' => ''];
try {
  $stmt = db()->prepare("SELECT title, content FROM pages WHERE slug = 'about'");
  $stmt->execute();
  if ($row = $stmt->fetch()) { $page = $row; }
} catch (Throwable $e) {}
?>

<!-- Hero Section -->
<section class="py-16 bg-gradient-to-br from-odpm-green to-green-700 text-white">
  <div class="max-w-4xl mx-auto px-4 text-center">
    <div class="inline-block mb-4">
      <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-semibold">
        <i class="fas fa-info-circle mr-2"></i>About Us
      </span>
    </div>
    <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo e($page['title']); ?></h1>
    <p class="text-xl opacity-90">Learn about our mission, vision, and impact</p>
  </div>
</section>

<!-- Content Section -->
<section class="py-16 bg-white">
  <div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-2xl shadow-lg p-8">
      <?php if (!empty($page['content'])): ?>
        <article class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
          <?php echo nl2br(e($page['content'])); ?>
        </article>
      <?php else: ?>
        <div class="text-center py-12">
          <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg">No content yet. Use the admin panel to add About content.</p>
          <a href="/ODPM/admin/manage-pages.php" class="inline-block mt-4 bg-odpm-green text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>Add Content in Admin
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <i class="fas fa-users text-5xl text-odpm-green mb-4"></i>
        <p class="text-4xl font-bold text-gray-900 mb-2">1500+</p>
        <p class="text-gray-600 font-semibold">Communities Served</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <i class="fas fa-heart text-5xl text-odpm-green mb-4"></i>
        <p class="text-4xl font-bold text-gray-900 mb-2">1000+</p>
        <p class="text-gray-600 font-semibold">Lives Impacted</p>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
        <i class="fas fa-project-diagram text-5xl text-odpm-green mb-4"></i>
        <p class="text-4xl font-bold text-gray-900 mb-2">50+</p>
        <p class="text-gray-600 font-semibold">Active Projects</p>
      </div>
    </div>
  </div>
</section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

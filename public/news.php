<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

$stmt = db()->query("SELECT id, title, slug, excerpt, image_path, published_at FROM news WHERE published_at IS NOT NULL ORDER BY published_at DESC");
$items = $stmt->fetchAll();
?>
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-bold mb-8">News</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php foreach ($items as $n): ?>
      <a href="/ODPM/public/news-single.php?slug=<?php echo e($n['slug']); ?>" class="group block rounded-2xl overflow-hidden shadow hover:shadow-lg">
        <div class="h-56 bg-center bg-cover" style="background-image:url('<?php echo e(($config['site']['upload_base_url'] . '/' . $n['image_path'])); ?>')"></div>
        <div class="p-5">
          <h3 class="text-xl font-semibold group-hover:text-odpm-green"><?php echo e($n['title']); ?></h3>
          <p class="text-gray-600 mt-2"><?php echo e($n['excerpt'] ?? ''); ?></p>
        </div>
      </a>
      <?php endforeach; if (!$items): ?>
        <div class="col-span-3 text-center text-gray-500">No news yet.</div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

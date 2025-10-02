<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare("SELECT id, title, content, image_path, published_at FROM news WHERE slug = ?");
$stmt->execute([$slug]);
$article = $stmt->fetch();
?>
<section class="py-16 bg-white">
  <div class="max-w-4xl mx-auto px-4">
    <?php if ($article): ?>
      <h1 class="text-4xl font-bold mb-4"><?php echo e($article['title']); ?></h1>
      <?php if(!empty($article['image_path'])): ?>
        <img class="rounded-xl shadow mb-6" src="<?php echo e($config['site']['upload_base_url'] . '/' . $article['image_path']); ?>" alt="<?php echo e($article['title']); ?>">
      <?php endif; ?>
      <article class="prose max-w-none"><?php echo nl2br(e($article['content'])); ?></article>
    <?php else: ?>
      <p class="text-gray-600">Article not found.</p>
    <?php endif; ?>
  </div>
</section>
<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

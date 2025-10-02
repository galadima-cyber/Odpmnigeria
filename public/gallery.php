<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

global $config;

$stmt = db()->query("SELECT id, filename, caption, created_at FROM gallery_images ORDER BY id DESC");
$images = $stmt->fetchAll();
$base = $config['site']['upload_base_url'];
?>

<!-- Hero Section -->
<section class="py-20 bg-gradient-to-br from-odpm-green to-odpm-mint relative overflow-hidden">
  <div class="absolute inset-0 opacity-10">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 40px 40px;"></div>
  </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
    <span class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold mb-6">Our Moments</span>
    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Photo Gallery</h1>
    <p class="text-xl text-white/90 max-w-3xl mx-auto">Capturing the impact of our work across Nigerian communities</p>
  </div>
</section>

<?php if ($images && count($images) > 0): ?>
<!-- Main Slider Section -->
<section class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="relative">
      <!-- Slider Container -->
      <div class="relative overflow-hidden rounded-2xl shadow-2xl" style="height: 600px;">
        <?php foreach ($images as $index => $img): ?>
        <div class="gallery-slide absolute inset-0 transition-all duration-700 ease-in-out <?php echo $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?>" data-slide="<?php echo $index; ?>">
          <img src="<?php echo e($base . '/' . $img['filename']); ?>" alt="<?php echo e($img['caption']); ?>" class="w-full h-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
            <h3 class="text-2xl md:text-3xl font-bold mb-2"><?php echo e($img['caption']); ?></h3>
            <p class="text-sm text-white/80"><?php echo date('F j, Y', strtotime($img['created_at'])); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Navigation Arrows -->
      <button onclick="changeSlide(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-900 p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 z-20">
        <i class="fas fa-chevron-left text-xl"></i>
      </button>
      <button onclick="changeSlide(1)" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-900 p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 z-20">
        <i class="fas fa-chevron-right text-xl"></i>
      </button>

      <!-- Slide Indicators -->
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        <?php foreach ($images as $index => $img): ?>
        <button onclick="goToSlide(<?php echo $index; ?>)" class="slide-indicator w-3 h-3 rounded-full transition-all duration-300 <?php echo $index === 0 ? 'bg-white w-8' : 'bg-white/50'; ?>"></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Thumbnail Grid -->
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-bold mb-8 text-center">All Photos</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php foreach ($images as $index => $img): ?>
      <div onclick="goToSlide(<?php echo $index; ?>); window.scrollTo({top: 0, behavior: 'smooth'});" class="group relative overflow-hidden rounded-xl shadow hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105">
        <img src="<?php echo e($base . '/' . $img['filename']); ?>" alt="<?php echo e($img['caption']); ?>" class="w-full h-64 object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform">
          <h3 class="text-sm font-semibold line-clamp-2"><?php echo e($img['caption']); ?></h3>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.gallery-slide');
const indicators = document.querySelectorAll('.slide-indicator');
const totalSlides = slides.length;
let autoSlideInterval;

function showSlide(n) {
  currentSlide = (n + totalSlides) % totalSlides;
  
  slides.forEach((slide, index) => {
    if (index === currentSlide) {
      slide.classList.remove('opacity-0', 'z-0');
      slide.classList.add('opacity-100', 'z-10');
    } else {
      slide.classList.remove('opacity-100', 'z-10');
      slide.classList.add('opacity-0', 'z-0');
    }
  });

  indicators.forEach((indicator, index) => {
    if (index === currentSlide) {
      indicator.classList.remove('bg-white/50', 'w-3');
      indicator.classList.add('bg-white', 'w-8');
    } else {
      indicator.classList.remove('bg-white', 'w-8');
      indicator.classList.add('bg-white/50', 'w-3');
    }
  });
}

function changeSlide(direction) {
  showSlide(currentSlide + direction);
  resetAutoSlide();
}

function goToSlide(n) {
  showSlide(n);
  resetAutoSlide();
}

function autoSlide() {
  changeSlide(1);
}

function resetAutoSlide() {
  clearInterval(autoSlideInterval);
  autoSlideInterval = setInterval(autoSlide, 5000);
}

// Auto slide every 5 seconds
autoSlideInterval = setInterval(autoSlide, 5000);

// Touch/Swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

const sliderContainer = document.querySelector('.relative.overflow-hidden');

sliderContainer.addEventListener('touchstart', (e) => {
  touchStartX = e.changedTouches[0].screenX;
});

sliderContainer.addEventListener('touchend', (e) => {
  touchEndX = e.changedTouches[0].screenX;
  handleSwipe();
});

function handleSwipe() {
  if (touchEndX < touchStartX - 50) {
    changeSlide(1); // Swipe left
  }
  if (touchEndX > touchStartX + 50) {
    changeSlide(-1); // Swipe right
  }
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') changeSlide(-1);
  if (e.key === 'ArrowRight') changeSlide(1);
});
</script>

<?php else: ?>
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
    <h2 class="text-2xl font-bold text-gray-600 mb-2">No images yet</h2>
    <p class="text-gray-500">Check back soon for our latest photos!</p>
  </div>
</section>
<?php endif; ?>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

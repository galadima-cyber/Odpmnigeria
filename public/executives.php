<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

// Fetch all executives from database
$executives = [];
try {
  $stmt = db()->query("SELECT * FROM executives ORDER BY display_order, name");
  $executives = $stmt->fetchAll();
} catch (Throwable $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Executive Members - ODPM Nigeria</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { 'odpm-green': '#118B50','odpm-light': '#FBF6E9','odpm-lemon': '#E3F0AF','odpm-mint': '#5DB996', }, }, }, };
  </script>
  <style>
    .executive-overlay { background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px); }
  </style>
</head>
<body class="bg-white font-sans">
  <!-- Navigation -->
  <!-- <nav class="bg-odpm-light shadow-lg sticky top-0 z-50">
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
            <a href="/ODPM/public/executives.php" class="text-odpm-green px-3 py-2 text-lg font-medium">Executives</a>
            <a href="/ODPM/public/founder.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">Founder</a>
            <a href="/ODPM/public/gallery.php" class="text-gray-900 hover:text-odpm-green px-3 py-2 text-lg font-medium">Gallery</a>
          </div>
        </div>
        <div class="md:hidden">
          <button type="button" class="text-gray-900 hover:text-odpm-green" id="mobile-menu-button">
            <i class="fas fa-bars text-2xl"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="md:hidden hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1 bg-odpm-light border-t">
        <a href="/ODPM/public/index.php" class="block px-3 py-2">Home</a>
        <a href="/ODPM/public/about.php" class="block px-3 py-2">About</a>
        <a href="/ODPM/public/news.php" class="block px-3 py-2">News</a>
        <a href="/ODPM/public/executives.php" class="block px-3 py-2">Executives</a>
        <a href="/ODPM/public/founder.php" class="block px-3 py-2">Founder</a>
        <a href="/ODPM/public/gallery.php" class="block px-3 py-2">Gallery</a>
      </div>
    </div>
  </nav> -->

  <!-- Hero Section -->
  <section style="background-image:url('/ODPM/images/Anualmeet.jpg');background-size:cover;background-position:center;">
    <div class="executive-overlay py-20 text-center text-white">
      <h1 class="text-4xl md:text-6xl font-bold mb-6">Executive Members</h1>
      <p class="text-xl max-w-3xl mx-auto px-4">Meet the dedicated leaders driving positive change across Nigeria through community development, humanitarian initiatives, and political activism.</p>
    </div>
  </section>

  <!-- Executives Content -->
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="inline-block bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold mb-4">Leadership Team</span>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Our Executive Board</h2>
        <?php if (!empty($page['content'])): ?>
          <article class="prose max-w-3xl mx-auto text-gray-600">
            <?php echo nl2br(e($page['content'])); ?>
          </article>
        <?php else: ?>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">Use the admin panel to add executive team information and bios.</p>
        <?php endif; ?>
      </div>

      <!-- Executive Cards from Database -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($executives as $exec): ?>
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2 cursor-pointer" onclick="openModal(<?php echo (int)$exec['id']; ?>)">
            <div class="h-48 bg-cover bg-center" style="background-image:url('<?php echo e($config['site']['upload_base_url'].'/'.($exec['image_path'] ?: 'default.jpg')); ?>')"></div>
            <div class="p-6 text-center">
              <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo e($exec['name']); ?></h3>
              <p class="text-odpm-green font-semibold mb-3"><?php echo e($exec['position']); ?></p>
              <p class="text-sm text-gray-600"><?php echo e(mb_strimwidth($exec['about'] ?: '', 0, 80, '...')); ?></p>
            </div>
          </div>
        <?php endforeach; if (!$executives): ?>
          <div class="col-span-4 p-12 text-center bg-white rounded-2xl shadow">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No executives yet. Add them via the admin panel.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Modal for Executive Details -->
  <div id="executiveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-8">
        <div class="flex justify-between items-start mb-6">
          <h3 class="text-2xl font-bold text-gray-900" id="modalName"></h3>
          <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6 mb-6">
          <div class="flex-shrink-0">
            <img id="modalImage" src="" alt="Executive Photo" class="w-48 h-48 object-cover rounded-xl shadow-lg" />
          </div>
          <div class="flex-1">
            <div class="space-y-4">
              <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Position</h4>
                <p id="modalPosition" class="text-gray-700"></p>
              </div>
              <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Experience</h4>
                <p id="modalExperience" class="text-gray-700"></p>
              </div>
              <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Education</h4>
                <p id="modalEducation" class="text-gray-700"></p>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">About</h4>
            <p id="modalAbout" class="text-gray-700 leading-relaxed"></p>
          </div>

          <div class="bg-gradient-to-r from-odpm-mint to-white p-6 rounded-xl" id="quoteSection">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Quote</h4>
            <blockquote id="modalQuote" class="text-gray-700 italic text-lg leading-relaxed"></blockquote>
          </div>

          <div class="flex flex-wrap gap-4" id="socialLinks">
            <a href="#" id="linkedinLink" class="flex items-center text-odpm-green hover:text-green-700 transition-colors">
              <i class="fab fa-linkedin mr-2"></i><span>LinkedIn</span>
            </a>
            <a href="#" id="twitterLink" class="flex items-center text-odpm-green hover:text-green-700 transition-colors">
              <i class="fab fa-twitter mr-2"></i><span>Twitter</span>
            </a>
            <a href="#" id="emailLink" class="flex items-center text-odpm-green hover:text-green-700 transition-colors">
              <i class="fas fa-envelope mr-2"></i><span>Email</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const executives = <?php echo json_encode($executives); ?>;
    
    function openModal(id) {
      const exec = executives.find(e => e.id == id);
      if (!exec) return;
      
      document.getElementById('modalName').textContent = exec.name;
      document.getElementById('modalPosition').textContent = exec.position || 'N/A';
      document.getElementById('modalExperience').textContent = exec.experience || 'N/A';
      document.getElementById('modalEducation').textContent = exec.education || 'N/A';
      document.getElementById('modalAbout').textContent = exec.about || 'No information available.';
      document.getElementById('modalImage').src = '<?php echo $config['site']['upload_base_url']; ?>/' + (exec.image_path || 'default.jpg');
      
      // Quote
      if (exec.quote) {
        document.getElementById('modalQuote').textContent = '"' + exec.quote + '"';
        document.getElementById('quoteSection').style.display = 'block';
      } else {
        document.getElementById('quoteSection').style.display = 'none';
      }
      
      // Social links
      const socialLinks = document.getElementById('socialLinks');
      socialLinks.innerHTML = '';
      if (exec.linkedin) {
        socialLinks.innerHTML += `<a href="${exec.linkedin}" target="_blank" class="flex items-center text-odpm-green hover:text-green-700"><i class="fab fa-linkedin mr-2"></i>LinkedIn</a>`;
      }
      if (exec.twitter) {
        socialLinks.innerHTML += `<a href="https://twitter.com/${exec.twitter}" target="_blank" class="flex items-center text-odpm-green hover:text-green-700"><i class="fab fa-twitter mr-2"></i>Twitter</a>`;
      }
      if (exec.email) {
        socialLinks.innerHTML += `<a href="mailto:${exec.email}" class="flex items-center text-odpm-green hover:text-green-700"><i class="fas fa-envelope mr-2"></i>Email</a>`;
      }
      
      document.getElementById('executiveModal').classList.remove('hidden');
    }
    
    function closeModal() {
      document.getElementById('executiveModal').classList.add('hidden');
    }
    
    document.getElementById('mobile-menu-button')?.addEventListener('click', function(){
      document.getElementById('mobile-menu').classList.toggle('hidden');
    });
  </script>

  <?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
</body>
</html>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

?>

  <!-- Hero Section -->
  <section class="py-20 bg-gradient-to-br relative overflow-hidden">
    <div class="absolute inset-0" style="background-image:url('/ODPM/images/meet2-founder.jpg');background-size:cover;background-position:center;">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
      <span class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold mb-6">Founder & Visionary</span>
      <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Meet Our Founder</h1>
      <p class="text-xl text-white/90 max-w-3xl mx-auto">The visionary leader who founded ODPM Nigeria with a dream to create lasting positive change across Nigeria.</p>
    </div>
  </section>

  <!-- Founder Profile Section -->
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1">
          <div class="relative">
            <img src="/ODPM/images/founder-side-image.jpg" alt="ODPM Founder" class="w-full h-[600px] object-cover rounded-2xl shadow-2xl" />
            <div class="absolute -bottom-6 -right-6 bg-odpm-light p-6 rounded-xl shadow-lg">
              <p class="text-3xl font-bold text-odpm-green">10+</p>
              <p class="text-gray-700 font-semibold">Years of Leadership</p>
            </div>
          </div>
        </div>
        <div class="order-1 lg:order-2 space-y-6">
          <div class="inline-block">
            <span class="bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold">Founder Profile</span>
          </div>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
            <?php echo e(get_content('founder_name', 'Umar Bello Galadima')); ?>
          </h2>
          <p class="text-lg text-gray-700 leading-relaxed">
            <?php echo e(get_content('founder_bio1', 'Umar Bello Galadima is the visionary founder and president of ODPM Nigeria. With over 10 years of experience in political activism, community development, and social work, Umar has dedicated his life to empowering young Nigerians and creating positive change across the country.')); ?>
          </p>
          <p class="text-lg text-gray-700 leading-relaxed">
            <?php echo e(get_content('founder_bio2', 'His journey began as a young activist in Kano State, where he witnessed firsthand the challenges facing Nigerian communities. This experience ignited his passion for social change and led to the establishment of ODPM Nigeria in 2015.')); ?>
          </p>

          <div class="grid grid-cols-2 gap-6 mt-8">
            <div class="text-center bg-gradient-to-r from-odpm-mint to-white p-4 rounded-xl shadow-lg">
              <i class="fas fa-graduation-cap text-3xl text-odpm-green mb-3"></i>
              <h3 class="text-lg font-bold text-gray-900 mb-2">Education</h3>
              <p class="text-gray-700 text-sm"><?php echo nl2br(e(get_content('founder_education', 'BSc Biotechnology<br>Federal University Dutse'))); ?></p>
            </div>
            <div class="text-center bg-gradient-to-r from-odpm-mint to-white p-4 rounded-xl shadow-lg">
              <i class="fas fa-award text-3xl text-odpm-green mb-3"></i>
              <h3 class="text-lg font-bold text-gray-900 mb-2">Experience</h3>
              <p class="text-gray-700 text-sm"><?php echo nl2br(e(get_content('founder_experience', '10+ Years<br>Leadership & Activism'))); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Vision & Mission Section -->
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="inline-block bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold mb-4">Founder's Vision</span>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">The Vision That Started It All</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="text-center mb-6">
            <i class="fas fa-eye text-4xl text-odpm-green mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Founder's Vision</h3>
          </div>
          <p class="text-gray-700 leading-relaxed text-center">
            "<?php echo e(get_content('founder_vision', 'I envision a Nigeria where every young person has the opportunity to contribute meaningfully to society.')); ?>"
          </p>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="text-center mb-6">
            <i class="fas fa-bullseye text-4xl text-odpm-green mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Founder's Mission</h3>
          </div>
          <p class="text-gray-700 leading-relaxed text-center">
            "<?php echo e(get_content('founder_mission', 'To create a platform where young Nigerians can unite, collaborate, and drive sustainable development.')); ?>"
          </p>
        </div>
      </div>

      <div class="bg-gradient-to-r from-odpm-green to-green-700 rounded-2xl p-8 text-white text-center">
        <i class="fas fa-quote-left text-3xl mb-4"></i>
        <blockquote class="text-xl md:text-2xl font-medium italic mb-4">
          <?php echo e(get_content('founder_quote', 'The future of Nigeria lies in the hands of our youth.')); ?>
        </blockquote>
        <p class="text-lg opacity-90">- <?php echo e(get_content('founder_name', 'Umar Bello Galadima')); ?></p>
      </div>
    </div>
  </section>

  <?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
  <script>
    document.getElementById('mobile-menu-button')?.addEventListener('click', function(){
      document.getElementById('mobile-menu').classList.toggle('hidden');
    });
  </script>
</body>
</html>

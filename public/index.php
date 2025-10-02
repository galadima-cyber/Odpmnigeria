<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/header.php';

// Fetch latest news with images for slideshow
$news = [];
try {
  $stmt = db()->query("SELECT id, title, slug, image_path, excerpt FROM news WHERE published_at IS NOT NULL ORDER BY published_at DESC LIMIT 6");
  $news = $stmt->fetchAll();
} catch (Throwable $e) { $news = []; }
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
  <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('/ODPM/images/meet-members3.jpg')"></div>
  <div class="absolute inset-0 bg-odpm-green bg-opacity-40"></div>
  
  <div class="absolute top-20 left-20 w-20 h-20 bg-odpm-light bg-opacity-30 rounded-full animate-bounce"></div>
  <div class="absolute bottom-20 right-20 w-20 h-20 bg-odpm-light bg-opacity-30 rounded-full animate-bounce" style="animation-delay: 1s"></div>

  <div class="relative z-10 text-center text-white px-4 max-w-6xl mx-auto">
    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 font-serif"><?php echo e(get_content('hero_title', 'Welcome To ODPM Nigeria')); ?></h1>
    <p class="text-xl md:text-2xl mb-4 font-medium"><?php echo e(get_content('hero_subtitle', 'Organisation For Development And Political Matrix')); ?></p>
    <p class="text-lg md:text-xl mb-6 max-w-4xl mx-auto leading-relaxed">
      <?php echo e(get_content('hero_description', 'Driving positive change across Nigeria through community development, humanitarian initiatives, and political activism')); ?>
    </p>
    <p class="text-base md:text-lg mb-8 max-w-3xl mx-auto leading-relaxed">
      <?php echo e(get_content('hero_description2', 'Join us in creating a more inclusive, equitable, and empowered society where every community thrives and young voices shape the future.')); ?>
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
      <button class="bg-odpm-lemon text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-yellow-300 transition-all duration-300 transform hover:scale-105 shadow-lg">
        <i class="fas fa-heart mr-2"></i>Start Volunteering
      </button>
      <button class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-gray-900 transition-all duration-300 transform hover:scale-105">
        <i class="fas fa-info-circle mr-2"></i>Learn More
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
      <div class="text-center">
        <p class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="<?php echo (int)get_content('stat_communities', '1500'); ?>">0</p>
        <p class="text-lg md:text-xl">Community Served</p>
      </div>
      <div class="text-center">
        <p class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="<?php echo (int)get_content('stat_lives', '1000'); ?>">0</p>
        <p class="text-lg md:text-xl">Lives Impacted</p>
      </div>
      <div class="text-center">
        <p class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="<?php echo (int)get_content('stat_projects', '50'); ?>">0</p>
        <p class="text-lg md:text-xl">Active Projects</p>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div class="space-y-6">
        <div class="inline-block">
          <span class="bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold">About</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6"><?php echo e(get_content('about_title', 'ABOUT ODPMNIGERIA')); ?></h2>
        <p class="text-lg text-gray-700 leading-relaxed">
          <?php echo e(get_content('about_text1', 'ODPMNIGERIA is a dynamic consortium of young activists united by a shared commitment to driving positive change across Nigeria.')); ?>
        </p>
        <p class="text-lg text-gray-700 leading-relaxed font-medium">
          <?php echo e(get_content('about_text2', 'Our mission is to amplify the voices of the underserved, foster sustainable development, and champion meaningful progress in communities nationwide.')); ?>
        </p>
        <ul class="space-y-3">
          <li class="flex items-start">
            <i class="fas fa-check-circle text-odpm-green text-xl mr-3 mt-1"></i>
            <span class="text-gray-700">Community-driven solutions and grassroots empowerment</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check-circle text-odpm-green text-xl mr-3 mt-1"></i>
            <span class="text-gray-700">Humanitarian assistance and emergency response</span>
          </li>
          <li class="flex items-start">
            <i class="fas fa-check-circle text-odpm-green text-xl mr-3 mt-1"></i>
            <span class="text-gray-700">Political activism and civic engagement</span>
          </li>
        </ul>
        <button class="bg-odpm-green text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold">
          <i class="fas fa-arrow-right mr-2"></i>Explore Our Programs
        </button>
      </div>
      <div class="relative">
        <img src="/ODPM/images/meet-members3.jpg" alt="ODPM members meeting" class="w-full rounded-2xl shadow-2xl" />
        <div class="absolute -bottom-6 -right-6 bg-odpm-light p-6 rounded-xl shadow-lg">
          <p class="text-3xl font-bold text-odpm-green">5+</p>
          <p class="text-gray-700 font-semibold">Years of impact</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Vision Section -->
<section id="vision" class="py-20 bg-odpm-green relative overflow-hidden" style="background-image: linear-gradient(to right, rgba(255, 255, 255, 0.15) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.15) 1px, transparent 1px); background-size: 40px 40px;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="text-center mb-16">
      <div class="inline-block mb-4">
        <span class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-full text-sm font-semibold">Our Vision</span>
      </div>
      <h2 class="text-3xl md:text-4xl font-bold text-white mb-8"><?php echo e(get_content('vision_title', 'Envisioning a Better Nigeria')); ?></h2>
    </div>

    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8 mb-16 max-w-4xl mx-auto">
      <div class="text-center">
        <i class="fas fa-eye-low-vision text-5xl text-white mb-6"></i>
        <p class="text-lg md:text-xl text-white leading-relaxed">
          <?php echo e(get_content('vision_text1', 'We envision a Nigeria where every community is vibrant, self-sufficient, and inclusive—where young people are active participants in shaping their present and future.')); ?>
        </p>
        <p class="text-lg md:text-xl text-white leading-relaxed mt-4 font-medium">
          <?php echo e(get_content('vision_text2', 'By combining community-driven solutions with impactful activism, we aim to create lasting change and contribute to national development.')); ?>
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
      <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 text-center hover:bg-opacity-20 transition-all duration-300 transform hover:-translate-y-2">
        <i class="fas fa-people-roof text-4xl text-white mb-4"></i>
        <h3 class="text-xl font-bold text-white mb-3">Inclusive Communities</h3>
        <p class="text-white">Building communities where everyone belongs and has equal opportunities to thrive.</p>
      </div>
      <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 text-center hover:bg-opacity-20 transition-all duration-300 transform hover:-translate-y-2">
        <i class="fas fa-leaf text-4xl text-white mb-4"></i>
        <h3 class="text-xl font-bold text-white mb-3">Sustainable Development</h3>
        <p class="text-white">Promoting environmentally conscious and economically viable development solutions.</p>
      </div>
      <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 text-center hover:bg-opacity-20 transition-all duration-300 transform hover:-translate-y-2">
        <i class="fas fa-handshake-simple text-4xl text-white mb-4"></i>
        <h3 class="text-xl font-bold text-white mb-3">Collaborative Action</h3>
        <p class="text-white">Fostering partnerships and collective action for meaningful social change.</p>
      </div>
    </div>

    <div class="text-center">
      <button class="bg-white text-odpm-green px-8 py-4 rounded-lg hover:bg-gray-100 transition-colors duration-300 font-semibold text-lg">
        <i class="fas fa-handshake-angle mr-2"></i>Join Our Mission
      </button>
    </div>
  </div>
</section>

<!-- Programs Section -->
<section id="programs" class="py-20 bg-white relative">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <div class="inline-block mb-4">
        <span class="bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold">Our Programs</span>
      </div>
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Creating Impact Through Action</h2>
      <p class="text-lg text-gray-600 max-w-3xl mx-auto">
        Our comprehensive programs address the most pressing challenges facing Nigerian communities through targeted initiatives and collaborative partnerships.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
      <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-4 overflow-hidden">
        <div class="h-48 bg-cover bg-center" style="background-image: url('/ODPM/images/Anualmeet.jpg')"></div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Community Development</h3>
          <p class="text-gray-600 mb-4 leading-relaxed">
            We believe in grassroots empowerment, working closely with local communities to identify challenges and implement solutions tailored to their specific needs.
          </p>
          <ul class="space-y-2 mb-6">
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Education & Skills Training</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Environmental Sustainability</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Infrastructure Development</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-4 overflow-hidden">
        <div class="h-48 bg-cover bg-center" style="background-image: url('/ODPM/images/prog-general.jpg')"></div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Humanitarian Aid</h3>
          <p class="text-gray-600 mb-4 leading-relaxed">
            Providing essential support during crises and emergencies, ensuring vulnerable communities receive the help they need when they need it most.
          </p>
          <ul class="space-y-2 mb-6">
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Emergency Response</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Food Security Programs</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Healthcare Access</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-4 overflow-hidden">
        <div class="h-48 bg-cover bg-center" style="background-image: url('/ODPM/images/members.jpg')"></div>
        <div class="p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Political Activism</h3>
          <p class="text-gray-600 mb-4 leading-relaxed">
            Engaging in civic participation and advocacy to ensure young voices are heard in political processes and policy-making decisions.
          </p>
          <ul class="space-y-2 mb-6">
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Youth Advocacy</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Policy Engagement</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-odpm-green mr-3"></i>
              <span class="text-gray-700">Civic Education</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="text-center">
      <button class="bg-odpm-green text-white px-8 py-4 rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold text-lg">
        Get Involved Today
      </button>
    </div>
  </div>
</section>

<!-- Our Approach Section -->
<section class="py-20 bg-odpm-light relative" style="background-image: linear-gradient(to right, rgba(131, 130, 130, 0.15) 1px, transparent 1px), linear-gradient(to bottom, rgba(131, 130, 130, 0.15) 1px, transparent 1px); background-size: 40px 40px;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div class="order-2 lg:order-1">
        <img src="/ODPM/images/members2.jpg" alt="ODPM team members" class="w-full h-[500px] object-cover rounded-2xl shadow-2xl" />
        <div class="mt-8 text-center">
          <button class="bg-odpm-green text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors duration-300 font-semibold">
            Join Our Team
          </button>
        </div>
      </div>
      <div class="order-1 lg:order-2 space-y-6">
        <div class="inline-block">
          <span class="bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold">Our Approach</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6"><?php echo e(get_content('approach_title', 'Collaborative Innovation for Lasting Change')); ?></h2>
        <p class="text-lg text-gray-700 leading-relaxed">
          <?php echo e(get_content('approach_description', 'At ODPMNIGERIA, we leverage the power of collaboration, innovative thinking, and community-driven initiatives.')); ?>
        </p>

        <div class="space-y-6">
          <div class="flex items-start space-x-4">
            <i class="fas fa-handshake-simple text-3xl text-odpm-green mt-2"></i>
            <div>
              <h3 class="text-xl font-bold text-gray-900 mb-2">Strategic Partnerships</h3>
              <p class="text-gray-700">Building alliances with organizations, agencies, and communities to maximize impact and reach.</p>
            </div>
          </div>
          <div class="flex items-start space-x-4">
            <i class="fas fa-lightbulb text-3xl text-yellow-500 mt-2"></i>
            <div>
              <h3 class="text-xl font-bold text-gray-900 mb-2">Innovative Solutions</h3>
              <p class="text-gray-700">Developing creative and effective approaches to address complex social challenges.</p>
            </div>
          </div>
          <div class="flex items-start space-x-4">
            <i class="fas fa-people-roof text-3xl text-green-500 mt-2"></i>
            <div>
              <h3 class="text-xl font-bold text-gray-900 mb-2">Community-Centered</h3>
              <p class="text-gray-700">Ensuring all initiatives are rooted in community needs and driven by local participation.</p>
            </div>
          </div>
        </div>

        <div class="flex gap-8 mt-8">
          <div class="text-center bg-white p-4 rounded-xl shadow-lg">
            <p class="text-2xl font-bold text-odpm-green"><?php echo (int)get_content('approach_partnerships', '15'); ?>+</p>
            <p class="text-gray-700 font-semibold">Active Partnerships</p>
          </div>
          <div class="text-center bg-white p-4 rounded-xl shadow-lg">
            <p class="text-2xl font-bold text-odpm-green"><?php echo (int)get_content('approach_members', '200'); ?>+</p>
            <p class="text-gray-700 font-semibold">Members</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Get Involved Section -->
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <div class="inline-block mb-4">
        <span class="bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold">Get Involved</span>
      </div>
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6"><?php echo e(get_content('involved_title', 'Join Our Mission for Change')); ?></h2>
      <p class="text-lg text-gray-600 max-w-3xl mx-auto">
        <?php echo e(get_content('involved_description', 'We believe that change begins with individuals who are willing to act.')); ?>
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div class="space-y-6">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Ways To Get Involved</h3>

        <div class="bg-gradient-to-r from-odpm-mint to-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <h4 class="text-xl font-bold text-gray-900 mb-3">Volunteer</h4>
          <p class="text-gray-700">Join our team of passionate volunteers and contribute your skills to meaningful projects that create lasting impact in communities across Nigeria.</p>
        </div>

        <div class="bg-gradient-to-r from-odpm-mint to-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <h4 class="text-xl font-bold text-gray-900 mb-3">Partner</h4>
          <p class="text-gray-700">Collaborate with us on projects and initiatives that align with your organization's mission and values.</p>
        </div>

        <div class="bg-gradient-to-r from-odpm-mint to-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <h4 class="text-xl font-bold text-gray-900 mb-3">Donate</h4>
          <p class="text-gray-700">Support our programs and initiatives through financial contributions that help us reach more communities.</p>
        </div>

        <div class="bg-gray-900 text-white p-6 rounded-xl">
          <h4 class="text-xl font-bold mb-4">Contact Information</h4>
          <ul class="space-y-2">
            <li class="flex items-center">
              <i class="fas fa-envelope mr-3"></i>
              <span>info@odpmnigeria.org</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-phone mr-3"></i>
              <span>+2347030895429</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-map-marker-alt mr-3"></i>
              <span>Kano, Nigeria</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="bg-gradient-to-br from-odpm-mint to-white p-8 rounded-2xl shadow-lg">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Get In Touch</h3>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'contact_sent'): ?>
          <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded">
            <i class="fas fa-check-circle mr-2"></i>Thank you! We'll get back to you soon.
          </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
          <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded">
            <i class="fas fa-exclamation-circle mr-2"></i>Something went wrong. Please try again.
          </div>
        <?php endif; ?>
        
        <form class="space-y-6" action="/ODPM/public/contact-submit.php" method="POST">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
              <input type="text" name="fname" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-odpm-green focus:border-transparent transition-colors duration-200" />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
              <input type="text" name="lname" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-odpm-green focus:border-transparent transition-colors duration-200" />
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-odpm-green focus:border-transparent transition-colors duration-200" />
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">I'm interested in</label>
            <select name="interest" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-odpm-green focus:border-transparent transition-colors duration-200">
              <option value="">Select an option</option>
              <option value="volunteer">Volunteering</option>
              <option value="partner">Partnership</option>
              <option value="donate">Donation</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
            <textarea name="message" rows="4" placeholder="Tell us more..." required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-odpm-green focus:border-transparent transition-colors duration-200"></textarea>
          </div>
          
          <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-lg hover:bg-gray-800 transition-colors duration-300 font-semibold text-lg">
            Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Latest News Section -->
<?php if ($news): ?>
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 text-center">
      <span class="inline-block bg-odpm-mint text-white px-4 py-2 rounded-full text-sm font-semibold mb-4">Latest Updates</span>
      <h2 class="text-3xl font-bold">Latest News</h2>
      <p class="text-gray-600">Recent updates and stories</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php foreach ($news as $n): ?>
        <a href="/ODPM/public/news-single.php?slug=<?php echo e($n['slug']); ?>" class="group block rounded-2xl overflow-hidden shadow hover:shadow-lg transition-all">
          <div class="h-56 bg-center bg-cover bg-gray-200" style="background-image:url('<?php echo e(($config['site']['upload_base_url'] . '/' . $n['image_path'])); ?>')"></div>
          <div class="p-5 bg-white">
            <h3 class="text-xl font-semibold group-hover:text-odpm-green transition-colors line-clamp-2"><?php echo e($n['title']); ?></h3>
            <p class="text-gray-600 mt-2 line-clamp-3"><?php echo e($n['excerpt'] ?? ''); ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<script>
// Counter Animation
function animateCounter(el, target) {
  let current = 0;
  const duration = 2000;
  const increment = target / (duration / 16);
  const update = () => {
    current += increment;
    if (current >= target) {
      el.textContent = target.toLocaleString() + "+";
    } else {
      el.textContent = Math.floor(current).toLocaleString();
      requestAnimationFrame(update);
    }
  };
  update();
}

const counters = document.querySelectorAll(".counter");
const counterObserver = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const el = entry.target;
      const target = parseInt(el.getAttribute("data-target"));
      animateCounter(el, target);
      observer.unobserve(el);
    }
  });
}, { threshold: 0.5 });

counters.forEach(c => counterObserver.observe(c));
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

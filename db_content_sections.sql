-- Enhanced Database Schema for Editable Content Sections
-- This allows admins to edit EVERY section of the website

-- Drop existing tables if recreating
-- DROP TABLE IF EXISTS content_sections;
-- DROP TABLE IF EXISTS executives;

-- Content Sections Table (for all editable text/content on the site)
CREATE TABLE IF NOT EXISTS content_sections (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  section_key VARCHAR(100) NOT NULL UNIQUE COMMENT 'Unique identifier like hero_title, about_text',
  section_name VARCHAR(200) NOT NULL COMMENT 'Human-readable name for admin',
  content_type ENUM('text','textarea','html','number','image') NOT NULL DEFAULT 'textarea',
  content_value TEXT NULL,
  page VARCHAR(50) NOT NULL COMMENT 'Which page: home, about, founder, executives',
  section_group VARCHAR(50) NULL COMMENT 'Group sections together like hero, stats, vision',
  display_order INT DEFAULT 0,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (page),
  INDEX (section_group)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Executives Table (for executive board members)
CREATE TABLE IF NOT EXISTS executives (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  position VARCHAR(200) NOT NULL,
  image_path VARCHAR(255) NULL,
  experience TEXT NULL,
  education TEXT NULL,
  about TEXT NULL,
  quote TEXT NULL,
  linkedin VARCHAR(255) NULL,
  twitter VARCHAR(255) NULL,
  email VARCHAR(190) NULL,
  display_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Homepage Content Sections
INSERT INTO content_sections (section_key, section_name, content_type, content_value, page, section_group, display_order) VALUES
-- Hero Section
('hero_title', 'Hero Title', 'text', 'Welcome To ODPM Nigeria', 'home', 'hero', 1),
('hero_subtitle', 'Hero Subtitle', 'text', 'Organisation For Development And Political Matrix', 'home', 'hero', 2),
('hero_description', 'Hero Description', 'textarea', 'Driving positive change across Nigeria through community development, humanitarian initiatives, and political activism', 'home', 'hero', 3),
('hero_description2', 'Hero Description 2', 'textarea', 'Join us in creating a more inclusive, equitable, and empowered society where every community thrives and young voices shape the future.', 'home', 'hero', 4),

-- Stats Section
('stat_communities', 'Communities Served Count', 'number', '1500', 'home', 'stats', 1),
('stat_lives', 'Lives Impacted Count', 'number', '1000', 'home', 'stats', 2),
('stat_projects', 'Active Projects Count', 'number', '50', 'home', 'stats', 3),

-- About Section
('about_title', 'About Section Title', 'text', 'ABOUT ODPMNIGERIA', 'home', 'about', 1),
('about_text1', 'About Paragraph 1', 'textarea', 'ODPMNIGERIA is a dynamic consortium of young activists united by a shared commitment to driving positive change across Nigeria. We focus on community development, humanitarian initiatives, and political activism to create a more inclusive, equitable, and empowered society.', 'home', 'about', 2),
('about_text2', 'About Paragraph 2', 'textarea', 'Our mission is to amplify the voices of the underserved, foster sustainable development, and champion meaningful progress in communities nationwide.', 'home', 'about', 3),

-- Vision Section
('vision_title', 'Vision Section Title', 'text', 'Envisioning a Better Nigeria', 'home', 'vision', 1),
('vision_text1', 'Vision Text 1', 'textarea', 'We envision a Nigeria where every community is vibrant, self-sufficient, and inclusive—where young people are active participants in shaping their present and future.', 'home', 'vision', 2),
('vision_text2', 'Vision Text 2', 'textarea', 'By combining community-driven solutions with impactful activism, we aim to create lasting change and contribute to national development.', 'home', 'vision', 3),

-- Founder Page Content
('founder_name', 'Founder Name', 'text', 'Umar Bello Galadima', 'founder', 'profile', 1),
('founder_title', 'Founder Title', 'text', 'Founder & President', 'founder', 'profile', 2),
('founder_bio1', 'Founder Biography Paragraph 1', 'textarea', 'Umar Bello Galadima is the visionary founder and president of ODPM Nigeria. With over 10 years of experience in political activism, community development, and social work, Umar has dedicated his life to empowering young Nigerians and creating positive change across the country.', 'founder', 'profile', 3),
('founder_bio2', 'Founder Biography Paragraph 2', 'textarea', 'His journey began as a young activist in Kano State, where he witnessed firsthand the challenges facing Nigerian communities. This experience ignited his passion for social change and led to the establishment of ODPM Nigeria in 2015.', 'founder', 'profile', 4),
('founder_education', 'Founder Education', 'text', 'BSc Biotechnology, Federal University Dutse', 'founder', 'profile', 5),
('founder_experience', 'Founder Experience', 'text', '10+ Years Leadership & Activism', 'founder', 'profile', 6),
('founder_vision', 'Founder Vision Statement', 'textarea', 'I envision a Nigeria where every young person has the opportunity to contribute meaningfully to society, where communities are self-sufficient and inclusive, and where political participation leads to positive change for all citizens.', 'founder', 'vision', 1),
('founder_mission', 'Founder Mission Statement', 'textarea', 'To create a platform where young Nigerians can unite, collaborate, and drive sustainable development through community engagement, humanitarian service, and active political participation.', 'founder', 'mission', 1),
('founder_quote', 'Founder Quote', 'textarea', 'The future of Nigeria lies in the hands of our youth. We must empower them to be agents of positive change, not just beneficiaries of development, but active participants in shaping our nation''s destiny.', 'founder', 'quote', 1),

-- Homepage - Our Approach Section
('approach_title', 'Our Approach Title', 'text', 'Collaborative Innovation for Lasting Change', 'home', 'approach', 1),
('approach_description', 'Our Approach Description', 'textarea', 'At ODPMNIGERIA, we leverage the power of collaboration, innovative thinking, and community-driven initiatives. We partner with local and international organizations, government agencies, and fellow activists to amplify our impact.', 'home', 'approach', 2),
('approach_partnerships', 'Active Partnerships Count', 'number', '15', 'home', 'approach', 3),
('approach_members', 'Members Count', 'number', '200', 'home', 'approach', 4),

-- Homepage - Get Involved Section
('involved_title', 'Get Involved Title', 'text', 'Join Our Mission for Change', 'home', 'involved', 1),
('involved_description', 'Get Involved Description', 'textarea', 'We believe that change begins with individuals who are willing to act. Whether you are looking to volunteer, partner, or contribute to our programs, there is a place for you in our mission.', 'home', 'involved', 2),

-- Founder - Achievements Section
('founder_achievement_title', 'Achievements Section Title', 'text', 'Impact & Recognition', 'founder', 'achievements', 1),
('founder_achievement_desc', 'Achievements Description', 'textarea', 'Under Umar''s leadership, ODPM Nigeria has achieved remarkable milestones and received recognition for its impactful work across Nigeria.', 'founder', 'achievements', 2)

ON DUPLICATE KEY UPDATE content_value=VALUES(content_value);

-- Seed Sample Executives
INSERT INTO executives (name, position, image_path, experience, education, about, quote, display_order) VALUES
('Umar Bello Galadima', 'President & Founder', 'founder.jpg', '10+ Years in Leadership & Activism', 'BSc Biotechnology, Federal University Dutse', 'Visionary leader dedicated to empowering Nigerian youth through community development and political activism.', 'The future of Nigeria lies in the hands of our youth.', 1),
('Fatima Abdullahi', 'Vice President', 'student-girls.jpg', '5+ Years in Community Development', 'MSc Public Administration', 'Passionate advocate for women empowerment and humanitarian initiatives across Nigeria.', 'Empowering women means empowering communities.', 2),
('Musa Garba', 'Secretary General', 'members.jpg', '7+ Years in Organizational Management', 'BSc Political Science', 'Ensuring transparency and effective operations in all ODPM initiatives.', 'Transparency builds trust and drives change.', 3)

ON DUPLICATE KEY UPDATE name=VALUES(name);

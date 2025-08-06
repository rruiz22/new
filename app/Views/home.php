<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title data-translate="page-title">My Detail Area - Auto Dealership Management Platform</title>
  <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #007AFF;
      --primary-dark: #0056CC;
      --secondary-color: #f5f5f7;
      --accent-color: #30D158;
      --text-primary: #1d1d1f;
      --text-secondary: #86868b;
      --text-light: #a1a1a6;
      --white: #ffffff;
      --gray-50: #f5f5f7;
      --gray-100: #f2f2f7;
      --gray-200: #e5e5ea;
      --gray-800: #1d1d1f;
      --gray-900: #000000;
      --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
      --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
      --shadow-lg: 0 8px 25px rgba(0,0,0,0.15);
      --shadow-xl: 0 20px 40px rgba(0,0,0,0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
      font-size: 17px;
    }

    body {
      font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.47059;
      color: var(--text-primary);
      background: var(--white);
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    /* Navigation */
    .nav-wrapper {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: saturate(180%) blur(20px);
      border-bottom: 1px solid rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .nav-wrapper.scrolled {
      background: rgba(255, 255, 255, 0.95);
    }

    nav {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 44px;
    }

    .logo {
      font-size: 21px;
      font-weight: 600;
      color: var(--text-primary);
      text-decoration: none;
      letter-spacing: -0.022em;
    }

    .nav-links {
      display: flex;
      list-style: none;
      gap: 32px;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--text-primary);
      font-size: 12px;
      font-weight: 400;
      letter-spacing: -0.01em;
      transition: opacity 0.3s ease;
    }

    .nav-links a:hover {
      opacity: 0.8;
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .language-selector {
      position: relative;
    }

    .language-btn {
      background: none;
      border: none;
      color: var(--text-primary);
      font-size: 12px;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: 4px;
      transition: background 0.3s ease;
    }

    .language-btn:hover {
      background: var(--gray-100);
    }

    .language-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 12px;
      box-shadow: var(--shadow-lg);
      padding: 8px 0;
      min-width: 120px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-8px);
      transition: all 0.3s ease;
      margin-top: 8px;
    }

    .language-dropdown.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .language-option {
      display: block;
      width: 100%;
      padding: 8px 16px;
      background: none;
      border: none;
      text-align: left;
      cursor: pointer;
      font-size: 12px;
      transition: background 0.2s ease;
    }

    .language-option:hover {
      background: var(--gray-50);
    }

    .cta-button {
      background: var(--primary-color);
      color: var(--white);
      padding: 8px 16px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 12px;
      font-weight: 400;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .cta-button:hover {
      background: var(--primary-dark);
      transform: scale(1.02);
    }

    /* Hero Section */
    .hero {
      background: var(--white);
      padding: 88px 0 0;
      text-align: center;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }

    .hero-content {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
    }

    .hero h1 {
      font-size: 56px;
      font-weight: 600;
      line-height: 1.07143;
      letter-spacing: -0.005em;
      margin-bottom: 6px;
      color: var(--text-primary);
    }

    .hero .tagline {
      font-size: 28px;
      font-weight: 400;
      line-height: 1.14286;
      letter-spacing: 0.007em;
      color: var(--text-secondary);
      margin-bottom: 19px;
    }

    .hero .subtitle {
      font-size: 19px;
      font-weight: 400;
      line-height: 1.42105;
      letter-spacing: 0.012em;
      color: var(--text-secondary);
      margin-bottom: 30px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      margin-bottom: 60px;
      flex-wrap: wrap;
    }

    .btn-primary {
      background: var(--primary-color);
      color: var(--white);
      padding: 12px 23px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 17px;
      font-weight: 400;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      min-width: 120px;
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: scale(1.02);
    }

    .btn-secondary {
      background: transparent;
      color: var(--primary-color);
      border: 1px solid var(--primary-color);
      padding: 12px 23px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 17px;
      font-weight: 400;
      transition: all 0.3s ease;
      cursor: pointer;
      min-width: 120px;
    }

    .btn-secondary:hover {
      background: var(--primary-color);
      color: var(--white);
      transform: scale(1.02);
    }

    .hero-image {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: var(--shadow-xl);
    }

    .hero-image img {
      width: 100%;
      height: auto;
      display: block;
    }

    /* Product Showcase */
    .product-showcase {
      background: var(--white);
      padding: 100px 0;
    }

    .showcase-container {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
    }

    .showcase-header {
      text-align: center;
      margin-bottom: 80px;
    }

    .showcase-header h2 {
      font-size: 48px;
      font-weight: 600;
      line-height: 1.08349;
      letter-spacing: -0.003em;
      margin-bottom: 6px;
      color: var(--text-primary);
    }

    .showcase-header .tagline {
      font-size: 24px;
      font-weight: 400;
      line-height: 1.16667;
      letter-spacing: 0.009em;
      color: var(--text-secondary);
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      margin-bottom: 80px;
    }

    .product-card {
      background: var(--white);
      border-radius: 18px;
      overflow: hidden;
      transition: all 0.4s ease;
      cursor: pointer;
    }

    .product-card:hover {
      transform: scale(1.02);
      box-shadow: var(--shadow-lg);
    }

    .product-image {
      width: 100%;
      height: 240px;
      background: var(--gray-50);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .product-info {
      padding: 30px 20px;
      text-align: center;
    }

    .product-info h3 {
      font-size: 24px;
      font-weight: 600;
      line-height: 1.16667;
      letter-spacing: 0.009em;
      margin-bottom: 8px;
      color: var(--text-primary);
    }

    .product-info p {
      font-size: 17px;
      font-weight: 400;
      line-height: 1.47059;
      letter-spacing: -0.022em;
      color: var(--text-secondary);
    }

    /* Feature Sections */
    .feature-section {
      padding: 100px 0;
      position: relative;
    }

    .feature-section:nth-child(even) {
      background: var(--gray-50);
    }

    .feature-container {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      align-items: center;
    }

    .feature-content h2 {
      font-size: 48px;
      font-weight: 600;
      line-height: 1.08349;
      letter-spacing: -0.003em;
      margin-bottom: 20px;
      color: var(--text-primary);
    }

    .feature-content .tagline {
      font-size: 24px;
      font-weight: 400;
      line-height: 1.16667;
      letter-spacing: 0.009em;
      color: var(--text-secondary);
      margin-bottom: 30px;
    }

    .feature-content p {
      font-size: 19px;
      font-weight: 400;
      line-height: 1.42105;
      letter-spacing: 0.012em;
      color: var(--text-secondary);
      margin-bottom: 30px;
    }

    .feature-list {
      list-style: none;
      margin-bottom: 40px;
    }

    .feature-list li {
      font-size: 17px;
      font-weight: 400;
      line-height: 1.47059;
      letter-spacing: -0.022em;
      color: var(--text-secondary);
      margin-bottom: 12px;
      padding-left: 20px;
      position: relative;
    }

    .feature-list li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: var(--accent-color);
      font-weight: 600;
    }

    .feature-image {
      border-radius: 18px;
      overflow: hidden;
      box-shadow: var(--shadow-lg);
    }

    .feature-image img {
      width: 100%;
      height: auto;
      display: block;
    }

    /* Stats Section */
    .stats-section {
      background: var(--gray-900);
      color: var(--white);
      padding: 100px 0;
      text-align: center;
    }

    .stats-container {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
    }

    .stats-header h2 {
      font-size: 48px;
      font-weight: 600;
      line-height: 1.08349;
      letter-spacing: -0.003em;
      margin-bottom: 20px;
      color: var(--white);
    }

    .stats-header p {
      font-size: 19px;
      font-weight: 400;
      line-height: 1.42105;
      letter-spacing: 0.012em;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 60px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 60px;
    }

    .stat-item {
      text-align: center;
    }

    .stat-number {
      font-size: 64px;
      font-weight: 600;
      line-height: 1.0625;
      letter-spacing: -0.009em;
      color: var(--white);
      display: block;
      margin-bottom: 8px;
    }

    .stat-label {
      font-size: 17px;
      font-weight: 400;
      line-height: 1.47059;
      letter-spacing: -0.022em;
      color: rgba(255, 255, 255, 0.8);
    }

    /* Testimonials */
    .testimonials-section {
      background: var(--white);
      padding: 100px 0;
    }

    .testimonials-container {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
      text-align: center;
    }

    .testimonials-header h2 {
      font-size: 48px;
      font-weight: 600;
      line-height: 1.08349;
      letter-spacing: -0.003em;
      margin-bottom: 60px;
      color: var(--text-primary);
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
    }

    .testimonial-card {
      background: var(--gray-50);
      padding: 40px 30px;
      border-radius: 18px;
      text-align: left;
    }

    .testimonial-quote {
      font-size: 19px;
      font-weight: 400;
      line-height: 1.42105;
      letter-spacing: 0.012em;
      color: var(--text-primary);
      margin-bottom: 20px;
      font-style: italic;
    }

    .testimonial-author {
      font-size: 17px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .testimonial-company {
      font-size: 15px;
      font-weight: 400;
      color: var(--text-secondary);
    }

    /* CTA Section */
    .cta-section {
      background: var(--gray-50);
      padding: 100px 0;
      text-align: center;
    }

    .cta-container {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
    }

    .cta-content h2 {
      font-size: 48px;
      font-weight: 600;
      line-height: 1.08349;
      letter-spacing: -0.003em;
      margin-bottom: 20px;
      color: var(--text-primary);
    }

    .cta-content p {
      font-size: 19px;
      font-weight: 400;
      line-height: 1.42105;
      letter-spacing: 0.012em;
      color: var(--text-secondary);
      margin-bottom: 40px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Footer */
    footer {
      background: var(--gray-50);
      padding: 60px 0 40px;
      border-top: 1px solid var(--gray-200);
    }

    .footer-container {
      max-width: 1024px;
      margin: 0 auto;
      padding: 0 22px;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-column h3 {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 16px;
      letter-spacing: -0.01em;
    }

    .footer-links {
      list-style: none;
    }

    .footer-links li {
      margin-bottom: 8px;
    }

    .footer-links a {
      color: var(--text-secondary);
      text-decoration: none;
      font-size: 12px;
      font-weight: 400;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--text-primary);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid var(--gray-200);
    }

    .footer-bottom p {
      font-size: 12px;
      color: var(--text-secondary);
    }

    /* Animations */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }

    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .scale-in {
      opacity: 0;
      transform: scale(0.95);
      transition: all 0.8s ease;
    }

    .scale-in.visible {
      opacity: 1;
      transform: scale(1);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
      .feature-container {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
      }

      .feature-container:nth-child(even) .feature-content {
        order: 2;
      }

      .feature-container:nth-child(even) .feature-image {
        order: 1;
      }
    }

    @media (max-width: 768px) {
      html {
        font-size: 16px;
      }

      nav {
        padding: 0 16px;
      }

      .nav-links {
        display: none;
      }

      .hero h1 {
        font-size: 40px;
      }

      .hero .tagline {
        font-size: 21px;
      }

      .showcase-header h2,
      .feature-content h2,
      .stats-header h2,
      .testimonials-header h2,
      .cta-content h2 {
        font-size: 32px;
      }

      .hero-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn-primary,
      .btn-secondary {
        width: 200px;
      }

      .product-grid {
        grid-template-columns: 1fr;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
      }

      .testimonials-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .showcase-container,
      .feature-container,
      .stats-container,
      .testimonials-container,
      .cta-container,
      .footer-container {
        padding: 0 16px;
      }

      .hero-content {
        padding: 0 16px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<!-- Navigation -->
<div class="nav-wrapper" id="navbar">
  <nav>
    <a href="#" class="logo" data-translate="nav-logo">My Detail Area</a>
    <ul class="nav-links">
      <li><a href="#features" data-translate="nav-features">Features</a></li>
      <li><a href="#solutions" data-translate="nav-solutions">Solutions</a></li>
      <li><a href="#testimonials" data-translate="nav-testimonials">Testimonials</a></li>
      <li><a href="#contact" data-translate="nav-contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <div class="language-selector">
        <button class="language-btn" id="languageBtn">EN</button>
        <div class="language-dropdown" id="languageDropdown">
          <button class="language-option" data-lang="en">English</button>
          <button class="language-option" data-lang="es">Español</button>
          <button class="language-option" data-lang="pt">Português</button>
        </div>
      </div>
      <a href="<?= base_url('login') ?>" class="cta-button" data-translate="nav-login">Sign In</a>
    </div>
  </nav>
</div>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1 data-translate="hero-title">My Detail Area</h1>
    <p class="tagline" data-translate="hero-tagline">Designed to be powerful.</p>
    <p class="subtitle" data-translate="hero-subtitle">The most advanced auto dealership management platform. Built for professionals who demand excellence in every detail.</p>
    <div class="hero-buttons">
      <a href="#contact" class="btn-primary" data-translate="hero-cta">Request Demo</a>
      <a href="#features" class="btn-secondary" data-translate="hero-learn-more">Learn More</a>
    </div>
    <div class="hero-image">
      <img src="/placeholder.svg?height=600&width=800&text=Dashboard+Interface" alt="My Detail Area Dashboard">
    </div>
  </div>
</section>

<!-- Product Showcase -->
<section class="product-showcase">
  <div class="showcase-container">
    <div class="showcase-header fade-in">
      <h2 data-translate="showcase-title">Three powerful solutions.</h2>
      <p class="tagline" data-translate="showcase-tagline">One seamless experience.</p>
    </div>
    <div class="product-grid">
      <div class="product-card scale-in">
        <div class="product-image">
          <img src="/placeholder.svg?height=240&width=300&text=Order+Management" alt="Order Management">
        </div>
        <div class="product-info">
          <h3 data-translate="product1-title">Order Management</h3>
          <p data-translate="product1-description">Streamline your workflow with intelligent order tracking and automated notifications.</p>
        </div>
      </div>
      <div class="product-card scale-in">
        <div class="product-image">
          <img src="/placeholder.svg?height=240&width=300&text=Team+Collaboration" alt="Team Collaboration">
        </div>
        <div class="product-info">
          <h3 data-translate="product2-title">Team Collaboration</h3>
          <p data-translate="product2-description">Connect your team with role-based permissions and real-time communication tools.</p>
        </div>
      </div>
      <div class="product-card scale-in">
        <div class="product-image">
          <img src="/placeholder.svg?height=240&width=300&text=Customer+Experience" alt="Customer Experience">
        </div>
        <div class="product-info">
          <h3 data-translate="product3-title">Customer Experience</h3>
          <p data-translate="product3-description">Deliver exceptional service with integrated SMS communication and status updates.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Feature Section 1 -->
<section id="features" class="feature-section">
  <div class="feature-container">
    <div class="feature-content fade-in">
      <h2 data-translate="feature1-title">Advanced Order Management</h2>
      <p class="tagline" data-translate="feature1-tagline">Every detail, perfectly organized.</p>
      <p data-translate="feature1-description">Transform your dealership operations with our intelligent order management system. Track every service from start to finish with unprecedented clarity and control.</p>
      <ul class="feature-list">
        <li data-translate="feature1-point1">Real-time order tracking and status updates</li>
        <li data-translate="feature1-point2">Automated workflow management</li>
        <li data-translate="feature1-point3">Complete service history and documentation</li>
        <li data-translate="feature1-point4">Intelligent task assignment and scheduling</li>
      </ul>
      <a href="#contact" class="btn-primary" data-translate="feature1-cta">Learn More</a>
    </div>
    <div class="feature-image scale-in">
      <img src="/placeholder.svg?height=500&width=600&text=Order+Management+Dashboard" alt="Order Management Dashboard">
    </div>
  </div>
</section>

<!-- Feature Section 2 -->
<section class="feature-section">
  <div class="feature-container">
    <div class="feature-image scale-in">
      <img src="/placeholder.svg?height=500&width=600&text=Team+Collaboration+Interface" alt="Team Collaboration">
    </div>
    <div class="feature-content fade-in">
      <h2 data-translate="feature2-title">Smart Team Collaboration</h2>
      <p class="tagline" data-translate="feature2-tagline">Work together, seamlessly.</p>
      <p data-translate="feature2-description">Empower your team with role-based access controls and real-time collaboration tools. From technicians to managers, everyone stays connected and informed.</p>
      <ul class="feature-list">
        <li data-translate="feature2-point1">Customizable role-based permissions</li>
        <li data-translate="feature2-point2">Real-time team communication</li>
        <li data-translate="feature2-point3">Task delegation and progress tracking</li>
        <li data-translate="feature2-point4">Performance analytics and reporting</li>
      </ul>
      <a href="#contact" class="btn-primary" data-translate="feature2-cta">Discover More</a>
    </div>
  </div>
</section>

<!-- Feature Section 3 -->
<section class="feature-section">
  <div class="feature-container">
    <div class="feature-content fade-in">
      <h2 data-translate="feature3-title">Exceptional Customer Experience</h2>
      <p class="tagline" data-translate="feature3-tagline">Communication that connects.</p>
      <p data-translate="feature3-description">Keep your customers informed and engaged with integrated SMS communication and automated updates. Build lasting relationships through transparency and exceptional service.</p>
      <ul class="feature-list">
        <li data-translate="feature3-point1">Integrated SMS communication via Twilio</li>
        <li data-translate="feature3-point2">Automated status notifications</li>
        <li data-translate="feature3-point3">Customer portal for real-time updates</li>
        <li data-translate="feature3-point4">Feedback collection and analysis</li>
      </ul>
      <a href="#contact" class="btn-primary" data-translate="feature3-cta">Get Started</a>
    </div>
    <div class="feature-image scale-in">
      <img src="/placeholder.svg?height=500&width=600&text=Customer+Communication+Interface" alt="Customer Communication">
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
  <div class="stats-container">
    <div class="stats-header fade-in">
      <h2 data-translate="stats-title">Trusted by professionals worldwide</h2>
      <p data-translate="stats-subtitle">Join thousands of dealerships that have transformed their operations with My Detail Area.</p>
    </div>
    <div class="stats-grid">
      <div class="stat-item scale-in">
        <span class="stat-number">50+</span>
        <span class="stat-label" data-translate="stat1-label">Dealerships</span>
      </div>
      <div class="stat-item scale-in">
        <span class="stat-number">10K+</span>
        <span class="stat-label" data-translate="stat2-label">Orders Processed</span>
      </div>
      <div class="stat-item scale-in">
        <span class="stat-number">99.9%</span>
        <span class="stat-label" data-translate="stat3-label">Uptime</span>
      </div>
      <div class="stat-item scale-in">
        <span class="stat-number">24/7</span>
        <span class="stat-label" data-translate="stat4-label">Support</span>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section id="testimonials" class="testimonials-section">
  <div class="testimonials-container">
    <div class="testimonials-header fade-in">
      <h2 data-translate="testimonials-title">What our customers say</h2>
    </div>
    <div class="testimonials-grid">
      <div class="testimonial-card scale-in">
        <p class="testimonial-quote" data-translate="testimonial1-quote">"My Detail Area has completely transformed how we manage our detailing operations. The efficiency gains are remarkable, and our customers love the real-time updates."</p>
        <div class="testimonial-author" data-translate="testimonial1-author">Sarah Johnson</div>
        <div class="testimonial-company" data-translate="testimonial1-company">Premium Auto Detailing</div>
      </div>
      <div class="testimonial-card scale-in">
        <p class="testimonial-quote" data-translate="testimonial2-quote">"The team collaboration features have streamlined our workflow tremendously. Everyone knows exactly what needs to be done and when. It's a game-changer."</p>
        <div class="testimonial-author" data-translate="testimonial2-author">Michael Chen</div>
        <div class="testimonial-company" data-translate="testimonial2-company">Elite Motors</div>
      </div>
      <div class="testimonial-card scale-in">
        <p class="testimonial-quote" data-translate="testimonial3-quote">"Customer satisfaction has increased significantly since we started using My Detail Area. The SMS notifications keep everyone informed and happy."</p>
        <div class="testimonial-author" data-translate="testimonial3-author">David Rodriguez</div>
        <div class="testimonial-company" data-translate="testimonial3-company">Rodriguez Auto Group</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section id="contact" class="cta-section">
  <div class="cta-container">
    <div class="cta-content fade-in">
      <h2 data-translate="cta-title">Ready to transform your dealership?</h2>
      <p data-translate="cta-subtitle">Experience the power of My Detail Area with a personalized demo. See how we can streamline your operations and delight your customers.</p>
      <div class="hero-buttons">
        <a href="<?= base_url('login') ?>" class="btn-primary" data-translate="cta-demo">Request Demo</a>
        <a href="#features" class="btn-secondary" data-translate="cta-learn">Learn More</a>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="footer-container">
    <div class="footer-content">
      <div class="footer-column">
        <h3 data-translate="footer-product">Product</h3>
        <ul class="footer-links">
          <li><a href="#features" data-translate="footer-features">Features</a></li>
          <li><a href="#solutions" data-translate="footer-solutions">Solutions</a></li>
          <li><a href="#" data-translate="footer-pricing">Pricing</a></li>
          <li><a href="#" data-translate="footer-integrations">Integrations</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3 data-translate="footer-company">Company</h3>
        <ul class="footer-links">
          <li><a href="#" data-translate="footer-about">About</a></li>
          <li><a href="#" data-translate="footer-careers">Careers</a></li>
          <li><a href="#" data-translate="footer-press">Press</a></li>
          <li><a href="#" data-translate="footer-blog">Blog</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3 data-translate="footer-resources">Resources</h3>
        <ul class="footer-links">
          <li><a href="#" data-translate="footer-documentation">Documentation</a></li>
          <li><a href="#" data-translate="footer-help">Help Center</a></li>
          <li><a href="#" data-translate="footer-community">Community</a></li>
          <li><a href="#" data-translate="footer-status">Status</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3 data-translate="footer-legal">Legal</h3>
        <ul class="footer-links">
          <li><a href="#" data-translate="footer-privacy">Privacy</a></li>
          <li><a href="#" data-translate="footer-terms">Terms</a></li>
          <li><a href="#" data-translate="footer-security">Security</a></li>
          <li><a href="#" data-translate="footer-cookies">Cookies</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p data-translate="footer-copyright">Copyright © 2024 My Detail Area Inc. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
// Translation system
const translations = {
  en: {
    'nav-logo': 'My Detail Area',
    'nav-features': 'Features',
    'nav-solutions': 'Solutions',
    'nav-testimonials': 'Testimonials',
    'nav-contact': 'Contact',
    'nav-login': 'Sign In',
    'hero-title': 'My Detail Area',
    'hero-tagline': 'Designed to be powerful.',
    'hero-subtitle': 'The most advanced auto dealership management platform. Built for professionals who demand excellence in every detail.',
    'hero-cta': 'Request Demo',
    'hero-learn-more': 'Learn More',
    'showcase-title': 'Three powerful solutions.',
    'showcase-tagline': 'One seamless experience.',
    'product1-title': 'Order Management',
    'product1-description': 'Streamline your workflow with intelligent order tracking and automated notifications.',
    'product2-title': 'Team Collaboration',
    'product2-description': 'Connect your team with role-based permissions and real-time communication tools.',
    'product3-title': 'Customer Experience',
    'product3-description': 'Deliver exceptional service with integrated SMS communication and status updates.',
    'feature1-title': 'Advanced Order Management',
    'feature1-tagline': 'Every detail, perfectly organized.',
    'feature1-description': 'Transform your dealership operations with our intelligent order management system. Track every service from start to finish with unprecedented clarity and control.',
    'feature1-point1': 'Real-time order tracking and status updates',
    'feature1-point2': 'Automated workflow management',
    'feature1-point3': 'Complete service history and documentation',
    'feature1-point4': 'Intelligent task assignment and scheduling',
    'feature1-cta': 'Learn More',
    'feature2-title': 'Smart Team Collaboration',
    'feature2-tagline': 'Work together, seamlessly.',
    'feature2-description': 'Empower your team with role-based access controls and real-time collaboration tools. From technicians to managers, everyone stays connected and informed.',
    'feature2-point1': 'Customizable role-based permissions',
    'feature2-point2': 'Real-time team communication',
    'feature2-point3': 'Task delegation and progress tracking',
    'feature2-point4': 'Performance analytics and reporting',
    'feature2-cta': 'Discover More',
    'feature3-title': 'Exceptional Customer Experience',
    'feature3-tagline': 'Communication that connects.',
    'feature3-description': 'Keep your customers informed and engaged with integrated SMS communication and automated updates. Build lasting relationships through transparency and exceptional service.',
    'feature3-point1': 'Integrated SMS communication via Twilio',
    'feature3-point2': 'Automated status notifications',
    'feature3-point3': 'Customer portal for real-time updates',
    'feature3-point4': 'Feedback collection and analysis',
    'feature3-cta': 'Get Started',
    'stats-title': 'Trusted by professionals worldwide',
    'stats-subtitle': 'Join thousands of dealerships that have transformed their operations with My Detail Area.',
    'stat1-label': 'Dealerships',
    'stat2-label': 'Orders Processed',
    'stat3-label': 'Uptime',
    'stat4-label': 'Support',
    'testimonials-title': 'What our customers say',
    'testimonial1-quote': '"My Detail Area has completely transformed how we manage our detailing operations. The efficiency gains are remarkable, and our customers love the real-time updates."',
    'testimonial1-author': 'Sarah Johnson',
    'testimonial1-company': 'Premium Auto Detailing',
    'testimonial2-quote': '"The team collaboration features have streamlined our workflow tremendously. Everyone knows exactly what needs to be done and when. It\'s a game-changer."',
    'testimonial2-author': 'Michael Chen',
    'testimonial2-company': 'Elite Motors',
    'testimonial3-quote': '"Customer satisfaction has increased significantly since we started using My Detail Area. The SMS notifications keep everyone informed and happy."',
    'testimonial3-author': 'David Rodriguez',
    'testimonial3-company': 'Rodriguez Auto Group',
    'cta-title': 'Ready to transform your dealership?',
    'cta-subtitle': 'Experience the power of My Detail Area with a personalized demo. See how we can streamline your operations and delight your customers.',
    'cta-demo': 'Request Demo',
    'cta-learn': 'Learn More',
    'footer-product': 'Product',
    'footer-features': 'Features',
    'footer-solutions': 'Solutions',
    'footer-pricing': 'Pricing',
    'footer-integrations': 'Integrations',
    'footer-company': 'Company',
    'footer-about': 'About',
    'footer-careers': 'Careers',
    'footer-press': 'Press',
    'footer-blog': 'Blog',
    'footer-resources': 'Resources',
    'footer-documentation': 'Documentation',
    'footer-help': 'Help Center',
    'footer-community': 'Community',
    'footer-status': 'Status',
    'footer-legal': 'Legal',
    'footer-privacy': 'Privacy',
    'footer-terms': 'Terms',
    'footer-security': 'Security',
    'footer-cookies': 'Cookies',
    'footer-copyright': 'Copyright © 2024 My Detail Area Inc. All rights reserved.'
  },
  es: {
    'nav-logo': 'My Detail Area',
    'nav-features': 'Características',
    'nav-solutions': 'Soluciones',
    'nav-testimonials': 'Testimonios',
    'nav-contact': 'Contacto',
    'nav-login': 'Iniciar Sesión',
    'hero-title': 'My Detail Area',
    'hero-tagline': 'Diseñado para ser poderoso.',
    'hero-subtitle': 'La plataforma de gestión de concesionarios más avanzada. Construida para profesionales que exigen excelencia en cada detalle.',
    'hero-cta': 'Solicitar Demo',
    'hero-learn-more': 'Conocer Más',
    'showcase-title': 'Tres soluciones poderosas.',
    'showcase-tagline': 'Una experiencia perfecta.',
    'product1-title': 'Gestión de Órdenes',
    'product1-description': 'Optimiza tu flujo de trabajo con seguimiento inteligente de órdenes y notificaciones automatizadas.',
    'product2-title': 'Colaboración en Equipo',
    'product2-description': 'Conecta tu equipo con permisos basados en roles y herramientas de comunicación en tiempo real.',
    'product3-title': 'Experiencia del Cliente',
    'product3-description': 'Ofrece un servicio excepcional con comunicación SMS integrada y actualizaciones de estado.',
    'feature1-title': 'Gestión Avanzada de Órdenes',
    'feature1-tagline': 'Cada detalle, perfectamente organizado.',
    'feature1-description': 'Transforma las operaciones de tu concesionario con nuestro sistema inteligente de gestión de órdenes. Rastrea cada servicio de principio a fin con claridad y control sin precedentes.',
    'feature1-point1': 'Seguimiento de órdenes y actualizaciones de estado en tiempo real',
    'feature1-point2': 'Gestión automatizada del flujo de trabajo',
    'feature1-point3': 'Historial completo de servicios y documentación',
    'feature1-point4': 'Asignación inteligente de tareas y programación',
    'feature1-cta': 'Conocer Más',
    'feature2-title': 'Colaboración Inteligente en Equipo',
    'feature2-tagline': 'Trabajar juntos, sin problemas.',
    'feature2-description': 'Empodera a tu equipo con controles de acceso basados en roles y herramientas de colaboración en tiempo real. Desde técnicos hasta gerentes, todos se mantienen conectados e informados.',
    'feature2-point1': 'Permisos personalizables basados en roles',
    'feature2-point2': 'Comunicación del equipo en tiempo real',
    'feature2-point3': 'Delegación de tareas y seguimiento del progreso',
    'feature2-point4': 'Análisis de rendimiento e informes',
    'feature2-cta': 'Descubrir Más',
    'feature3-title': 'Experiencia Excepcional del Cliente',
    'feature3-tagline': 'Comunicación que conecta.',
    'feature3-description': 'Mantén a tus clientes informados y comprometidos con comunicación SMS integrada y actualizaciones automatizadas. Construye relaciones duraderas a través de la transparencia y el servicio excepcional.',
    'feature3-point1': 'Comunicación SMS integrada vía Twilio',
    'feature3-point2': 'Notificaciones automáticas de estado',
    'feature3-point3': 'Portal del cliente para actualizaciones en tiempo real',
    'feature3-point4': 'Recopilación y análisis de comentarios',
    'feature3-cta': 'Comenzar',
    'stats-title': 'Confiado por profesionales en todo el mundo',
    'stats-subtitle': 'Únete a miles de concesionarios que han transformado sus operaciones con My Detail Area.',
    'stat1-label': 'Concesionarios',
    'stat2-label': 'Órdenes Procesadas',
    'stat3-label': 'Tiempo Activo',
    'stat4-label': 'Soporte',
    'testimonials-title': 'Lo que dicen nuestros clientes',
    'testimonial1-quote': '"My Detail Area ha transformado completamente cómo gestionamos nuestras operaciones de detallado. Las ganancias en eficiencia son notables, y a nuestros clientes les encantan las actualizaciones en tiempo real."',
    'testimonial1-author': 'Sarah Johnson',
    'testimonial1-company': 'Premium Auto Detailing',
    'testimonial2-quote': '"Las características de colaboración en equipo han optimizado tremendamente nuestro flujo de trabajo. Todos saben exactamente qué necesita hacerse y cuándo. Es un cambio radical."',
    'testimonial2-author': 'Michael Chen',
    'testimonial2-company': 'Elite Motors',
    'testimonial3-quote': '"La satisfacción del cliente ha aumentado significativamente desde que comenzamos a usar My Detail Area. Las notificaciones SMS mantienen a todos informados y felices."',
    'testimonial3-author': 'David Rodriguez',
    'testimonial3-company': 'Rodriguez Auto Group',
    'cta-title': '¿Listo para transformar tu concesionario?',
    'cta-subtitle': 'Experimenta el poder de My Detail Area con una demo personalizada. Ve cómo podemos optimizar tus operaciones y deleitar a tus clientes.',
    'cta-demo': 'Solicitar Demo',
    'cta-learn': 'Conocer Más',
    'footer-product': 'Producto',
    'footer-features': 'Características',
    'footer-solutions': 'Soluciones',
    'footer-pricing': 'Precios',
    'footer-integrations': 'Integraciones',
    'footer-company': 'Empresa',
    'footer-about': 'Acerca de',
    'footer-careers': 'Carreras',
    'footer-press': 'Prensa',
    'footer-blog': 'Blog',
    'footer-resources': 'Recursos',
    'footer-documentation': 'Documentación',
    'footer-help': 'Centro de Ayuda',
    'footer-community': 'Comunidad',
    'footer-status': 'Estado',
    'footer-legal': 'Legal',
    'footer-privacy': 'Privacidad',
    'footer-terms': 'Términos',
    'footer-security': 'Seguridad',
    'footer-cookies': 'Cookies',
    'footer-copyright': 'Copyright © 2024 My Detail Area Inc. Todos los derechos reservados.'
  },
  pt: {
    'nav-logo': 'My Detail Area',
    'nav-features': 'Recursos',
    'nav-solutions': 'Soluções',
    'nav-testimonials': 'Depoimentos',
    'nav-contact': 'Contato',
    'nav-login': 'Entrar',
    'hero-title': 'My Detail Area',
    'hero-tagline': 'Projetado para ser poderoso.',
    'hero-subtitle': 'A plataforma de gestão de concessionárias mais avançada. Construída para profissionais que exigem excelência em cada detalhe.',
    'hero-cta': 'Solicitar Demo',
    'hero-learn-more': 'Saiba Mais',
    'showcase-title': 'Três soluções poderosas.',
    'showcase-tagline': 'Uma experiência perfeita.',
    'product1-title': 'Gestão de Pedidos',
    'product1-description': 'Otimize seu fluxo de trabalho com rastreamento inteligente de pedidos e notificações automatizadas.',
    'product2-title': 'Colaboração em Equipe',
    'product2-description': 'Conecte sua equipe com permissões baseadas em funções e ferramentas de comunicação em tempo real.',
    'product3-title': 'Experiência do Cliente',
    'product3-description': 'Ofereça um serviço excepcional com comunicação SMS integrada e atualizações de status.',
    'feature1-title': 'Gestão Avançada de Pedidos',
    'feature1-tagline': 'Cada detalhe, perfeitamente organizado.',
    'feature1-description': 'Transforme as operações da sua concessionária com nosso sistema inteligente de gestão de pedidos. Rastreie cada serviço do início ao fim com clareza e controle sem precedentes.',
    'feature1-point1': 'Rastreamento de pedidos e atualizações de status em tempo real',
    'feature1-point2': 'Gestão automatizada do fluxo de trabalho',
    'feature1-point3': 'Histórico completo de serviços e documentação',
    'feature1-point4': 'Atribuição inteligente de tarefas e agendamento',
    'feature1-cta': 'Saiba Mais',
    'feature2-title': 'Colaboração Inteligente em Equipe',
    'feature2-tagline': 'Trabalhar juntos, perfeitamente.',
    'feature2-description': 'Capacite sua equipe com controles de acesso baseados em funções e ferramentas de colaboração em tempo real. De técnicos a gerentes, todos permanecem conectados e informados.',
    'feature2-point1': 'Permissões personalizáveis baseadas em funções',
    'feature2-point2': 'Comunicação da equipe em tempo real',
    'feature2-point3': 'Delegação de tarefas e acompanhamento do progresso',
    'feature2-point4': 'Análise de desempenho e relatórios',
    'feature2-cta': 'Descobrir Mais',
    'feature3-title': 'Experiência Excepcional do Cliente',
    'feature3-tagline': 'Comunicação que conecta.',
    'feature3-description': 'Mantenha seus clientes informados e engajados com comunicação SMS integrada e atualizações automatizadas. Construa relacionamentos duradouros através da transparência e serviço excepcional.',
    'feature3-point1': 'Comunicação SMS integrada via Twilio',
    'feature3-point2': 'Notificações automáticas de status',
    'feature3-point3': 'Portal do cliente para atualizações em tempo real',
    'feature3-point4': 'Coleta e análise de feedback',
    'feature3-cta': 'Começar',
    'stats-title': 'Confiado por profissionais em todo o mundo',
    'stats-subtitle': 'Junte-se a milhares de concessionárias que transformaram suas operações com My Detail Area.',
    'stat1-label': 'Concessionárias',
    'stat2-label': 'Pedidos Processados',
    'stat3-label': 'Tempo de Atividade',
    'stat4-label': 'Suporte',
    'testimonials-title': 'O que nossos clientes dizem',
    'testimonial1-quote': '"My Detail Area transformou completamente como gerenciamos nossas operações de detalhamento. Os ganhos de eficiência são notáveis, e nossos clientes adoram as atualizações em tempo real."',
    'testimonial1-author': 'Sarah Johnson',
    'testimonial1-company': 'Premium Auto Detailing',
    'testimonial2-quote': '"Os recursos de colaboração em equipe otimizaram tremendamente nosso fluxo de trabalho. Todos sabem exatamente o que precisa ser feito e quando. É uma mudança radical."',
    'testimonial2-author': 'Michael Chen',
    'testimonial2-company': 'Elite Motors',
    'testimonial3-quote': '"A satisfação do cliente aumentou significativamente desde que começamos a usar My Detail Area. As notificações SMS mantêm todos informados e felizes."',
    'testimonial3-author': 'David Rodriguez',
    'testimonial3-company': 'Rodriguez Auto Group',
    'cta-title': 'Pronto para transformar sua concessionária?',
    'cta-subtitle': 'Experimente o poder do My Detail Area com uma demonstração personalizada. Veja como podemos otimizar suas operações e encantar seus clientes.',
    'cta-demo': 'Solicitar Demo',
    'cta-learn': 'Saiba Mais',
    'footer-product': 'Produto',
    'footer-features': 'Recursos',
    'footer-solutions': 'Soluções',
    'footer-pricing': 'Preços',
    'footer-integrations': 'Integrações',
    'footer-company': 'Empresa',
    'footer-about': 'Sobre',
    'footer-careers': 'Carreiras',
    'footer-press': 'Imprensa',
    'footer-blog': 'Blog',
    'footer-resources': 'Recursos',
    'footer-documentation': 'Documentação',
    'footer-help': 'Central de Ajuda',
    'footer-community': 'Comunidade',
    'footer-status': 'Status',
    'footer-legal': 'Legal',
    'footer-privacy': 'Privacidade',
    'footer-terms': 'Termos',
    'footer-security': 'Segurança',
    'footer-cookies': 'Cookies',
    'footer-copyright': 'Copyright © 2024 My Detail Area Inc. Todos os direitos reservados.'
  }
};

let currentLanguage = 'en';

function translatePage(language) {
  currentLanguage = language;
  const elements = document.querySelectorAll('[data-translate]');
  
  elements.forEach(element => {
    const key = element.getAttribute('data-translate');
    if (translations[language] && translations[language][key]) {
      element.textContent = translations[language][key];
    }
  });

  // Update language button
  const languageBtn = document.getElementById('languageBtn');
  const flags = { en: 'EN', es: 'ES', pt: 'PT' };
  languageBtn.textContent = flags[language];
}

document.addEventListener('DOMContentLoaded', function() {
  // Language selector functionality
  const languageBtn = document.getElementById('languageBtn');
  const languageDropdown = document.getElementById('languageDropdown');
  const languageOptions = document.querySelectorAll('.language-option');

  languageBtn.addEventListener('click', function() {
    languageDropdown.classList.toggle('show');
  });

  languageOptions.forEach(option => {
    option.addEventListener('click', function() {
      const selectedLang = this.getAttribute('data-lang');
      translatePage(selectedLang);
      languageDropdown.classList.remove('show');
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    if (!languageBtn.contains(event.target) && !languageDropdown.contains(event.target)) {
      languageDropdown.classList.remove('show');
    }
  });

  // Navbar scroll effect
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Intersection Observer for animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, observerOptions);

  // Observe all animated elements
  const animatedElements = document.querySelectorAll('.fade-in, .scale-in');
  animatedElements.forEach(el => observer.observe(el));

  // Smooth scrolling for navigation links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
});
</script>
</body>
</html>

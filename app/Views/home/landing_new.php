<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, width=device-width">
  
  <link rel="stylesheet" href="<?= base_url('webfront-assets/index.css') ?>">
  <link rel="stylesheet" href="<?= base_url('webfront-assets/home.css') ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap">
  
  <title><?= esc($company_name ?? 'Nipo Agro') ?> - Global Agri-Traders</title>
</head>
<body>
  <div class="home-page">
    <div class="contact-us-wrapper" id="frameContainer">
      <div class="contact-us">Contact us</div>
    </div>
    <div class="about-parent">
      <div class="about" id="aboutText">About</div>
      <div class="services" id="servicesText">Services</div>
      <div class="product" id="productText">Product</div>
      <b class="home">Home</b>
      <div class="instance-child"></div>
    </div>
    <div class="global-agri-traders">
      <p class="global">Global</p>
      <p class="global">Agri-Traders</p>
    </div>
    <div class="we-connect-farmers">We connect farmers, traders, and buyers worldwide with quality cereals, oil plants, agrochemicals, and machinery. From planting to harvest, we deliver reliable agricultural solutions for global markets.</div>
    <div class="contact-us-container" id="frameContainer1">
      <div class="home-page-contact-us">Contact Us</div>
    </div>
    <div class="explore-products-wrapper" id="frameContainer2">
      <div class="explore-products">Explore Products</div>
    </div>
    <img class="vector-icon" src="<?= base_url('webfront-assets/Vector.svg') ?>" alt="">
    <img class="home-page-child" src="<?= base_url('webfront-images/Rectangle 7.png') ?>" alt="">
    <img class="home-page-item" src="<?= base_url('webfront-images/Rectangle 8.png') ?>" alt="">
    <img class="home-page-inner" src="<?= base_url('webfront-images/Rectangle 9.png') ?>" alt="">
    <img class="rectangle-icon" src="<?= base_url('webfront-images/Rectangle 10.png') ?>" alt="">
    <img class="home-page-home-page-child" src="<?= base_url('webfront-images/Rectangle 11.png') ?>" alt="">
    <div class="home-page-about">About</div>
    <div class="we-are-an">We are an integrated agribusiness serving local and international markets. Our mission is to ensure consistent supply of grains, oilseeds, agrochemicals, and farming machinery — building reliable connections from East Africa to the world.</div>
    <div class="lean-more-wrapper" id="frameContainer3">
      <div class="lean-more">Learn more</div>
    </div>
    <div class="line-div"></div>
    <div class="rectangle-div"></div>
    <img class="nipo-agro-man" src="<?= base_url('webfront-images/Nipo Agro man.png') ?>" alt="Nipo Agro Team">
    <div class="home-page-child2"></div>
    <div class="home-page-child3"></div>
    <div class="home-page-child4"></div>
    <div class="home-page-child5"></div>
    <div class="home-page-child6"></div>
    <div class="home-page-child7"></div>
    <div class="home-page-child8"></div>
    <div class="international">International</div>
    <div class="warehousing-storage-container">
      <p class="global">Warehousing</p>
      <p class="global">& Storage</p>
    </div>
    <div class="consultation">Consultation</div>
    <div class="service-maintenance">
      <p class="global">Service</p>
      <p class="global">&Maintenance</p>
    </div>
    <b class="services-we-render-container">
      <p class="global">Services</p>
      <p class="global">we render</p>
    </b>
    
    .nav-indicator {
      width: 3.35px;
      height: 3.35px;
      left: 19.44px;
      top: 18.77px;
      position: absolute;
      background: #2ACC32;
      border-radius: 50%;
    }
    
    /* Contact Button */
    .contact-btn {
      padding: 11.18px 29.41px;
      left: 1152.89px;
      top: 54.17px;
      position: absolute;
      border-radius: 24.70px;
      border: 1.18px solid #8AC653;
      background: transparent;
      cursor: pointer;
      text-decoration: none;
    }
    
    .contact-btn-text {
      color: #191819;
      font-size: 19.13px;
      font-family: Montserrat;
      font-weight: 600;
      line-height: 19.58px;
    }
    
    /* Logo */
    .logo {
      width: 120px;
      height: 120px;
      left: 94px;
      top: 60px;
      position: absolute;
      background: #2ACC32;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      font-weight: 800;
    }
    
    /* Hero Section */
    .hero-title {
      left: 404.32px;
      top: 243.87px;
      position: absolute;
      text-align: center;
      color: #191819;
      font-size: 89.27px;
      font-family: Montserrat;
      font-weight: 800;
      line-height: 95.92px;
    }
    
    .hero-description {
      width: 861px;
      left: 269px;
      top: 481px;
      position: absolute;
      text-align: center;
      color: #191819;
      font-size: 26.55px;
      font-family: Montserrat;
      font-weight: 400;
      line-height: 40.79px;
    }
    
    /* Hero Buttons */
    .hero-btn {
      position: absolute;
      background: #218225;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .hero-btn-primary {
      width: 252px;
      height: 53px;
      left: 468px;
      top: 665px;
    }
    
    .hero-btn-secondary {
      width: 185px;
      height: 53px;
      left: 747px;
      top: 665px;
    }
    
    .hero-btn-text {
      color: white;
      font-size: 26.35px;
      font-family: Montserrat;
      font-weight: 600;
      line-height: 24px;
    }
    
    /* Decorative Elements */
    .green-accent {
      width: 342.68px;
      height: 104.05px;
      left: 324.08px;
      top: 349.64px;
      position: absolute;
      transform: rotate(-4deg);
      background: #2ACC32;
    }
    
    /* Floating Images */
    .floating-img {
      position: absolute;
      border-radius: 19.35px;
      box-shadow: 0px 31px 39px rgba(138, 198, 83, 0.24);
      outline: 5.76px rgba(183, 183, 183, 0.40) solid;
    }
    
    .floating-img-1 {
      width: 165.28px;
      height: 177.29px;
      left: 141.97px;
      top: 614.93px;
    }
    
    .floating-img-2 {
      width: 184.54px;
      height: 197.95px;
      left: 954.17px;
      top: 737.87px;
    }
    
    .floating-img-3 {
      width: 158.61px;
      height: 170.14px;
      left: 1130.21px;
      top: 324.07px;
    }
    
    .floating-img-4 {
      width: 264.93px;
      height: 284.19px;
      left: -12.96px;
      top: 243.87px;
      border-radius: 31.02px;
      outline: 9.23px rgba(183, 183, 183, 0.40) solid;
    }
    
    .floating-img-5 {
      width: 264.93px;
      height: 284.19px;
      left: 1216.09px;
      top: 458.56px;
      border-radius: 31.02px;
      outline: 9.23px rgba(183, 183, 183, 0.40) solid;
    }
    
    /* About Section */
    .about-section {
      position: absolute;
      top: 1000px;
      width: 100%;
    }
    
    .about-title {
      left: 742.13px;
      top: 1183.68px;
      position: absolute;
      color: #2ACC32;
      font-size: 36.41px;
      font-family: Montserrat;
      font-weight: 800;
      line-height: 39.12px;
    }
    
    .about-text {
      width: 487.73px;
      left: 742.13px;
      top: 1237.83px;
      position: absolute;
      color: #191819;
      font-size: 22.90px;
      font-family: Montserrat;
      font-weight: 400;
      line-height: 35.19px;
    }
    
    .about-btn {
      padding: 15.39px 40.51px;
      left: 742.13px;
      top: 1498.84px;
      position: absolute;
      background: #218225;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    
    .about-btn-text {
      color: white;
      font-size: 26.35px;
      font-family: Montserrat;
      font-weight: 600;
      line-height: 26.97px;
    }
    
    .about-line {
      width: 68.87px;
      height: 1px;
      left: 887.15px;
      top: 1203.12px;
      position: absolute;
      background: black;
    }
    
    .about-bg {
      width: 396.99px;
      height: 684.61px;
      left: 239.81px;
      top: 1026.50px;
      position: absolute;
      background: #2ACC32;
      border-radius: 198.50px;
    }
    
    .about-img {
      width: 655px;
      height: 886px;
      left: 128px;
      top: 1085px;
      position: absolute;
    }
    
    .about-gradient {
      width: 526px;
      height: 234px;
      left: 194px;
      top: 1855px;
      position: absolute;
      background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, white 100%);
    }
    
    /* Services Section */
    .services-bg {
      width: 100%;
      height: 1624px;
      left: 0px;
      top: 2516px;
      position: absolute;
      background: #EEEEEE;
    }
    
    .services-title {
      left: 160.42px;
      top: 2142.94px;
      position: absolute;
      color: #218225;
      font-size: 51.57px;
      font-family: Montserrat;
      font-weight: 700;
      line-height: 50.15px;
    }
    
    .services-line {
      width: 78.59px;
      height: 1px;
      left: 481.25px;
      top: 2224.77px;
      position: absolute;
      background: black;
    }
    
    /* Service Cards */
    .service-card {
      position: absolute;
      background: white;
      border-radius: 34.03px;
    }
    
    .service-card-1 {
      width: 487.73px;
      height: 449.65px;
      left: 160.42px;
      top: 2285.53px;
    }
    
    .service-card-2 {
      width: 488px;
      height: 501px;
      left: 751px;
      top: 2097px;
    }
    
    .service-card-3 {
      width: 487.73px;
      height: 449.65px;
      left: 160.42px;
      top: 2817.01px;
    }
    
    .service-card-4 {
      width: 488px;
      height: 556px;
      left: 751px;
      top: 2654px;
    }
    
    .service-title {
      position: absolute;
      color: #2ACC32;
      font-size: 36.41px;
      font-family: Montserrat;
      font-weight: 500;
      line-height: 39.12px;
    }
    
    .service-text {
      position: absolute;
      color: #191819;
      font-size: 22.90px;
      font-family: Montserrat;
      font-weight: 400;
      line-height: 35.19px;
    }
    
    .service-icon {
      width: 89.93px;
      height: 89.93px;
      position: absolute;
      background: rgba(33, 130, 37, 0.13);
      border-radius: 50%;
    }
    
    .services-btn {
      padding: 15.39px 40.51px;
      left: 750.23px;
      top: 3240.51px;
      position: absolute;
      background: #218225;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    
    /* Why Choose Section */
    .why-title {
      left: 130.44px;
      top: 3447.34px;
      position: absolute;
      color: #2ACC32;
      font-size: 35.14px;
      font-family: Montserrat;
      font-weight: 500;
      line-height: 34.17px;
    }
    
    .why-card {
      width: 251.51px;
      height: 324.39px;
      position: absolute;
      background: #F5F5F5;
      border-radius: 21.56px;
      top: 3526.83px;
    }
    
    .why-card-1 { left: 130.44px; }
    .why-card-2 { left: 430.09px; }
    .why-card-3 { left: 729.74px; }
    .why-card-4 { left: 1029.39px; }
    
    .why-card-img {
      width: 224.30px;
      height: 123.70px;
      position: absolute;
      top: 11.81px;
      left: 13.61px;
      border-radius: 11.29px;
    }
    
    .why-card-title {
      position: absolute;
      color: #2ACC32;
      font-size: 23.07px;
      font-family: Montserrat;
      font-weight: 500;
      line-height: 24.79px;
      top: 146.29px;
      left: 22.07px;
    }
    
    .why-card-text {
      position: absolute;
      color: #191819;
      font-size: 14.51px;
      font-family: Montserrat;
      font-weight: 400;
      line-height: 22.29px;
      top: 202.29px;
      left: 22.07px;
      width: 198.64px;
    }
    
    .why-btn {
      padding: 15.39px 40.51px;
      left: 586.57px;
      top: 3919.77px;
      position: absolute;
      background: #218225;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    
    /* Newsletter Section */
    .newsletter-bg {
      width: 100%;
      height: 422px;
      left: 0px;
      top: 4140px;
      position: absolute;
      background: #EEEEEE;
    }
    
    .newsletter-title {
      left: 454.51px;
      top: 4241.32px;
      position: absolute;
      color: #2ACC32;
      font-size: 35.14px;
      font-family: Montserrat;
      font-weight: 500;
      line-height: 34.17px;
    }
    
    .newsletter-input {
      width: 567.94px;
      height: 60px;
      left: 416.44px;
      top: 4310.18px;
      position: absolute;
      background: white;
      border-radius: 15px;
      border: none;
      padding: 0 59.95px;
      font-size: 22.15px;
      font-family: Montserrat;
      font-weight: 300;
      color: rgba(86, 86, 86, 0.42);
    }
    
    .newsletter-btn {
      padding: 15.39px 40.51px;
      left: 593.06px;
      top: 4421.99px;
      position: absolute;
      background: #218225;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    
    /* Footer */
    .footer-logo {
      width: 120px;
      height: 120px;
      left: 126px;
      top: 4640px;
      position: absolute;
      background: #2ACC32;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      font-weight: 800;
    }
    
    .footer-text {
      position: absolute;
      color: #191819;
      font-size: 18.70px;
      font-family: Montserrat;
      font-weight: 500;
      line-height: 26.87px;
    }
    
    .footer-address {
      left: 572px;
      top: 4641.81px;
    }
    
    .footer-phone {
      left: 572px;
      top: 4740.41px;
    }
    
    .footer-email1 {
      left: 1083.33px;
      top: 4641px;
    }
    
    .footer-email2 {
      left: 1083.33px;
      top: 4671px;
    }
    
    .footer-social {
      left: 1083.48px;
      top: 4740.72px;
      font-size: 17.95px;
      line-height: 19.97px;
    }
    
    .footer-copyright {
      left: 144.31px;
      top: 4767.45px;
      position: absolute;
      color: #191819;
      font-size: 12.04px;
      font-family: Montserrat;
      font-weight: 500;
      line-height: 13.40px;
    }
    
    .footer-icon {
      width: 12.77px;
      height: 12.77px;
      left: 131px;
      top: 4767.99px;
      position: absolute;
    }
    
    /* Responsive Design */
    @media (max-width: 1400px) {
      .container {
        transform: scale(0.8);
        transform-origin: top left;
        width: 125%;
        height: 125vh;
      }
    }
    
    @media (max-width: 1200px) {
      .container {
        transform: scale(0.7);
        transform-origin: top left;
        width: 143%;
        height: 143vh;
      }
    }
    
    @media (max-width: 768px) {
      .container {
        transform: scale(0.5);
        transform-origin: top left;
        width: 200%;
        height: 200vh;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- Logo -->
    <div class="logo">
      NIPO
    </div>
    
    <!-- Contact Button -->
    <a href="<?= site_url('login') ?>" class="contact-btn">
      <div class="contact-btn-text">Contact us</div>
    </a>
    
    <!-- Navigation Menu -->
    <div class="nav-menu">
      <a href="#" class="nav-item active">Home</a>
      <a href="#about" class="nav-item">About</a>
      <a href="#services" class="nav-item">Services</a>
      <a href="#products" class="nav-item">Product</a>
      <div class="nav-indicator"></div>
    </div>
    
    <!-- Hero Title -->
    <div class="hero-title">Global<br/>Agri-Traders</div>
    
    <!-- Hero Description -->
    <div class="hero-description">
      We connect farmers, traders, and buyers worldwide with quality cereals, oil plants, agrochemicals, and machinery. From planting to harvest, we deliver reliable agricultural solutions for global markets.
    </div>
    
    <!-- Hero Buttons -->
    <a href="#products" class="hero-btn hero-btn-primary">
      <div class="hero-btn-text">Explore Products</div>
    </a>
    <a href="#contact" class="hero-btn hero-btn-secondary">
      <div class="hero-btn-text">Contact Us</div>
    </a>
    
    <!-- Decorative Green Accent -->
    <div class="green-accent"></div>
    
    <!-- Floating Images -->
    <img class="floating-img floating-img-1" src="https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?q=80&w=400&auto=format&fit=crop" alt="Agriculture" />
    <img class="floating-img floating-img-2" src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=400&auto=format&fit=crop" alt="Farming" />
    <img class="floating-img floating-img-3" src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=400&auto=format&fit=crop" alt="Grains" />
    <img class="floating-img floating-img-4" src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?q=80&w=400&auto=format&fit=crop" alt="Wheat Field" />
    <img class="floating-img floating-img-5" src="https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?q=80&w=400&auto=format&fit=crop" alt="Agriculture Equipment" />
    
    <!-- About Section -->
    <div class="about-title">About</div>
    <div class="about-line"></div>
    <div class="about-text">
      We are an integrated agribusiness serving local and international markets. Our mission is to ensure consistent supply of grains, oilseeds, agrochemicals, and farming machinery — building reliable connections from East Africa to the world.
    </div>
    <a href="#about" class="about-btn">
      <div class="about-btn-text">Learn more</div>
    </a>
    
    <!-- About Background and Image -->
    <div class="about-bg"></div>
    <img class="about-img" src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?q=80&w=800&auto=format&fit=crop" alt="About Us" />
    <div class="about-gradient"></div>
    
    <!-- Services Section Background -->
    <div class="services-bg"></div>
    
    <!-- Services Title -->
    <div class="services-title">Services <br/>we render</div>
    <div class="services-line"></div>
    
    <!-- Service Cards -->
    <div class="service-card service-card-1"></div>
    <div class="service-card service-card-2"></div>
    <div class="service-card service-card-3"></div>
    <div class="service-card service-card-4"></div>
    
    <!-- Service Titles and Descriptions -->
    <div class="service-title" style="left: 230.09px; top: 2447.29px;">International</div>
    <div class="service-text" style="width: 414px; left: 203px; top: 2502px;">
      We manage sourcing, documentation, and customs clearance to ensure smooth cross-border trade. From farm to port, we deliver on time and to global standards.
    </div>
    
    <div class="service-title" style="left: 820.72px; top: 2254.44px;">Warehousing <br/>& Storage</div>
    <div class="service-text" style="width: 359.72px; left: 820.72px; top: 2348.73px;">
      Our facilities provide safe, moisture-controlled storage that preserves grain quality. We ensure products remain fresh and compliant until delivery.
    </div>
    
    <div class="service-title" style="left: 230.09px; top: 2988.77px;">Consultation</div>
    <div class="service-text" style="width: 359.72px; left: 230.09px; top: 3043.06px;">
      We build resourceful partnerships agile enough to move with you, whether you're an international corporation or a small startup
    </div>
    
    <div class="service-title" style="left: 820.72px; top: 2825.93px;">Service <br/>&Maintenance</div>
    <div class="service-text" style="width: 359.72px; left: 820.72px; top: 2920.21px;">
      We provide professional installation of agricultural machinery to ensure peak performance. Our maintenance support reduces downtime and extends equipment lifespan.
    </div>
    
    <!-- Service Icons -->
    <div class="service-icon" style="left: 226.85px; top: 2342.24px;"></div>
    <div class="service-icon" style="left: 817px; top: 2139.40px;"></div>
    <div class="service-icon" style="left: 226.85px; top: 2873.73px;"></div>
    <div class="service-icon" style="left: 817px; top: 2711px;"></div>
    
    <!-- Services Button -->
    <a href="#services" class="services-btn">
      <div class="hero-btn-text">See more</div>
    </a>
    
    <!-- Why Choose Section -->
    <div class="why-title">Why Choose Nipo Agro?</div>
    
    <!-- Why Choose Cards -->
    <div class="why-card why-card-1">
      <img class="why-card-img" src="https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?q=80&w=400&auto=format&fit=crop" alt="Customer Satisfaction" />
      <div class="why-card-title">Customer <br/>Satisfaction</div>
      <div class="why-card-text">This is the Bedrock of our establishment. We are poised to always maximally satisfy our customers.</div>
    </div>
    
    <div class="why-card why-card-2">
      <img class="why-card-img" src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?q=80&w=400&auto=format&fit=crop" alt="Loyalty" />
      <div class="why-card-title">Loyalty</div>
      <div class="why-card-text">We do not comprise quality and trust once it comes to our customers satisfaction and make sure we keep up with our customers need</div>
    </div>
    
    <div class="why-card why-card-3">
      <img class="why-card-img" src="https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=400&auto=format&fit=crop" alt="Quality" />
      <div class="why-card-title">Quality</div>
      <div class="why-card-text">We provide only industry-standard product and services to our clients, partners, and customers.</div>
    </div>
    
    <div class="why-card why-card-4">
      <img class="why-card-img" src="https://images.unsplash.com/photo-1580983559360-0a88c9f0a3a5?q=80&w=400&auto=format&fit=crop" alt="Feedback" />
      <div class="why-card-title">Feedback</div>
      <div class="why-card-text">We consider feedback the most important tool for improvement and take into consideration every detail</div>
    </div>
    
    <!-- Why Choose Button -->
    <a href="#contact" class="why-btn">
      <div class="hero-btn-text">Get Started</div>
    </a>
    
    <!-- Newsletter Section -->
    <div class="newsletter-bg"></div>
    <div class="newsletter-title">Subscribe to our newsletter</div>
    <input type="email" class="newsletter-input" placeholder="johndoe@gmail.com" />
    <a href="#" class="newsletter-btn">
      <div class="hero-btn-text">Subscribe</div>
    </a>
    
    <!-- Footer -->
    <div class="footer-logo">NIPO</div>
    <img class="nipo-agro-logo4x-2" src="<?= base_url('webfront-images/Nipo Agro Logo@4x 2.png') ?>" alt="Nipo Agro Logo">
    <img class="nipo-agro-logo4x-3" src="<?= base_url('webfront-images/Nipo Agro Logo@4x 3.png') ?>" alt="Nipo Agro Logo">
    <div class="kisasa-street-kisasa-road-do-parent">
      <div class="kisasa-street-kisasa-container">
        <p class="global">43 , Kisasa Street, Kisasa Road, </p>
        <p class="global">Dodoma, Tanzania.</p>
      </div>
      <div class="div">0714349614, 0713671675</div>
      <div class="infonipoagrocom">info@nipoagro.com</div>
      <div class="salesnipoagrocom">sales@nipoagro.com</div>
      <div class="nipoagroinsights-wrapper">
        <div class="nipoagroinsights">nipoagro.insights</div>
      </div>
    </div>
  </div>
  
  <!-- Navigation and Interactive Functionality -->
  <script src="<?= base_url('webfront-assets/navigation.js') ?>"></script>
</body>
</html>

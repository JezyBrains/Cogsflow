<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, width=device-width">
  
  <link rel="stylesheet" href="<?= base_url('webfront-assets/index.css') ?>">
  <link rel="stylesheet" href="<?= base_url('webfront-assets/contactus.css') ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap">
  
  <title>Contact Us - <?= esc($company_name ?? 'Nipo Agro') ?></title>
</head>
<body>
  <div class="contact-us">
    <div class="contact-us-wrapper">
      <div class="contact-us-contact-us">Contact us</div>
    </div>
    <div class="about-parent">
      <div class="about" id="aboutText">About</div>
      <div class="services" id="servicesText">Services</div>
      <div class="product" id="productText">Product</div>
      <div class="home" id="homeText">Home</div>
      <div class="instance-child"></div>
    </div>
    <div class="get-in-touch">Get in touch with us</div>
    <div class="we-are-here">We are here to assist you with all your agricultural commodity needs. Whether you're looking to source premium grains, require logistics support, or need consultation on agricultural trade, our team is ready to help.</div>
    <form action="<?= site_url('contact/submit') ?>" method="post">
      <?= csrf_field() ?>
      <div class="john-doe" style="position: absolute; left: 120px; top: 400px;">
        <input type="text" name="full_name" placeholder="Full Name" required style="width: 300px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: Montserrat;">
      </div>
      <div class="john-doe" style="position: absolute; left: 450px; top: 400px;">
        <input type="tel" name="phone" placeholder="Phone Number" required style="width: 300px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: Montserrat;">
      </div>
      <div class="john-doe" style="position: absolute; left: 120px; top: 480px;">
        <input type="email" name="email" placeholder="Email Address" required style="width: 300px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: Montserrat;">
      </div>
      <div class="john-doe" style="position: absolute; left: 450px; top: 480px;">
        <input type="text" name="subject" placeholder="Subject" required style="width: 300px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: Montserrat;">
      </div>
      <div class="john-doe" style="position: absolute; left: 120px; top: 560px;">
        <textarea name="message" placeholder="Your Message" required style="width: 630px; height: 120px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: Montserrat; resize: vertical;"></textarea>
      </div>
      <button type="submit" style="position: absolute; left: 120px; top: 720px; padding: 15px 40px; background: #2ACC32; color: white; border: none; border-radius: 8px; font-family: Montserrat; font-weight: 600; cursor: pointer;">Send Message</button>
    </form>
    <div class="contact-info" style="position: absolute; left: 900px; top: 400px; width: 300px;">
      <h3 style="color: #2ACC32; margin-bottom: 20px;">Contact Information</h3>
      <div style="margin-bottom: 15px;">
        <strong>Address:</strong><br>
        43, Kisasa Street, Kisasa Road<br>
        Dodoma, Tanzania
      </div>
      <div style="margin-bottom: 15px;">
        <strong>Phone:</strong><br>
        0714349614, 0713671675
      </div>
      <div style="margin-bottom: 15px;">
        <strong>Email:</strong><br>
        info@nipoagro.com<br>
        sales@nipoagro.com
      </div>
      <div>
        <strong>Social:</strong><br>
        nipoagro.insights
      </div>
    </div>
    <img class="nipo-agro-logo4x-1" src="<?= base_url('webfront-images/Nipo Agro Logo@4x 3.png') ?>" alt="Nipo Agro Logo">
    <img class="nipo-agro-logo4x-2" src="<?= base_url('webfront-images/Nipo Agro Logo@4x 2.png') ?>" alt="Nipo Agro Logo" id="nipoAgroLogo4x2">
  </div>
  
  <!-- Navigation and Interactive Functionality -->
  <script src="<?= base_url('webfront-assets/navigation.js') ?>"></script>
</body>
</html>

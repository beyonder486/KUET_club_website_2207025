<?php require_once 'session_config.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KUET Career Club</title>
    <meta name="description" content="KUET Career Club — connect, learn, and grow together." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <section id="hero" class="hero">
        <div class="hero-grid">
          <div class="hero-copy">
            <h1>KUET Career Club</h1>
            <p>
              A club for KUET students to connect, learn, and grow together.
              Join us to build your career, network with peers, and access
              exclusive resources. Whether you're looking for internships, job
              opportunities, or just want to connect with like-minded
              individuals, KUET Career Club is the place for you.
            </p>
            <div class="hero-actions">
              <a class="btn btn-primary" href="signup.php">Get Started</a>
              <a class="btn btn-secondary" href="events.php">View Events</a>
            </div>
          </div>
          <div class="hero-visual">
            <div class="hero-card">
              <div class="hero-card-image"></div>
            </div>
          </div>
        </div>
      </section>

      <section id="testimonials" class="testimonials">
        <div class="testimonials-container">
          <div class="testimonial">
            <p>"KUET Career Club transformed my career path. The workshops and mentorship were invaluable."</p>
            <cite>- John Doe, Software Engineer</cite>
          </div>
          <div class="testimonial">
            <p>"Amazing community! I gained skills and connections that led to my dream job."</p>
            <cite>- Jane Smith, Data Scientist</cite>
          </div>
          <div class="testimonial">
            <p>"The resources provided are top-notch. Highly recommend to all students."</p>
            <cite>- Alex Johnson, Product Manager</cite>
          </div>
          <div class="testimonial">
            <p>"KUET Career Club helped me build confidence and land multiple interviews."</p>
            <cite>- Emily Davis, UX Designer</cite>
          </div>
          <div class="testimonial">
            <p>"Incredible support and guidance. My career trajectory changed for the better."</p>
            <cite>- Michael Brown, Marketing Specialist</cite>
          </div>
          <div class="testimonial">
            <p>"KUET Career Club transformed my career path. The workshops and mentorship were invaluable."</p>
            <cite>- John Doe, Software Engineer</cite>
          </div>
          <div class="testimonial">
            <p>"Amazing community! I gained skills and connections that led to my dream job."</p>
            <cite>- Jane Smith, Data Scientist</cite>
          </div>
          <div class="testimonial">
            <p>"The resources provided are top-notch. Highly recommend to all students."</p>
            <cite>- Alex Johnson, Product Manager</cite>
          </div>
      </section>

      <section id="mission" class="mission">
        <div class="mission-copy">
          <h2>Our Mission is Simple</h2>
          <p>
            To empower KUET students to achieve their career goals by providing
            a supportive community, valuable resources, and opportunities for
            growth. We are dedicated to fostering a culture of learning,
            collaboration, and success among our members.
          </p>
          <a class="btn btn-primary" href="signup.php">Get Started</a>
        </div>
        <div class="mission-panel">
          <div class="brand-card">
            <div class="brand-text">
            </div>
          </div>
        </div>
      </section>
    </main>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>

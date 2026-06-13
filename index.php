<?php require_once 'session_config.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kuet Career Club</title>
    <meta name="description" content="KUET Career Club — connect, learn, and grow together." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <section id="hero" class="hero">
        <div class="hero-grid">
          <div class="hero-copy">
            <span class="eyebrow">  Launch Your Career</span>
            <h1>Build Your Future with KUET Career Club</h1>
            <p>
              Join 500+ members connecting with mentors, landing dream roles, and mastering industry skills. From exclusive workshops to direct recruiter access—your success starts here.
            </p>
            <div class="hero-actions">
              <a class="btn btn-primary" href="signup.php"> Get Started Free</a>
              <a class="btn btn-secondary" href="events.php"> View Events</a>
            </div>
          </div>
          <div class="hero-visual">
            <div class="hero-card">
              <div class="hero-card-stats">
                <div class="hero-stat"><span class="hero-stat-num">500+</span><span class="hero-stat-label">Active Members</span></div>
                <div class="hero-stat"><span class="hero-stat-num">50+</span><span class="hero-stat-label">Events/Year</span></div>
                <div class="hero-stat"><span class="hero-stat-num">200+</span><span class="hero-stat-label">Success Stories</span></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="featured" class="featured">
        <h2 style="text-align:center;margin-bottom:2.5rem;font-size:clamp(1.8rem,3vw,2.2rem);color:#f7f9ff;">Why Join KUET Career Club?</h2>
        <div class="features-grid">
          <div class="feature-box">
            <div class="feature-icon">🎯</div>
            <h3>Career Coaching</h3>
            <p>Get mentored by industry experts. Resume reviews, interview prep, and 1-on-1 guidance from professionals at top companies.</p>
          </div>
          <div class="feature-box">
            <div class="feature-icon">💼</div>
            <h3>Job Opportunities</h3>
            <p>Access exclusive internships and full-time roles. Direct connections with 100+ recruiters and hiring managers.</p>
          </div>
          <div class="feature-box">
            <div class="feature-icon">📚</div>
            <h3>Skill Workshops</h3>
            <p>Master in-demand skills: data science, web dev, cloud, DSA, soft skills. Monthly workshops from industry leaders.</p>
          </div>
          <div class="feature-box">
            <div class="feature-icon">🤝</div>
            <h3>Networking Events</h3>
            <p>Connect with 500+ peers, alumni, and professionals. Career fairs, seminars, and exclusive networking nights.</p>
          </div>
        </div>
      </section>

      <section id="testimonials" class="testimonials">
        <div class="testimonials-container">
          <div class="testimonial">
            <img class="testimonial-avatar" src="flat-design-young-person-avatar-profile-round-circle-icon-vector.jpg" alt="John Doe">
            <p>"KUET Career Club transformed my career path. The workshops and mentorship were invaluable."</p>
            <cite>- John Doe, Software Engineer</cite>
          </div>
          <div class="testimonial">
            <img class="testimonial-avatar" src="flat-design-young-person-avatar-profile-round-circle-icon-vector.jpg" alt="Jane Smith">
            <p>"Amazing community! I gained skills and connections that led to my dream job."</p>
            <cite>- Jane Smith, Data Scientist</cite>
          </div>
          <div class="testimonial">
            <img class="testimonial-avatar" src="flat-design-young-person-avatar-profile-round-circle-icon-vector.jpg" alt="Alex Johnson">
            <p>"The resources provided are top-notch. Highly recommend to all students."</p>
            <cite>- Alex Johnson, Product Manager</cite>
          </div>
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
              <span>Inside KUET Career Club</span>
            </div>
            <p>
              Discover a premium student network crafted for career growth,
              real-world opportunities, and continuous support from alumni and
              professionals.
            </p>
          </div>
        </div>
      </section>
    </main>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>

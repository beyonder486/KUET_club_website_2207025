<?php
require_once 'session_config.php';
require_once 'db_config.php';

$memberQuery = $pdo->query("SELECT name, email, student_id, role, created_at FROM users ORDER BY created_at DESC");
$registeredMembers = $memberQuery->fetchAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About - KUET Career Club</title>
    <meta name="description" content="Learn about KUET Career Club — our mission, values, and the team behind the club." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <section class="about-hero">
        <span class="eyebrow">🎓 Who We Are</span>
        <h1>Building Careers,<br>One Step at a Time</h1>
        <p>KUET Career Club is a student-led organization dedicated to helping KUET students navigate their professional journeys — from first internship to dream job.</p>
      </section>

      <div class="stats-row">
        <div class="stat-item"><span class="num">2019</span><span>Founded</span></div>
        <div class="stat-item"><span class="num">500+</span><span>Members</span></div>
        <div class="stat-item"><span class="num">24+</span><span>Events/Year</span></div>
        <div class="stat-item"><span class="num">80%</span><span>Placement Rate</span></div>
      </div>

      <section class="values-section">
        <p class="section-label">Our Values</p>
        <div class="values-grid">
          <div class="value-card"><div class="value-icon">🤝</div><h3>Community</h3><p>We believe careers are built on relationships. We foster a supportive network where every student feels welcome and empowered.</p></div>
          <div class="value-card"><div class="value-icon">📚</div><h3>Learning</h3><p>Continuous growth is at our core. From workshops to mentorship, we create opportunities to keep learning beyond the classroom.</p></div>
          <div class="value-card"><div class="value-icon">🚀</div><h3>Ambition</h3><p>We set high goals and help our members reach them. Whether it's a local job or an international role, we support every aspiration.</p></div>
          <div class="value-card"><div class="value-icon">🌍</div><h3>Inclusion</h3><p>We welcome students from every department and background. Diversity of thought makes our community stronger and more innovative.</p></div>
        </div>
      </section>

      <section class="story-section">
        <div class="story-text">
          <p class="section-label">Our Story</p>
          <h2>From a Small Group to a Thriving Community</h2>
          <p>KUET Career Club was founded in 2019 by a small group of final-year students who felt there was no structured support for career development on campus. What started as informal resume-review sessions in the library grew into a full-fledged club with hundreds of members.</p>
          <p>Today, we host career fairs that attract 20+ companies, run year-round workshops, and maintain an active alumni network spanning 15+ countries.</p>
        </div>
        <div class="story-visual">
          <div class="story-card">
            <div class="story-card-inner"><span class="story-year">2019</span><span class="story-milestone">Club Founded</span></div>
            <div class="story-card-inner"><span class="story-year">2021</span><span class="story-milestone">First Career Fair</span></div>
            <div class="story-card-inner"><span class="story-year">2023</span><span class="story-milestone">500+ Members</span></div>
            <div class="story-card-inner"><span class="story-year">2026</span><span class="story-milestone">20+ Partner Companies</span></div>
          </div>
        </div>
      </section>

      <section class="team-section">
        <p class="section-label">Registered Members</p>
        <div class="team-grid" id="team-grid">
          <?php foreach ($registeredMembers as $member): ?>
            <?php
              $displayRole = $member['role'] === 'admin' ? 'Admin' : 'Member';
              $label = $member['role'] === 'admin' ? 'admin' : 'member';
              $city = 'kuet';
            ?>
            <div class="team-card" data-name="<?php echo htmlspecialchars($member['name']); ?>" data-city="<?php echo htmlspecialchars($city); ?>" data-type="<?php echo htmlspecialchars($label); ?>">
              <div class="team-avatar">
                <img src="flat-design-young-person-avatar-profile-round-circle-icon-vector.jpg" alt="<?php echo htmlspecialchars($member['name']); ?>" />
              </div>
              <div class="team-info">
                <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                <span class="team-role"><?php echo htmlspecialchars($displayRole); ?></span>
                <p><?php echo htmlspecialchars($member['email']); ?> · ID: <?php echo htmlspecialchars($member['student_id']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="about-cta">
        <h2>Ready to Join Us?</h2>
        <p>Become a member and get access to exclusive events, resources, and a community of ambitious KUET students.</p>
        <div class="hero-actions" style="justify-content:center;">
          <a href="signup.php" class="btn btn-primary">Join the Club</a>
          <a href="contact.php" class="btn btn-secondary">Get in Touch</a>
        </div>
      </section>
    </main>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>

<?php require_once 'session_config.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resources - KUET Career Club</title>
    <meta name="description" content="Curated career resources for KUET students." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <section class="events-hero">
        <span class="eyebrow">📂 Member Resources</span>
        <h1>Everything You Need<br>to Land Your Dream Job</h1>
        <p>Curated tools, guides, and platforms handpicked by the KUET Career Club team and alumni.</p>
        <div class="filter-bar">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="resume">Resume</button>
          <button class="filter-btn" data-filter="jobs">Job Boards</button>
          <button class="filter-btn" data-filter="interview">Interview Prep</button>
          <button class="filter-btn" data-filter="learning">Learning</button>
          <button class="filter-btn" data-filter="networking">Networking</button>
        </div>
      </section>

      <div class="stats-row">
        <div class="stat-item"><span class="num">40+</span><span>Resources</span></div>
        <div class="stat-item"><span class="num">6</span><span>Categories</span></div>
        <div class="stat-item"><span class="num">Weekly</span><span>Updates</span></div>
        <div class="stat-item"><span class="num">Free</span><span>For Members</span></div>
      </div>

      <section class="events-section-wrapper">
        <p class="section-label">Resume &amp; CV</p>
        <div class="resource-grid" id="resource-grid">
          <div class="resource-card" data-cat="resume"><div class="resource-icon-wrap res-blue">📄</div><div class="resource-body"><div class="resource-tag">Resume</div><h3>KCC Resume Template</h3><p>A clean, ATS-friendly resume template designed for KUET students.</p><a href="#" class="resource-link">Download Template →</a></div></div>
          <div class="resource-card" data-cat="resume"><div class="resource-icon-wrap res-green">✍️</div><div class="resource-body"><div class="resource-tag">Resume</div><h3>Resume Review Checklist</h3><p>A 30-point checklist to self-review your resume before submitting.</p><a href="#" class="resource-link">View Checklist →</a></div></div>
          <div class="resource-card" data-cat="resume"><div class="resource-icon-wrap res-purple">🎨</div><div class="resource-body"><div class="resource-tag">Resume</div><h3>Overleaf LaTeX CV</h3><p>Professional LaTeX CV templates on Overleaf.</p><a href="https://www.overleaf.com/gallery/tagged/cv" target="_blank" rel="noopener" class="resource-link">Open Overleaf →</a></div></div>
          <div class="resource-card" data-cat="jobs"><div class="resource-icon-wrap res-pink">💼</div><div class="resource-body"><div class="resource-tag">Job Board</div><h3>LinkedIn Jobs</h3><p>The most widely used platform for professional job hunting.</p><a href="https://www.linkedin.com/jobs/" target="_blank" rel="noopener" class="resource-link">Browse Jobs →</a></div></div>
          <div class="resource-card" data-cat="jobs"><div class="resource-icon-wrap res-orange">🌐</div><div class="resource-body"><div class="resource-tag">Job Board</div><h3>Bdjobs</h3><p>Bangladesh's largest job portal for local internships and entry-level tech roles.</p><a href="https://www.bdjobs.com" target="_blank" rel="noopener" class="resource-link">Browse Jobs →</a></div></div>
          <div class="resource-card" data-cat="jobs"><div class="resource-icon-wrap res-blue">🔍</div><div class="resource-body"><div class="resource-tag">Job Board</div><h3>Glassdoor</h3><p>Find jobs and read company reviews.</p><a href="https://www.glassdoor.com" target="_blank" rel="noopener" class="resource-link">Explore →</a></div></div>
          <div class="resource-card" data-cat="interview"><div class="resource-icon-wrap res-green">🧠</div><div class="resource-body"><div class="resource-tag">Interview Prep</div><h3>LeetCode</h3><p>The go-to platform for DSA practice. Start with the Blind 75 list.</p><a href="https://leetcode.com" target="_blank" rel="noopener" class="resource-link">Start Practicing →</a></div></div>
          <div class="resource-card" data-cat="interview"><div class="resource-icon-wrap res-purple">💬</div><div class="resource-body"><div class="resource-tag">Interview Prep</div><h3>STAR Method Guide</h3><p>A KCC guide to answering behavioral interview questions.</p><a href="#" class="resource-link">Read Guide →</a></div></div>
          <div class="resource-card" data-cat="interview"><div class="resource-icon-wrap res-pink">🎤</div><div class="resource-body"><div class="resource-tag">Interview Prep</div><h3>Pramp</h3><p>Free peer-to-peer mock interviews with real-time feedback.</p><a href="https://www.pramp.com" target="_blank" rel="noopener" class="resource-link">Book a Mock →</a></div></div>
          <div class="resource-card" data-cat="learning"><div class="resource-icon-wrap res-orange">🗺️</div><div class="resource-body"><div class="resource-tag">Learning</div><h3>Developer Roadmaps</h3><p>Visual roadmaps for frontend, backend, DevOps, ML, and more.</p><a href="https://roadmap.sh" target="_blank" rel="noopener" class="resource-link">View Roadmaps →</a></div></div>
          <div class="resource-card" data-cat="learning"><div class="resource-icon-wrap res-blue">🎓</div><div class="resource-body"><div class="resource-tag">Learning</div><h3>Coursera &amp; edX</h3><p>Top university courses online — many free to audit.</p><a href="https://www.coursera.org" target="_blank" rel="noopener" class="resource-link">Browse Courses →</a></div></div>
          <div class="resource-card" data-cat="learning"><div class="resource-icon-wrap res-green">⚡</div><div class="resource-body"><div class="resource-tag">Learning</div><h3>freeCodeCamp</h3><p>Completely free, self-paced coding curriculum with certifications.</p><a href="https://www.freecodecamp.org" target="_blank" rel="noopener" class="resource-link">Start Learning →</a></div></div>
          <div class="resource-card" data-cat="networking"><div class="resource-icon-wrap res-blue">🔗</div><div class="resource-body"><div class="resource-tag">Networking</div><h3>LinkedIn Profile Guide</h3><p>KCC's step-by-step guide to building a recruiter-magnet LinkedIn profile.</p><a href="#" class="resource-link">Read Guide →</a></div></div>
          <div class="resource-card" data-cat="networking"><div class="resource-icon-wrap res-purple">👥</div><div class="resource-body"><div class="resource-tag">Networking</div><h3>KCC Alumni Network</h3><p>Connect directly with KUET alumni working at top companies.</p><a href="signup.php" class="resource-link">Join to Access →</a></div></div>
          <div class="resource-card" data-cat="networking"><div class="resource-icon-wrap res-pink">📧</div><div class="resource-body"><div class="resource-tag">Networking</div><h3>Cold Email Templates</h3><p>Proven email templates for reaching out to professionals and recruiters.</p><a href="#" class="resource-link">Download Templates →</a></div></div>
        </div>
        <p class="no-results" id="no-res-results">No resources found for this category.</p>
      </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script>
      const filterBtns = document.querySelectorAll('.filter-btn');
      const cards = document.querySelectorAll('.resource-card');
      const noResults = document.getElementById('no-res-results');
      filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          filterBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          const filter = btn.dataset.filter;
          let visible = 0;
          cards.forEach(card => {
            const show = filter === 'all' || card.dataset.cat === filter;
            card.style.display = show ? 'flex' : 'none';
            if (show) visible++;
          });
          noResults.style.display = visible === 0 ? 'block' : 'none';
        });
      });
    </script>
  </body>
</html>

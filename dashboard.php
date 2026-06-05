<?php
require_once 'session_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT name, email, student_id, role, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch registered events
$stmt = $pdo->prepare("
    SELECT e.title, e.type, e.status, e.event_date, e.location, er.registered_at
    FROM event_registrations er
    JOIN events e ON er.event_id = e.id
    WHERE er.user_id = ?
    ORDER BY e.event_date DESC
");
$stmt->execute([$user_id]);
$my_events = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - KUET Career Club</title>
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <section class="about-hero">
        <span class="eyebrow">👋 Welcome back</span>
        <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        <p>Manage your profile and registered events</p>
      </section>

      <div class="stats-row">
        <div class="stat-item"><span class="num"><?php echo htmlspecialchars($user['student_id']); ?></span><span>Student ID</span></div>
        <div class="stat-item"><span class="num"><?php echo ucfirst($user['role']); ?></span><span>Role</span></div>
        <div class="stat-item"><span class="num"><?php echo count($my_events); ?></span><span>Events Joined</span></div>
        <div class="stat-item"><span class="num"><?php echo date('M Y', strtotime($user['created_at'])); ?></span><span>Member Since</span></div>
      </div>

      <section class="values-section">
        <p class="section-label">Profile Info</p>
        <div class="values-grid">
          <div class="value-card">
            <div class="value-icon">👤</div>
            <h3>Name</h3>
            <p><?php echo htmlspecialchars($user['name']); ?></p>
          </div>
          <div class="value-card">
            <div class="value-icon">📧</div>
            <h3>Email</h3>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
          </div>
          <div class="value-card">
            <div class="value-icon">🎓</div>
            <h3>Student ID</h3>
            <p><?php echo htmlspecialchars($user['student_id']); ?></p>
          </div>
          <div class="value-card">
            <div class="value-icon">📅</div>
            <h3>Joined</h3>
            <p><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
          </div>
        </div>
      </section>

      <section class="team-section">
        <p class="section-label">My Registered Events (<?php echo count($my_events); ?>)</p>
        <?php if (empty($my_events)): ?>
          <p style="text-align:center; color:#9cb8ff; padding:2rem;">You haven't registered for any events yet. <a href="events.php" class="form-link">Browse events →</a></p>
        <?php else: ?>
          <div class="events-grid">
            <?php foreach ($my_events as $ev): ?>
              <div class="event-card">
                <div class="event-banner <?php echo htmlspecialchars($ev['type']); ?> <?php echo $ev['status'] === 'past' ? 'past-banner' : ''; ?>">
                  <span class="status-badge <?php echo $ev['status']; ?>"><?php echo ucfirst($ev['status']); ?></span>
                  <span class="event-tag"><?php echo ucfirst($ev['type']); ?></span>
                </div>
                <div class="event-body">
                  <h3><?php echo htmlspecialchars($ev['title']); ?></h3>
                  <div class="event-meta">
                    <span><span class="icon">📅</span> <?php echo date('M j, Y · g:i A', strtotime($ev['event_date'])); ?></span>
                    <span><span class="icon">📍</span> <?php echo htmlspecialchars($ev['location']); ?></span>
                    <span><span class="icon">✅</span> Registered <?php echo date('M j, Y', strtotime($ev['registered_at'])); ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    </main>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>

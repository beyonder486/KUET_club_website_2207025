<?php
require_once 'session_config.php';

$logged_in = isset($_SESSION['user_id']);

// Handle event registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_event'])) {
    if (!$logged_in) {
        header("Location: login.php");
        exit;
    }
    $event_id = (int) $_POST['event_id'];
    // Check if already registered
    $stmt = $pdo->prepare("SELECT id FROM event_registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$_SESSION['user_id'], $event_id]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $event_id]);
    }
    header("Location: events.php?registered=1");
    exit;
}

// Fetch all events
$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();

// Get user's registered event IDs
$my_regs = [];
if ($logged_in) {
    $stmt = $pdo->prepare("SELECT event_id FROM event_registrations WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $my_regs = array_column($stmt->fetchAll(), 'event_id');
}

// Compute seats left for each event
function seatsLeft($pdo, $event) {
    if ($event['total_seats'] == 0) return 'Unlimited';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM event_registrations WHERE event_id = ?");
    $stmt->execute([$event['id']]);
    $taken = $stmt->fetchColumn();
    return max(0, $event['total_seats'] - $taken);
}

$upcoming = array_filter($events, fn($e) => $e['status'] === 'upcoming');
$past     = array_filter($events, fn($e) => $e['status'] === 'past');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events - KUET Career Club</title>
    <meta name="description" content="Upcoming and past events by KUET Career Club." />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <section class="events-hero">
        <span class="eyebrow">📅 What's On</span>
        <h1>Club Events</h1>
        <p>Workshops, seminars, career fairs &amp; networking nights — all in one place.</p>

        <?php if (isset($_GET['registered'])): ?>
          <div class="form-alert form-success" style="max-width:400px; margin:1rem auto;">Successfully registered!</div>
        <?php endif; ?>

        <div class="filter-bar">
          <button class="filter-btn active" data-filter="all" id="filter-all">All Events</button>
          <button class="filter-btn" data-filter="upcoming" id="filter-upcoming">Upcoming</button>
          <button class="filter-btn" data-filter="workshop" id="filter-workshop">Workshops</button>
          <button class="filter-btn" data-filter="seminar" id="filter-seminar">Seminars</button>
          <button class="filter-btn" data-filter="fair" id="filter-fair">Career Fairs</button>
          <button class="filter-btn" data-filter="networking" id="filter-networking">Networking</button>
        </div>
      </section>

      <div class="stats-row">
        <div class="stat-item"><span class="num"><?php echo count($events); ?></span><span>Events Hosted</span></div>
        <div class="stat-item"><span class="num"><?php echo count($upcoming); ?></span><span>Upcoming</span></div>
        <div class="stat-item"><span class="num"><?php echo count($past); ?></span><span>Past Events</span></div>
      </div>

      <section class="events-section-wrapper">
        <p class="section-label">All Events</p>
        <div class="events-grid" id="events-grid">
          <?php foreach ($events as $ev):
            $is_past   = $ev['status'] === 'past';
            $is_regged = in_array($ev['id'], $my_regs);
            $seats     = seatsLeft($pdo, $ev);
          ?>
          <div class="event-card" data-type="<?php echo $ev['type']; ?>" data-status="<?php echo $ev['status']; ?>">
            <div class="event-banner <?php echo $ev['type']; ?> <?php echo $is_past ? 'past-banner' : ''; ?>">
              <?php if ($is_past): ?>
                <span class="status-badge past">Past</span>
              <?php elseif ($is_regged): ?>
                <span class="status-badge open" style="background:rgba(43,212,255,0.2);color:#2bd4ff;border:1px solid rgba(43,212,255,0.4);">Registered ✓</span>
              <?php else: ?>
                <span class="status-badge open">Registration Open</span>
              <?php endif; ?>
              <span class="event-tag"><?php echo ucfirst($ev['type']); ?></span>
            </div>
            <div class="event-body">
              <h3><?php echo htmlspecialchars($ev['title']); ?></h3>
              <div class="event-meta">
                <span><span class="icon">📅</span> <?php echo date('M j, Y · g:i A', strtotime($ev['event_date'])); ?></span>
                <span><span class="icon">📍</span> <?php echo htmlspecialchars($ev['location']); ?></span>
                <?php if ($ev['host']): ?>
                  <span><span class="icon">👤</span> <?php echo htmlspecialchars($ev['host']); ?></span>
                <?php endif; ?>
              </div>
              <p><?php echo htmlspecialchars($ev['description']); ?></p>
              <div class="event-footer">
                <?php if ($is_past): ?>
                  <div class="seats ended">Event has ended</div>
                  <span class="register-btn disabled">Closed</span>
                <?php elseif ($is_regged): ?>
                  <div class="seats"><span>You're in!</span></div>
                  <span class="register-btn disabled">Registered</span>
                <?php else: ?>
                  <div class="seats">Seats: <span><?php echo $seats; ?> <?php echo is_int($seats) ? 'left' : ''; ?></span></div>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="event_id" value="<?php echo $ev['id']; ?>" />
                    <button type="submit" name="register_event" class="register-btn">Register</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="no-results" id="no-results">No events found for this filter.</p>
      </section>
    </main>
    <?php include 'includes/footer.php'; ?>

    <script>
      const filterBtns = document.querySelectorAll('.filter-btn');
      const cards = document.querySelectorAll('.event-card');
      const noResults = document.getElementById('no-results');
      filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          filterBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          const filter = btn.dataset.filter;
          let visible = 0;
          cards.forEach(card => {
            const type   = card.dataset.type;
            const status = card.dataset.status;
            const show   = filter === 'all' || filter === type || filter === status;
            card.style.display = show ? 'flex' : 'none';
            if (show) visible++;
          });
          noResults.style.display = visible === 0 ? 'block' : 'none';
        });
      });
    </script>
  </body>
</html>

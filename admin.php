<?php
require_once 'session_config.php';

// ── Access guard ────────────────────────────────────────────────────
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$flash = '';
$flash_type = 'success'; // 'success' | 'error'
$active_tab = $_GET['tab'] ?? 'messages';

// ── POST handler ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── Messages ─────────────────────────────────────────────────────
    if ($action === 'delete_message') {
        $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([(int)$_POST['id']]);
        $flash = 'Message deleted.';
        header("Location: admin.php?tab=messages&flash=" . urlencode($flash));
        exit;
    }

    // ── Create event ─────────────────────────────────────────────────
    if ($action === 'create_event') {
        $title       = trim($_POST['title']);
        $type        = $_POST['type'];
        $status      = $_POST['status'];
        $event_date  = $_POST['event_date'];
        $location    = trim($_POST['location']);
        $host        = trim($_POST['host']);
        $description = trim($_POST['description']);
        $total_seats = (int)$_POST['total_seats'];

        if ($title && $type && $status && $event_date && $location) {
            $pdo->prepare("INSERT INTO events (title,type,status,event_date,location,host,description,total_seats)
                           VALUES (?,?,?,?,?,?,?,?)")
                ->execute([$title,$type,$status,$event_date,$location,$host,$description,$total_seats]);
            $flash = "Event \"$title\" created successfully.";
        } else {
            $flash = 'Please fill in all required fields.';
            $flash_type = 'error';
        }
        header("Location: admin.php?tab=events&flash=" . urlencode($flash) . "&ft=$flash_type");
        exit;
    }

    // ── Update event ─────────────────────────────────────────────────
    if ($action === 'update_event') {
        $id          = (int)$_POST['id'];
        $title       = trim($_POST['title']);
        $type        = $_POST['type'];
        $status      = $_POST['status'];
        $event_date  = $_POST['event_date'];
        $location    = trim($_POST['location']);
        $host        = trim($_POST['host']);
        $description = trim($_POST['description']);
        $total_seats = (int)$_POST['total_seats'];

        $pdo->prepare("UPDATE events SET title=?,type=?,status=?,event_date=?,location=?,host=?,description=?,total_seats=? WHERE id=?")
            ->execute([$title,$type,$status,$event_date,$location,$host,$description,$total_seats,$id]);
        $flash = "Event updated successfully.";
        header("Location: admin.php?tab=events&flash=" . urlencode($flash));
        exit;
    }

    // ── Delete event ─────────────────────────────────────────────────
    if ($action === 'delete_event') {
        $id = (int)$_POST['id'];
        $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$id]);
        $flash = 'Event deleted.';
        header("Location: admin.php?tab=events&flash=" . urlencode($flash));
        exit;
    }
}

// ── Read flash from redirect ─────────────────────────────────────────
if (isset($_GET['flash'])) {
    $flash = htmlspecialchars($_GET['flash']);
    $flash_type = $_GET['ft'] ?? 'success';
}

// ── Fetch data ────────────────────────────────────────────────────────
$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY sent_at DESC")->fetchAll();
$events   = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();

// Registrations: each event with list of registered users
$registrations = $pdo->query("
    SELECT e.id AS event_id, e.title AS event_title, e.event_date, e.type,
           u.name AS user_name, u.email AS user_email, u.student_id,
           er.registered_at
    FROM event_registrations er
    JOIN events e ON er.event_id = e.id
    JOIN users  u ON er.user_id  = u.id
    ORDER BY e.event_date DESC, er.registered_at DESC
")->fetchAll();

// Group registrations by event
$reg_by_event = [];
foreach ($registrations as $r) {
    $reg_by_event[$r['event_id']]['title']      = $r['event_title'];
    $reg_by_event[$r['event_id']]['event_date'] = $r['event_date'];
    $reg_by_event[$r['event_id']]['type']       = $r['type'];
    $reg_by_event[$r['event_id']]['users'][]    = $r;
}

// Stats
$total_messages = count($messages);
$today = date('Y-m-d');
$today_msgs = array_reduce($messages, fn($c,$m) => $c + (str_starts_with($m['sent_at'], $today) ? 1 : 0), 0);
$upcoming_count = count(array_filter($events, fn($e) => $e['status'] === 'upcoming'));
$total_regs = count($registrations);

// If editing an event, fetch it
$editing_event = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editing_event = $stmt->fetch();
    $active_tab = 'events';
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard · KUET Career Club</title>
    <meta name="description" content="Admin dashboard — manage events, messages and registrations." />
    <link rel="stylesheet" href="styles.css" />
    <style>
      /* ── Admin Badge ── */
      .admin-hero {
        text-align: center;
        padding: 2.5rem 2rem 2rem;
        background: rgba(15,25,48,0.85);
        border: 1px solid rgba(255,255,255,0.14);
      }
      .admin-hero h1 { font-size: clamp(1.8rem,3vw,2.6rem); color:#f7f9ff; margin-bottom:.3rem; }
      .admin-hero p  { color:#9cb8ff; font-size:.95rem; }
      .admin-badge {
        display:inline-flex; align-items:center; gap:.4rem;
        background:rgba(255,80,80,.15); border:1px solid rgba(255,80,80,.35);
        color:#ff8080; font-size:.75rem; font-weight:700; letter-spacing:.12em;
        text-transform:uppercase; padding:.28rem .8rem; border-radius:999px; margin-bottom:.8rem;
      }

      /* ── Tabs ── */
      .admin-tabs {
        display:flex; gap:.5rem; flex-wrap:wrap;
        background:rgba(255,255,255,.03);
        border:1px solid rgba(255,255,255,.09);
        border-radius:16px; padding:.5rem; margin-bottom:1.8rem;
      }
      .tab-btn {
        flex:1; min-width:120px; padding:.65rem 1.2rem;
        border-radius:10px; border:none; background:transparent;
        color:#6b7fa8; font-size:.9rem; font-weight:600;
        font-family:inherit; cursor:pointer;
        transition:background .18s, color .18s;
        display:flex; align-items:center; justify-content:center; gap:.45rem;
      }
      .tab-btn:hover { background:rgba(255,255,255,.07); color:#c8d7ff; }
      .tab-btn.active {
        background:var(--accent-grad); color:#020817;
        box-shadow:0 2px 16px rgba(43,212,255,.25);
      }
      .tab-btn .badge {
        background:rgba(0,0,0,.25); color:inherit;
        border-radius:999px; font-size:.72rem; padding:.1rem .5rem;
        font-weight:700;
      }
      .tab-btn.active .badge { background:rgba(0,0,0,.2); }
      .tab-panel { display:none; }
      .tab-panel.active { display:block; }

      /* ── Flash ── */
      .flash {
        border-radius:12px; padding:.75rem 1.2rem;
        font-size:.9rem; margin-bottom:1.5rem;
        animation: fadein .3s ease;
      }
      .flash.success { background:rgba(43,255,130,.1); border:1px solid rgba(43,255,130,.3); color:#2bff82; }
      .flash.error   { background:rgba(255,80,80,.1);  border:1px solid rgba(255,80,80,.3);  color:#ff8080; }
      @keyframes fadein { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }

      /* ── Table shared ── */
      .table-wrap {
        background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1);
        border-radius:20px; overflow:hidden; overflow-x:auto;
      }
      table { width:100%; border-collapse:collapse; font-size:.88rem; min-width:560px; }
      thead tr { background:rgba(59,131,255,.1); border-bottom:1px solid rgba(255,255,255,.1); }
      thead th { padding:.9rem 1.1rem; text-align:left; font-size:.72rem; font-weight:700;
                 letter-spacing:.12em; text-transform:uppercase; color:#82b7ff; }
      tbody tr { border-bottom:1px solid rgba(255,255,255,.06); transition:background .15s; }
      tbody tr:last-child { border-bottom:none; }
      tbody tr:hover { background:rgba(255,255,255,.035); }
      tbody td { padding:.85rem 1.1rem; color:#c8d7ff; vertical-align:middle; }
      .td-primary { color:#f0f4ff; font-weight:600; }
      .td-muted   { color:#6b7fa8; font-size:.8rem; }
      .td-email a { color:#6fc7ff; text-decoration:none; }
      .td-email a:hover { color:#2bd4ff; }
      .table-wrap .status-badge {
        position:static; display:inline-flex; align-items:center;
        white-space:nowrap; line-height:1.2;
      }

      /* ── Action buttons ── */
      .act-btn {
        padding:.32rem .8rem; border-radius:7px; font-size:.78rem; font-weight:600;
        cursor:pointer; font-family:inherit; border:1px solid; transition:background .15s;
        text-decoration:none; display:inline-block;
      }
      .act-btn.del  { border-color:rgba(255,80,80,.3);  background:rgba(255,80,80,.1);  color:#ff8080; }
      .act-btn.del:hover  { background:rgba(255,80,80,.22); }
      .act-btn.edit { border-color:rgba(43,212,255,.3); background:rgba(43,212,255,.1); color:#2bd4ff; }
      .act-btn.edit:hover { background:rgba(43,212,255,.2); }
      .act-group { display:flex; gap:.4rem; flex-wrap:wrap; }
      .act-group form { margin:0; display:inline; }

      /* ── Empty state ── */
      .empty-state { text-align:center; padding:3.5rem 2rem; color:#6b7fa8; }
      .empty-state .ei { font-size:2.8rem; margin-bottom:.8rem; }

      /* ── Event form ── */
      .event-form-section { padding:0; background:transparent; border:none; margin-bottom:1.8rem; }
      .form-card {
        background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.12);
        border-radius:20px; padding:2rem;
      }
      .form-card h2 { color:#f0f4ff; font-size:1.15rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:.5rem; }
      .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
      .form-grid .full { grid-column:1/-1; }
      .form-field { display:flex; flex-direction:column; gap:.35rem; }
      .form-field label { font-size:.82rem; font-weight:600; color:#c8d7ff; }
      .form-field input,
      .form-field select,
      .form-field textarea {
        background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.14);
        border-radius:10px; color:#e6ecff; font-size:.9rem; font-family:inherit;
        padding:.65rem .9rem; outline:none; transition:border-color .2s, box-shadow .2s;
        width:100%; margin:0;
      }
      .form-field select option { background:#0d1a35; }
      .form-field input:focus,
      .form-field select:focus,
      .form-field textarea:focus {
        border-color:rgba(59,131,255,.6);
        box-shadow:0 0 0 3px rgba(59,131,255,.12);
      }
      .form-field textarea { resize:vertical; min-height:90px; }
      .form-actions { display:flex; gap:.8rem; margin-top:1.2rem; flex-wrap:wrap; }
      .btn-save {
        padding:.65rem 1.8rem; border-radius:999px; border:none;
        background:var(--accent-grad); color:#020817; font-weight:700; font-size:.9rem;
        cursor:pointer; font-family:inherit;
        transition:transform .18s, box-shadow .18s;
        box-shadow:0 4px 20px rgba(43,212,255,.25);
      }
      .btn-save:hover { transform:translateY(-2px); box-shadow:0 6px 28px rgba(43,212,255,.4); }
      .btn-cancel {
        padding:.65rem 1.4rem; border-radius:999px;
        background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.14);
        color:#c8d7ff; font-size:.9rem; font-weight:600; cursor:pointer; font-family:inherit;
        text-decoration:none; display:inline-flex; align-items:center;
        transition:background .18s;
      }
      .btn-cancel:hover { background:rgba(255,255,255,.1); }

      /* ── Registration accordion ── */
      .reg-event-block {
        background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.09);
        border-radius:16px; overflow:hidden; margin-bottom:1rem;
      }
      .reg-event-header {
        display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap;
        gap:.5rem; padding:1rem 1.3rem; cursor:pointer;
        background:rgba(255,255,255,.03);
        transition:background .15s;
      }
      .reg-event-header:hover { background:rgba(255,255,255,.06); }
      .reg-event-header h3 { color:#f0f4ff; font-size:.98rem; }
      .reg-event-header .reg-meta { color:#9cb8ff; font-size:.82rem; }
      .reg-count-badge {
        background:rgba(43,212,255,.15); border:1px solid rgba(43,212,255,.3);
        color:#2bd4ff; border-radius:999px; padding:.2rem .7rem; font-size:.78rem; font-weight:700;
      }
      .reg-toggle-icon { color:#6b7fa8; font-size:.85rem; transition:transform .2s; }
      .reg-event-body { display:none; padding:0 1.3rem 1rem; }
      .reg-event-body.open { display:block; }
      .reg-event-block.expanded .reg-toggle-icon { transform:rotate(180deg); }

      /* type tag reuse */
      .type-tag {
        padding:.2rem .65rem; border-radius:999px; font-size:.72rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.06em;
      }
      .type-tag.workshop   { background:rgba(59,131,255,.2); color:#7ab2ff; }
      .type-tag.seminar    { background:rgba(43,170,126,.2); color:#2bda9e; }
      .type-tag.fair       { background:rgba(170,43,126,.2); color:#da7eb2; }
      .type-tag.networking { background:rgba(170,139,43,.2); color:#dabb7e; }

      /* section-label reuse, transparent wrapper */
      .nosec { padding:0; background:transparent; border:none; }

      /* Admin stats display - prevent overlap */
      .stats-admin { display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:1.5rem; }
      .stats-admin .stat-item span:last-child { font-size:.75rem; line-height:1.4; word-break:break-word; }

      @media(max-width:640px) {
        .form-grid { grid-template-columns:1fr; }
        .form-grid .full { grid-column:1; }
        .admin-tabs { flex-direction:column; }
        .tab-btn { min-width:unset; }
      }
    </style>
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>

      <!-- Hero -->
      <section class="admin-hero">
        <div class="admin-badge">🛡 Admin Panel</div>
        <h1>Dashboard</h1>
        <p>Manage events, view contact messages, and track registrations</p>
      </section>

      <!-- Global stats -->
      <div class="stats-row stats-admin">
        <div class="stat-item"><span class="num"><?= count($events) ?></span><span>Total<br>Events</span></div>
        <div class="stat-item"><span class="num"><?= $upcoming_count ?></span><span>Coming<br>Up</span></div>
        <div class="stat-item"><span class="num"><?= $total_regs ?></span><span>Event<br>Signups</span></div>
        <div class="stat-item"><span class="num"><?= $total_messages ?></span><span>New<br>Messages</span></div>
      </div>

      <!-- Flash -->
      <?php if ($flash): ?>
        <div class="flash <?= $flash_type === 'error' ? 'error' : 'success' ?>">
          <?= $flash_type === 'error' ? '⚠️' : '✅' ?> <?= $flash ?>
        </div>
      <?php endif; ?>

      <!-- Tabs -->
      <div class="admin-tabs" role="tablist">
        <button class="tab-btn <?= $active_tab === 'events'        ? 'active' : '' ?>"
                onclick="switchTab('events')" id="tab-events" role="tab">
          📅 Events <span class="badge"><?= count($events) ?></span>
        </button>
        <button class="tab-btn <?= $active_tab === 'registrations' ? 'active' : '' ?>"
                onclick="switchTab('registrations')" id="tab-registrations" role="tab">
          ✅ Registrations <span class="badge"><?= $total_regs ?></span>
        </button>
        <button class="tab-btn <?= $active_tab === 'messages'      ? 'active' : '' ?>"
                onclick="switchTab('messages')" id="tab-messages" role="tab">
          📬 Messages <span class="badge"><?= $total_messages ?></span>
        </button>
      </div>


      <!-- ════════════════════════════════════════════════════════════
           TAB: EVENTS
      ════════════════════════════════════════════════════════════ -->
      <div class="tab-panel <?= $active_tab === 'events' ? 'active' : '' ?>" id="panel-events">

        <!-- Create / Edit form -->
        <section class="nosec event-form-section">
          <div class="form-card">
            <h2><?= $editing_event ? '✏️ Edit Event' : '➕ Create New Event' ?></h2>
            <form method="POST" action="admin.php">
              <input type="hidden" name="action" value="<?= $editing_event ? 'update_event' : 'create_event' ?>" />
              <?php if ($editing_event): ?>
                <input type="hidden" name="id" value="<?= (int)$editing_event['id'] ?>" />
              <?php endif; ?>

              <div class="form-grid">
                <div class="form-field full">
                  <label for="ev-title">Event Title *</label>
                  <input type="text" id="ev-title" name="title" required
                         placeholder="e.g. Resume Building Masterclass"
                         value="<?= $editing_event ? htmlspecialchars($editing_event['title']) : '' ?>" />
                </div>

                <div class="form-field">
                  <label for="ev-type">Type *</label>
                  <select id="ev-type" name="type" required>
                    <?php foreach (['workshop','seminar','fair','networking'] as $t): ?>
                      <option value="<?= $t ?>" <?= ($editing_event && $editing_event['type']===$t) ? 'selected' : '' ?>>
                        <?= ucfirst($t) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-field">
                  <label for="ev-status">Status *</label>
                    <select id="ev-status" name="status" required>
                      <option value="upcoming" <?= ($editing_event && $editing_event['status']==='upcoming') ? 'selected' : '' ?>>Upcoming events</option>
                      <option value="past"     <?= ($editing_event && $editing_event['status']==='past')     ? 'selected' : '' ?>>Past</option>
                    </select>
                </div>

                <div class="form-field">
                  <label for="ev-date">Date & Time *</label>
                  <input type="datetime-local" id="ev-date" name="event_date" required
                         value="<?= $editing_event ? date('Y-m-d\TH:i', strtotime($editing_event['event_date'])) : '' ?>" />
                </div>

                <div class="form-field">
                  <label for="ev-seats">Total Seats (0 = Unlimited)</label>
                  <input type="number" id="ev-seats" name="total_seats" min="0"
                         value="<?= $editing_event ? (int)$editing_event['total_seats'] : '0' ?>" />
                </div>

                <div class="form-field full">
                  <label for="ev-location">Location *</label>
                  <input type="text" id="ev-location" name="location" required
                         placeholder="e.g. ECE Seminar Hall, KUET"
                         value="<?= $editing_event ? htmlspecialchars($editing_event['location']) : '' ?>" />
                </div>

                <div class="form-field full">
                  <label for="ev-host">Host / Speaker</label>
                  <input type="text" id="ev-host" name="host"
                         placeholder="e.g. Career Club Core Team"
                         value="<?= $editing_event ? htmlspecialchars($editing_event['host']) : '' ?>" />
                </div>

                <div class="form-field full">
                  <label for="ev-desc">Description</label>
                  <textarea id="ev-desc" name="description"
                            placeholder="Brief description of the event..."><?= $editing_event ? htmlspecialchars($editing_event['description']) : '' ?></textarea>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-save">
                  <?= $editing_event ? '💾 Save Changes' : '✨ Create Event' ?>
                </button>
                <?php if ($editing_event): ?>
                  <a href="admin.php?tab=events" class="btn-cancel">✕ Cancel</a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </section>

        <!-- Events list -->
        <section class="nosec">
          <p class="section-label">Events (<?= count($events) ?>)</p>
          <?php if (empty($events)): ?>
            <div class="table-wrap">
              <div class="empty-state"><div class="ei">📅</div><p>No events yet. Create one above.</p></div>
            </div>
          <?php else: ?>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Seats</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($events as $ev): ?>
                    <tr>
                      <td class="td-primary"><?= htmlspecialchars($ev['title']) ?></td>
                      <td><span class="type-tag <?= $ev['type'] ?>"><?= ucfirst($ev['type']) ?></span></td>
                      <td>
                        <?php if ($ev['status'] === 'upcoming'): ?>
                          <span class="status-badge upcoming">Upcoming event</span>
                        <?php else: ?>
                          <span class="status-badge past">Past</span>
                        <?php endif; ?>
                      </td>
                      <td class="td-muted"><?= date('M j, Y', strtotime($ev['event_date'])) ?></td>
                      <td class="td-muted"><?= htmlspecialchars($ev['location']) ?></td>
                      <td class="td-muted"><?= $ev['total_seats'] == 0 ? '∞' : $ev['total_seats'] ?></td>
                      <td>
                        <div class="act-group">
                          <a href="admin.php?edit=<?= $ev['id'] ?>" class="act-btn edit">✏️ Edit</a>
                          <form method="POST" action="admin.php"
                                onsubmit="return confirm('Delete event: <?= addslashes(htmlspecialchars($ev['title'])) ?>?')">
                            <input type="hidden" name="action" value="delete_event" />
                            <input type="hidden" name="id" value="<?= (int)$ev['id'] ?>" />
                            <button type="submit" class="act-btn del">🗑 Delete</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </section>
      </div><!-- /panel-events -->


      <!-- ════════════════════════════════════════════════════════════
           TAB: REGISTRATIONS
      ════════════════════════════════════════════════════════════ -->
      <div class="tab-panel <?= $active_tab === 'registrations' ? 'active' : '' ?>" id="panel-registrations">
        <section class="nosec">
          <p class="section-label">Registrations by Event (<?= $total_regs ?> total)</p>

          <?php if (empty($reg_by_event)): ?>
            <div class="table-wrap">
              <div class="empty-state"><div class="ei">📋</div><p>No one has registered for any event yet.</p></div>
            </div>
          <?php else: ?>
            <?php foreach ($reg_by_event as $eid => $group): ?>
              <div class="reg-event-block" id="reg-block-<?= $eid ?>">
                <div class="reg-event-header" onclick="toggleReg(<?= $eid ?>)">
                  <div>
                    <h3><?= htmlspecialchars($group['title']) ?></h3>
                    <span class="reg-meta">
                      <span class="type-tag <?= $group['type'] ?>"><?= ucfirst($group['type']) ?></span>
                      &nbsp;<?= date('M j, Y', strtotime($group['event_date'])) ?>
                    </span>
                  </div>
                  <div style="display:flex;align-items:center;gap:.6rem;">
                    <span class="reg-count-badge"><?= count($group['users']) ?> registered</span>
                    <span class="reg-toggle-icon">▼</span>
                  </div>
                </div>
                <div class="reg-event-body" id="reg-body-<?= $eid ?>">
                  <div class="table-wrap" style="border-radius:10px;margin-top:.6rem;">
                    <table style="min-width:400px;">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Student ID</th>
                          <th>Registered At</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($group['users'] as $i => $u): ?>
                          <tr>
                            <td class="td-muted"><?= $i + 1 ?></td>
                            <td class="td-primary"><?= htmlspecialchars($u['user_name']) ?></td>
                            <td class="td-email"><a href="mailto:<?= htmlspecialchars($u['user_email']) ?>"><?= htmlspecialchars($u['user_email']) ?></a></td>
                            <td class="td-muted"><?= htmlspecialchars($u['student_id']) ?></td>
                            <td class="td-muted"><?= date('M j, Y · g:i A', strtotime($u['registered_at'])) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </section>
      </div><!-- /panel-registrations -->


      <!-- ════════════════════════════════════════════════════════════
           TAB: MESSAGES
      ════════════════════════════════════════════════════════════ -->
      <div class="tab-panel <?= $active_tab === 'messages' ? 'active' : '' ?>" id="panel-messages">
        <section class="nosec">
          <p class="section-label">Inbox (<?= $total_messages ?>)&nbsp;&nbsp;
            <span style="color:#4a5a80;font-size:.75rem;letter-spacing:.05em;"><?= $today_msgs ?> today</span>
          </p>

          <?php if (empty($messages)): ?>
            <div class="table-wrap">
              <div class="empty-state"><div class="ei">📭</div><p>No messages yet.</p></div>
            </div>
          <?php else: ?>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name / Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Sent At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($messages as $i => $msg): ?>
                    <tr>
                      <td class="td-muted"><?= $total_messages - $i ?></td>
                      <td>
                        <div class="td-primary"><?= htmlspecialchars($msg['name']) ?></div>
                        <div class="td-email"><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></div>
                      </td>
                      <td class="td-primary"><?= htmlspecialchars($msg['subject']) ?></td>
                      <td style="max-width:280px;color:#9cb8ff;font-size:.83rem;word-break:break-word;">
                        <?= htmlspecialchars($msg['message']) ?>
                      </td>
                      <td class="td-muted" style="white-space:nowrap;">
                        <?= date('M j, Y', strtotime($msg['sent_at'])) ?><br>
                        <span style="color:#4a5a80;"><?= date('g:i A', strtotime($msg['sent_at'])) ?></span>
                      </td>
                      <td>
                        <form method="POST" action="admin.php"
                              onsubmit="return confirm('Delete message from <?= addslashes(htmlspecialchars($msg['name'])) ?>?')">
                          <input type="hidden" name="action" value="delete_message" />
                          <input type="hidden" name="id" value="<?= (int)$msg['id'] ?>" />
                          <button type="submit" class="act-btn del">🗑 Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </section>
      </div><!-- /panel-messages -->

    </main>
    <?php include 'includes/footer.php'; ?>

    <script>
      // ── Tab switching ──────────────────────────────────────────────
      function switchTab(name) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('panel-' + name).classList.add('active');
        document.getElementById('tab-' + name).classList.add('active');
        history.replaceState(null, '', '?tab=' + name);
      }

      // ── Registration accordion ─────────────────────────────────────
      function toggleReg(id) {
        const block = document.getElementById('reg-block-' + id);
        const body  = document.getElementById('reg-body-'  + id);
        const isOpen = body.classList.contains('open');
        body.classList.toggle('open', !isOpen);
        block.classList.toggle('expanded', !isOpen);
      }
    </script>
  </body>
</html>

-- ================================================
-- KUET Career Club — Database Setup
-- Database name: kc
-- Import this file via phpMyAdmin → kc → Import
-- ================================================

USE kc;

-- ------------------------------------------------
-- 1. Users
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    student_id  VARCHAR(20)   NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,
    role        ENUM('member','admin') DEFAULT 'member',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------
-- 2. Events
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS events (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(200)  NOT NULL,
    type        ENUM('workshop','seminar','fair','networking') NOT NULL,
    status      ENUM('upcoming','past') DEFAULT 'upcoming',
    event_date  DATETIME      NOT NULL,
    location    VARCHAR(200)  NOT NULL,
    host        VARCHAR(150),
    description TEXT,
    total_seats INT           DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------
-- 3. Event Registrations (users ↔ events)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS event_registrations (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    event_id      INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reg (user_id, event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------
-- 4. Contact Messages
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL,
    subject     VARCHAR(200)  NOT NULL,
    message     TEXT          NOT NULL,
    sent_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------
-- 5. Remember Me Tokens (for persistent login cookies)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS remember_tokens (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    token       VARCHAR(64)  NOT NULL UNIQUE,
    expires_at  DATETIME     NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------
-- 6. Seed: Insert the 8 existing events
-- ------------------------------------------------
INSERT INTO events (title, type, status, event_date, location, host, description, total_seats) VALUES
('Resume Building Masterclass',      'workshop',   'upcoming', '2026-05-10 15:00:00', 'ECE Seminar Hall, KUET',      'Career Club Core Team',    'Learn how to craft an ATS-friendly resume that stands out. Covers formatting, keywords, project descriptions, and common mistakes to avoid.', 40),
('Breaking into Big Tech — Insider Tips', 'seminar', 'upcoming', '2026-05-17 17:00:00', 'Online (Zoom)',                'Rahman Ahmed, Google SWE', 'A candid talk from a KUET alumnus working at Google. Topics: DSA prep, behavioral interviews, offer negotiation, and relocation tips.', 0),
('KUET Tech Career Fair 2026',       'fair',       'upcoming', '2026-05-25 10:00:00', 'KUET Central Auditorium',     '20+ Companies',            'Meet recruiters from top tech companies, startups, and NGOs. Bring your CV, dress sharp, and attend mock interviews on the spot.', 200),
('Alumni Networking Night',          'networking', 'upcoming', '2026-06-03 18:30:00', 'KUET Faculty Club Lawn',      '50+ Alumni Expected',      'Connect with KUET graduates working across the globe. Informal conversations, mentorship opportunities, and a chance to expand your professional network.', 80),
('LinkedIn Profile Optimization',   'workshop',   'upcoming', '2026-06-14 16:00:00', 'CS Building Lab 2, KUET',     'Career Club',              'Hands-on session on building a recruiter-magnet LinkedIn profile — headline, about section, skills, and reaching out to hiring managers effectively.', 30),
('Intro to Data Science Careers',    'seminar',    'past',     '2026-03-22 15:00:00', 'ECE Seminar Hall',            'Career Club',              'An overview of roles in data science, machine learning engineering, and analytics — with real career path examples from alumni in the field.', 100),
('Mock Interview Marathon',          'workshop',   'past',     '2026-02-08 10:00:00', 'CSE Department, KUET',        'Career Club',              'Full-day mock interview event with senior students and alumni acting as interviewers. Covered technical, HR, and group discussion rounds.', 40),
('Industry Connect Meetup',          'networking', 'past',     '2026-01-15 18:00:00', 'KUET Faculty Club',           'Career Club',              'An informal evening with professionals from local and multinational tech companies. Sparked several internship and job referrals for club members.', 60);

-- ------------------------------------------------
-- 7. Seed: One admin account (password: admin123)
-- ------------------------------------------------
INSERT INTO users (name, email, student_id, password, role) VALUES
('Admin', 'admin@kuet.ac.bd', '0000000', 'admin123', 'admin');

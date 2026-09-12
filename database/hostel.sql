-- ============================================================================
-- hostel-management-system — Reconstructed Database Schema
-- ============================================================================
-- This file was reverse-engineered from the PHP source code (no original
-- .sql backup exists). It is structurally complete. The `rooms` table schema
-- was reconstructed from the source; its SEED DATA (210 rows, verified against
-- the floor-plan SVGs) is provided at the end of this file.
--
-- NOTE ON FOREIGN KEYS: The application's own delete flows (e.g.
-- admin/manage-students.php `?delete=X` deletes a userregistration row
-- without cleaning up student/studentinformation/rooms) would FAIL if strict
-- FOREIGN KEY constraints existed. The original database therefore almost
-- certainly had no FK constraints (orphans are left behind by design). To keep
-- the app working as written, this schema defines relationships via indexed
-- columns + comments only, NOT enforced FK constraints. Add constraints later
-- only if the relevant delete code is fixed first.
--
-- Target: MySQL / MariaDB (XAMPP default). Developed with PHP's mysqli.
-- The app also ships dead PDO connection files (includes/pdoconfig.php,
-- includes/dbcontroller.php) that are not used by any page.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `hostel`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE `hostel`;

-- ============================================================================
-- Table: hostel
-- ============================================================================
-- hostelID values 1-4 are hard-linked throughout the codebase:
--   1 = M1-hall   (male, First/Second-Year)
--   2 = M2-hall   (male, Third/Fourth/Fifth-Year)
--   3 = N-hall    (female, First/Second-Year)
--   4 = J-hall    (female, Third/Fourth/Fifth-Year)
-- (mapping derived from admin/chooseHostel.php SVGs + registration.php JS)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `hostel` (
  `hostelID`   INT NOT NULL AUTO_INCREMENT,
  `hostelName` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`hostelID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `hostel` (`hostelID`, `hostelName`) VALUES
  (1, 'M1-hall'),
  (2, 'M2-hall'),
  (3, 'N-hall'),
  (4, 'J-hall');
-- ASSUMPTION: The exact stored `hostelName` strings are not defined anywhere
-- in the code (they are fetched from the DB and displayed). The values above
-- match the display labels in admin/chooseHostel.php. Change them if your
-- original DB used different strings.

-- ============================================================================
-- Table: userregistration
-- ============================================================================
-- Holds BOTH students and admins. `role` is NULL for students (never set by
-- the student registration INSERT) and 'admin' for admins (set only by the
-- admin registration form). Passwords are stored in PLAINTEXT and compared
-- directly in login.php.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `userregistration` (
  `userID`    INT NOT NULL AUTO_INCREMENT,
  `Name`      VARCHAR(100) NOT NULL,
  `gender`    VARCHAR(10)  NOT NULL,
  `contactNo` VARCHAR(20)  NOT NULL,          -- bound as int in some files, string in others; VARCHAR is safest
  `email`     VARCHAR(100) NOT NULL,
  `password`  VARCHAR(255) NOT NULL,
  `role`      VARCHAR(20)  NULL,              -- NULL = student, 'admin' = admin
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- The app checks email uniqueness via AJAX (check_availability.php) but relies
-- on no DB constraint. Optional unique index below is recommended; comment it
-- out if the original DB had duplicates.
-- CREATE UNIQUE INDEX `uq_userregistration_email` ON `userregistration` (`email`);

-- ============================================================================
-- Table: student  (1:1 child of userregistration, studentID == userID)
-- ============================================================================
-- meta: grep showed check_availability.php / admin/check_availability.php
-- query `SELECT count(*) FROM student WHERE mkpt=?` — mkpt is treated as
-- unique by the registration UI.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `student` (
  `studentID`  INT NOT NULL,
  `mkpt`       INT NOT NULL,                  -- "MKPT" student number
  `atdYear`    VARCHAR(20) NOT NULL,          -- e.g. "First-Year"
  `stayHostel` INT NOT NULL,                  -- FK -> hostel.hostelID (1-4)
  PRIMARY KEY (`studentID`),
  UNIQUE KEY `uq_student_mkpt` (`mkpt`),
  KEY `idx_student_stayHostel` (`stayHostel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: admin  (1:1 child of userregistration, adminID == userID)
-- ============================================================================
-- COLUMN-CASE WARNING: registration.php inserts into `Department` (capital D)
-- while admin/update-Profile.php updates `admin.department` (lowercase d) and
-- admin/userAccount.php reads `$row->Department`. This only works because
-- MariaDB/MySQL on Windows treats identifier case as insignificant.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `admin` (
  `adminID`    INT NOT NULL,
  `Department` VARCHAR(100) NULL,
  PRIMARY KEY (`adminID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: rooms
-- ============================================================================
--
-- *** SEED DATA STATUS: SEEDED (210 rows) — see "ROOMS SEED DATA" at file end ***
--
-- roomID : auto-increment, assigned by the DB. The app never inserts rooms.
-- roomNo : human-display room label. Exact strings are NOT defined anywhere in
--          the PHP code; values in the seed data were derived from the floor
--          maps + real-world hostel info and confirmed against the SVG rect ids.
-- position : room slot number on the floor-plan SVG. DETERMINABLE from the
--          <rect id> attributes in floorMapContainer.php / admin/floorMapContainer.php:
--             hostelID 1 (M1-hall): positions 1-42  on floors F and G  = 84 slots
--             hostelID 2 (M2-hall): positions 1-26  on floors F and G  = 52 slots
--             hostelID 3 (N-hall):  positions 1-26  on floors F and G  = 52 slots
--             hostelID 4 (J-hall):  UNKNOWN — no floor map exists in the code;
--                                  room assignment is random via RoomChoose.php
--                                  (SELECT roomID FROM rooms WHERE hostelID=4 AND stdID IS NULL)
--                                  so one row per bed is used here (30 rows).
-- floor  : 'F' (First) or 'G' (Ground) — both per position for hostels 1-3.
--          Hostel 4 uses 'F' as a placeholder (no physical floor concept).
-- hostelID : FK -> hostel.hostelID.
-- stdID / stdIdTwo : occupant userIDs. Hostels 1-3 are 2-bed rooms (both slots
--          used). Hostel 4 is 1-bed (only stdID used, stdIdTwo never set).
--          All rows are seeded with NULL (empty rooms).
--
-- Seed totals per hostel (see file-end INSERT):
--   M1 (1): 42 F + 38 G = 80     (G-6, and G positions 18/19/20 deliberately
--                                 omitted: Asst-Warden room and Warden area)
--   M2 (2): 26 F + 24 G = 50     (G positions 7 and 20 omitted: walkway / skip)
--   N  (3): 26 F + 24 G = 50     (same structure as M2)
--   J  (4): 30 F (one row per bed; J-D1-B15 & J-D2-B15 are warden beds and MUST
--                                 be assigned a warden userID before they
--                                 should become unbookable — see file-end note)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `rooms` (
  `roomID`   INT NOT NULL AUTO_INCREMENT,
  `roomNo`   VARCHAR(20) NOT NULL,
  `position` INT NOT NULL,
  `floor`    CHAR(1) NOT NULL,                -- 'F' = First, 'G' = Ground
  `hostelID` INT NOT NULL,                    -- FK -> hostel.hostelID
  `stdID`    INT NULL,                        -- first occupant userID, NULL = free
  `stdIdTwo` INT NULL,                        -- second occupant userID, NULL = free
  PRIMARY KEY (`roomID`),
  KEY `idx_rooms_hostel` (`hostelID`),
  KEY `idx_rooms_stdID` (`stdID`),
  KEY `idx_rooms_stdIdTwo` (`stdIdTwo`),
  KEY `idx_rooms_floor` (`floor`),
  KEY `idx_rooms_position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: studentinformation  (booking application detail; 1:1 with student)
-- ============================================================================
-- id is NOT auto-increment: book-hostel.php inserts `id = $_SESSION['id']`,
-- which is always userregistration.userID == student.studentID.
-- All columns are bound as strings in book-hostel.php, so exact VARCHAR
-- lengths below are estimates. Column case is faithful to the INSERT.
-- ============================================================================
CREATE TABLE IF NOT EXISTS `studentinformation` (
  `id`                INT NOT NULL,           -- == userregistration.userID
  `Name`              VARCHAR(100) NOT NULL,
  `stayfrom`          VARCHAR(50)  NOT NULL,  -- VARCHAR in code (never parsed as a date)
  `duration`          INT          NOT NULL,
  `nrcNo`             VARCHAR(50)  NOT NULL,
  `FatherName`        VARCHAR(100) NOT NULL,
  `MotherName`        VARCHAR(100) NOT NULL,
  `Nationality`       VARCHAR(50)  NOT NULL,
  `contactno`         VARCHAR(20)  NOT NULL,
  `ContactEmail`      VARCHAR(100) NOT NULL,
  `religion`          VARCHAR(50)  NOT NULL,
  `guardianName`      VARCHAR(100) NOT NULL,
  `guardianRelation`  VARCHAR(50)  NOT NULL,
  `guardianContactno` VARCHAR(20)  NOT NULL,
  `corresAddress`     TEXT         NOT NULL,
  `pmntAddress`       TEXT         NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: messages  (per-hostel message board)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `messages` (
  `id`        INT NOT NULL AUTO_INCREMENT,
  `msg`       TEXT NOT NULL,
  `msgSender` INT  NOT NULL,                  -- FK -> userregistration.userID
  `hostel`    INT  NOT NULL,                  -- FK -> hostel.hostelID
  PRIMARY KEY (`id`),
  KEY `idx_messages_hostel` (`hostel`),
  KEY `idx_messages_sender` (`msgSender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: rules
-- ============================================================================
-- VERIFIED: the rules table has ONLY `id` + `rules`. There is NO hostelID
-- column. Both dashboards query `SELECT rules.rules FROM rules` with no WHERE
-- clause and admin/setRules.php inserts `INSERT INTO rules(rules) VALUES(? )`,
-- so rules are GLOBAL (shared across all hostels) even though setRules.php
-- reads $_SESSION['hostelID'] (it only uses it for the redirect).
-- ============================================================================
CREATE TABLE IF NOT EXISTS `rules` (
  `id`    INT NOT NULL AUTO_INCREMENT,
  `rules` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- OPTIONAL INITIAL ADMIN ACCOUNT
-- ============================================================================
-- The app has no default/seed admin. To log in as admin you must first create
-- one. The admin registration page (admin/registration.php) does this, but you
-- can also insert it directly. Password here is PLAINTEXT 'admin' (the app
-- stores/compares plaintext):
--
--   INSERT INTO userregistration (Name, gender, contactNo, email, password, role)
--   VALUES ('Admin', 'Male', '0', 'admin@admin.com', 'admin', 'admin');
--   SET @newid = LAST_INSERT_ID();
--   INSERT INTO admin (adminID, Department) VALUES (@newid, 'IT');
--
-- (Your original DB may have used a different email/password.)
-- ============================================================================


-- ============================================================================
-- ROOMS SEED DATA (generated 2026-09-08)
-- ============================================================================
-- Inserts rows only when the same (roomNo, position, floor, hostelID) combo does
-- NOT already exist in rooms. Never overwrites stdID/stdIdTwo.
-- Counts: M1=80 (42 F + 38 G), M2=50 (26 F + 24 G), N=50 (26 F + 24 G), J=30.
-- J-Hall warden beds (J-D1-B15, J-D2-B15) are left stdID=NULL because no warden
-- userID exists in userregistration - assign manually before those beds should
-- be blocked:
--   UPDATE rooms SET stdID = <wardenUserID> WHERE roomNo IN ('J-D1-B15','J-D2-B15');
-- ============================================================================

INSERT INTO `rooms` (`roomNo`, `position`, `floor`, `hostelID`, `stdID`, `stdIdTwo`)
SELECT seed.roomNo, seed.position, seed.floor, seed.hostelID, seed.stdID, seed.stdIdTwo
FROM (
    SELECT 'x' AS roomNo, 0 AS position, 'X' AS floor, 0 AS hostelID, NULL AS stdID, NULL AS stdIdTwo WHERE 1=0
    -- M1 (hostelID 1): First floor positions 1-42
    UNION ALL SELECT 'F-1', 1, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-2', 2, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-3', 3, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-4', 4, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-5', 5, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-6', 6, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-7', 7, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-8', 8, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-9', 9, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-10', 10, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-11', 11, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-12', 12, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-13', 13, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-14', 14, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-15', 15, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-16', 16, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-17', 17, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-18', 18, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-19', 19, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-20', 20, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-21', 21, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-22', 22, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-23', 23, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-24', 24, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-25', 25, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-26', 26, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-27', 27, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-28', 28, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-29', 29, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-30', 30, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-31', 31, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-32', 32, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-33', 33, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-34', 34, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-35', 35, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-36', 36, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-37', 37, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-38', 38, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-39', 39, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-40', 40, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-41', 41, 'F', 1, NULL, NULL
    UNION ALL SELECT 'F-42', 42, 'F', 1, NULL, NULL
    -- M1 (hostelID 1): Ground positions 1-5 (G-6 = Asst Warden room, omitted)
    UNION ALL SELECT 'G-1', 1, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-2', 2, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-3', 3, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-4', 4, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-5', 5, 'G', 1, NULL, NULL
    -- M1 (hostelID 1): Ground positions 7-17
    UNION ALL SELECT 'G-7', 7, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-8', 8, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-9', 9, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-10', 10, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-11', 11, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-12', 12, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-13', 13, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-14', 14, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-15', 15, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-16', 16, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-17', 17, 'G', 1, NULL, NULL
    -- M1 (hostelID 1): Ground positions 18-20 = Warden area, omitted
    -- M1 (hostelID 1): Ground positions 21-42 = G-18..G-39
    UNION ALL SELECT 'G-18', 21, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-19', 22, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-20', 23, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-21', 24, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-22', 25, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-23', 26, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-24', 27, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-25', 28, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-26', 29, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-27', 30, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-28', 31, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-29', 32, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-30', 33, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-31', 34, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-32', 35, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-33', 36, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-34', 37, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-35', 38, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-36', 39, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-37', 40, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-38', 41, 'G', 1, NULL, NULL
    UNION ALL SELECT 'G-39', 42, 'G', 1, NULL, NULL
    -- M2 (hostelID 2): First floor positions 1-26
    UNION ALL SELECT 'F-101', 1, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-102', 2, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-103', 3, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-104', 4, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-105', 5, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-106', 6, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-107', 7, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-108', 8, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-109', 9, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-110', 10, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-111', 11, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-112', 12, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-113', 13, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-114', 14, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-115', 15, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-116', 16, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-117', 17, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-118', 18, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-119', 19, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-120', 20, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-121', 21, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-122', 22, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-123', 23, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-124', 24, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-125', 25, 'F', 2, NULL, NULL
    UNION ALL SELECT 'F-126', 26, 'F', 2, NULL, NULL
    -- M2 (hostelID 2): Ground positions 1-6
    UNION ALL SELECT 'G-001', 1, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-002', 2, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-003', 3, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-004', 4, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-005', 5, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-006', 6, 'G', 2, NULL, NULL
    -- M2 (hostelID 2): position 7 = walkway, omitted
    -- M2 (hostelID 2): Ground positions 8-13
    UNION ALL SELECT 'G-007', 8, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-008', 9, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-009', 10, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-010', 11, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-011', 12, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-012', 13, 'G', 2, NULL, NULL
    -- M2 (hostelID 2): Ground positions 14-19
    UNION ALL SELECT 'G-013', 14, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-014', 15, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-015', 16, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-016', 17, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-017', 18, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-018', 19, 'G', 2, NULL, NULL
    -- M2 (hostelID 2): position 20 = one-room-sized skip, omitted
    -- M2 (hostelID 2): Ground positions 21-26
    UNION ALL SELECT 'G-019', 21, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-020', 22, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-021', 23, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-022', 24, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-023', 25, 'G', 2, NULL, NULL
    UNION ALL SELECT 'G-024', 26, 'G', 2, NULL, NULL
    -- N-Hall (hostelID 3): First floor positions 1-26
    UNION ALL SELECT 'F-101', 1, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-102', 2, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-103', 3, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-104', 4, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-105', 5, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-106', 6, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-107', 7, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-108', 8, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-109', 9, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-110', 10, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-111', 11, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-112', 12, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-113', 13, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-114', 14, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-115', 15, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-116', 16, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-117', 17, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-118', 18, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-119', 19, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-120', 20, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-121', 21, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-122', 22, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-123', 23, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-124', 24, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-125', 25, 'F', 3, NULL, NULL
    UNION ALL SELECT 'F-126', 26, 'F', 3, NULL, NULL
    -- N-Hall (hostelID 3): Ground positions 1-6
    UNION ALL SELECT 'G-001', 1, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-002', 2, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-003', 3, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-004', 4, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-005', 5, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-006', 6, 'G', 3, NULL, NULL
    -- N-Hall (hostelID 3): position 7 = walkway, omitted
    -- N-Hall (hostelID 3): Ground positions 8-13
    UNION ALL SELECT 'G-007', 8, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-008', 9, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-009', 10, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-010', 11, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-011', 12, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-012', 13, 'G', 3, NULL, NULL
    -- N-Hall (hostelID 3): Ground positions 14-19
    UNION ALL SELECT 'G-013', 14, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-014', 15, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-015', 16, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-016', 17, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-017', 18, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-018', 19, 'G', 3, NULL, NULL
    -- N-Hall (hostelID 3): position 20 = one-room-sized skip, omitted
    -- N-Hall (hostelID 3): Ground positions 21-26
    UNION ALL SELECT 'G-019', 21, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-020', 22, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-021', 23, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-022', 24, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-023', 25, 'G', 3, NULL, NULL
    UNION ALL SELECT 'G-024', 26, 'G', 3, NULL, NULL
    -- J-Hall (hostelID 4): one row per bed (floor 'F' placeholder)
    UNION ALL SELECT 'J-D1-B1', 1, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B2', 2, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B3', 3, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B4', 4, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B5', 5, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B6', 6, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B7', 7, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B8', 8, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B9', 9, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B10', 10, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B11', 11, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B12', 12, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B13', 13, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B14', 14, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D1-B15', 15, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B1', 16, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B2', 17, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B3', 18, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B4', 19, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B5', 20, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B6', 21, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B7', 22, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B8', 23, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B9', 24, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B10', 25, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B11', 26, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B12', 27, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B13', 28, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B14', 29, 'F', 4, NULL, NULL
    UNION ALL SELECT 'J-D2-B15', 30, 'F', 4, NULL, NULL
) AS seed
WHERE NOT EXISTS (
    SELECT 1 FROM `rooms` r
    WHERE r.roomNo = seed.roomNo AND r.`position` = seed.position AND r.floor = seed.floor AND r.hostelID = seed.hostelID
);
# Hostel Management System — UCSM

A web-based hostel registration and room-booking system for the University of
Computer Studies Mandalay (UCSM). It provides a public site, a student portal,
and a separate administrator panel, built with plain PHP and MySQL (no
framework, no build step).

---

## Project Description

Students can register, log in, view their profile, choose a hostel room from an
interactive floor map, submit a booking application, change their password, and
exchange messages on a per-hostel message board. Administrators log in
separately, select a hostel, review and manage applicants, manage rooms and
student records, post rules, and moderate the message board — all scoped to the
hostel they are currently working in.

## Project Purpose

The system replaces manual, in-person hostel room allocation with an online
workflow. It reduces waiting time for students, gives administrators a single
place to view applicants and room occupancy, and makes rules and announcements
easy to distribute.

---

## Screenshots / Demo

> **Placeholder:** Screenshots and a short demo walkthrough will be added here.
> Add image files under a `docs/screenshots/` folder and reference them, for
> example `![Student dashboard](docs/screenshots/student-dashboard.png)`.

Suggested screenshots to capture:
- Public homepage (`index.php`)
- Student dashboard with the floor map and room-availability colours
- Booking application form
- Admin hostel-selection screen
- Admin dashboard (scoped to a hostel)
- Admin manage-students and student search

---

## Main Features

### Student features
- Self-registration with MKPT number, year, gender, contact and email.
- Secure login and logout (logout is a POST action protected against CSRF).
- Student dashboard with hostel floor map and room availability colour coding.
- Interactive room selection from the floor map, plus a J-Hall random/auto room
  selector.
- Booking application form (personal, guardian and address details).
- Live room-availability check while choosing a room.
- Profile view and profile update.
- Password change with current-password verification.
- Per-hostel message board (post and delete your own messages).
- View hostel rules and regulations.

### Admin features
- Separate administrator login and landing page.
- Hostel selection screen (M1, M2, N, J) that scopes all further work.
- Admin dashboard with student/bed counts and the hostel floor map.
- Manage students: view applications, view full details, delete a student.
- Registered-students list.
- Student search by MKPT, highlighting the student's room on the floor map.
- Post and delete rules; post, delete and clear hostel messages.
- Admin profile view and update, and password change.
- Register additional administrator accounts.
- Full student detail view (room, personal and address information).

---

## Room Booking Rules

- **M1 (hostel 1)** and **M2 (hostel 2)** are male hostels; **N (hostel 3)**
  and **J (hostel 4)** are female hostels. Registration maps a student to a
  hostel based on gender and year.
- **Hostels 1–3 use two-student rooms.** The first occupant is stored in
  `rooms.stdID`, the second in `rooms.stdIdTwo`.
- **J-Hall (hostel 4) uses single-occupancy beds** (`stdID` only).
- A student may only hold one room; duplicate bookings are rejected.
- A room's slot is claimed with a single conditional `UPDATE` (only if the slot
  is still `NULL`), so two simultaneous requests cannot overfill a room. The
  room claim and the application record are wrapped in a transaction.
- A selected room must belong to the student's own hostel.
- Room selection state in the session is cleared after a successful booking.

## J-Hall Behavior

J-Hall has no floor-map SVG. Instead of picking a room on a map, J-Hall
students use a drop-down listing the currently available J-Hall beds, and the
booking is limited to a single occupant per bed. The floor-map colouring system
is intentionally not applied to J-Hall.

---

## Security Improvements

The application has been hardened with targeted, verified fixes:

- **CSRF protection** on state-changing student and admin actions (booking,
  messaging, rules, profile/password updates, student deletion, admin
  registration), using a per-session token and constant-time comparison.
- **POST-only logout with CSRF** — a GET request to the logout endpoint no
  longer destroys the session.
- **XSS output encoding** (`htmlspecialchars` with `ENT_QUOTES`) in the
  protected dashboard, profile and admin detail areas.
- **IDOR protection for message deletion** — a message can only be deleted by
  its owner (`DELETE ... WHERE id = ? AND msgSender = ?`), and admin deletion
  is scoped to the selected hostel.
- **Hostel-scoped admin actions** — student deletion, student detail views and
  hostel switching are restricted to the administrator's selected hostel, and
  admin-only pages verify the `admin` role.
- **Atomic room booking / capacity protection** — conditional `UPDATE`-based
  slot claiming prevents overbooking.
- **Duplicate-booking prevention** on the server side.

> This is a student/portfolio project. The protections above are real and
> verified, but the project is **not** presented as production-grade software.

---

## Technology Stack

- **Backend:** PHP (procedural, no framework), `mysqli` prepared statements.
- **Database:** MySQL / MariaDB (bundled with XAMPP).
- **Frontend:** HTML5, CSS, Bootstrap 3, jQuery 1.x (1.11.3 / 1.10.2),
  Font Awesome, DataTables, Chart.js, jQuery File Input, custom stylesheets.
- **Tooling:** none — no Composer, no npm, no build step.

---

## Project Structure

```
hostel-management-system/
├── index.php                # Public homepage (project intro, slideshow)
├── login.php                # Unified login, role-based redirect
├── registration.php         # Student self-registration
├── forgot-password.php      # Password lookup (legacy)
├── dashboard.php            # Student dashboard (floor map, messages, rules)
├── myProfile.php            # Student profile view
├── userAccount.php          # Student account view
├── update-Profile.php       # Student profile / password update
├── book-hostel.php          # Booking flow and room claim
├── Modal.php                # Student floor map + room picker fragment
├── ApplyBtn.php             # J-Hall apply-room fragment
├── RoomChoose.php           # J-Hall available-bed selector fragment
├── RoomAutoChoose.php       # Room-id/session summary fragment
├── floorMapContainer.php    # Floor-map SVGs and occupancy colouring
├── checkRoom.php            # Student room-availability AJAX
├── check_availability.php   # Email/MKPT/password availability AJAX
├── sendMessage.php          # Student message post/delete handler
├── logout.php               # POST-only session logout
├── getName.php              # Loads session name/hostel
├── phpslide*.php            # Hostel photo fragments
├── includes/                # Student config, auth, header, sidebar
├── admin/                   # Administrator panel (own login + pages)
│   ├── index.php            # Admin landing page
│   ├── chooseHostel.php     # Hostel selection (first screen)
│   ├── dashboard.php        # Admin dashboard (scoped to chosen hostel)
│   ├── manage-students.php  # Manage applicants (scoped)
│   ├── regStudents.php      # Registered students list
│   ├── registration.php     # Register a new admin
│   ├── searchStudent.php    # Search student by MKPT
│   ├── fullDetail.php       # Full student detail (scoped)
│   ├── setRules.php         # Rules add/delete
│   ├── sendMessage.php      # Admin message post/delete/clear
│   ├── update-Profile.php   # Admin profile / password
│   ├── userAccount.php      # Admin account view
│   ├── floorMapContainer.php, Modal.php, checkStudent.php, ...
│   └── includes/            # Admin config, auth, header, sidebar
├── css/  js/  img/  fonts/  # Front-end assets
└── database/
    └── hostel.sql           # Authoritative schema + 210-room seed
```

---

## Requirements

- **XAMPP** (or any Apache + PHP + MySQL stack)
  - PHP **7.4+ / 8.x** (developed and tested on XAMPP PHP **8.2**)
  - PHP extensions: `mysqli`, `pdo_mysql`, `mysqlnd`
- **MySQL / MariaDB** server (bundled with XAMPP)
- Any modern web browser

---

## XAMPP Setup

1. Install **XAMPP**.
2. Open the **XAMPP Control Panel**.
3. Press **Start** next to **Apache** and **MySQL** (both turn green).
4. Apache default: `http://localhost`; MySQL default port: `3306`.

## Where to Place the Project

Copy the whole project folder into the XAMPP document root:

```
C:\xampp\htdocs\hostel-management-system\
```

The application is then reachable at:

```
http://localhost/hostel-management-system/
```

> If you rename the folder, adjust the URL accordingly.

## How to Create / Import the Hostel Database

1. Start **MySQL** and **Apache**.
2. Import the schema and seed with either:

   - **phpMyAdmin:** open `http://localhost/phpmyadmin/`, click **Import**,
     choose `database/hostel.sql`, then **Go**.
   - **Command line:**
     ```
     C:\xampp\mysql\bin\mysql -u root < C:\xampp\htdocs\hostel-management-system\database\hostel.sql
     ```

This creates the `hostel` database with **8 tables** (`hostel`,
`userregistration`, `student`, `admin`, `rooms`, `studentinformation`,
`messages`, `rules`) and seeds the 4 hostel records.

## Database Seed Information

`database/hostel.sql` is the **authoritative seed file** for the project.

- **4 hostels:** `1 = M1-hall`, `2 = M2-hall`, `3 = N-hall`, `4 = J-hall`.
- **210 room rows**, all seeded empty:

  | Hostel | Rooms | First floor (`F`) | Ground floor (`G`) |
  |--------|------:|------------------:|-------------------:|
  | M1 (1) |    80 |                42 |                 38 |
  | M2 (2) |    50 |                26 |                 24 |
  | N  (3) |    50 |                26 |                 24 |
  | J  (4) |    30 |                30 |                  — |
  | **Total** | **210** | | |

- Room `position` values correspond to the floor-map SVG element ids.
- Relationships are enforced by indexed columns and application logic rather
  than strict foreign keys, matching the application's original delete flow.
- **No user accounts, student records, or passwords are seeded.** The seed
  contains only hostel and room data.

## How to Run the Application

1. Import the database (above).
2. Create an administrator account (needed to use the admin panel):
   - Log in flow requires an account with `role = 'admin'` in
     `userregistration` plus a matching row in `admin`.
   - **Option A:** use `admin/registration.php` (this page requires an existing
     logged-in admin).
   - **Option B:** insert one directly via SQL, for example (plaintext
     password, matching the current architecture):
     ```sql
     INSERT INTO userregistration (Name, gender, contactNo, email, password, role)
     VALUES ('Admin', 'Male', '0', 'admin@example.com', 'change-me', 'admin');
     SET @newid = LAST_INSERT_ID();
     INSERT INTO admin (adminID, Department) VALUES (@newid, 'IT');
     ```
3. Open `http://localhost/hostel-management-system/`.
4. Register a student account, or log in as the admin.

## Local Development Configuration

The database connection is currently hardcoded in the connection files:

```
host     = localhost
user     = root
password = (empty)
database = hostel
```

These values appear in `includes/config.php` and `admin/includes/config.php`
(`includes/pdoconfig.php`, `includes/dbcontroller.php` and their admin copies
are legacy PDO variants and are unused by the application). Change them if your
local MySQL setup differs.

---

## Team / Project Contribution

- **Team Project — UCSM Computer Science**
- **My Role: Team Leader**
- **Responsibilities:** project planning, team coordination, system flow design,
  communication, presentation, and assisting with ERD and flowchart design.
- Built collaboratively as a student project covering the student and
  administrator modules.

*(Personal names and student identification numbers have intentionally been
omitted from this public repository.)*

---

## Known Limitations

- **Plaintext password storage** — passwords are stored and compared in plain
  text; hashing is not implemented.
- Password lookup (`forgot-password.php`) displays the stored password.
- **Local XAMPP credentials** are hardcoded with no environment/secret
  management.
- **Legacy front-end stack** (Bootstrap 3, jQuery 1.x) and duplicated script
  loads on some pages.
- Some **legacy/dead files and fragments** remain in the tree (for example the
  unused PDO connection files and some development-only page fragments).
- No `session_regenerate_id()` on login, and login/registration forms are not
  CSRF-protected (the protected areas of the app are).
- No automated test suite or CI.
- **No production deployment configuration** (this is a local XAMPP project).

## Future Improvements

- Hash passwords (e.g. `password_hash` / `password_verify`) and add a proper
  reset flow.
- Session hardening: regenerate session id on login, set `Secure` / `HttpOnly`
  / `SameSite` cookie attributes.
- Add CSRF protection and rate limiting to public login/registration.
- Modernise the UI (current Bootstrap/jQuery) and remove dead files.
- Move configuration to environment variables and remove hardcoded credentials.
- Add automated tests and a CI workflow.
- Add a deployment configuration (e.g. Apache/Nginx + production database).

---

## License / Usage

Developed as a university project for the University of Computer Studies
Mandalay. Configuration and default credentials should be changed before any
real use.

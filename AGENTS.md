# AGENTS.md

## Project Overview

PHP-based hostel registration system for UCSM (University of Computer Studies Mandalay). Two-role web app: **Students** and **Admins**. No build tools, package manager, or framework — plain PHP with MySQL.

## Prerequisites

- **PHP** with `mysqli` and `pdo_mysql` extensions
- **MySQL** server running on `localhost`
- Database named `hostel` must exist (reconstructed schema + verified 210-room seed provided in `database/hostel.sql`; import it to create the DB/tables/rooms)
- Web server (Apache/XAMPP recommended) serving from project root

## Database

- DB name: `hostel`, user: `root`, no password (hardcoded in `includes/config.php`, `includes/pdoconfig.php`, `includes/dbcontroller.php`)
- Key tables: `userregistration` (id, name, gender, contactNo, email, password, role), `student` (mkpt, atdYear, stayHostel, studentID FK)
- Admin config files duplicate the same credentials in `admin/includes/`
- **Seed file provided.** `database/hostel.sql` is the authoritative reconstructed schema + verified 210-room seed (M1=80, M2=50, N=50, J=30; rooms seeded empty).

## Architecture

```
├── index.php              # Public homepage
├── login.php              # Unified login (role-based redirect)
├── registration.php       # Student registration
├── dashboard.php          # Student dashboard
├── myProfile.php          # Student profile view
├── book-hostel.php        # Hostel booking flow
├── RoomChoose.php         # Room selection
├── RoomAutoChoose.php     # Auto room assignment
├── check_availability.php # Availability check
├── checkRoom.php        # Student room-availability AJAX (POST posi)
├── floorMapContainer.php  # Floor map UI
├── sendMessage.php        # Student messaging
├── forgot-password.php    # Password reset
├── update-Profile.php     # Profile update
├── userAccount.php        # Account management
├── getName.php            # Fetches user name into session
├── phpslide*.php          # Hostel description pages (F/O/T/Th)
├── includes/              # Shared PHP includes (config, auth, header, sidebar)
├── admin/                 # Admin panel (separate set of pages)
│   ├── index.php          # Admin login/landing
│   ├── chooseHostel.php   # Admin hostel selection (first screen after login)
│   ├── dashboard.php      # Admin dashboard
│   ├── manage-students.php
│   ├── registration.php   # Admin student registration
│   ├── searchStudent.php
│   ├── checkStudent.php
│   ├── setRules.php
│   ├── sendMessage.php
│   ├── update-Profile.php
│   ├── userAccount.php
│   ├── floorMapContainer.php
│   ├── fullDetail.php
│   ├── regStudents.php
│   ├── testphp.php
│   └── includes/          # Duplicated config/auth includes
├── css/                   # Stylesheets (Bootstrap, FontAwesome, custom)
├── js/                    # JavaScript (jQuery, DataTables, Chart.js, validation)
├── img/                   # Images and video assets
├── database/            # hostel.sql (authoritative schema + 210-room seed)
└── fonts/                 # Font files
```

## Key Conventions

- **No framework** — all files are standalone PHP pages with inline HTML
- **Session-based auth**: `session_start()` + `$_SESSION['id']` at top of every protected page
- **Auth check**: `includes/checklogin.php` provides `check_login()` — redirects to index if session empty
- **Two parallel include sets**: `includes/` (student-facing) and `admin/includes/` (admin-facing) with duplicated config
- **DB connection**: MySQLi (`config.php`) is used by pages; `pdoconfig.php`/`dbcontroller.php` are unused/dead and can be ignored
- **Role routing**: `login.php` checks `$_SESSION['role']` — `admin` role redirects to `admin/chooseHostel.php`, others to `dashboard.php`
- **Inline CSS/JS**: Most pages include `<style>` and `<script>` blocks directly in the PHP file
- **No linting, testing, or typecheck** configured

## Running the App

1. Place in web server document root (e.g., `htdocs/`)
2. Import `database/hostel.sql` (creates DB 'hostel', all 8 tables, 4 hostels, 210 empty rooms)
3. Access via `http://localhost/hostel-management-system/`

## Things an Agent Should Know

- **Passwords stored in plaintext** — the `login.php` compares raw password input against DB
- **Credentials hardcoded** — `root`/no-password in 6 config files across both include directories
- **CSRF protection** on state-changing student/admin forms (per-session token + constant-time compare); logout is POST-only
- **`database/hostel.sql`** is the reconstructed schema + 210-room seed (DDL verified against live DB)
- **Admin panel has its own separate login and page set** — not using the same `includes/` as student pages
- **jQuery loaded twice** on some pages (local + CDN)
- **`phpslide*.php` files** are individual hostel description/landing pages (F, O, T, Th hostsels)

# Modern Profile Card Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign personnel directory cards in `/directory` to match the CodewithFaraz modern profile card design with full-cover photo, bottom glassmorphic info panel, 3-column quick info bar, verified badge, and glowing action button.

**Architecture:** Update `ConUserDirectory.php` query builder to supply `pers_phone` and `pers_nickname`, and update `UserDirectoryMain.php` CSS and markup with pure CSS styling, responsive grid, and responsive fallback for avatars.

**Tech Stack:** PHP 8.1+ (CodeIgniter 4), Vanilla CSS / Boxicons / Lozad.js, K2D Google Font.

**Spec:** `docs/superpowers/specs/2026-10-28-directory-profile-card-design.md`

## Global Constraints
- Typography: Universally enforce Google Font 'K2D' (`font-family: 'K2D', sans-serif !important;`).
- Color Theme: Signature royal/ocean blue accents (`#1d4ed8`, `#0284c7`, `#075985`) and dark glassmorphic overlays.
- Zero Regression: Preserve all existing tab switches (Executives, Teachers, Staff) and category filters.
- Fallback: Gracefully render when `pers_img` or `pers_phone` is null or empty.

## Review Focus
- Cards with missing `pers_img` should display a stylish initials gradient avatar without broken layout.
- Personnel without `pers_phone` should gracefully fall back to a detail page link rather than an empty `tel:`.
- Cards on mobile viewports (down to 360px) should keep names and quick info readable without overflow.
- Lazy loading with Lozad.js must remain functional on all image elements.
- Stretched link or clickability of buttons should not prevent navigation to `directory/detail/{id}`.

---

### Task 1: Update Controller Data in `ConUserDirectory.php`

**Files:**
- Modify: `app/Controllers/ConUserDirectory.php:38-79`

**Interfaces:**
- Produces: `$data['Executive']`, `$data['AllTeachers']`, `$data['SupportStaff']` with `pers_phone` and `pers_nickname` properties.

- [x] **Step 1: Inspect and add fields to Query Builder in `ConUserDirectory.php`**
  - Add `pers_phone, pers_nickname` to `$Executive` select query.
  - Add `pers_phone, pers_nickname` to `$AllTeachers` select query.
  - Add `pers_phone, pers_nickname` to `$SupportStaff` select query.

- [x] **Step 2: Verify PHP syntax of `ConUserDirectory.php`**
  Run: `php -l app/Controllers/ConUserDirectory.php`
  Expected: "No syntax errors detected"

---

### Task 2: Redesign Profile Card CSS and Markup in `UserDirectoryMain.php`

**Files:**
- Modify: `app/Views/User/UserDirectory/UserDirectoryMain.php`

**Interfaces:**
- Consumes: `Executive`, `LearningGroups`, `AllTeachers`, `SupportStaff` with `pers_phone` and `pers_nickname`.
- Produces: Modern CodewithFaraz profile cards for Executives, Teachers, and Staff sections.

- [x] **Step 1: Implement Card CSS styles in `<style>` block**
  - `.p-card`: Aspect ratio approx 3:4.5 / 1:1.45, `border-radius: 24px`, `overflow: hidden`, `position: relative`, luxury box shadow and smooth transform on hover.
  - Full-cover image container `.p-card-bg`: `position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;`.
  - Glassmorphic dark gradient overlay `.p-card-overlay`: `position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.7) 42%, rgba(15,23,42,0.15) 70%, transparent 100%); z-index: 2;`.
  - Content container `.p-card-content`: `position: absolute; bottom: 0; left: 0; right: 0; padding: 1.25rem 1rem 1rem; z-index: 3; color: #ffffff;`.
  - Verified badge next to name: `<i class='bx bxs-badge-check text-sky-400 ms-1'></i>`.
  - 3-column stats bar `.p-stats-bar`: Flex row with subtle border dividers (`border-gray-500/40`), displaying Group/Department, Position/Rank, Status.
  - Action buttons `.p-card-actions`:
    - Glowing pill button `.glow-action-btn`: background `#ffffff`, color `#0f172a`, font-weight 700, rounded pill, glowing box-shadow on hover.
    - Secondary circular button `.quick-icon-btn`: background `rgba(255,255,255,0.15)`, backdrop blur, icon phone / view.
  - Top Badges: Floating badges for Director, Deputy Director, Head of Learning Group.

- [x] **Step 2: Update Markup for Executives Section**
  - Render each Executive inside the new `.p-card` structure with correct leader/deputy badge.

- [x] **Step 3: Update Markup for Teachers Section**
  - Render teachers inside each learning group block with head badge for `pers_groupleade === 'หัวหน้ากลุ่มสาระ'`.

- [x] **Step 4: Update Markup for Support Staff Section**
  - Render staff inside each position block with appropriate titles and quick info.

- [x] **Step 5: Verify PHP syntax of `UserDirectoryMain.php`**
  Run: `php -l app/Views/User/UserDirectory/UserDirectoryMain.php`
  Expected: "No syntax errors detected"

---

### Task 3: Verification & Visual Confirmation

**Files:**
- Verify: `app/Views/User/UserDirectory/UserDirectoryMain.php`
- Verify: `app/Controllers/ConUserDirectory.php`

- [x] **Step 1: Check HTTP response code of `/directory`**
  Run local curl or php CLI test script to ensure `/directory` loads with HTTP 200 and no PHP notices or warnings.

- [x] **Step 2: Visual and functional review**
  Verify all 3 tabs (Executives, Teachers, Staff), filter buttons, lazy-load images, and detail link navigation.

# Personnel Restore System Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create a robust and elegant Personnel Restore System in `Admin/WorkPerson/Personnel` allowing administrators to find and restore resigned or inactive personnel back to active status with new position/learning assignments.

**Architecture:** CodeIgniter 4 backend endpoints (`ConAdminWorkPerson`) connecting `skjacth_personnel` and `skjacth_skj` databases, communicating via JSON/AJAX with a high-contrast luxury UI modal in `AdminPersonMain.php` featuring live search, re-assignment options, and SweetAlert2 notifications.

**Tech Stack:** PHP 8.1+, CodeIgniter 4, MySQL/MariaDB (Docker), Bootstrap 5, SweetAlert2, Google Font K2D.

**Spec:** [2026-10-28-personnel-restore-design.md](file:///d:/SkjSystem/personnel2025/docs/superpowers/specs/2026-10-28-personnel-restore-design.md)

## Global Constraints
- Google Font K2D (`font-family: 'K2D', sans-serif !important;`) across all modal elements
- High-contrast badges (`#0f172a` / `#0369a1` on tinted background)
- Zero regression: do not break existing `tb_personnel` queries or tables
- Thai language UI and messages

## Review Focus
- Inactive personnel with NULL or missing `pers_img`, `pers_position`, or `pers_learning` handled gracefully
- Search query handling special characters, Thai vowels, or empty inputs without errors
- Ensuring session authentication check is enforced on both new endpoints
- SweetAlert2 error handling if network or DB update fails
- Dynamic toggle of learning group and academic rank fields based on selected position

---

### Task 1: Backend Controller Endpoints & Routes

**Files:**
- Modify: `app/Config/Routes.php:110-140`
- Modify: `app/Controllers/ConAdminWorkPerson.php`

**Interfaces:**
- Produces:
  - `ConAdminWorkPerson::GetInactivePersonnel(): ResponseInterface`
  - `ConAdminWorkPerson::RestorePersonnel(): ResponseInterface`

- [ ] **Step 1: Define routes in `app/Config/Routes.php`**
  Add routes for `Admin/WorkPerson/Personnel/DB/GetInactivePersonnel` and `Admin/WorkPerson/Personnel/DB/Restore`.

- [ ] **Step 2: Implement `GetInactivePersonnel` in `ConAdminWorkPerson.php`**
  Fetch all personnel where `pers_status != 'กำลังใช้งาน'` with joined position name (`posi_name`) and learning group name (`lear_namethai`). Return clean JSON array.

- [ ] **Step 3: Implement `RestorePersonnel` in `ConAdminWorkPerson.php`**
  Validate POST inputs (`pers_id`, `pers_position`, `pers_learning`, `pers_academic`, `pers_status`), update `tb_personnel`, and return JSON response `{status: 'success', message: '...', redirect_url: '...'}`.

- [ ] **Step 4: Test backend endpoints via Docker command line**
  Verify `GetInactivePersonnel` returns records with status 200 and expected JSON structure.

---

### Task 2: View Integration & Modal Structure in `AdminPersonMain.php`

**Files:**
- Modify: `app/Controllers/ConAdminWorkPerson.php:20-60` (pass count and dropdown data)
- Modify: `app/Views/Admin/AdminWorkPerson/AdminPersonMain.php`

**Interfaces:**
- Consumes: `$countInactive`, `$position`, `$learning` in `AdminPersonMain.php`
- Produces: Modal markup `#modalRestorePersonnel` and `#modalConfirmRestore`

- [ ] **Step 1: Pass inactive count and dropdown options from `ConAdminWorkPerson::index`**
  Update `index()` method to count records where `pers_status != 'กำลังใช้งาน'` and pass `$data['countInactive']`, `$data['allPositions']`, and `$data['allLearnings']`.

- [ ] **Step 2: Add "กู้คืนข้อมูลบุคลากร" button in `AdminPersonMain.php` header**
  Add styled button with badge counter next to "เพิ่มบุคลากร" and "ล้างรูปขยะ".

- [ ] **Step 3: Build `#modalRestorePersonnel` Modal in `AdminPersonMain.php`**
  Construct modal containing search input, status filter pills/tabs, and table/card container for inactive staff.

- [ ] **Step 4: Build `#modalConfirmRestore` Modal in `AdminPersonMain.php`**
  Construct modal displaying selected personnel info and dropdown fields to configure new position, learning group, and academic rank before activating.

---

### Task 3: Client-side Interactivity & AJAX Workflow

**Files:**
- Modify: `app/Views/Admin/AdminWorkPerson/AdminPersonMain.php` (script section)

**Interfaces:**
- Consumes: Endpoints from Task 1, Modals from Task 2

- [ ] **Step 1: Implement AJAX loader and live rendering for inactive personnel**
  Write JavaScript function to fetch data from `GetInactivePersonnel`, render rows with photos, badges, and "กู้คืนข้อมูล" action button.

- [ ] **Step 2: Implement client-side search and status filter**
  Add real-time filter by keyword (name, ID, phone) and filter by status pills (ทั้งหมด, ลาออก, ย้ายสถานศึกษา, เกษียรอายุ).

- [ ] **Step 3: Implement Quick Restore click handler & form population**
  When clicking "กู้คืนข้อมูล", populate `#modalConfirmRestore` with personnel details and trigger position/learning change logic.

- [ ] **Step 4: Implement AJAX submission with SweetAlert2 confirmation & redirects**
  Submit restoration request, handle responses, display SweetAlert2 success with action to redirect to edit profile or reload list.

---

### Task 4: End-to-End Verification

**Files:**
- Verify with Docker / Spark / cURL / Browser

- [ ] **Step 1: Test API responses and schema**
  Verify JSON outputs from `GetInactivePersonnel` and `RestorePersonnel`.

- [ ] **Step 2: Test complete restore workflow with a test record**
  Restore a record, verify its status in `tb_personnel` changes to `กำลังใช้งาน` and appears in `Admin/WorkPerson/Personnel/Group/...`.

- [ ] **Step 3: Visual & UI quality audit**
  Check responsive layout, K2D font, contrast badges, and mobile layout.

# System Analysis: Personnel Management (CodeIgniter 4)

This document summarizes the architecture, features, and technologies used in the school personnel management system.

## 1. Project Overview

- **Project:** Personnel Management System
- **Framework:** CodeIgniter 4
- **Purpose:** Manage school personnel data, with a focus on performance appraisals (PA) and attendance tracking.

## 2. Technology Stack

- **Backend:** PHP 8.1+
- **Frontend:**
  - Bootstrap
  - jQuery
  - Select2.js
  - Flatpickr.js
  - Signature Pad
- **Database:** MySQL, with a multi-database architecture.

## 3. Database Architecture

The system connects to multiple databases:

1.  **`skjacth_personnel` (Default Group):**
    - **Purpose:** Stores primary personnel information.
    - **Data Access:** Primarily uses raw CodeIgniter Query Builder calls. Models are not consistently used for this database.

2.  **`skjacth_pa_evaluation` (`pa_evaluation` Group):**
    - **Purpose:** Stores data related to performance appraisals, including evaluation forms, items, and scores.
    - **Data Access:** Uses proper CodeIgniter Models (`EvaluationModel`, `EvaluatorScoreModel`, `ItemScoreModel`).

3.  **`skjacth_skj` (`skj` Group):**
    - **Purpose:** Appears to hold academic-related data, such as subjects and learning groups.
    - **Data Access:** Uses raw Query Builder calls.

## 4. Authentication and Security

- **Authentication Methods:**
  - **Personnel:** Google OAuth2 (`google/apiclient`).
  - **Admin:** A traditional username/password form.

- **‼️ Critical Security Vulnerabilities:**
  1.  **Plain Text Passwords:** Admin passwords are stored and verified in plain text in the `tb_personnel` table. This is a major security risk.
  2.  **Hardcoded Credentials:** Database connection details (username, password) are hardcoded directly into `app/Config/Database.php`, making the system vulnerable if the source code is exposed.

## 5. Key Features & Application Structure

The application is divided into Admin and User (personnel) sections.

### Admin Section (`/admin/...`)

- **Controllers:** `ConAdminHome`, `ConAdminWorkPerson`, `ConAdminPaConfig`, `ConAdminRoles`, `ConAdminSaveAttendance`.
- **Functionality:**
  - **Dashboard:** Overview and statistics.
  - **Personnel Management:** Add, edit, and manage personnel records.
  - **PA Configuration:** Set up evaluation criteria, rubrics, and scoring.
  - **Role Management:** Define user roles and permissions.
  - **Attendance:** Manage and record personnel attendance.

### User/Personnel Section (`/user/...`)

- **Controllers:** `ConUserHome`, `ConUserPaEvaluation`.
- **Functionality:**
  - **User Dashboard:** View personal information and evaluation status.
  - **Performance Evaluation:** Fill out and submit self-evaluation forms.
  - **View Scores:** Check evaluation results from supervisors.

## 6. Architectural Observations

- **Inconsistent Data Access:** The project uses a mix of CodeIgniter Models (for the `pa_evaluation` database) and raw Query Builder calls (for the `personnel` and `skj` databases). This inconsistency can make maintenance and debugging more difficult. A more consistent approach using Models for all database interactions is recommended.
- **Lack of Input Validation:** While not exhaustively checked, the reliance on raw queries suggests a potential risk of SQL injection if inputs are not properly sanitized. Using Models and the framework's built-in validation would mitigate this.
- **Frontend Asset Management:** Frontend assets (CSS/JS) are stored in the `assets/` directory and appear to be manually included in views. Using a more modern asset bundling tool could improve performance and development workflow.

## 7. UI/UX & System Design Standards

- **🇹🇭 Thai Calendar & Date Localization (ปฏิทินและวันที่ภาษาไทย พ.ศ. ทั้งระบบ):**
  - **Date Format:** Everywhere in the system (tables, badges, cards, modals, datepickers, headers, reports, and filters), dates must be displayed in **Thai format using the Buddhist Era (พ.ศ. / BE)**:
    - *Example (Short):* `dd/mm/yyyy (พ.ศ.)` เช่น `23/08/2569` (ปี ค.ศ. + 543)
    - *Example (Full/Medium):* `23 สิงหาคม 2569` หรือ `23 ส.ค. 2569`
  - **Global Flatpickr Configuration (ปฏิทิน Flatpickr ภาษาไทย พ.ศ. สากล):**
    - Must use Thai locale (`th`), Buddhist Era year (`applyThaiBE`), and custom luxury modern theme (Custom UI - ไม่ใช้หน้าตาเดิมๆ/จืดชืดของ template).
    - Header must have high-contrast background with crisp white typography and bold BE Year.
    - Dates must have smooth hover transitions, rounded selections, and high-contrast selected day indicator (`#0284c7`).
    - Date format on inputs: `d/m/Y (พ.ศ.)` using `altInput: true`, while sending `Y-m-d (ค.ศ.)` to the backend.
  - **Year Dropdowns & Filters:** Must always display and label years in Thai Buddhist Era (พ.ศ.) เช่น `พ.ศ. 2569 (2026)` หรือ `ปีการศึกษา / ปีงบประมาณ 2569`.
- **High-Contrast Badges & Labels:**
  - All `.badge` and `.bg-label-*` elements must strictly maintain high contrast (dark bold text `#0f172a` / `#0369a1` on tinted background with clear borders) to prevent blending into backgrounds.
- **💙 Hero & Header Card Signature Blue Theme (พื้นหลังการ์ดฮีโร่และแบนเนอร์สีฟ้า-น้ำเงินทั้งระบบ):**
  - All Hero Cards, Header Banners, and Welcome Panels across every page and subsystem (Admin & User) must strictly use the signature royal/ocean blue gradient (`linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #075985 100%)` / `--primary-gradient`) with crisp white typography (`#ffffff` / `#e0f2fe`) and high-contrast action buttons.

## 8. Business Logic: Leave Quota & Fiscal Year (การคำนวณโควตาวันลาและปีงบประมาณ)

- **🇹🇭 Auto Fiscal Year Calculation (รันปีงบประมาณอัตโนมัติ):**
  - ปีงบประมาณไทยนับตั้งแต่วันที่ **1 ตุลาคม ถึง 30 กันยายน** ของปีถัดไป (เช่น วันที่ระหว่าง 1 ต.ค. 2568 - 30 ก.ย. 2569 คือ **ปีงบประมาณ 2569**)
  - ระบบจะคำนวณและตัดรอบปีงบประมาณตามวันที่เริ่มต้นขอลา (`leave_start_date`) โดยอัตโนมัติ
- **⚖️ Fiscal Year Quota Rules (กฎเกณฑ์โควตาวันลาข้าราชการครู):**
  - **1 ปีงบประมาณ:** ครูสามารถลาได้สูงสุด **23 วันทำการ** (คำนวณวันลาที่อนุมัติแล้วทั้งหมดในปีงบนั้น)
  - **รอบ 2 ปีงบประมาณสะสม:** รวมวันลาสะสม 2 ปีงบประมาณติดต่อกันได้สูงสุด **45 วันทำการ** (เช่น ปีงบ 2568 + 2569 รวมไม่เกิน 45 วัน)




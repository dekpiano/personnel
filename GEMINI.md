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
- **🔤 Universal Typography Standard: K2D Font (ฟอนต์ K2D ภาษาไทยสากลทั้งระบบ):**
  - All pages, components, cards, tables, modals, menus, inputs, and buttons must strictly use Google Font **'K2D'** (`font-family: 'K2D', sans-serif !important;`) as the primary typeface for modern, clean, and legible Thai text.
  - Numbers, metrics, and KPI counters can complement with **'Outfit'** or bold weights of **'K2D'**.

## 8. Business Logic: Leave Quota & Fiscal Year (การคำนวณโควตาวันลาและปีงบประมาณ)

- **🇹🇭 Auto Fiscal Year Calculation (รันปีงบประมาณอัตโนมัติ):**
  - ปีงบประมาณไทยนับตั้งแต่วันที่ **1 ตุลาคม ถึง 30 กันยายน** ของปีถัดไป (เช่น วันที่ระหว่าง 1 ต.ค. 2568 - 30 ก.ย. 2569 คือ **ปีงบประมาณ 2569**)
  - ระบบจะคำนวณและตัดรอบปีงบประมาณตามวันที่เริ่มต้นขอลา (`leave_start_date`) โดยอัตโนมัติ
- **⚖️ Fiscal Year Quota Rules (กฎเกณฑ์โควตาวันลาข้าราชการครู):**
  - **1 ปีงบประมาณ:** ครูสามารถลาได้สูงสุด **23 วันทำการ** (คำนวณวันลาที่อนุมัติแล้วทั้งหมดในปีงบนั้น)
  - **รอบ 2 ปีงบประมาณสะสม:** รวมวันลาสะสม 2 ปีงบประมาณติดต่อกันได้สูงสุด **45 วันทำการ** (เช่น ปีงบ 2568 + 2569 รวมไม่เกิน 45 วัน)

---

## 9. 🧠 Master Programmer & System Architect (ทักษะโปรแกรมเมอร์ & สถาปนิกขั้นเทพ)

ผู้ช่วย AI จะทำงานในฐานะ **Senior Principal Full-Stack Engineer & System Architect** ที่มีความเข้าใจระบบอย่างลึกซึ้งและเฉียบคม:

### 1. Architectural & Multi-DB Mastery (เชี่ยวชาญสถาปัตยกรรมและการเชื่อมโยงข้อมูล)
- **Deep CodeIgniter 4 Insight:** เข้าใจวงจรชีวิต (Lifecycle) ของ Request, Routing, Filters/Middleware, Session Management, Controller, Models, Views และ Service Container อย่างถ่องแท้
- **Multi-Database Integrity:** จัดการและแยกแยะ Database 3 ชุด (`skjacth_personnel`, `skjacth_pa_evaluation`, `skjacth_skj`) อย่างแม่นยำ ไม่สับสน Connection Group และรักษา Data Integrity ของ Foreign Keys ข้ามฐานข้อมูล
- **Zero Regression Principle:** วิเคราะห์ผลกระทบ (Impact Analysis) รอบด้านก่อนลงมือแก้ไขโค้ดทุกครั้ง ไม่ทำให้ฟีเจอร์เดิม รูทเดิม หรือฟังก์ชันที่ทำงานร่วมกันพังเด็ดขาด

### 2. Root-Cause Debugging & Precision Fixes (แก้บั๊กตรงจุดระดับรากเหง้า)
- **End-to-End Tracing:** ไล่สายข้อมูลอย่างเป็นระบบ: `View (HTML/JS/AJAX)` ➡️ `Route/Filter` ➡️ `Controller Method` ➡️ `Model/Query Builder` ➡️ `MySQL Database`
- **Zero Symptom-Patching:** ไม่แก้ปัญหาแบบขอไปที แต่แก้ที่ต้นเหตุของ Logic, Data Structure หรือ Constraint เสมอ
- **Graceful Error Handling:** จัดการ Response (JSON/SweetAlert2/View) ป้องกันปัญหาหน้าขาว (White Screen), ป้องกัน PHP Warnings/Deprecations ใน PHP 8.1+, และมี Logging ที่ตรวจสอบได้

### 3. Clean, Secure & High-Performance Code (โค้ดสะอาด ปลอดภัย และประสิทธิภาพสูง)
- **Security by Default:** ป้องกัน SQL Injection ด้วย Prepared Statements/Query Builder ป้องกัน XSS ด้วย `esc()` และตรวจสอบ Session Authentication/Role Permissions สม่ำเสมอ
- **Optimized Queries:** หลีกเลี่ยง N+1 Query Problem, ใช้ Index ให้เกิดประโยชน์สูงสุด, และเขียน JOIN อย่างมีประสิทธิภาพ
- **Refactoring & Maintainability:** โค้ดอ่านง่าย มีโครงสร้างมาตรฐาน มี Comment อธิบาย Logic ซับซ้อนตามหลัก Clean Code

---

## 10. 🎨 Elite UX/UI Designer & Visual Architect (ทักษะนักออกแบบ UX/UI ระดับแนวหน้า)

ผู้ช่วย AI จะออกแบบและพัฒนาส่วนหน้า (Frontend) ในฐานะ **Lead Product Designer & Design System Architect**:

### 1. Pixel-Perfect Spacing & Golden Proportion (สัดส่วนเป๊ะทุกมิลลิเมตร)
- **8px Grid System & Hierarchy:** จัดวาง Layout, Padding, Margin, Gap, Line-height และ Card Dimensions ให้สมดุลกลมกลืนตามหลัก Modern UI/UX สากล
- **Responsive Mastery:** ออกแบบให้แสดงผลสวยงามอย่างไร้ที่ติบนทุกหน้าจอ (Mobile First, Tablet, Desktop, Ultra-wide Screen)
- **Component Consistency:** ฟอร์ม, ตาราง (DataTables), โมดอล (Modal), ป้ายสถานะ (Badges), ปุ่ม (Buttons), และ Dropdown ต้องมีสัดส่วน ความโค้งมน (Border Radius: 8px-16px) และเงา (Drop Shadow) ที่เป็นเนื้อเดียวกันทั้งระบบ

### 2. Luxury Aesthetics & Design System (ความพรีเมียมและสวยงามสะกดสายตา)
- **Signature Blue Palette:** ใช้พาเลทสีน้ำเงินรอยัล/โอเชียนหรูหรา (`#1d4ed8`, `#0284c7`, `#075985`, `#0f172a`) ผสานพื้นหลังการ์ดกระจก (Subtle Glassmorphism / Crisp White `#ffffff` with smooth border `#e2e8f0`)
- **Universal Typography:** บังคับใช้ Google Font **'K2D'** (`font-family: 'K2D', sans-serif !important;`) เป็นฟอนต์หลักทั้งระบบ สระและวรรณยุกต์ภาษาไทยเรียงตัวสวยงาม ไม่มีปัญหาตกขอบหรือเหลื่อมล้ำ ตัวเลขและสถิติคมชัด
- **High-Contrast Readability:** ตัวหนังสือและป้ายกำกับ (Badges/Labels) คมชัด อ่านง่าย สบายตา คอนทราสต์ตัดกับพื้นหลังชัดเจน ไม่จม ไม่กลืน
- **Delightful Micro-Interactions:** การ Hover, Focus, Active, Skeleton Loading, และ SweetAlert2 มี Transition ลื่นไหล นุ่มนวล (`0.25s cubic-bezier(0.4, 0, 0.2, 1)`)

### 3. Frictionless User Experience (ประสบการณ์ผู้ใช้ที่ราบรื่น ไร้รอยต่อ)
- **User-Centric Workflows:** ผู้ใช้ทำงานเสร็จได้ในคลิกที่น้อยที่สุด ฟอร์มกรอกง่าย มี Auto-complete, มี Validation Feedback ชัดเจน เข้าใจง่าย
- **100% Thai Buddhist Era (พ.ศ.):** วันที่ ปฏิทิน Flatpickr ปีงบประมาณ และรายงานทั้งหมดเป็นภาษาไทย พ.ศ. สากล ถูกต้องตามระเบียบราชการไทย 100%





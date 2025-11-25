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

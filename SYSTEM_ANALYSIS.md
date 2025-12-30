# System Analysis: Personnel Management (personnel2025)

บันทึกการวิเคราะห์ระบบบริหารจัดการบุคลากร (School Personnel Management System)
วันที่วิเคราะห์: 30 ธันวาคม 2025

## 1. ข้อมูลพื้นฐาน (Basic Information)

- **Framework:** CodeIgniter 4.x
- **Frontend Template:** Sneat Bootstrap 5 Admin Template
- **Database Architecture:** เชื่อมต่อหลายฐานข้อมูล (Multi-database)
  - `default` / `personnel`: เก็บข้อมูลบุคลากรหลัก
  - `pa_evaluation`: เก็บข้อมูลการประเมิน PA (Performance Appraisal)
  - `skj`: ข้อมูลด้านวิชาการ/สาระการเรียนรู้
- **Authentication:**
  - บุคลากรทั่วไป: ใช้ Google OAuth2
  - ผู้ดูแลระบบ: ใช้รหัสผ่าน (Traditional Login)

## 2. ฟีเจอร์หลักในระบบ (Core Features)

### 2.1 ระบบบริหารจัดการบุคลากร (Personnel Management)

- แสดงรายการบุคลากรทั้งหมด แยกตามกลุ่ม/ฝ่าย
- การเพิ่ม แก้ไข และลบข้อมูลบุคลากร
- ระบบอัปโหลดรูปภาพประจำตัว
- ระบบจัดการตำแหน่ง และกลุ่มงานบุคลากร
- ระบบเรียงลำดับบุคลากร (Sortable)

### 2.2 ระบบลงเวลาปฏิบัติราชการ (Attendance Management)

- บันทึกการลงเวลามาปฏิบัติราชการ
- แสดงสถิติการลงเวลาในรูปแบบ Dashboard
- ระบบสรุปการลา (Leave Summary) แยกตามตำแหน่งและวัน

### 2.3 ระบบประเมินผลการปฏิบัติงาน (PA Evaluation)

- การตั้งค่าขอบเขตการประเมิน (PA Config)
- การจัดการผู้ประเมิน (Evaluators)
- การจัดการเกณฑ์การประเมิน (Rubric Items)
- การบันทึกคะแนนการประเมิน
- ระบบรายงานผลคะแนนการประเมิน (PA Report)
- หน้าจัดการสำหรับบุคลากรเพื่อทำแบบประเมินตนเอง (Self-Evaluation)

### 2.4 ระบบจัดการสิทธิ์ผู้ใช้งาน (Role & Permission Management)

- กำหนดสิทธิ์ผู้ดูแลระบบ (Admin) และผู้จัดการ (Manager)
- กำหนดการเข้าถึงส่วนต่างๆ ของระบบตามหน้าที่

## 3. สิ่งที่พบเพิ่มเติม (Potential & In-Progress Features)

พบไฟล์ SQL ในรูทของโปรเจกต์ที่บ่งบอกถึงฟีเจอร์อื่นๆ ที่อาจจะกำลังพัฒนาหรือเคยมีอยู่:

- **ระบบจองห้อง (Booking System):** มีตาราง `tb_booking` และ `tb_location`
- **ระบบแจ้งซ่อม (Repair System):** มีตาราง `tb_repair` ที่รองรับการเซ็นชื่อดิจิทัล (Signature Pad)
- **ระบบรับนักเรียน (Enrollment):** มีการกล่าวถึงในประวัติการสนทนา แต่ยังไม่พบไฟล์ Logic หลักใน Workspace นี้

## 4. เทคโนโลยีที่ใช้ (Tech Stack Details)

- **Backend:** PHP 8+
- **Database:** MySQL
- **Libraries/Tools:**
  - **jQuery & Bootstrap 5:** จัดการ UI
  - **DataTables:** แสดงผลและค้นหาข้อมูลในตาราง
  - **ApexCharts:** แสดงผลกราฟสถิติ
  - **Signature Pad:** ใช้สำหรับเซ็นชื่อดิจิทัลในงานแจ้งซ่อม
  - **Flatpickr:** เลือกวันที่
  - **SweetAlert2:** แสดงการแจ้งเตือน
  - **Google API Client:** สำหรับ Google Login

## 5. ข้อสังเกตด้านความปลอดภัยและโครงสร้าง (Architectural Observations)

- มีการใช้ Raw Query ในบางส่วนและใช้ Model ในบางส่วน (ตามที่ระบุใน Gemini.md)
- ข้อมูลบางส่วนยังถูกจัดเก็บในรูปแบบ SVG Base64 (Signature) โดยตรงในฐานข้อมูล
- ระบบมีการแยก Layout ระหว่าง Admin และ User ชัดเจน

---

_บันทึกนี้จัดทำขึ้นโดย Antigravity เพื่อใช้เป็นข้อมูลอ้างอิงในการพัฒนาและปรับปรุงระบบต่อไป_

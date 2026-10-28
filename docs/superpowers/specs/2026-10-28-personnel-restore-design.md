# Spec: Personnel Restore System (ระบบกู้คืนข้อมูลครูและบุคลากรที่พ้นสภาพ/ลาออก)

**Date:** 2026-10-28  
**Feature:** Personnel Restore System for Resigned / Inactive Personnel  
**Path:** `Admin/WorkPerson/Personnel`  
**Target:** School Personnel Management System (CodeIgniter 4)

---

## 1. Background & Problem Statement
ในระบบปัจจุบัน ข้อมูลของครูและบุคลากรที่ลาออก ย้ายสถานศึกษา หรือเกษียณอายุ ยังคงถูกบันทึกไว้อย่างสมบูรณ์ในตาราง `tb_personnel` ของฐานข้อมูล `skjacth_personnel` (มีข้อมูลผู้พ้นสภาพรวม 68 รายการ โดยเป็นสถานะ "ลาออก" 50 รายการ)

อย่างไรก็ดี ในหน้าหลักทะเบียนบุคลากร `Admin/WorkPerson/Personnel` และหน้ารายกลุ่ม `Admin/WorkPerson/Personnel/Group/...` มีเงื่อนไขกรองเฉพาะ `pers_status = 'กำลังใช้งาน'` เท่านั้น ส่งผลให้:
1. เมื่อบุคลากรลาออกไปแล้ว ผู้ดูแลระบบจะไม่เห็นรายชื่อในหน้าใดๆ ของระบบอีก
2. เมื่อบุคลากรคนเดิมกลับเข้ามาปฏิบัติงานใหม่ (เช่น สอบบรรจุกลับมา, กลับมาเป็นอัตราจ้างใหม่, โอนย้ายกลับมา) ผู้ดูแลระบบไม่สามารถค้นหาหรือกดกู้คืนประวัติเดิมได้ ทำให้เกิดความยุ่งยากหรืออาจเผลอสร้างรหัสและข้อมูลซ้ำซ้อน

---

## 2. Goals & Anti-Goals
- **Goals:**
  - สร้างจุดเชื่อมต่อ (Action Button) ในหน้า `Admin/WorkPerson/Personnel` เพื่อเปิด "ศูนย์กู้คืนข้อมูลบุคลากร" (Personnel Restore Center)
  - ให้ค้นหาบุคลากรที่ลาออก/ย้าย/เกษียณ ได้สะดวกรวดเร็ว (ค้นหาตามชื่อ-สกุล, ตำแหน่งเดิม, เลขบัตรประชาชน หรือกลุ่มสาระเดิม)
  - มีกระบวนการกู้คืน (Restore Flow) ที่ปลอดภัย ผ่าน Modal ปรับปรุงสถานะ ตำแหน่ง และกลุ่มสาระใหม่ พร้อมสลับสถานะเป็น `กำลังใช้งาน`
  - มีปุ่มทางเลือกว่าจะ "กู้คืนและแก้ไขข้อมูลต่อทันที" (เปิดหน้า Update) หรือ "กู้คืนและอยู่ในหน้ารายชื่อต่อ"
  - รองรับ UI หรูหรา สไตล์ K2D Font, High-Contrast Badges และตอบสนองรวดเร็วผ่าน AJAX & SweetAlert2
- **Anti-Goals:**
  - ไม่ลบประวัติการทำงานเดิมใน `tb_personnel_work_history` หรือประวัติอื่นใดที่มีอยู่แล้ว
  - ไม่เปลี่ยนแปลงรหัสประจำตัวบุคลากร (`pers_id`) เดิม เพื่อคงความต่อเนื่องของข้อมูลประวัติและเอกสาร

---

## 3. Architecture & Data Flow

### 3.1 New Routes
1. `GET Admin/WorkPerson/Personnel/DB/GetInactivePersonnel`
   - คืนค่ารายการบุคลากรที่ `pers_status != 'กำลังใช้งาน'` ในรูปแบบ JSON (สำหรับค้นหาและแสดงใน Modal)
2. `POST Admin/WorkPerson/Personnel/DB/Restore`
   - รับค่า: `pers_id`, `pers_position`, `pers_learning`, `pers_academic`, `pers_workother_id`, `pers_status` (ค่าเริ่มต้น 'กำลังใช้งาน')
   - อัปเดตข้อมูลในตาราง `tb_personnel`
   - เพิ่มประวัติการทำงาน (tb_personnel_work_history) อัตโนมัติ (ถ้าต้องการบันทึกประวัติการกลับเข้ารับตำแหน่งใหม่)
   - ส่ง JSON Response แจ้งสถานะความสำเร็จ

### 3.2 UI Components
1. **Header Button in `AdminPersonMain.php`:**
   - ปุ่ม **"กู้คืนข้อมูลบุคลากร"** พร้อม Badge แสดงยอดรวมผู้ที่สามารถกู้คืนได้ (เช่น `68 คน`) ไอคอน `bx bx-user-check` หรือ `bx bx-history`
2. **Personnel Restore Modal (`#modalRestorePersonnel`):**
   - ตารางรายการบุคลากรที่พ้นสภาพ (Live Search, Filter by Status เช่น ทั้งหมด / ลาออก / ย้ายสถานศึกษา)
   - ข้อมูลแต่ละคน: รูปโปรไฟล์, ชื่อ-สกุล, ตำแหน่งเดิม, กลุ่มสาระเดิม, สถานะล่าสุด (Badge สีชัดเจน), ปุ่มกด "กู้คืนข้อมูล"
3. **Quick Reassign / Confirm Modal (`#modalConfirmRestore`):**
   - ฟอร์มเลือกตำแหน่งใหม่ (`pers_position`), สายงาน (`pers_workother_id`), กลุ่มสาระ (`pers_learning`), วิทยฐานะ (`pers_academic`)
   - ปุ่ม "ยืนยันการกู้คืน" -> ดำเนินการผ่าน AJAX -> SweetAlert2 แจ้งผลสำเร็จ พร้อมปุ่มไปแก้ไขข้อมูลต่อทันที

---

## 4. UI/UX Standards Checklist (GEMINI.md)
- [x] Universal Typography: Google Font **K2D**
- [x] High-Contrast Badges (`bg-label-warning text-dark`, `bg-label-danger text-dark` เป็นต้น)
- [x] Signature Royal/Ocean Blue Palette
- [x] Thai Date / Buddhist Era where applicable
- [x] Smooth SweetAlert2 transitions and error handling

# Design Specification: Modern Profile Card for Personnel Directory (CodewithFaraz Inspired)

## 1. Intent & Goals
Redesign the personnel directory cards in `/directory` (`UserDirectoryMain.php`) based on the modern profile card from CodeWithFaraz (`https://www.codewithfaraz.com/content/487/create-modern-profile-card-using-html-and-css`).

## 2. Requirements & Aesthetics
- Full-immersive portrait photo card (`border-radius: 24px`, smooth box shadow).
- Bottom glassmorphism overlay (`backdrop-filter: blur(8px)`, gradient from slate/ocean dark to transparent).
- Teacher / Staff Name in K2D font + Verified checkmark badge (`bx bxs-badge-check text-sky-400` / SVG badge).
- Position / Academic Standing / Group title.
- 3-column Quick Info Bar separated by vertical dividers:
  1. กลุ่มสาระ / หน่วยงาน
  2. ตำแหน่ง / วิทยฐานะ
  3. สถานะ ("ปฏิบัติงาน")
- Bottom Action buttons:
  - Main button (col-span-3 or flex-grow): Glowing pill button "ดูข้อมูลประวัติ" with icon arrow and hover glow effect.
  - Secondary button: Quick contact button (call `tel:phone` or profile icon if no phone).
- Top role badges for special positions:
  - ผู้อำนวยการ (Golden badge with crown)
  - รองผู้อำนวยการ (Ocean blue badge with star)
  - หัวหน้ากลุ่มสาระ (Violet / Indigo badge with crown)
- Fallback placeholder when no photo exists: Initials in a vibrant modern gradient background.
- Responsive grid (Mobile: 2 cols, Tablet: 3 cols, Desktop: 4 cols).

## 3. Touched Files
- `app/Controllers/ConUserDirectory.php`: Add `pers_phone` and `pers_nickname` to query selects.
- `app/Views/User/UserDirectory/UserDirectoryMain.php`: Modernize CSS styles and card template.

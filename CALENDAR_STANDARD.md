# มาตรฐานการใช้งานปฏิทิน พ.ศ. (Thai Clean Reset Standard - Updated CSS)

นี่คือการตั้งค่าที่สะอาดและเสถียรที่สุด โดยรีเซตทุกอย่างกลับไปเป็นค่าเริ่มต้นและใช้เทคนิคการซ้อนทับ (Overlay) แบบบางเบาที่สุด ไม่กระทบเลย์เอาต์เดิมของไลบรารี

## 1. การตั้งค่าระบบกลาง (Global Configuration)
ใส่ไว้ใน `main.php` หรือ `UserFooter.php`:

```html
<style>
    /* บังคับแสดงลูกศรเปลี่ยนปีเสมอ ไม่ต้องรอเอาเมาส์ชี้ (Hover) เพื่อความชัดเจนต่อผู้ใช้ */
    .flatpickr-current-month .numInputWrapper span.arrowUp,
    .flatpickr-current-month .numInputWrapper span.arrowDown {
        opacity: 1 !important;
        visibility: visible !important;
    }
</style>

<script>
if (typeof flatpickr !== 'undefined') {
    flatpickr.localize(flatpickr.l10ns.th); // รีเซตตั้งค่าภาษาไทยเป็นค่าเริ่มต้น

    const applyThaiBE = (instance) => {
        const yearInput = instance.calendarContainer?.querySelector(".cur-year");
        if (!yearInput) return;
        
        // ใช้สีโปร่งใสซ่อนปี ค.ศ. แต่ยังคงลูกศรและ Layout เดิมไว้
        yearInput.style.color = "transparent";
        
        let beWrap = yearInput.parentElement.querySelector(".be-year");
        if (!beWrap) {
            beWrap = document.createElement("span");
            beWrap.className = "be-year";
            // ใช้ transform เพื่อให้อยู่กึ่งกลางเป๊ะๆ ไม่ดัน layout ของเดิม
            beWrap.style.cssText = "position:absolute; left:0; width:100%; top:50%; transform:translateY(-50%); text-align:center; padding-right:15px; box-sizing:border-box; pointer-events:none; color:inherit; font-family:inherit; margin:0;";
            yearInput.parentElement.appendChild(beWrap);
            yearInput.parentElement.style.position = "relative";
        }
        
        let y = instance.currentYear;
        beWrap.innerText = y > 2400 ? y : y + 543; // ป้องกันบวกเบิ้ล 543
    };

    // ตั้งค่าค่าเริ่มต้นใหม่ทั้งหมด
    flatpickr.setDefaults({
        dateFormat: "Y-m-d", // สำหรับลงฐานข้อมูล
        altInput: true,
        altFormat: "d-m-Y", // 01-01-2539 (พ.ศ.)
        allowInput: true,
        formatDate: (date) => {
            const d = date.getDate().toString().padStart(2, '0');
            const m = (date.getMonth() + 1).toString().padStart(2, '0');
            const y = date.getFullYear() + 543;
            return `${d}-${m}-${y}`;
        },
        parseDate: (dateStr) => {
            if (!dateStr || /^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return new Date(dateStr);
            const p = dateStr.split('-');
            if (p.length === 3) {
                let y = parseInt(p[2]);
                if (y > 2400) y -= 543; // ลบ 543 กลับเป็น ค.ศ.
                return new Date(y, parseInt(p[1]) - 1, parseInt(p[0]));
            }
            return new Date(dateStr);
        },
        onReady: (d, s, i) => applyThaiBE(i),
        onMonthChange: (d, s, i) => applyThaiBE(i),
        onYearChange: (d, s, i) => applyThaiBE(i)
    });
}
</script>
```

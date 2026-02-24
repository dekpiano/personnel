document.addEventListener("DOMContentLoaded", function () {
  let TbSaveAttendance = null;

  function loadAttendanceTable() {
    const tbody = document.getElementById("personnel-tbody");
    const date = document.getElementById("att_date").value;
    const loadingEl = document.getElementById("attendance-loading");
    const tableContainer = document.getElementById(
      "attendance-table-container",
    );

    // Show loading, hide table
    loadingEl.style.display = "block";
    tableContainer.style.display = "none";

    fetch("../Admin/SaveAttendance/DB/Select/GetPersonnalData")
      .then((res) => res.json())
      .then((personnel) => {
        fetch(
          "../Admin/SaveAttendance/DB/Select/GetAttendanceToDate?date=" + date,
        )
          .then((res) => res.json())
          .then((attendance) => {
            if (TbSaveAttendance) {
              TbSaveAttendance.destroy();
              $("#TbSaveAttendance tbody").empty();
            }

            let html = "";
            personnel.forEach(function (p) {
              const att =
                attendance.find((a) => a.person_id == p.pers_id) || {};
              const status = att.status || "มา";
              const remark = att.remark || "";
              html += `<tr>
                                <td>
                                    <div class="fw-bold">${p.pers_prefix}${p.pers_firstname} ${p.pers_lastname}</div>
                                    <small class="text-muted">${p.posi_name}</small>
                                </td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="มา" ${status == "มา" ? "checked" : ""}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="สาย" ${status == "สาย" ? "checked" : ""}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ขาด" ${status == "ขาด" ? "checked" : ""}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ลากิจ" ${status == "ลากิจ" ? "checked" : ""}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ลาป่วย" ${status == "ลาป่วย" ? "checked" : ""}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="ไปราชการ" ${status == "ไปราชการ" ? "checked" : ""}></td>
                                <td class="text-center"><input class="form-check-input" type="radio" name="status[${p.pers_id}]" value="อื่นๆ" ${status == "อื่นๆ" ? "checked" : ""}></td>
                                <td><input type="text" class="form-control form-control-sm" name="remark[${p.pers_id}]" value="${remark}" placeholder="-"></td>
                            </tr>`;
            });
            tbody.innerHTML = html;

            // Hide loading, show table
            loadingEl.style.display = "none";
            tableContainer.style.display = "block";
          })
          .catch((err) => {
            console.error(`เกิดข้อผิดพลาด: ${err.message}`);
            loadingEl.innerHTML = `<p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
          });
      })
      .catch((err) => {
        console.error(`เกิดข้อผิดพลาด: ${err.message}`);
        loadingEl.innerHTML = `<p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
      });
  }

  document
    .getElementById("attendanceModal")
    .addEventListener("show.bs.modal", loadAttendanceTable);
  document
    .getElementById("att_date")
    .addEventListener("change", loadAttendanceTable);

  document
    .getElementById("attendance-form")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      // Get all rows from tbody directly (not using DataTables)
      const tbody = document.getElementById("personnel-tbody");
      const allRows = tbody.querySelectorAll("tr");

      allRows.forEach(function (row) {
        let radios = row.querySelectorAll('input[type="radio"]:checked');
        radios.forEach((input) => formData.append(input.name, input.value));
        let remark = row.querySelector('input[name^="remark"]');
        if (remark) formData.append(remark.name, remark.value);
      });

      const btn = document.getElementById("btn-save-attendance");
      if (btn) {
        btn.disabled = true;
        btn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-2"></span>กำลังบันทึก...';
      }

      fetch("../Admin/SaveAttendance/DB/Select/SaveAttendanceToDB", {
        method: "POST",
        body: formData,
      })
        .then((res) => {
          if (!res.ok) throw new Error("บันทึกข้อมูลล้มเหลว");
          return res.json();
        })
        .then((result) => {
          bootstrap.Modal.getInstance(
            document.getElementById("attendanceModal"),
          ).hide();
          if (result.success) {
            Swal.fire({
              icon: "success",
              title: "บันทึกสำเร็จ!",
              text: "บันทึกข้อมูลการมาทำงานเรียบร้อยแล้ว",
              timer: 1500,
              showConfirmButton: false,
            });
            setTimeout(updateDashboard, 500);
          } else {
            Swal.fire({
              icon: "error",
              title: "เกิดข้อผิดพลาด",
              text: result.message || "ไม่สามารถบันทึกข้อมูลได้",
            });
          }
        })
        .catch((err) =>
          Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดพลาด",
            text: err.message,
          }),
        )
        .finally(() => {
          if (btn) {
            btn.disabled = false;
            btn.innerHTML =
              '<i class="bi bi-check2-circle me-1"></i> บันทึกข้อมูล';
          }
        });
    });

  const dateType = document.getElementById("dateType");
  const container = document.getElementById("datePickerContainer");
  dateType.addEventListener("change", function () {
    let html = "";
    if (this.value === "day") {
      html = `<label class="filter-label">เลือกวันที่</label>
                    <input type="date" id="dateInput" class="form-control filter-input" value="${new Date().toISOString().split("T")[0]}">`;
    } else if (this.value === "month") {
      html = `<label class="filter-label">เลือกเดือน</label>
                    <input type="month" id="dateInput" class="form-control filter-input" value="${new Date().toISOString().split("T")[0].slice(0, 7)}">`;
    } else if (this.value === "year") {
      html = `<label class="filter-label">เลือกปี</label>
                    <input type="number" id="dateInput" class="form-control filter-input" min="2020" max="2100" value="${new Date().getFullYear()}">`;
    }
    container.innerHTML = html;
    setTimeout(() => {
      const dateInput = document.getElementById("dateInput");
      if (dateInput) dateInput.addEventListener("change", updateDashboard);
      updateDashboard();
    }, 50);
  });

  let chart;
  let datatable = null;

  function updateDashboard() {
    const type = document.getElementById("dateType").value;
    const inputEl = document.getElementById("dateInput");
    if (!inputEl) return;
    const dateInput = inputEl.value;
    let params = `type=${type}&value=${dateInput}`;

    fetch("../Admin/SaveAttendance/DB/Select/DashboardAttendance?" + params)
      .then((res) => res.json())
      .then((data) => {
        // Chart
        const series = [
          data.stats.present,
          data.stats.late,
          data.stats.absent,
          data.stats.personal,
          data.stats.sick,
          data.stats.official,
          data.stats.other,
        ];
        const labels = [
          "มาทำงาน",
          "มาสาย",
          "ขาดงาน",
          "ลากิจ",
          "ลาป่วย",
          "ไปราชการ",
          "อื่นๆ",
        ];
        const colors = [
          "#28a745",
          "#17a2b8",
          "#dc3545",
          "#fd7e14",
          "#ffc107",
          "#007bff",
          "#6c757d",
        ];

        if (chart) chart.destroy();
        chart = new ApexCharts(document.querySelector("#chart"), {
          chart: {
            type: "donut",
            height: 350,
            fontFamily: "thsarabun, sans-serif",
          },
          series,
          labels,
          colors,
          stroke: { width: 0 },
          plotOptions: {
            pie: {
              donut: {
                size: "70%",
                labels: {
                  show: true,
                  total: {
                    show: true,
                    label: "มาทำงาน",
                    formatter: () => data.stats.present_percent + "%",
                  },
                },
              },
            },
          },
          legend: { position: "bottom", fontSize: "14px" },
        });
        chart.render();

        // Summary Cards
        document.getElementById("summaryBox").innerHTML = `
                    ${renderStatCard("มาทำงาน", data.stats.present_percent, "bi-person-check-fill", "icon-present")}
                    ${renderStatCard("มาสาย", data.stats.late_percent, "bi-clock-history", "icon-late")}
                    ${renderStatCard("ขาดงาน", data.stats.absent_percent, "bi-person-x-fill", "icon-absent")}
                    ${renderStatCard("ลาป่วย", data.stats.sick_percent, "bi-thermometer-half", "icon-sick")}
                    ${renderStatCard("ลากิจ", data.stats.personal_percent, "bi-person-workspace", "icon-personal")}
                    ${renderStatCard("ไปราชการ", data.stats.official_percent, "bi-briefcase-fill", "icon-official")}
                    ${renderStatCard("อื่นๆ", data.stats.other_percent, "bi-three-dots", "icon-other")}
                `;

        // Table
        if (datatable) {
          datatable.destroy();
          datatable = null;
        }
        $("#TbDashboradAttendance tbody").empty();

        let rows = "";
        if (data.table && data.table.length) {
          data.table.forEach((row) => {
            let badgeClass = getBadgeClass(row.status);
            rows += `<tr>
                            <td class="text-nowrap">${row.date}</td>
                            <td class="fw-bold">${row.name}</td>
                            <td><span class="badge-status ${badgeClass}">${row.status}</span></td>
                            <td class="text-muted">${row.remark || "-"}</td>
                        </tr>`;
          });
          $("#TbDashboradAttendance tbody").html(rows);
          datatable = $("#TbDashboradAttendance").DataTable({
            pageLength: 25,
            language: {
              search: "ค้นหา:",
              lengthMenu: "แสดง _MENU_ รายการ",
              info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
              paginate: {
                first: "หน้าแรก",
                last: "หน้าสุดท้าย",
                next: "ถัดไป",
                previous: "ก่อนหน้า",
              },
              emptyTable: "ไม่พบข้อมูล",
            },
          });
        } else {
          $("#TbDashboradAttendance tbody").html(`
                        <tr>
                            <td class="text-center py-4 text-muted">-</td>
                            <td class="text-center py-4 text-muted">ไม่พบข้อมูลการมาทำงานในช่วงเวลานี้</td>
                            <td class="text-center py-4 text-muted">-</td>
                            <td class="text-center py-4 text-muted">-</td>
                        </tr>`);
        }
      })
      .catch((err) => console.error("Error:", err));
  }

  function renderStatCard(label, value, icon, iconClass) {
    return `
            <div class="col-xl-3 col-md-4 col-6">
                <div class="stat-card">
                    <div class="stat-icon ${iconClass}"><i class="bi ${icon}"></i></div>
                    <div class="stat-value">${value}%</div>
                    <div class="stat-label">${label}</div>
                </div>
            </div>
        `;
  }

  function getBadgeClass(status) {
    switch (status) {
      case "มา":
        return "badge-present";
      case "สาย":
        return "badge-late";
      case "ขาด":
        return "badge-absent";
      case "ลาป่วย":
        return "badge-sick";
      case "ลากิจ":
        return "badge-personal";
      case "ไปราชการ":
        return "badge-official";
      default:
        return "badge-other";
    }
  }

  const initialDateInput = document.getElementById("dateInput");
  if (initialDateInput)
    initialDateInput.addEventListener("change", updateDashboard);
  updateDashboard();

  // Handle Parse Excel Button
  document.getElementById("btn-parse-excel").addEventListener("click", function () {
    let fileInput = document.getElementById("excel_file");
    if (fileInput.files.length === 0) {
      Swal.fire({
        icon: "warning",
        title: "กรุณาเลือกไฟล์",
        text: "คุณต้องเลือกไฟล์ Excel ก่อนดึงข้อมูล",
      });
      return;
    }

    let formData = new FormData();
    formData.append("excel_file", fileInput.files[0]);

    const btn = document.getElementById("btn-parse-excel");
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>กำลังดึง...';

    fetch("../Admin/SaveAttendance/UploadExcel", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          return response.json().then((err) => {
            throw new Error(err.message || "เกิดข้อผิดพลาดจากเซิร์ฟเวอร์");
          });
        }
        return response.json();
      })
      .then((result) => {
        if (result.status === "success") {
          const parsedData = result.data || {};
          let foundCount = 0;
          let notFoundCount = 0;
          
          // Loop through table rows and update radio inputs
          const tbody = document.getElementById('personnel-tbody');
          const allRows = tbody.querySelectorAll('tr');

          allRows.forEach(function(row) {
            // Find pers_id from the radio input name string "status[pers_id]"
            let anyRadio = row.querySelector('input[type="radio"]');
            if (anyRadio) {
              let match = anyRadio.name.match(/status\[(.*?)\]/);
              if (match && match[1]) {
                let pId = match[1];
                let data = parsedData[pId];

                if (data) {
                  // User found in Excel data
                  let targetedRadio = row.querySelector(`input[type="radio"][value="${data.status}"]`);
                  if (targetedRadio) {
                     targetedRadio.checked = true;
                  }
                  
                  let remarkInput = row.querySelector(`input[name="remark[${pId}]"]`);
                  if (remarkInput) {
                     remarkInput.value = data.remark;
                  }
                  foundCount++;
                } else {
                  // User not found in Excel data, default to "ขาด"
                  let absentRadio = row.querySelector(`input[type="radio"][value="ขาด"]`);
                  if (absentRadio) {
                     absentRadio.checked = true;
                  }
                  
                  let remarkInput = row.querySelector(`input[name="remark[${pId}]"]`);
                  if (remarkInput) {
                     remarkInput.value = "ไม่มีข้อมูลการสแกน";
                  }
                  notFoundCount++;
                }
              }
            }
          });

          Swal.fire({
            icon: "success",
            title: "ดึงข้อมูลสำเร็จ!",
            text: `พบข้อมูลการสแกน ${foundCount} คน และ ไม่พบข้อมูล ${notFoundCount} คน\n\nโปรดตรวจสอบรายการและกด "บันทึกข้อมูล" ด้านล่าง`,
          });
          
        } else {
          throw new Error(result.message || "เกิดข้อผิดพลาดในการประมวลผลไฟล์");
        }
      })
      .catch((error) => {
        Swal.fire({
          icon: "error",
          title: "เกิดข้อผิดพลาด",
          text: error.message,
        });
      })
      .finally(() => {
        btn.disabled = false;
        btn.innerHTML = 'ดึงข้อมูล';
      });
  });
});

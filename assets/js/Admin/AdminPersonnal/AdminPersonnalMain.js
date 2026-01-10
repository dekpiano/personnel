$(".select2Personnel").select2({
  placeholder: "เลือกตัวเลือก",
  allowClear: true,
  dropdownParent: $(".content-wrapper"), // Ensure it works in modals/tabs if needed
});

// Flatpickr initialization
$(".selectorEdit").flatpickr({
  dateFormat: "d/m/Y",
  locale: "th",
  allowInput: true,
});

// Input Mask initialization
$("#pers_id_card").inputmask("9-9999-99999-99-9");

//ฟังก์ชันหาอายุ
function calculateAge(dateStr) {
  if (!dateStr || dateStr === "00/00/0000" || dateStr === "0000-00-00")
    return "";

  let day, month, year;
  let parts;

  if (dateStr.includes("/")) {
    // DD/MM/YYYY
    parts = dateStr.split("/");
    if (parts.length !== 3) return "";
    day = parseInt(parts[0]);
    month = parseInt(parts[1]);
    year = parseInt(parts[2]);
  } else if (dateStr.includes("-")) {
    // YYYY-MM-DD
    parts = dateStr.split("-");
    if (parts.length !== 3) return "";
    year = parseInt(parts[0]);
    month = parseInt(parts[1]);
    day = parseInt(parts[2]);
  } else {
    return "";
  }

  if (isNaN(day) || isNaN(month) || isNaN(year)) return "";

  // Smart BE/CE Detection
  // If year is > 2400, it's almost certainly Buddhist Era
  if (year > 2400) {
    year -= 543;
  }

  const birthDate = new Date(year, month - 1, day);
  if (isNaN(birthDate.getTime())) return "";

  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const m = today.getMonth() - birthDate.getMonth();

  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age;
}

// Helper to get FormData with cropped images
function getFormDataWithCroppedImages(form) {
  const formData = new FormData(form);
  $(form)
    .find('input[type="file"].crop-target')
    .each(function () {
      const blob = $(this).data("cropped-blob");
      if (blob) {
        const name = $(this).attr("name");
        const filename = $(this).val().split("\\").pop() || "image.jpg";
        formData.set(name, blob, filename);
      }
    });
  return formData;
}

$(document).on("submit", "#FormPersonnalAdd", function (e) {
  e.preventDefault();

  const formData = getFormDataWithCroppedImages(this);

  $.ajax({
    url: "../../../Admin/WorkPerson/Personnel/DB/Insert",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    dataType: "json",
    success: function (res) {
      if (res.status === "success" || res == 1) {
        Swal.fire({
          title: "แจ้งเตือน",
          text: "บันทึกข้อมูลสำเร็จ!",
          icon: "success",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../../../Admin/WorkPerson/Personnel";
          }
        });
      } else {
        Swal.fire(
          "แจ้งเตือน!",
          res.message || "บันทึกข้อมูลไม่สำเร็จ!",
          "error"
        );
      }
    },
  });
});

$(document).on("submit", "#FormPersonnalUpdateDataPersonnel", function (e) {
  e.preventDefault();
  console.log("555");
  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Update/DataPersonnel",
    method: "POST",
    data: new FormData(this),
    processData: false,
    contentType: false,
    cache: false,
    dataType: "json",
    success: function (res) {
      if (res.status === "success" || res == 1) {
        Swal.fire({
          title: "แจ้งเตือน",
          text: "อัปเดตข้อมูลสำเร็จ!",
          icon: "success",
        });
      } else {
        Swal.fire(
          "แจ้งเตือน!",
          res.message || "บันทึกข้อมูลไม่สำเร็จ!",
          "error"
        );
      }
    },
    error: function () {
      alert("ไม่พบข้อมูลบุคลากร");
    },
  });
});

// Update Personal History (Trigger via Button Click)
$(document).on("click", "#btnSaveHistory", function (e) {
  e.preventDefault();

  // Show loading
  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  const form = document.getElementById("FormPersonnalHistory");
  const formData = new FormData(form);

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Update/DataHistory",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success" || res.data == 1 || res == 1) {
        Swal.fire({
          title: "สำเร็จ!",
          text: "บันทึกประวัติส่วนตัวเรียบร้อยแล้ว",
          icon: "success",
        });
      } else {
        Swal.fire(
          "แจ้งเตือน!",
          res.message || "บันทึกข้อมูลไม่สำเร็จ!",
          "error"
        );
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("ข้อผิดพลาด!", "ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้", "error");
    },
  });
});

// License Info Form Handler
$(document).on("submit", "#FormLicenseInfo", function (e) {
  e.preventDefault();
  const PresID = $("#pers_id").val();
  const formData = $(this).serializeArray();

  // Show loading
  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  // Send each field individually using existing PersonnelUpdateAlone endpoint
  const promises = formData.map((item) => {
    return $.ajax({
      url: "../../../../Admin/WorkPerson/Personnel/DB/Update/Alone",
      type: "POST",
      data: {
        PresID: PresID,
        field: item.name,
        value: item.value,
      },
      dataType: "json",
    });
  });

  Promise.all(promises)
    .then(() => {
      Swal.close();
      Swal.fire({
        title: "สำเร็จ!",
        text: "บันทึกข้อมูลใบประกอบวิชาชีพเรียบร้อยแล้ว",
        icon: "success",
      });
    })
    .catch(() => {
      Swal.close();
      Swal.fire("ข้อผิดพลาด!", "บันทึกข้อมูลไม่สำเร็จ", "error");
    });
});

$(document).on("cropped", 'input[name="pers_img"]', function (e, blob) {
  // Check if we are on the Update page (not Add page)
  if ($("#ChangeImgPersonnal").length > 0) {
    var formData = new FormData();
    formData.append("file", blob, "profile.jpg");
    formData.append("KeyPresID", $(this).attr("key-persid"));

    $.ajax({
      url: "../../../../Admin/WorkPerson/Personnel/DB/Update/Img",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
      success: function (res) {
        if (res.status === "success" || res == 1) {
          Swal.fire("แจ้งเตือน!", "เปลี่ยนรูปภาพสำเร็จ", "success");
        } else {
          Swal.fire(
            "แจ้งเตือน!",
            "เปลี่ยนรูปภาพไม่สำเร็จ: " + (res.message || res),
            "error"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Upload Error:", xhr.responseText);
        Swal.fire(
          "เกิดข้อผิดพลาด!",
          "ไม่สามารถอัปโหลดรูปภาพได้ (500 Internal Server Error)<br><small>" +
            error +
            "</small>",
          "error"
        );
      },
    });
  } else {
    Swal.fire("แจ้งเตือน!", "ไม่พบรหัสบุคลากรสำหรับอัปเดตรูปภาพ", "warning");
  }
});

function loadPersonnelData(id) {
  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Get/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      $("#pers_position").val(data[0].pers_position); // ทำให้ "ผู้จัดการ" ถูกเลือก

      var index = $("#pers_position").prop("selectedIndex"); // รับลำดับที่เลือก (0-based)

      if ($("#key_update").val() === "Update") {
        if (index > 0 && index <= 6) {
          // แสดง select ถัดไป
          $("#show_learning").show();
          $("#show_position").hide();
          $("#pers_workother_id").removeAttr("required");
          $("#pers_workother_id").val("");
        } else if (index >= 7) {
          $("#show_position").show();
          $("#show_learning").hide();
          $("#pers_learning").val("");
        }
      }

      $(".pers_prefix").val(data[0].pers_prefix);
      $(".pers_firstname").val(data[0].pers_firstname);
      $(".pers_lastname").val(data[0].pers_lastname);
      $(".pers_id_card").val(data[0].pers_id_card);
      $(".pers_britday").val(data[0].pers_britday);
      $(".pers_nationality").val(data[0].pers_nationality);
      $(".pers_race").val(data[0].pers_race);
      $(".pers_religion").val(data[0].pers_religion);
      $(".pers_marital_status").val(data[0].pers_marital_status);
      $(".pers_blood_type").val(data[0].pers_blood_type);
      $(".pers_email").val(data[0].pers_username);
      $(".pers_phone").val(data[0].pers_phone);
      $(".pers_facebook").val(data[0].pers_facebook);
      $(".pers_instagram").val(data[0].pers_instagram);
      $(".pers_youtube").val(data[0].pers_youtube);
      $(".pers_social_links").val(data[0].pers_social_links);
      $(".pers_line").val(data[0].pers_line);
      $(".pers_military_service").val(data[0].pers_military_service);

      $("#pers_age").val(calculateAge(data[0].pers_britday));

      $(".pers_status").val(data[0].pers_status);
      $(".pers_username").val(data[0].pers_username);
      $(".pers_position").val(data[0].pers_position);
      $(".pers_department").val(data[0].pers_department);
      $(".pers_learning").val(data[0].pers_learning);
      $(".pers_academic").val(data[0].pers_academic);
      $(".pers_groupleade").val(data[0].pers_groupleade);
      $("#pers_workother_id").val(data[0].work_id).trigger("change");

      //$('#pers_workother_id').append(new Option(data[0].work_name, data[0].work_name, true, true)).trigger('change');
      //console.log(data[0].pers_workother_id);

      // ที่อยู่ตามทะเบียนบ้าน
      $("#addr_house_no").val(data[0].addr_house_no);
      $("#addr_moo").val(data[0].addr_moo);
      $("#addr_village").val(data[0].addr_village);
      $("#addr_soi").val(data[0].addr_soi);
      $("#addr_road").val(data[0].addr_road);
      $("#addr_postcode").val(data[0].addr_postcode);
      $(".province")
        .append(
          new Option(data[0].addr_province, data[0].addr_province, true, true)
        )
        .trigger("change");
      $(".district")
        .append(
          new Option(data[0].addr_district, data[0].addr_district, true, true)
        )
        .trigger("change");
      $(".subdistrict")
        .append(
          new Option(
            data[0].addr_subdistrict,
            data[0].addr_subdistrict,
            true,
            true
          )
        )
        .trigger("change");

      // Load Family Data
      const familyTable = $("#familyTable tbody");
      familyTable.empty();
      if (data[0].family && data[0].family.length > 0) {
        data[0].family.forEach((member) => {
          const newRow = `
                    <tr data-id="${member.id}">
                    <td>${member.fam_fullname}</td>
                    <td>${member.fam_relationship}</td>
                    <td>${member.fam_age}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-family" data-id="${member.id}">
                        <i class="bx bx-trash"></i>
                        </button>
                    </td>
                    </tr>
                `;
          familyTable.append(newRow);
        });
      }

      // Load License Data
      $("#pers_license_no").val(data[0].pers_license_no);
      $("#pers_license_issue").val(data[0].pers_license_issue);
      $("#pers_license_exp").val(data[0].pers_license_exp);

      // Load Education Data
      const eduTable = $("#educationTable tbody");
      eduTable.empty();
      if (data[0].education && data[0].education.length > 0) {
        data[0].education.forEach((edu) => {
          const docCell = edu.doc_id 
            ? `<button type="button" class="btn btn-sm btn-success" onclick="viewPersonnelDoc('${edu.doc_id}')"><i class='bx bx-file'></i></button>
               <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTableRowDoc('${edu.doc_id}', 'education', '${edu.id}')"><i class='bx bx-x'></i></button>`
            : `<label class="btn btn-sm btn-outline-secondary m-0" style="cursor:pointer;"><i class='bx bx-upload'></i><input type="file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadTableRowDoc('education', '${edu.id}', this)"></label>`;

          const newRow = `
                    <tr data-id="${edu.id}">
                    <td>${edu.edu_level}</td>
                    <td>${edu.edu_degree} ${
            edu.edu_major ? `(${edu.edu_major})` : ""
          }</td>
                    <td>${edu.edu_institute}</td>
                    <td>${edu.edu_year}</td>
                    <td class="text-center" id="doc-cell-education-${edu.id}">${docCell}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-education" data-id="${
                          edu.id
                        }">
                        <i class="bx bx-trash"></i>
                        </button>
                    </td>
                    </tr>
                `;
          eduTable.append(newRow);
        });
      }

      // Load Work History Data
      const workTable = $("#workHistoryTable tbody");
      workTable.empty();
      if (data[0].work_history && data[0].work_history.length > 0) {
        data[0].work_history.forEach((work) => {
          const salary =
            work.work_salary > 0
              ? parseFloat(work.work_salary).toLocaleString("th-TH", {
                  minimumFractionDigits: 2,
                })
              : "-";

          // Format Command Date (Use display from backend if available)
          const cmdDateDisplay = work.work_command_date_display || "-";

          const jsonStr = encodeURIComponent(JSON.stringify(work));

          const workDocCell = work.doc_id 
            ? `<button type="button" class="btn btn-sm btn-success" onclick="viewPersonnelDoc('${work.doc_id}')"><i class='bx bx-file'></i></button>
               <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTableRowDoc('${work.doc_id}', 'work_order', '${work.id}')"><i class='bx bx-x'></i></button>`
            : `<label class="btn btn-sm btn-outline-secondary m-0" style="cursor:pointer;"><i class='bx bx-upload'></i><input type="file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadTableRowDoc('work_order', '${work.id}', this)"></label>`;

          const newRow = `
                    <tr data-id="${work.id}" data-json="${jsonStr}">
                    <td>${work.work_date_display}</td>
                    <td>${work.work_change_type || "-"}</td>
                    <td>
                        <div class="fw-bold">${work.work_position || "-"}</div>
                        <div class="text-muted small">${
                          work.work_level || ""
                        }</div>
                    </td>
                    <td>${work.work_location || "-"}</td>
                    <td>${salary}</td>
                    <td>
                        <div>${work.work_command_no || "-"}</div>
                        <div class="text-muted small">ลว. ${cmdDateDisplay}</div>
                    </td>
                    <td class="text-center" id="doc-cell-work_order-${work.id}">${workDocCell}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-icon btn-label-warning edit-work-history">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-work-history" data-id="${
                              work.id
                            }">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                    </tr>
                `;
          workTable.append(newRow);
        });
      }
      // Load Decoration Data
      const decoTable = $("#decorationTable tbody");
      decoTable.empty();
      if (data[0].decorations && data[0].decorations.length > 0) {
        data[0].decorations.forEach((deco) => {
          const decoDate = deco.deco_date_display || "-";
          const gazetteDate = deco.deco_gazette_date_display || "-";
          const gazetteInfo = `เล่ม ${deco.deco_gazette_vol || "-"} ตอน ${
            deco.deco_gazette_part || "-"
          } หน้า ${deco.deco_gazette_page || "-"} ลำดับ ${
            deco.deco_gazette_seq || "-"
          }`;

          const decoDocCell = deco.doc_id 
            ? `<button type="button" class="btn btn-sm btn-success" onclick="viewPersonnelDoc('${deco.doc_id}')"><i class='bx bx-file'></i></button>
               <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTableRowDoc('${deco.doc_id}', 'decoration', '${deco.id}')"><i class='bx bx-x'></i></button>`
            : `<label class="btn btn-sm btn-outline-secondary m-0" style="cursor:pointer;"><i class='bx bx-upload'></i><input type="file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadTableRowDoc('decoration', '${deco.id}', this)"></label>`;

          const newRow = `
                    <tr data-id="${deco.id}">
                        <td>${decoDate}</td>
                        <td><div class="fw-bold">${deco.deco_name}</div></td>
                        <td><small>${gazetteInfo}</small></td>
                        <td>${gazetteDate}</td>
                        <td class="text-center" id="doc-cell-decoration-${deco.id}">${decoDocCell}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-decoration" data-id="${deco.id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
          decoTable.append(newRow);
        });
      }

      // Load Training Data
      const trainTable = $("#trainingTable tbody");
      trainTable.empty();
      if (data[0].training && data[0].training.length > 0) {
        data[0].training.forEach((train) => {
          const dateRange =
            train.train_end_display && train.train_end_display !== "-"
              ? `${train.train_start_display} - ${train.train_end_display}`
              : train.train_start_display;

          const trainDocCell = train.doc_id 
            ? `<button type="button" class="btn btn-sm btn-success" onclick="viewPersonnelDoc('${train.doc_id}')"><i class='bx bx-file'></i></button>
               <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTableRowDoc('${train.doc_id}', 'training', '${train.id}')"><i class='bx bx-x'></i></button>`
            : `<label class="btn btn-sm btn-outline-secondary m-0" style="cursor:pointer;"><i class='bx bx-upload'></i><input type="file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadTableRowDoc('training', '${train.id}', this)"></label>`;

          const newRow = `
                    <tr data-id="${train.id}">
                        <td>${dateRange}</td>
                        <td><div class="fw-bold">${train.train_name}</div></td>
                        <td>${train.train_location || "-"}</td>
                        <td class="text-center">${train.train_hours || "-"}</td>
                        <td class="text-center" id="doc-cell-training-${train.id}">${trainDocCell}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-training" data-id="${
                              train.id
                            }">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
          trainTable.append(newRow);
        });
      }

      // Load Leave/Attendance Data from Attendance System
      const leaveTable = $("#leaveTable tbody");
      leaveTable.empty();
      
      // Update summary stats
      if (data[0].attendance_summary) {
        const sum = data[0].attendance_summary;
        $("#stat-present").text(sum.present || 0);
        $("#stat-sick").text(sum.sick || 0);
        $("#stat-personal").text(sum.personal || 0);
        $("#stat-official").text(sum.official || 0);
        $("#stat-absent").text(sum.absent || 0);
        $("#stat-other").text(sum.other || 0);
      }

      // Load leave records
      if (data[0].leave_records && data[0].leave_records.length > 0) {
        data[0].leave_records.forEach((rec) => {
          const statusClass = {
            'ลาป่วย': 'text-danger',
            'ลากิจ': 'text-warning',
            'ไปราชการ': 'text-primary',
            'ขาด': 'text-dark fw-bold',
            'อื่นๆ': 'text-muted'
          }[rec.att_status] || '';

          const newRow = `
                    <tr>
                        <td>${rec.date_display}</td>
                        <td><span class="${statusClass}">${rec.att_status}</span></td>
                        <td><small>${rec.att_reason || "-"}</small></td>
                    </tr>
                `;
          leaveTable.append(newRow);
        });
      } else {
        leaveTable.append('<tr><td colspan="3" class="text-center text-muted py-4">ไม่มีข้อมูลการลา/ขาดในปีนี้</td></tr>');
      }

      // ที่อยู่ปัจจุบัน

      if (data[1]) {
        $("#curr_addr_house_no").val(data[1].addr_house_no);
        $("#curr_addr_moo").val(data[1].addr_moo);
        $("#curr_addr_village").val(data[1].addr_village);
        $("#curr_addr_soi").val(data[1].addr_soi);
        $("#curr_addr_road").val(data[1].addr_road);
        $("#curr_addr_postcode").val(data[1].addr_postcode);
        $(".curr_province")
          .append(
            new Option(data[1].addr_province, data[1].addr_province, true, true)
          )
          .trigger("change");
        $(".curr_district")
          .append(
            new Option(data[1].addr_district, data[0].addr_district, true, true)
          )
          .trigger("change");
        $(".curr_subdistrict")
          .append(
            new Option(
              data[1].addr_subdistrict,
              data[1].addr_subdistrict,
              true,
              true
            )
          )
          .trigger("change");
      }
    },
    error: function () {
      alert("ไม่พบข้อมูลบุคลากร");
    },
  });
}

function showSaveStatus(message, color = "#333") {
  const statusBox = document.getElementById("save-status");
  statusBox.style.color = color;
  statusBox.textContent = message;
  statusBox.style.display = "block";

  // ซ่อนหลัง 2 วิ (ถ้าต้องการ)
  if (message !== "กำลังบันทึก...") {
    setTimeout(() => (statusBox.style.display = "none"), 2000);
  }
}

$("#pers_id_card").on("input change blur", function () {
  const value = $(this).val();
  const raw = value.replace(/-/g, "").replace(/_/g, "");

  if (raw.length === 0) {
    $("#cid-error").hide();
    return;
  }

  if (raw.length < 13) {
    $("#cid-error")
      .text("กรุณากรอกเลขบัตรให้ครบ 13 หลัก")
      .css("color", "orange")
      .show();
  } else if (raw.length === 13) {
    if (isValidThaiID(raw)) {
      $("#cid-error")
        .text("✅ เลขบัตรประชาชนถูกต้อง")
        .css("color", "green")
        .show();
    } else {
      $("#cid-error")
        .text("❌ เลขบัตรประชาชนไม่ถูกต้อง")
        .css("color", "red")
        .show();
    }
  }
});

/*
// =============================================
// AUTO-SAVE DISABLED - User prefers manual save
// =============================================

// Debounce Function
function debounce(func, wait) {
  let timeout;
  return function(...args) {
    const context = this;
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), wait);
  };
}

// Track Original Values on Focus
$(document).on("focus", ".auto-save", function() {
    if(!$(this).data("original-value")) {
        $(this).data("original-value", $(this).val());
    }
});

// Auto-Save Logic
const performAutoSave = function(element) {
  let PresID = $("#pers_id").val();
  let field = $(element).attr("name");
  let value = $(element).val();
  let originalValue = $(element).data("original-value");

  // Skip if value hasn't changed
  if (value === originalValue) {
      return;
  }

  if (field === "pers_id_card") {
    const raw = value.replace(/-/g, "").replace(/_/g, "");
    if (raw.length !== 13 || !isValidThaiID(raw)) {
        return; // Don't save if invalid
    }
  }

  if ("pers_britday" == $(element).attr("name")) {
    $("#pers_age").val(calculateAge(value));
  }

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Update/Alone",
    type: "POST",
    data: {
      PresID: PresID,
      field: field,
      value: value,
    },
    dataType: "json",
    success: function (res) {
       // Update original value after successful save
       $(element).data("original-value", value);

       // Show Toast Notification
       const Toast = Swal.mixin({
          toast: true,
          position: 'bottom-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        if(res.status === 'success' || res.data === 1) {
            Toast.fire({
                icon: 'success',
                title: 'บันทึกข้อมูลเรียบร้อยแล้ว'
            });
        }
    },
    error: function(err) {
        console.error("Auto-save failed", err);
    }
  });
};

// Apply handlers
const debouncedSave = debounce(function() {
    performAutoSave(this);
}, 1000);

$(document).on("input", ".auto-save:not(select):not(.selectorEdit)", debouncedSave);
$(document).on("change", "select.auto-save, .auto-save.selectorEdit", function() {
    performAutoSave(this);
});
*/

const pathSegments = window.location.pathname.split("/");
const lastSegment = pathSegments[pathSegments.length - 1];
const matches = lastSegment.match(/^pers_(\d+)$/);
//console.log(matches[0]);
if (matches) {
  const id = matches[0];
  loadPersonnelData(id);
}

// ฟังก์ชันคัดลอกที่อยู่เมื่อ checkbox ถูกติ๊ก
$("#same_address").on("change", function () {
  const isChecked = $(this).is(":checked");
  const fields = [
    "house_no",
    "moo",
    "village",
    "soi",
    "road",
    "subdistrict",
    "district",
    "province",
    "postcode",
  ];

  fields.forEach((f) => {
    const $source = $("#addr_" + f);
    const $target = $("#curr_addr_" + f);

    if (isChecked) {
      const value = $source.is("select")
        ? $source.find(":selected").val()
        : $source.val();
      $target.val(value).trigger("change"); // ให้ select2 แสดงผลด้วย
    }
  });
});

// sync แบบ realtime เมื่อบ้านถูกแก้ และ checkbox ถูกติ๊ก
const addrFields = [
  "house_no",
  "moo",
  "village",
  "soi",
  "road",
  "subdistrict",
  "district",
  "province",
  "postcode",
];

addrFields.forEach((f) => {
  $("#addr_" + f).on("input change", function () {
    if ($("#same_address").is(":checked")) {
      const value = $(this).is("select")
        ? $(this).find(":selected").val()
        : $(this).val();
      $("#curr_addr_" + f)
        .val(value)
        .trigger("change");
    }
  });
});

// Initialize Select2 in Education Modal with Dynamic Data
$("#educationModal").on("shown.bs.modal", function () {
  const $modal = $(this);

  // Fetch options from API
  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Education/GetOptions",
    method: "GET",
    dataType: "json",
    success: function (data) {
      const populateSelect = ($select, options) => {
        $select
          .empty()
          .append(
            '<option value="" selected disabled>เลือกหรือพิมพ์เพิ่ม...</option>'
          );
        options.forEach((opt) => {
          $select.append(new Option(opt, opt, false, false));
        });
      };

      populateSelect($("#edu_degree"), data.degrees);
      populateSelect($("#edu_major"), data.majors);
      populateSelect($("#edu_institute"), data.institutes);

      // Init Select2
      $(".select2-tags").select2({
        dropdownParent: $modal,
        tags: true, // Allow custom input
        width: "100%",
        placeholder: "เลือกหรือพิมพ์เพิ่ม...",
        allowClear: true,
        language: "th",
      });
    },
    error: function () {
      console.error("Failed to load education options");
      // Fallback init if API fails
      $(".select2-tags").select2({
        dropdownParent: $modal,
        tags: true,
        width: "100%",
        placeholder: "เลือกหรือพิมพ์เพิ่ม...",
        allowClear: true,
        language: "th",
      });
    },
  });
});

// Reset form and Select2 when modal closes
$("#educationModal").on("hidden.bs.modal", function () {
  $(this).find("form")[0].reset();
  $(".select2-tags").val(null).trigger("change");
});

//   ครอบครัว - ปรับปรุงใหม่ใช้ Modal และ Table พร้อม AJAX
$(document).on("submit", "#familyForm", function (e) {
  e.preventDefault();
  const name = $("#name").val();
  const relation = $("#relation").val();
  const age = $("#age").val();
  const pers_id = $("#pers_id").val();

  if (!pers_id) {
    Swal.fire("ข้อผิดพลาด", "ไม่พบรหัสบุคลากร", "error");
    return;
  }

  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Family/Add",
    type: "POST",
    data: { pers_id: pers_id, name: name, relation: relation, age: age },
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        const newRow = `
                <tr data-id="${res.id}">
                  <td>${name}</td>
                  <td>${relation}</td>
                  <td>${age}</td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-family" data-id="${res.id}">
                      <i class="bx bx-trash"></i>
                    </button>
                  </td>
                </tr>
              `;
        $("#familyTable tbody").append(newRow);
        bootstrap.Modal.getInstance(
          document.getElementById("familyModal")
        ).hide();
        $("#familyForm")[0].reset();
        Swal.fire("สำเร็จ", "เพิ่มสมาชิกครอบครัวแล้ว", "success");
      } else {
        Swal.fire("ล้มเหลว", "บันทึกข้อมูลไม่สำเร็จ", "error");
      }
    },
    error: function (xhr) {
      Swal.close();
      console.log(xhr.responseText);
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
    },
  });
});

$(document).on("click", ".remove-family", function () {
  const btn = $(this);
  const id = btn.data("id");
  const row = btn.closest("tr");

  if (!id) {
    row.remove();
    return;
  }

  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบข้อมูล",
    cancelButtonText: "ยกเลิก",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Family/Delete",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            row.remove();
            Swal.fire("สำเร็จ", "ลบข้อมูลเรียบร้อย", "success");
          } else {
            Swal.fire("ล้มเหลว", "ลบข้อมูลไม่สำเร็จ", "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
        },
      });
    }
  });
});

// Education Form Handler
$(document).on("submit", "#educationForm", function (e) {
  e.preventDefault();
  const level = $("#edu_level").val();
  const degree = $("#edu_degree").val();
  const institute = $("#edu_institute").val();
  const year = $("#edu_year").val();
  const pers_id = $("#pers_id").val();

  if (!pers_id) {
    Swal.fire("ข้อผิดพลาด", "ไม่พบรหัสบุคลากร", "error");
    return;
  }

  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Education/Add",
    type: "POST",
    data: {
      pers_id: pers_id,
      edu_level: level,
      edu_degree: degree,
      edu_major: $("#edu_major").val(),
      edu_institute: institute,
      edu_year: year,
    },
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        const newRow = `
                <tr data-id="${res.id}">
                  <td>${level}</td>
                  <td>${degree} ${
          $("#edu_major").val() ? `(${$("#edu_major").val()})` : ""
        }</td>
                  <td>${institute}</td>
                  <td>${year}</td>
                  <td class="text-center" id="doc-cell-education-${res.id}">
                    <label class="btn btn-sm btn-outline-secondary m-0" style="cursor:pointer;">
                      <i class='bx bx-upload'></i>
                      <input type="file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadTableRowDoc('education', '${res.id}', this)">
                    </label>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-label-danger remove-education" data-id="${
                      res.id
                    }">
                      <i class="bx bx-trash"></i>
                    </button>
                  </td>
                </tr>
              `;
        $("#educationTable tbody").prepend(newRow);
        bootstrap.Modal.getInstance(
          document.getElementById("educationModal")
        ).hide();
        $("#educationForm")[0].reset();
        Swal.fire("สำเร็จ", "เพิ่มประวัติการศึกษาแล้ว", "success");
      } else {
        Swal.fire("ล้มเหลว", "บันทึกข้อมูลไม่สำเร็จ", "error");
      }
    },
    error: function (xhr) {
      Swal.close();
      console.log(xhr.responseText);
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
    },
  });
});

// Remove Education Handler
$(document).on("click", ".remove-education", function () {
  const btn = $(this);
  const id = btn.data("id");
  const row = btn.closest("tr");

  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบข้อมูล",
    cancelButtonText: "ยกเลิก",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Education/Delete",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            row.remove();
            Swal.fire("สำเร็จ", "ลบข้อมูลเรียบร้อย", "success");
          } else {
            Swal.fire("ล้มเหลว", "ลบข้อมูลไม่สำเร็จ", "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
        },
      });
    }
  });
});

// --- Professional License Management ---
$("#FormLicenseInfo")
  .off("submit")
  .on("submit", function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    const pers_id =
      new URLSearchParams(window.location.search).get("pers_id") ||
      $("#pers_id_hidden").val() ||
      $("input[name='pers_id']").val();

    Swal.fire({
      title: "กำลังบันทึก...",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const formData = $(this).serialize() + "&pers_id=" + pers_id;

    $.ajax({
      url: "../../../../Admin/WorkPerson/Personnel/DB/License/Update",
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (res) {
        Swal.close();
        if (res.status === "success") {
          Swal.fire(
            "สำเร็จ",
            "บันทึกข้อมูลใบประกอบวิชาชีพเรียบร้อย",
            "success"
          );
        } else {
          Swal.fire("ล้มเหลว", "บันทึกข้อมูลไม่สำเร็จ", "error");
        }
      },
      error: function (xhr) {
        Swal.close();
        console.error(xhr.responseText);
        Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
      },
    });
  });

// --- Work History Management (Vor.Kor.7) ---

// Save Work History (Add/Update)
$("#workHistoryForm")
  .off("submit")
  .on("submit", function (e) {
    e.preventDefault();
    const pers_id =
      new URLSearchParams(window.location.search).get("pers_id") ||
      $("#pers_id_hidden").val() ||
      $("input[name='pers_id']").val();
    const work_id = $("#work_id").val();

    // Basic validation
    if (!$("#work_date").val() || !$("#work_position").val()) {
      Swal.fire("แจ้งเตือน", "กรุณากรอกวันที่และตำแหน่งงาน", "warning");
      return;
    }

    Swal.fire({
      title: "กำลังบันทึก...",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const formData = $(this).serialize() + "&pers_id=" + pers_id;
    const url = work_id
      ? "../../../../Admin/WorkPerson/Personnel/DB/History/Update"
      : "../../../../Admin/WorkPerson/Personnel/DB/History/Add";

    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      dataType: "json",
      success: function (res) {
        Swal.close();
        if (res.status === "success") {
          if (pers_id) loadPersonnelData(pers_id);

          bootstrap.Modal.getInstance(
            document.getElementById("workHistoryModal")
          ).hide();
          $("#workHistoryForm")[0].reset();
          $("#work_id").val("");
          $("#workHistoryTitle").text("เพิ่มประวัติการทำงาน");
          $(".select2-tags").val(null).trigger("change");

          Swal.fire("สำเร็จ", "บันทึกข้อมูลเรียบร้อย", "success");
        } else {
          Swal.fire("ล้มเหลว", "บันทึกข้อมูลไม่สำเร็จ", "error");
        }
      },
      error: function (xhr) {
        Swal.close();
        console.error(xhr.responseText);
        Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
      },
    });
  });

// Edit Work History
$(document).on("click", ".edit-work-history", function () {
  const row = $(this).closest("tr");
  const jsonStr = row.attr("data-json");
  if (!jsonStr) return;

  const data = JSON.parse(decodeURIComponent(jsonStr));

  // Populate Modal
  $("#work_id").val(data.id);
  $("#work_date").val(data.work_date_display);

  $("#work_change_type").val(data.work_change_type).trigger("change");
  $("#work_position").val(data.work_position).trigger("change");
  $("#work_level").val(data.work_level).trigger("change");
  $("#work_location").val(data.work_location);
  $("#work_salary").val(data.work_salary);
  $("#work_command_no").val(data.work_command_no);
  $("#work_note").val(data.work_note);

  // Command Date logic
  if (data.work_command_date) {
    const d = new Date(data.work_command_date);
    if (!isNaN(d.getTime())) {
      const thYear = d.getFullYear() + 543;
      const thDate = `${d.getDate().toString().padStart(2, "0")}/${(
        d.getMonth() + 1
      )
        .toString()
        .padStart(2, "0")}/${thYear}`;
      $("#work_command_date").val(thDate);
    }
  } else {
    $("#work_command_date").val("");
  }

  $("#workHistoryTitle").text("แก้ไขประวัติการทำงาน");
  new bootstrap.Modal(document.getElementById("workHistoryModal")).show();
});

// Reset form on modal close
$("#workHistoryModal").on("hidden.bs.modal", function () {
  $("#workHistoryForm")[0].reset();
  $("#work_id").val("");
  $("#workHistoryTitle").text("เพิ่มประวัติการทำงาน");
  $(".select2-tags").val(null).trigger("change");
});

// Remove Work History
$(document).on("click", ".remove-work-history", function () {
  const btn = $(this);
  const id = btn.data("id");
  const row = btn.closest("tr");

  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบข้อมูลประวัติการทำงานนี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบข้อมูล",
    cancelButtonText: "ยกเลิก",
    confirmButtonColor: "#ff3e1d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/History/Delete",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            row.fadeOut(300, function () {
              $(this).remove();
            });
            Swal.fire("ลบสำเร็จ", "ลบข้อมูลเรียบร้อยแล้ว", "success");
          } else {
            Swal.fire("ขัดข้อง", "ไม่สามารถลบข้อมูลได้", "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
        },
      });
    }
  });
});

// Auto-load if PID exists (Update Page)
$(document).ready(function () {
  const pid = $("#pers_id").val();
  if (pid && $("#FormPersonnalUpdateDataPersonnel").length > 0) {
    loadPersonnelData(pid);
  }

  // Calculate age on load if birthday is pre-populated
  const bday = $("#pers_britday").val();
  if (bday) {
    $("#pers_age").val(calculateAge(bday));
  }
});

// เมื่อมีการเปลี่ยนแปลงค่าของ select แรก
$("#pers_position").on("change", function () {
  // ถ้าเลือก "แสดง Select ถัดไป"
  var index = $(this).prop("selectedIndex"); // รับลำดับที่เลือก (0-based)

  if ($("#key_update").val() === "Update") {
    var urlUpdate =
      "../../../../Admin/WorkPerson/Personnel/DB/Select/GetPositionData";
  } else {
    var urlUpdate =
      "../../../Admin/WorkPerson/Personnel/DB/Select/GetPositionData";
  }

  if (index > 0 && index <= 6) {
    // แสดง select ถัดไป
    $("#show_learning").show();
    $("#show_position").hide();
    $("#pers_workother_id").removeAttr("required");
    $("#pers_workother_id").val("");
  } else if (index >= 7) {
    $("#show_position").show();
    $("#show_learning").hide();
    $("#pers_learning").val("");

    if (selectedPosition !== "") {
      var selectedPosition = $(this).val();
      // alert(selectedPosition);
      $.ajax({
        url: urlUpdate, // URL ที่จะส่งคำขอไปยัง controller
        type: "POST",
        data: { position_id: selectedPosition },
        success: function (response) {
          var data = response;

          var secondSelect = $("#pers_workother_id");
          secondSelect.empty(); // ล้างค่าเก่า

          if (data.length > 0) {
            // เพิ่มข้อมูลใน select
            secondSelect.append('<option value="">--เลือกข้อมูล--</option>');
            $.each(data, function (index, item) {
              secondSelect.append(
                '<option value="' +
                  item.work_id +
                  '">' +
                  item.work_name +
                  "</option>"
              );
            });
          } else {
            secondSelect.append('<option value="">ไม่มีข้อมูล</option>');
          }
        },
      });
    } else {
      $("#pers_workother_id")
        .empty()
        .append('<option value="">--กรุณาเลือกตำแหน่งก่อน--</option>');
    }
  } else {
    // ซ่อน select ถัดไป
    $("#show_learning").hide();
    $("#show_position").hide();
  }
});

const items = document.querySelector(".sortable");
if (items) {
  Sortable.create(items, {
    animation: 150,
    chosenClass: "selected",
    ghostClass: "ghost",
    dragClass: "drag",
    onEnd: () => {
      //console.log('an element was inserted');
    },
    group: "cards",
    store: {
      set: (sortable) => {
        const orden = sortable.toArray();
        localStorage.setItem(sortable.options.group.name, orden.join("|"));
        // console.log(orden);

        $.post(
          "../../../../Admin/WorkPerson/Personnel/DB/SortableTeacher",
          {
            data: orden,
          },
          function (data, status) {
            console.log("Data: " + data + "\nStatus: " + status);
          }
        );
      },
      //get list order
      get: (sortable) => {
        const orden = localStorage.getItem(sortable.options.group.name);
        return orden ? orden.split("|") : [];
      },
    },
  });
}

// Cleanup Images Handler
$(document).on("click", "#btnCleanupImages", function () {
  const $btn = $(this);

  Swal.fire({
    title: "ยืนยันการล้างไฟล์ขยะ?",
    text: "ระบบจะลบรูปภาพที่ไม่ได้ถูกใช้งานในฐานข้อมูลออกถาวรเพื่อเพิ่มพื้นที่ว่าง",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ff3e1d",
    cancelButtonColor: "#8592a3",
    confirmButtonText: "ใช่, ลบเลย!",
    cancelButtonText: "ยกเลิก",
  }).then((result) => {
    if (result.isConfirmed) {
      // Show loading state
      const originalHtml = $btn.html();
      $btn
        .prop("disabled", true)
        .html(
          '<span class="spinner-border spinner-border-sm me-2"></span>กำลังประมวลผล...'
        );

      $.ajax({
        url: $btn.data("url"),
        method: "POST",
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            Swal.fire({
              title: "สำเร็จ!",
              text: res.message,
              icon: "success",
            });
          } else {
            Swal.fire(
              "ข้อผิดพลาด!",
              res.message || "ไม่สามารถล้างไฟล์ขยะได้",
              "error"
            );
          }
        },
        error: function () {
          Swal.fire(
            "ข้อผิดพลาด!",
            "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์",
            "error"
          );
        },
        complete: function () {
          $btn.prop("disabled", false).html(originalHtml);
        },
      });
    }
  });
});
// Decoration Form Handler
$(document).on("submit", "#decorationForm", function (e) {
  e.preventDefault();
  const pid = $("#pers_id").val();
  const formData = new FormData(this);
  formData.append("pers_id", pid);

  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Decoration/Add",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        $("#decorationModal").modal("hide");
        loadPersonnelData(pid);
        Swal.fire("สำเร็จ", "บันทึกข้อมูลเครื่องราชฯ เรียบร้อยแล้ว", "success");
        $("#decorationForm")[0].reset();
      } else {
        Swal.fire("ขัดข้อง", "ไม่สามารถบันทึกข้อมูลได้", "error");
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
    },
  });
});

// Remove Decoration
$(document).on("click", ".remove-decoration", function () {
  const btn = $(this);
  const id = btn.data("id");
  const pid = $("#pers_id").val();

  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบข้อมูลเครื่องราชอิสริยาภรณ์นี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบข้อมูล",
    cancelButtonText: "ยกเลิก",
    confirmButtonColor: "#ff3e1d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Decoration/Delete",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            loadPersonnelData(pid);
            Swal.fire("ลบสำเร็จ", "ลบข้อมูลเรียบร้อยแล้ว", "success");
          } else {
            Swal.fire("ขัดข้อง", "ไม่สามารถลบข้อมูลได้", "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
        },
      });
    }
  });
});

// Training Form Handler
$(document).on("submit", "#trainingForm", function (e) {
  e.preventDefault();
  const pid = $("#pers_id").val();
  const formData = new FormData(this);
  formData.append("pers_id", pid);

  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Training/Add",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        $("#trainingModal").modal("hide");
        loadPersonnelData(pid);
        Swal.fire("สำเร็จ", "บันทึกประวัติการอบรมเรียบร้อยแล้ว", "success");
        $("#trainingForm")[0].reset();
      } else {
        Swal.fire("ขัดข้อง", "ไม่สามารถบันทึกข้อมูลได้", "error");
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
    },
  });
});

// Remove Training
$(document).on("click", ".remove-training", function () {
  const btn = $(this);
  const id = btn.data("id");
  const pid = $("#pers_id").val();

  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบข้อมูลการฝึกอบรมนี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบข้อมูล",
    cancelButtonText: "ยกเลิก",
    confirmButtonColor: "#ff3e1d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Training/Delete",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            loadPersonnelData(pid);
            Swal.fire("ลบสำเร็จ", "ลบข้อมูลเรียบร้อยแล้ว", "success");
          } else {
            Swal.fire("ขัดข้อง", "ไม่สามารถลบข้อมูลได้", "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
        },
      });
    }
  });
});

// Leave Form Handler
$(document).on("submit", "#leaveForm", function (e) {
  e.preventDefault();
  const pid = $("#pers_id").val();
  const formData = new FormData(this);
  formData.append("pers_id", pid);

  Swal.fire({
    title: "กำลังบันทึก...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Leave/Add",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        $("#leaveModal").modal("hide");
        loadPersonnelData(pid);
        Swal.fire("สำเร็จ", "บันทึกประวัติการลาเรียบร้อยแล้ว", "success");
        $("#leaveForm")[0].reset();
      } else {
        Swal.fire("ขัดข้อง", "ไม่สามารถบันทึกข้อมูลได้", "error");
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
    },
  });
});

// Remove Leave
$(document).on("click", ".remove-leave", function () {
  const btn = $(this);
  const id = btn.data("id");
  const pid = $("#pers_id").val();

  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบข้อมูลการลานี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบข้อมูล",
    cancelButtonText: "ยกเลิก",
    confirmButtonColor: "#ff3e1d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Leave/Delete",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            loadPersonnelData(pid);
            Swal.fire("ลบสำเร็จ", "ลบข้อมูลเรียบร้อยแล้ว", "success");
          } else {
            Swal.fire("ขัดข้อง", "ไม่สามารถลบข้อมูลได้", "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
        },
      });
    }
  });
});

// Quick View Profile
$(document).on("click", ".view-profile-btn", function () {
  const id = $(this).data("id");

  Swal.fire({
    title: "กำลังดึงข้อมูล...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: window.location.origin + "/Admin/WorkPerson/Personnel/DB/Get/" + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      Swal.close();
      if (data && data[0]) {
        const p = data[0];
        const baseUrl = window.location.origin;

        // Header Info
        $("#preview_pers_img").attr(
          "src",
          baseUrl + "/uploads/admin/Personnal/" + (p.pers_img || "default.png")
        );
        $("#preview_fullname").text(
          p.pers_prefix + p.pers_firstname + " " + p.pers_lastname
        );
        $("#preview_pers_id").text(p.pers_id);
        $("#preview_posi_name").text(p.posi_name || "-");
        $("#preview_full_edit_link").attr(
          "href",
          baseUrl + "/Admin/WorkPerson/Personnel/Update/" + p.pers_id
        );

        // General Info
        $("#preview_birthday").text(p.pers_britday || "-");
        $("#preview_id_card").text(p.pers_id_card || "-");
        $("#preview_nat_race").text(
          (p.pers_nationality || "-") + " / " + (p.pers_race || "-")
        );
        $("#preview_religion").text(p.pers_religion || "-");
        $("#preview_marital").text(p.pers_marital_status || "-");
        $("#preview_phone").text(p.pers_phone || "-");
        $("#preview_line").text(p.pers_line || "-");
        $("#preview_license").text(
          p.pers_license_no
            ? "มี (เลขที่ " + p.pers_license_no + ")"
            : "ไม่มีข้อมูล"
        );

        // Education
        const eduBody = $("#preview_edu_table tbody").empty();
        if (p.education && p.education.length > 0) {
          p.education.forEach((e) => {
            eduBody.append(
              `<tr><td>${e.edu_year || "-"}</td><td>${
                e.edu_degree || "-"
              }</td><td>${e.edu_major || "-"}</td><td>${
                e.edu_institution || "-"
              }</td></tr>`
            );
          });
        } else {
          eduBody.append(
            '<tr><td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td></tr>'
          );
        }

        // Work History
        const workBody = $("#preview_work_table tbody").empty();
        if (p.work_history && p.work_history.length > 0) {
          p.work_history.forEach((w) => {
            workBody.append(
              `<tr><td>${w.work_date_display || "-"}</td><td>${
                w.work_position || "-"
              }</td><td>${w.work_academic || "-"}</td><td>${
                w.work_department || "-"
              }</td></tr>`
            );
          });
        } else {
          workBody.append(
            '<tr><td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td></tr>'
          );
        }

        // Decorations
        const decoBody = $("#preview_deco_table tbody").empty();
        if (p.decorations && p.decorations.length > 0) {
          p.decorations.forEach((d) => {
            decoBody.append(
              `<tr><td>${d.deco_year || "-"}</td><td>${
                d.deco_badge || "-"
              }</td><td>เล่มที่ ${d.deco_gazette_volume || "-"} ตอนที่ ${
                d.deco_gazette_section || "-"
              }</td></tr>`
            );
          });
        } else {
          decoBody.append(
            '<tr><td colspan="3" class="text-center text-muted">ไม่มีข้อมูล</td></tr>'
          );
        }

        // Training
        const trainBody = $("#preview_training_table tbody").empty();
        if (p.training && p.training.length > 0) {
          p.training.forEach((t) => {
            const date =
              t.train_end_display && t.train_end_display !== "-"
                ? t.train_start_display + " - " + t.train_end_display
                : t.train_start_display;
            trainBody.append(
              `<tr><td>${date}</td><td>${t.train_name || "-"}</td><td>${
                t.train_location || "-"
              }</td><td class="text-center">${t.train_hours || "-"}</td></tr>`
            );
          });
        } else {
          trainBody.append(
            '<tr><td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td></tr>'
          );
        }

        $("#profilePreviewModal").modal("show");
      } else {
        Swal.fire("ขัดข้อง", "ไม่พบข้อมูลบุคลากร", "error");
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
    },
  });
});

// ==========================================
// Document Management Functions
// ==========================================

// Trigger file input when clicking on upload card
function triggerDocUpload(docType) {
  document.getElementById("file-" + docType).click();
}

// Upload personnel document
function uploadPersonnelDoc(docType, input) {
  const file = input.files[0];
  if (!file) return;

  // Validate file size (5MB max)
  if (file.size > 5 * 1024 * 1024) {
    Swal.fire("ขนาดไฟล์ใหญ่เกินไป", "ขนาดไฟล์ต้องไม่เกิน 5MB", "warning");
    input.value = "";
    return;
  }

  // Validate file type
  const allowedTypes = [
    "application/pdf",
    "image/jpeg",
    "image/png",
    "image/jpg",
  ];
  if (!allowedTypes.includes(file.type)) {
    Swal.fire(
      "ประเภทไฟล์ไม่ถูกต้อง",
      "รองรับเฉพาะ PDF, JPG, PNG เท่านั้น",
      "warning"
    );
    input.value = "";
    return;
  }

  const pers_id = $("#pers_id").val();
  if (!pers_id) {
    Swal.fire("ข้อผิดพลาด", "ไม่พบรหัสบุคลากร", "error");
    return;
  }

  // Determine category based on docType
  let docCategory = "personal";
  if (["teacher_license", "admin_license"].includes(docType)) {
    docCategory = "license";
  } else if (docType.startsWith("edu_")) {
    docCategory = "education";
  } else if (docType.startsWith("work_")) {
    docCategory = "work_order";
  } else if (docType.startsWith("train_")) {
    docCategory = "training";
  } else if (docType.startsWith("deco_")) {
    docCategory = "decoration";
  }

  const formData = new FormData();
  formData.append("document", file);
  formData.append("pers_id", pers_id);
  formData.append("doc_type", docType);
  formData.append("doc_category", docCategory);

  Swal.fire({
    title: "กำลังอัปโหลด...",
    text: "กรุณารอสักครู่",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Document/Upload",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        updateDocCardUI(docType, res.doc_id, res.file_name);
        Swal.fire({
          icon: "success",
          title: "อัปโหลดสำเร็จ",
          text: res.file_name,
          timer: 2000,
          showConfirmButton: false,
        });
      } else {
        Swal.fire("อัปโหลดล้มเหลว", res.message || "เกิดข้อผิดพลาด", "error");
      }
    },
    error: function (xhr) {
      Swal.close();
      console.error(xhr.responseText);
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
    },
  });

  // Reset input for re-upload
  input.value = "";
}

// Update document card UI after upload
function updateDocCardUI(docType, docId, fileName) {
  const card = $(`#card-${docType}`);
  card.addClass("has-file");
  card
    .find(".upload-icon")
    .removeClass("bx-upload")
    .addClass("bx-check-circle");

  // Remove old file info if exists
  card.find(".file-name").remove();
  card.find(".file-actions").remove();

  // Add new file info
  card.append(`<div class="file-name">${fileName}</div>`);
  card.append(`
        <div class="file-actions" onclick="event.stopPropagation();">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewPersonnelDoc('${docId}')"><i class='bx bx-show'></i></button>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePersonnelDoc('${docId}', '${docType}')"><i class='bx bx-trash'></i></button>
        </div>
    `);
}

// View personnel document
function viewPersonnelDoc(docId) {
  window.open(
    "../../../../Admin/WorkPerson/Personnel/DB/Document/View/" + docId,
    "_blank"
  );
}

// Delete personnel document
function deletePersonnelDoc(docId, docType) {
  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "คุณต้องการลบเอกสารนี้ใช่หรือไม่?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบเอกสาร",
    cancelButtonText: "ยกเลิก",
    confirmButtonColor: "#ff3e1d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Document/Delete",
        type: "POST",
        data: { doc_id: docId },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            resetDocCardUI(docType);
            Swal.fire({
              icon: "success",
              title: "ลบสำเร็จ",
              text: "ลบเอกสารเรียบร้อยแล้ว",
              timer: 2000,
              showConfirmButton: false,
            });
          } else {
            Swal.fire(
              "ขัดข้อง",
              res.message || "ไม่สามารถลบเอกสารได้",
              "error"
            );
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
        },
      });
    }
  });
}

// Reset document card UI after delete
function resetDocCardUI(docType) {
  const card = $(`#card-${docType}`);
  card.removeClass("has-file");
  card
    .find(".upload-icon")
    .removeClass("bx-check-circle")
    .addClass("bx-upload");
  card.find(".file-name").remove();
  card.find(".file-actions").remove();
}

// Upload document for table row (education, training, etc.)
function uploadTableRowDoc(category, relatedId, input) {
  const file = input.files[0];
  if (!file) return;

  if (file.size > 5 * 1024 * 1024) {
    Swal.fire("ขนาดไฟล์ใหญ่เกินไป", "ขนาดไฟล์ต้องไม่เกิน 5MB", "warning");
    input.value = "";
    return;
  }

  const pers_id = $("#pers_id").val();
  if (!pers_id) {
    Swal.fire("ข้อผิดพลาด", "ไม่พบรหัสบุคลากร", "error");
    return;
  }

  const formData = new FormData();
  formData.append("document", file);
  formData.append("pers_id", pers_id);
  formData.append("doc_type", category);
  formData.append("doc_category", category);
  formData.append("related_id", relatedId);

  Swal.fire({
    title: "กำลังอัปโหลด...",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    url: "../../../../Admin/WorkPerson/Personnel/DB/Document/Upload",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {
      Swal.close();
      if (res.status === "success") {
        // Update table cell with file icon
        const cell = $(`#doc-cell-${category}-${relatedId}`);
        cell.html(`
                    <button type="button" class="btn btn-sm btn-success" onclick="viewPersonnelDoc('${res.doc_id}')">
                        <i class='bx bx-file'></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTableRowDoc('${res.doc_id}', '${category}', '${relatedId}')">
                        <i class='bx bx-x'></i>
                    </button>
                `);
        Swal.fire({
          icon: "success",
          title: "อัปโหลดสำเร็จ",
          timer: 1500,
          showConfirmButton: false,
        });
      } else {
        Swal.fire("อัปโหลดล้มเหลว", res.message, "error");
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว", "error");
    },
  });

  input.value = "";
}

// Delete document from table row
function deleteTableRowDoc(docId, category, relatedId) {
  Swal.fire({
    title: "ยืนยันการลบ?",
    text: "ลบเอกสารแนบนี้?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "ลบ",
    cancelButtonText: "ยกเลิก",
    confirmButtonColor: "#ff3e1d",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Document/Delete",
        type: "POST",
        data: { doc_id: docId },
        dataType: "json",
        success: function (res) {
          if (res.status === "success") {
            const cell = $(`#doc-cell-${category}-${relatedId}`);
            cell.html(`
                            <label class="btn btn-sm btn-outline-secondary m-0" style="cursor:pointer;">
                                <i class='bx bx-upload'></i>
                                <input type="file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" onchange="uploadTableRowDoc('${category}', '${relatedId}', this)">
                            </label>
                        `);
            Swal.fire({
              icon: "success",
              title: "ลบสำเร็จ",
              timer: 1500,
              showConfirmButton: false,
            });
          } else {
            Swal.fire("ขัดข้อง", res.message, "error");
          }
        },
        error: function () {
          Swal.fire("ข้อผิดพลาด", "เชื่อมต่อเซิร์ฟเวอร์ไม่ได้", "error");
        },
      });
    }
  });
}

// ==========================================
// Attendance Summary Functions (for Leave History tab)
// ==========================================

// Load attendance summary for a specific date range
function loadAttendanceSummary(persId, start, end) {
    // Show Loading
    Swal.fire({
        title: 'กำลังประมวลผล...',
        text: 'กรุณารอสักครู่ ระบบกำลังสรุปข้อมูลการมาทำงาน',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Update display labels
    $("#display-start").text(start);
    $("#display-end").text(end);

    $.ajax({
        url: `../../../../Admin/WorkPerson/Personnel/DB/Attendance/Summary/${persId}`,
        type: "GET",
        data: { start: start, end: end },
        dataType: "json",
        success: function(res) {
            Swal.close();
            // Update summary stats
            if (res.summary) {
                $("#stat-present").text(res.summary.present || 0);
                $("#stat-sick").text(res.summary.sick || 0);
                $("#stat-personal").text(res.summary.personal || 0);
                $("#stat-official").text(res.summary.official || 0);
                $("#stat-absent").text(res.summary.absent || 0);
                $("#stat-other").text(res.summary.other || 0);
            }

            // Update table
            const leaveTable = $("#leaveTable tbody");
            leaveTable.empty();
            
            if (res.records && res.records.length > 0) {
                res.records.forEach((rec) => {
                    const statusClass = {
                        'ลาป่วย': 'text-danger',
                        'ลากิจ': 'text-warning',
                        'ไปราชการ': 'text-primary',
                        'ขาด': 'text-dark fw-bold',
                        'อื่นๆ': 'text-muted'
                    }[rec.att_status] || '';

                    const newRow = `
                        <tr>
                            <td>${rec.date_display}</td>
                            <td><span class="${statusClass}">${rec.att_status}</span></td>
                            <td><small>${rec.att_reason || "-"}</small></td>
                        </tr>
                    `;
                    leaveTable.append(newRow);
                });
            } else {
                leaveTable.append('<tr><td colspan="3" class="text-center text-muted py-4">ไม่มีข้อมูลการลา/ขาดในช่วงวันที่เลือก</td></tr>');
            }
        },
        error: function() {
            Swal.close();
            Swal.fire("ข้อผิดพลาด", "ไม่สามารถโหลดข้อมูลได้", "error");
        }
    });
}

// Search button click handler
$(document).on("click", "#btnLoadAttendance", function() {
    const start = $("#leave_start_date").val();
    const end = $("#leave_end_date").val();
    const persId = $("#pers_id").val();
    
    if (!start || !end) {
        Swal.fire("แจ้งเตือน", "กรุณาระบุช่วงวันที่ต้องการค้นหา", "warning");
        return;
    }

    if (persId) {
        loadAttendanceSummary(persId, start, end);
    }
});

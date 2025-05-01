$('.select2Personnel').select2({
    placeholder: "เลือกตัวเลือก",
    allowClear: true,
  });


//ฟังก์ชันหาอายุ
function calculateAge(birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);

    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();

    // ถ้ายังไม่ถึงวันเกิดปีนี้ → ลบอายุลง 1
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}

$(document).on('submit', '#FormPersonnalAdd', function (e) {
    e.preventDefault();

    $.ajax({
        url: "../../../Admin/WorkPerson/Personnel/DB/Insert",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            if (res == 1) {
                Swal.fire(
                    'แจ้งเตือน!', "บันทึกข้อมูลสำเร็จ!",
                    'success'
                )
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "บันทึกข้อมูลสำเร็จ!",
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../../../Admin/WorkPerson/Personnel";
                    }
                })

            } else if (res.success == false) {
                Swal.fire(
                    'แจ้งเตือน!', "บันทึกข้อมูลไม่สำเร็จ!",
                    'error'
                )
            }
        }
    });
});

$(document).on('submit', '#FormPersonnalUpdateDataPersonnel', function (e) {
    e.preventDefault();

    $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Update/DataPersonnel",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            if (res == 1) {
                Swal.fire({
                    title: 'แจ้งเตือน?',
                    text: "อัพเดตข้อมูลสำเร็จ!",
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {

                    }
                })

            } else if (res.success == false) {
                Swal.fire(
                    'แจ้งเตือน!', "บันทึกข้อมูลไม่สำเร็จ!",
                    'error'
                )
            }
        }
    });
});

$(document).on('submit', '#FormPersonnalHistory', function (e) {
    e.preventDefault();
    $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Update/DataHistory",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            console.log(res);
            // if (res == 1) {
            //     Swal.fire({
            //         title: 'แจ้งเตือน?',
            //         text: "อัพเดตข้อมูลสำเร็จ!",
            //         icon: 'success'
            //     }).then((result) => {
            //         if (result.isConfirmed) {

            //         }
            //     })

            // } else if (res.success == false) {
            //     Swal.fire(
            //         'แจ้งเตือน!', "บันทึกข้อมูลไม่สำเร็จ!",
            //         'error'
            //     )
            // }
        }
    });
});


$(document).on('change', '#pers_img', function () {

    var fileInput = $('#pers_img')[0].files[0];
    var formData = new FormData();
    formData.append('file', fileInput);
    formData.append('KeyPresID', $(this).attr('key-persid'));
    $.ajax({
        url: "../../../../Admin/WorkPerson/Personnel/DB/Update/Img",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (res) {
            if (res == 1) {
                Swal.fire(
                    'แจ้งเตือน!', "เปลี่ยนรูปภาพสำเร็จ",
                    'success'
                )
            }
        }
    });
});


function loadPersonnelData(id) {
    $.ajax({
        url: '../../../../Admin/WorkPerson/Personnel/DB/Get/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            //console.log(data);

            $('.pers_prefix').val(data.pers_prefix);
            $('.pers_firstname').val(data.pers_firstname);
            $('.pers_lastname').val(data.pers_lastname);
            $('.pers_id_card').val(data.pers_id_card);
            $('.pers_britday').val(data.pers_britday);
            $('.pers_nationality').val(data.pers_nationality);
            $('.pers_race').val(data.pers_race);
            $('.pers_religion').val(data.pers_religion);
            $('.pers_marital_status').val(data.pers_marital_status);
            $('.pers_blood_type').val(data.pers_blood_type);
            $('.pers_email').val(data.pers_username);
            $('.pers_phone').val(data.pers_phone);
            $('.pers_facebook').val(data.pers_facebook);
            $('.pers_instagram').val(data.pers_instagram);
            $('.pers_youtube').val(data.pers_youtube);
            $('.pers_social_links').val(data.pers_social_links);
            $('.pers_line').val(data.pers_line);
            $('.pers_military_service').val(data.pers_military_service);

            $('.pers_age').val(calculateAge(data.pers_britday));

            $('.pers_status').val(data.pers_status);
            $('.pers_username').val(data.pers_username);
            $('.pers_position').val(data.pers_position);
            $('.pers_department').val(data.pers_department);
            $('.pers_learning').val(data.pers_learning);
            $('.pers_academic').val(data.pers_academic);
            $('.pers_groupleade').val(data.pers_groupleade);
        },
        error: function () {
            alert('ไม่พบข้อมูลบุคลากร');
        }
    });
}

const pathSegments = window.location.pathname.split('/');
const lastSegment = pathSegments[pathSegments.length - 1];
const matches = lastSegment.match(/^pers_(\d+)$/);
//console.log(matches);
if (matches) {
    const id = matches[0];
    loadPersonnelData(id);
}


// ฟังก์ชันคัดลอกที่อยู่เมื่อ checkbox ถูกติ๊ก
$('#same_address').on('change', function () {
    const isChecked = $(this).is(':checked');
    const fields = [
        'house_no', 'moo', 'village', 'soi', 'road', 'subdistrict', 'district', 'province', 'postcode'
    ];
    fields.forEach(f => {
        const sourceVal = $('#addr_' + f).val();
        const $target = $('#curr_addr_' + f);
        if (isChecked) {
            $target.val(sourceVal);
        }
    });
});
// ถ้ามีการแก้ไขที่อยู่ทะเบียนบ้าน และ checkbox ถูกติ๊ก -> sync อัตโนมัติ
const addrFields = [
    'house_no', 'moo', 'village', 'soi', 'road', 'subdistrict', 'district', 'province', 'postcode'
];
addrFields.forEach(f => {
    $('#addr_' + f).on('input', function () {
        if ($('#same_address').is(':checked')) {
            $('#curr_addr_' + f).val($(this).val());
        }
    });
});



//   ครอบครัว

let familyIndex = 1;

$('#add-family-member').on('click', function () {
    
    familyIndex++;
    const familyTemplate = `
      <div class="row g-3 family-member mb-3">
        <div class="col-md-4 form-floating">
          <input type="text" class="form-control" id="fam_name_${familyIndex}" name="fam_name[]" placeholder="ชื่อ">
          <label for="fam_name_${familyIndex}">ชื่อสมาชิกในครอบครัว</label>
        </div>
        <div class="col-md-4 form-floating">
          <select class="form-select" id="fam_relation_${familyIndex}" name="fam_relation[]" aria-label="ความสัมพันธ์">
            <option value="">เลือก...</option>
            <option value="บิดา">บิดา</option>
            <option value="มารดา">มารดา</option>
            <option value="คู่สมรส">คู่สมรส</option>
            <option value="บุตร">บุตร</option>
          </select>
          <label for="fam_relation_${familyIndex}">ความสัมพันธ์</label>
        </div>
        <div class="col-md-2 form-floating">
          <input type="date" class="form-control selectorEdit" id="fam_birth_date_${familyIndex}" name="fam_birth_date[]" placeholder="วันเกิด">
          <label for="fam_birth_date_${familyIndex}">วันเกิด</label>
        </div>
        <div class="col-md-2 form-floating">
          <div class="d-flex">
            <input type="text" class="form-control" id="fam_occupation_${familyIndex}" name="fam_occupation[]" placeholder="อาชีพ">
            <button type="button" class="btn btn-danger ms-2" onclick="this.closest('.family-member').remove()">ลบ</button>
          </div>
        </div>
      </div>`;
    $('#family-section').append(familyTemplate);
     selectorEdit();
});




 // เมื่อมีการเปลี่ยนแปลงค่าของ select แรก
 $('#pers_position').on('change', function() {
    // ถ้าเลือก "แสดง Select ถัดไป"
    var index = $(this).prop('selectedIndex'); // รับลำดับที่เลือก (0-based)
   
    if (index > 0 && index <= 6) {
        // แสดง select ถัดไป
        $('#show_learning').show();
        $('#show_position').hide();
    } else if (index >= 7) {
        $('#show_position').show();
        $('#show_learning').hide();

        if (selectedPosition !== "") {
            var selectedPosition = $(this).val();
          // alert(selectedPosition);
            $.ajax({
                url: '../../../Admin/WorkPerson/Personnel/DB/Select/GetPositionData', // URL ที่จะส่งคำขอไปยัง controller
                type: 'POST',
                data: { position_id: selectedPosition },
                success: function(response) {
                   
                    var data = (response);
           
                    var secondSelect = $('#pers_workother_id');
                    secondSelect.empty(); // ล้างค่าเก่า

                    if (data.length > 0) {
                        // เพิ่มข้อมูลใน select
                        secondSelect.append('<option value="">--เลือกข้อมูล--</option>');
                        $.each(data, function(index, item) {
                            secondSelect.append('<option value="' + item.work_id + '">' + item.work_name + '</option>');
                        });
                    } else {
                        secondSelect.append('<option value="">ไม่มีข้อมูล</option>');
                    }
                }
            });
        } else {
            $('#pers_workother_id').empty().append('<option value="">--กรุณาเลือกตำแหน่งก่อน--</option>');
        }

     } else {
        // ซ่อน select ถัดไป
        $('#show_learning').hide();
        $('#show_position').hide();
    }
});


var items = document.querySelector('.sortable');
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
            localStorage.setItem(sortable.options.group.name, orden.join('|'));
            // console.log(orden);

            $.post("../../../../Admin/WorkPerson/Personnel/DB/SortableTeacher", {
                data: orden
            }, function (data, status) {
                console.log("Data: " + data + "\nStatus: " + status);
            });
        },
        //get list order       
        get: (sortable) => {
            const orden = localStorage.getItem(sortable.options.group.name);
            return orden ? orden.split('|') : [];
            console.log(orden);
        }

    }
});

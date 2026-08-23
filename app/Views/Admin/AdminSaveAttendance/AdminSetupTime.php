<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Hero Banner Card -->
<div class="page-header p-4 mb-4">
    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
        <span class="badge bg-white text-primary rounded-pill px-3 py-1 text-uppercase fw-bold" style="font-size: 0.75rem;">
            <i class="bx bx-time-five me-1"></i> เกณฑ์เวลา
        </span>
    </div>
    <h3 class="fw-extrabold text-white mb-2 text-shadow">
        ตั้งค่าเวลามาปฏิบัติหน้าที่ (Work Shift Time)
    </h3>
    <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
        กำหนดเวลาเข้างานของแต่ละตำแหน่ง (หากสแกนหลังเวลานี้ระบบจะประเมินว่ามาสายโดยอัตโนมัติ)
    </p>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tbTimeConfig">
                <thead class="table-light">
                    <tr>
                        <th width="10%" class="text-center">ลำดับ</th>
                        <th width="50%">กลุ่มตำแหน่ง/สังกัด</th>
                        <th width="40%" class="text-center">เวลาที่ถือว่าสาย (HH:mm)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loading... -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let table = $('#tbTimeConfig').DataTable({
            processing: true,
            ajax: {
                url: '<?= base_url("Admin/SaveAttendance/DB/Select/GetTimeConfigs") ?>',
                dataSrc: ''
            },
            columns: [
                {
                    data: null,
                    className: "text-center align-middle",
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { 
                    data: 'posi_name',
                    className: "align-middle fw-bold",
                },
                {
                    data: 'late_time',
                    className: "text-center",
                    render: function(data, type, row) {
                        // Make sure late_time handles null, default to 08:00
                        let timeVal = (data && data.length >= 5) ? data.substring(0, 5) : '08:00';
                        return `<div class="d-flex justify-content-center">
                                  <div class="input-group" style="max-width: 250px;">
                                      <input type="time" class="form-control text-center time-input" 
                                             data-id="${row.posi_id}" value="${timeVal}">
                                      <button class="btn btn-primary btn-save-time" type="button" 
                                              data-id="${row.posi_id}">บันทึก</button>
                                  </div>
                                </div>`;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json',
            }
        });

        // Delegate event for saving
        $('#tbTimeConfig').on('click', '.btn-save-time', function() {
            let posiId = $(this).data('id');
            let input = $(this).siblings('input.time-input');
            let timeVal = input.val();
            let btn = $(this);

            if(!timeVal) {
                Swal.fire('แจ้งเตือน', 'กรุณาระบุเวลาให้ครบถ้วน', 'warning');
                return;
            }

            // Append seconds for MySQL TIME format (HH:mm:ss)
            let mysqlTime = timeVal + ':00';

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: '<?= base_url("Admin/SaveAttendance/SetupTime/Save") ?>',
                method: 'POST',
                data: {
                    posi_id: posiId,
                    late_time: mysqlTime
                },
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'บันทึกเวลาสำเร็จ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        btn.prop('disabled', false).html('บันทึก');
                    } else {
                        Swal.fire('ข้อผิดพลาด', res.message, 'error');
                        btn.prop('disabled', false).html('บันทึก');
                    }
                },
                error: function() {
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                    btn.prop('disabled', false).html('บันทึก');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

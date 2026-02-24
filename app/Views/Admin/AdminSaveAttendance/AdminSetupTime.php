<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary: #007bff;
        --success: #28a745;
        --card-shadow: 0 4px 20px rgba(0, 123, 255, 0.08);
    }
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0056b3 100%);
        padding: 2rem 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.2);
        position: relative;
        overflow: hidden;
    }
    .page-header h1 { color: white; font-size: 1.75rem; font-weight: 700; }
    .page-header p { color: rgba(255,255,255,0.8); margin-bottom: 0; }
    
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #e9ecef;
        box-shadow: var(--card-shadow);
    }
</style>

<div class="p-4">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-clock-history me-2"></i>ตั้งค่าเวลามาทำงาน</h1>
            <p>กำหนดเวลาเข้างานของแต่ละตำแหน่ง (หากสแกนหลังเวลานี้จะถือว่ามาสาย)</p>
        </div>
    </div>

    <div class="section-card">
        <div class="table-responsive">
            <table class="table table-hover table-bordered w-100" id="tbTimeConfig">
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

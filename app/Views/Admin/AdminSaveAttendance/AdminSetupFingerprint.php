<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<!-- Hero Banner Card -->
<div class="page-header p-4 mb-4">
    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
        <span class="badge bg-white text-primary rounded-pill px-3 py-1 text-uppercase fw-bold" style="font-size: 0.75rem;">
            <i class="bx bx-fingerprint me-1"></i> ระบบสแกนนิ้ว
        </span>
    </div>
    <h3 class="fw-extrabold text-white mb-2 text-shadow">
        ตั้งค่ารหัสเครื่องสแกนนิ้ว (Attendance Machine)
    </h3>
    <p class="text-white text-opacity-90 mb-0" style="max-width: 650px; font-size: 0.92rem; line-height: 1.5;">
        เชื่อมโยงและจับคู่รหัสบุคลากรกับรหัสที่บันทึกจากเครื่องสแกนลายนิ้วมือ
    </p>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tbFingerprint">
                <thead class="table-light">
                    <tr>
                        <th width="10%">ลำดับ</th>
                        <th width="40%">ชื่อ-นามสกุล</th>
                        <th width="30%">ตำแหน่ง</th>
                        <th width="20%">รหัสเครื่องสแกนนิ้ว</th>
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
        let table = $('#tbFingerprint').DataTable({
            processing: true,
            ajax: {
                url: '<?= base_url("Admin/SaveAttendance/DB/Select/GetPersonnalData") ?>',
                dataSrc: ''
            },
            columns: [
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.pers_prefix + row.pers_firstname + ' ' + row.pers_lastname + 
                               ' <br><small class="text-muted">' + row.pers_id + '</small>';
                    }
                },
                { data: 'posi_name' },
                {
                    data: 'pers_finger_id',
                    render: function(data, type, row) {
                        let val = data ? data : '';
                        return `<div class="input-group">
                                    <input type="text" class="form-control fingerprint-input" 
                                           data-id="${row.pers_id}" value="${val}" placeholder="ระบุเลขสแกนนิ้ว">
                                    <button class="btn btn-primary btn-save-fingerprint" type="button" 
                                            data-id="${row.pers_id}">บันทึก</button>
                                </div>`;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json',
            }
        });

        // Delegate event for saving
        $('#tbFingerprint').on('click', '.btn-save-fingerprint', function() {
            let persId = $(this).data('id');
            let input = $(this).siblings('input.fingerprint-input');
            let fingerId = input.val().trim();
            let btn = $(this);

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: '<?= base_url("Admin/SaveAttendance/SetupFingerprint/Save") ?>',
                method: 'POST',
                data: {
                    pers_id: persId,
                    pers_finger_id: fingerId
                },
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
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

        // Allow pressing Enter to save
        $('#tbFingerprint').on('keypress', '.fingerprint-input', function(e) {
            if(e.which == 13) {
                $(this).siblings('.btn-save-fingerprint').click();
            }
        });
    });
</script>
<?= $this->endSection() ?>

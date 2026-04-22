<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --primary: #03c3ec;
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
    <div class="page-header">
        <h1><i class="bi bi-fingerprint me-2"></i>ตั้งค่ารหัสเครื่องสแกนนิ้ว</h1>
        <p>เชื่อมโยงรหัสบุคลากรกับรหัสที่ได้จากเครื่องสแกนนิ้ว (Attendance Machine)</p>
    </div>

    <div class="section-card">
        <div class="table-responsive">
            <table class="table table-hover table-bordered w-100" id="tbFingerprint">
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

<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --header-gradient: linear-gradient(135deg, #0267C1 0%, #009DFF 100%);
        --card-border-radius: 15px;
        --pending-color: #FFB000;
        --approved-color: #00D26A;
        --rejected-color: #F8312F;
    }

    .leave-header {
        background: var(--header-gradient);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 157, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .leave-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stat-card-leave {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .stat-card-leave:hover {
        transform: translateY(-5px);
    }

    .leave-table-container {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-pending { background: #FFF9E6; color: var(--pending-color); }
    .status-approved { background: #E6FBF0; color: var(--approved-color); }
    .status-rejected { background: #FEEBEB; color: var(--rejected-color); }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
    }

    .custom-table thead th {
        background: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #6c757d;
        border-bottom: none;
    }
</style>

<div class="leave-header">
    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
        <div>
            <span class="badge bg-white text-primary mb-2 fw-bold px-3">E-Leave Management</span>
            <h2 class="text-white mb-1 fw-bold"><i class="bi bi-calendar-check me-2"></i><?= $title ?></h2>
            <p class="text-white opacity-75 mb-0">จัดการคำขอลาและตรวจสอบสถิติการลาของบุคลากรทั้งโรงเรียน</p>
        </div>
        <div>
            <a href="<?= base_url('Admin/Leave/Settings') ?>" class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow">
                <i class="bi bi-gear-fill me-2"></i> ตั้งค่าประเภทการลา
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-leave border-start border-4 border-info">
            <h6 class="text-muted small fw-bold">รายการลาทั้งหมด</h6>
            <h3 class="fw-bold mb-0"><?= count($leaveRequests) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-leave border-start border-4 border-warning">
            <h6 class="text-muted small fw-bold">รออนุมัติ</h6>
            <h3 class="fw-bold mb-0 text-warning">
                <?= count(array_filter($leaveRequests, fn($x) => $x['leave_status'] == 'pending')) ?>
            </h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-leave border-start border-4 border-success">
            <h6 class="text-muted small fw-bold">อนุมัติแล้ว</h6>
            <h3 class="fw-bold mb-0 text-success">
                <?= count(array_filter($leaveRequests, fn($x) => $x['leave_status'] == 'approved')) ?>
            </h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-leave border-start border-4 border-danger">
            <h6 class="text-muted small fw-bold">ไม่อนุมัติ/ยกเลิก</h6>
            <h3 class="fw-bold mb-0 text-danger">
                <?= count(array_filter($leaveRequests, fn($x) => in_array($x['leave_status'], ['rejected', 'cancelled']))) ?>
            </h3>
        </div>
    </div>
</div>

<div class="leave-table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle custom-table" id="tableLeaveRequests">
            <thead>
                <tr>
                    <th>บุคลากร</th>
                    <th>ประเภทการลา</th>
                    <th>วันที่ลา</th>
                    <th>จำนวนวัน</th>
                    <th>สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($leaveRequests as $leave): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= base_url('uploads/admin/Personnal/' . ($leave['pers_img'] ?: 'default.png')) ?>" class="user-avatar shadow-sm" alt="">
                            <div>
                                <div class="fw-bold"><?= $leave['pers_prefix'].$leave['pers_firstname'].' '.$leave['pers_lastname'] ?></div>
                                <small class="text-muted"><?= $leave['pers_id'] ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-label-info"><?= $leave['leave_type_name'] ?></span>
                    </td>
                    <td>
                        <div class="small fw-bold text-dark">
                            <?= date('d/m/Y', strtotime($leave['leave_start_date'])) ?> - 
                            <?= date('d/m/Y', strtotime($leave['leave_end_date'])) ?>
                        </div>
                    </td>
                    <td>
                        <span class="fw-bold"><?= floatval($leave['leave_total_days']) ?></span> <small>วัน</small>
                        <div class="xsmall text-muted" style="font-size: 0.7rem;">
                            <?= $leave['leave_period'] === 'full' ? 'เต็มวัน' : ($leave['leave_period'] === 'morning' ? 'ครึ่งวันเช้า' : 'ครึ่งวันบ่าย') ?>
                        </div>
                    </td>
                    <td>
                        <?php if($leave['leave_status'] == 'pending'): ?>
                            <span class="status-badge status-pending"><i class="bi bi-clock-history"></i> รออนุมัติ</span>
                        <?php elseif($leave['leave_status'] == 'approved'): ?>
                            <span class="status-badge status-approved"><i class="bi bi-check-circle"></i> อนุมัติแล้ว</span>
                        <?php elseif($leave['leave_status'] == 'rejected'): ?>
                            <span class="status-badge status-rejected"><i class="bi bi-x-circle"></i> ไม่อนุมัติ</span>
                        <?php else: ?>
                            <span class="status-badge bg-light text-muted"><i class="bi bi-slash-circle"></i> ยกเลิก</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm btn-view-leave" data-id="<?= $leave['leave_id'] ?>">
                            <i class="bi bi-search me-1"></i> รายละเอียด
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal รายละเอียดการลา -->
<div class="modal fade" id="modalLeaveDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">รายละเอียดคำขอลา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="leaveDetailContent">
                    <!-- Loading or Content -->
                </div>
                <hr>
                <form id="formUpdateStatus">
                    <input type="hidden" name="leave_id" id="modal_leave_id">
                    <div class="col-md-12">
                        <div class="form-floating h-100">
                            <textarea class="form-control" name="leave_comment" placeholder="ความคิดเห็น/เหตุผล" style="height: 100px" id="modal_leave_comment"></textarea>
                            <label><i class="bi bi-chat-dots me-1"></i> ความคิดเห็นจากผู้อนุมัติ</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
                <div id="approvalButtons">
                    <button type="button" class="btn btn-danger rounded-pill px-4 me-2" onclick="handleApproval('rejected')">
                        <i class="bi bi-x-circle me-1"></i> ไม่อนุมัติ
                    </button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow" onclick="handleApproval('approved')">
                        <i class="bi bi-check2-circle me-1"></i> อนุมัติการลา
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#tableLeaveRequests').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json'
        },
        order: [[4, 'asc'], [2, 'desc']]
    });

    // เปิด Modal ดูรายละเอียด
    $('.btn-view-leave').on('click', function() {
        const id = $(this).data('id');
        $('#modal_leave_id').val(id);
        $('#leaveDetailContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
        $('#modalLeaveDetail').modal('show');

        $.get(`<?= base_url('Admin/Leave/Get/') ?>${id}`, function(res) {
            let html = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">ผู้ขอลา</small>
                        <div class="fw-bold">${res.pers_prefix}${res.pers_firstname} ${res.pers_lastname}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">ประเภทการลา</small>
                        <span class="badge bg-label-info text-info">${res.leave_type_name}</span>
                    </div>
                </div>
                
                <div class="row g-3 mt-1">
                    <div class="col-md-12">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>สรุปโควตาในปีนี้</h6>
                                <div class="row text-center g-2">
                                    <div class="col-4">
                                        <div class="small text-muted">ใช้แล้ว</div>
                                        <div class="fw-bold text-danger">${res.used_days} วัน</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">สิทธิทั้งหมด</div>
                                        <div class="fw-bold">${res.quota_total} วัน</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="small text-muted">คงเหลือ</div>
                                        <div class="fw-bold text-success">${res.quota_remaining} วัน</div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 8px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: ${(res.used_days/res.quota_total)*100}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <small class="text-muted d-block">วันที่ลา</small>
                        <div class="fw-bold"><i class="bi bi-calendar-event me-2 text-primary"></i>${res.start_date_th} - ${res.end_date_th}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">จำนวนวัน / ช่วงเวลา</small>
                        <div class="fw-bold text-primary fs-5">
                            ${parseFloat(res.leave_total_days)} วัน 
                            <small class="text-muted fs-6">(${res.leave_period === 'full' ? 'เต็มวัน' : (res.leave_period === 'morning' ? 'ครึ่งวันเช้า' : 'ครึ่งวันบ่าย')})</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">เหตุผลการลา/หัวข้อ</small>
                        <div class="p-3 bg-light rounded-3 mt-1 fw-bold">${res.leave_topic}</div>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">รายละเอียดเพิ่มเติม</small>
                        <div class="mt-1">${res.leave_detail || '-'}</div>
                    </div>
                </div>
            `;
            $('#leaveDetailContent').html(html);
            $('#modal_leave_comment').val(res.leave_comment || '');
            
            // แสดง/ซ่อนปุ่มอนุมัติตามสถานะ
            if(res.leave_status !== 'pending') {
                $('#approvalButtons').hide();
            } else {
                $('#approvalButtons').show();
            }
        });
    });
});

// ฟังก์ชันจัดการการอนุมัติ
function handleApproval(status) {
    const leaveId = $('#modal_leave_id').val();
    const comment = $('#modal_leave_comment').val();
    const statusText = status === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
    const confirmColor = status === 'approved' ? '#3085d6' : '#d33';

    Swal.fire({
        title: `ยืนยันพิจารณา [${statusText}]?`,
        text: "เมื่อบันทึกแล้วจะไม่สามารถแก้ไขได้ในภายหลัง",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#aaa',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url('Admin/Leave/UpdateStatus') ?>', {
                leave_id: leaveId,
                leave_status: status,
                leave_comment: comment
            }, function(res) {
                if(res.status === 'success'){
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            }).fail(function(xhr) {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            });
        }
    });
}
</script>
<?= $this->endSection() ?>

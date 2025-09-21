<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">ตั้งค่าระบบ /</span> ตั้งค่าผู้ประเมิน PA</h4>

<div class="card mb-4">
    <h5 class="card-header">เพิ่มขอบเขตการประเมิน</h5>
    <div class="card-body">
        <form action="<?= base_url('Admin/PaConfig/save'); ?>" method="POST">
            <div class="mb-3">
                <label for="assessor_e_id" class="form-label">ผู้ประเมิน (Assessor)</label>
                <div class="input-group">
                    <select class="form-select" id="assessor_e_id" name="assessor_e_id" required>
                        <option value="">เลือกผู้ประเมิน</option>
                        <?php foreach ($evaluators as $e): ?>
                            <option value="<?= esc($e['e_id']); ?>">
                                <?= esc($e['e_first_name'] . ' ' . $e['e_last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#evaluatorManagerModal">
                        จัดการผู้ประเมิน
                    </button>
                </div>
            </div>
            <div class="mb-3">
                <label for="scope_posi_id" class="form-label">ตำแหน่งที่ประเมิน (Position Scope)</label>
                <select class="form-select" id="scope_posi_id" name="scope_posi_id">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($positions as $pos): ?>
                        <?php if ($pos['posi_name'] === 'ครู' || $pos['posi_name'] === 'ครูผู้ช่วย'): ?>
                        <option value="<?= esc($pos['posi_id']); ?>">
                            <?= esc($pos['posi_name']); ?>
                        </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">เลือก 'ทั้งหมด' หากผู้ประเมินสามารถประเมินได้ทุกตำแหน่ง</small>
            </div>
            <div class="mb-3">
                <label for="scope_lear_id" class="form-label">กลุ่มสาระที่ประเมิน (Learning Group Scope)</label>
                <select class="form-select" id="scope_lear_id" name="scope_lear_id">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($learningGroups as $lg): ?>
                        <option value="<?= esc($lg['lear_id']); ?>">
                            <?= esc($lg['lear_namethai']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">เลือก 'ทั้งหมด' หากผู้ประเมินสามารถประเมินได้ทุกกลุ่มสาระ</small>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการตั้งค่า</button>
        </form>
    </div>
</div>

<div class="card">
    <h5 class="card-header">ขอบเขตการประเมินที่ตั้งค่าไว้</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ผู้ประเมิน</th>
                    <th>ตำแหน่งที่ประเมิน</th>
                    <th>กลุ่มสาระที่ประเมิน</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assessorScopes)): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($assessorScopes as $scope): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td>
                                <?php
                                    $assessorName = 'ไม่พบข้อมูล';
                                    foreach ($evaluators as $e) {
                                        if ($e['e_id'] === $scope['assessor_e_id']) {
                                            $assessorName = esc($e['e_first_name'] . ' ' . $e['e_last_name']);
                                            break;
                                        }
                                    }
                                    echo $assessorName;
                                ?>
                            </td>
                            <td>
                                <?php
                                    $posName = 'ทั้งหมด';
                                    if ($scope['scope_posi_id']) {
                                        foreach ($positions as $pos) {
                                            if ($pos['posi_id'] === $scope['scope_posi_id']) {
                                                $posName = esc($pos['posi_name']);
                                                break;
                                            }
                                        }
                                    }
                                    echo $posName;
                                ?>
                            </td>
                            <td>
                                <?php
                                    $learName = 'ทั้งหมด';
                                    if ($scope['scope_lear_id']) {
                                        foreach ($learningGroups as $lg) {
                                            if ($lg['lear_id'] === $scope['scope_lear_id']) {
                                                $learName = esc($lg['lear_namethai']);
                                                break;
                                            }
                                        }
                                    }
                                    echo $learName;
                                ?>
                            </td>
                            <td>
                                <a href="<?= base_url('Admin/PaConfig/delete/' . $scope['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบการตั้งค่านี้?');">
                                    <i class="bx bx-trash me-1"></i> ลบ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ยังไม่มีการตั้งค่าขอบเขตการประเมิน</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Evaluator Manager Modal -->
<div class="modal fade" id="evaluatorManagerModal" tabindex="-1" aria-labelledby="evaluatorManagerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="evaluatorManagerModalLabel">จัดการผู้ประเมิน</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEvaluatorModal">เพิ่มผู้ประเมินใหม่</button>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>ชื่อ-นามสกุล</th>
                <th>ตำแหน่ง</th>
                <th>วิทยฐานะ</th>
                <th>หน่วยงาน</th>
                <th>ชื่อผู้ใช้</th>
                <th>การกระทำ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($evaluators)): ?>
                <?php $i = 1; ?>
                <?php foreach ($evaluators as $e): ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= esc(($e['e_first_name'] ?? '') . ' ' . ($e['e_last_name'] ?? '')); ?></td>
                    <td><?= esc($e['e_position'] ?? ''); ?></td>
                    <td><?= esc($e['e_academic_standing'] ?? ''); ?></td>
                    <td><?= esc($e['e_organization'] ?? ''); ?></td>
                    <td><?= esc($e['e_Username'] ?? ''); ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm edit-evaluator-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#editEvaluatorModal"
                            data-id="<?= esc($e['e_id']); ?>"
                            data-first-name="<?= esc($e['e_first_name'] ?? ''); ?>"
                            data-last-name="<?= esc($e['e_last_name'] ?? ''); ?>"
                            data-position="<?= esc($e['e_position'] ?? ''); ?>"
                            data-academic-standing="<?= esc($e['e_academic_standing'] ?? ''); ?>"
                            data-organization="<?= esc($e['e_organization'] ?? ''); ?>"
                            data-username="<?= esc($e['e_Username'] ?? ''); ?>">
                            <i class="bx bx-edit-alt me-1"></i> แก้ไข
                        </button>
                        <a href="<?= base_url('Admin/PaConfig/deleteEvaluator/' . $e['e_id']); ?>" class="btn btn-danger btn-sm">
                           <i class="bx bx-trash me-1"></i> ลบ
                        </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">ไม่พบข้อมูลผู้ประเมิน</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Evaluator Modal -->
<div class="modal fade" id="addEvaluatorModal" tabindex="-1" aria-labelledby="addEvaluatorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEvaluatorModalLabel">เพิ่มผู้ประเมินใหม่</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addEvaluatorForm" action="<?= base_url('Admin/PaConfig/addEvaluator'); ?>" method="POST">
          <div class="mb-3">
            <label for="e_first_name" class="form-label">ชื่อจริง</label>
            <input type="text" class="form-control" id="e_first_name" name="e_first_name" required>
          </div>
          <div class="mb-3">
            <label for="e_last_name" class="form-label">นามสกุล</label>
            <input type="text" class="form-control" id="e_last_name" name="e_last_name" required>
          </div>
          <div class="mb-3">
            <label for="e_position" class="form-label">ตำแหน่ง</label>
            <input type="text" class="form-control" id="e_position" name="e_position">
          </div>
          <div class="mb-3">
            <label for="e_academic_standing" class="form-label">วิทยฐานะ</label>
            <input type="text" class="form-control" id="e_academic_standing" name="e_academic_standing">
          </div>
          <div class="mb-3">
            <label for="e_organization" class="form-label">หน่วยงาน</label>
            <input type="text" class="form-control" id="e_organization" name="e_organization">
          </div>
          <div class="mb-3">
            <label for="e_Username" class="form-label">ชื่อผู้ใช้งาน</label>
            <input type="text" class="form-control" id="e_Username" name="e_Username" required>
          </div>
          <div class="mb-3">
            <label for="e_Password" class="form-label">รหัสผ่าน</label>
            <input type="password" class="form-control" id="e_Password" name="e_Password" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-target="#evaluatorManagerModal" data-bs-toggle="modal" data-bs-dismiss="modal">กลับไปที่รายการ</button>
        <button type="submit" form="addEvaluatorForm" class="btn btn-primary">บันทึก</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Evaluator Modal -->
<div class="modal fade" id="editEvaluatorModal" tabindex="-1" aria-labelledby="editEvaluatorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEvaluatorModalLabel">แก้ไขข้อมูลผู้ประเมิน</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editEvaluatorForm" action="<?= base_url('Admin/PaConfig/updateEvaluator'); ?>" method="POST">
          <input type="hidden" id="edit_e_id" name="e_id">
          <div class="mb-3">
            <label for="edit_e_first_name" class="form-label">ชื่อจริง</label>
            <input type="text" class="form-control" id="edit_e_first_name" name="e_first_name" required>
          </div>
          <div class="mb-3">
            <label for="edit_e_last_name" class="form-label">นามสกุล</label>
            <input type="text" class="form-control" id="edit_e_last_name" name="e_last_name" required>
          </div>
          <div class="mb-3">
            <label for="edit_e_position" class="form-label">ตำแหน่ง</label>
            <input type="text" class="form-control" id="edit_e_position" name="e_position">
          </div>
          <div class="mb-3">
            <label for="edit_e_academic_standing" class="form-label">วิทยฐานะ</label>
            <input type="text" class="form-control" id="edit_e_academic_standing" name="e_academic_standing">
          </div>
          <div class="mb-3">
            <label for="edit_e_organization" class="form-label">หน่วยงาน</label>
            <input type="text" class="form-control" id="edit_e_organization" name="e_organization">
          </div>
          <div class="mb-3">
            <label for="edit_e_Username" class="form-label">ชื่อผู้ใช้งาน</label>
            <input type="text" class="form-control" id="edit_e_Username" name="e_Username" required>
          </div>
          <div class="mb-3">
            <label for="edit_e_Password" class="form-label">รหัสผ่านใหม่ (ไม่บังคับ)</label>
            <input type="password" class="form-control" id="edit_e_Password" name="e_Password">
            <small class="form-text text-muted">เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-target="#evaluatorManagerModal" data-bs-toggle="modal" data-bs-dismiss="modal">กลับไปที่รายการ</button>
        <button type="submit" form="editEvaluatorForm" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Handle flashdata messages with Swal2
    <?php if (session()->getFlashdata('Success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '<?= session()->getFlashdata('Success'); ?>',
            confirmButtonText: 'ตกลง'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('Error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด!',
            text: '<?= session()->getFlashdata('Error'); ?>',
            confirmButtonText: 'ตกลง'
        });
    <?php endif; ?>

    // Handle delete confirmation with Swal2 for assessor scopes
    document.querySelectorAll('.btn-danger[onclick*="confirm"]').forEach(button => {
        button.removeAttribute('onclick'); // Remove old onclick attribute
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบการตั้งค่านี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl; // Proceed with deletion
                }
            });
        });
    });

    // Handle delete confirmation with Swal2 for evaluators
    document.querySelectorAll('a[href*="deleteEvaluator"]').forEach(button => {
        button.removeAttribute('onclick'); // Remove old onclick attribute
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;

            // Hide the manager modal when delete is clicked
            const managerModal = bootstrap.Modal.getInstance(document.getElementById('evaluatorManagerModal'));
            if (managerModal) {
                managerModal.hide();
            }

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบผู้ประเมินนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl; // Proceed with deletion
                }
            });
        });
    });

    // Handle edit evaluator modal
    const editEvaluatorModal = document.getElementById('editEvaluatorModal');
    if (editEvaluatorModal) {
        editEvaluatorModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const modal = this;

            // Extract info from data-* attributes
            const id = button.getAttribute('data-id');
            const firstName = button.getAttribute('data-first-name');
            const lastName = button.getAttribute('data-last-name');
            const position = button.getAttribute('data-position');
            const academicStanding = button.getAttribute('data-academic-standing');
            const organization = button.getAttribute('data-organization');
            const username = button.getAttribute('data-username');

            // Update the modal's content.
            modal.querySelector('#edit_e_id').value = id;
            modal.querySelector('#edit_e_first_name').value = firstName;
            modal.querySelector('#edit_e_last_name').value = lastName;
            modal.querySelector('#edit_e_position').value = position;
            modal.querySelector('#edit_e_academic_standing').value = academicStanding;
            modal.querySelector('#edit_e_organization').value = organization;
            modal.querySelector('#edit_e_Username').value = username;
            modal.querySelector('#edit_e_Password').value = ''; // Clear password field
        });
    }
</script>
<?= $this->endSection() ?>

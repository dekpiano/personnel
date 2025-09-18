<?= $this->extend('User/Layout/main') ?>

<?= $this->section('content') ?>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">แบบประเมิน PA /</span> รายชื่อบุคลากร ที่รับการประเมิน (PA)</h4>

<div class="card p-3">
    <h5 class="card-header">รายชื่อบุคลากร ที่รับการประเมิน (PA)</h5>
    <div class="card-datatable table-responsive">
        <table id="personnel-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อ-สกุล</th>
                    <th>ตำแหน่ง</th>
                    <th>วิทยฐานะ</th>
                    <th>กลุ่มสาระฯ</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($personnel) && count($personnel) > 0): ?>
                    <?php $index = 1; ?>
                    <?php foreach ($personnel as $person): ?>
                        <tr>
                            <td><?= $index++; ?></td>
                            <td><?= esc($person['pers_prefix'] . $person['pers_firstname'] . ' ' . $person['pers_lastname']); ?></td>
                            <td><?= esc($person['posi_name']); ?></td>
                            <td><?= empty($person['pers_academic']) ? 'ไม่มีวิทยฐาน' : esc($person['pers_academic']); ?></td>
                            <td><?= esc($person['lear_namethai']); ?></td>
                            <td>
                                <?php if ($person['has_pa_evaluation']): ?>
                                    <span class="badge bg-label-success">ทำแบบประเมินแล้ว</span>
                                    <a href="<?= base_url('pa-form/' . $person['pers_id']); ?>" class="btn btn-info btn-sm ms-2">
                                        <i class="bx bx-edit-alt me-1"></i> แก้ไข/ดู
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('pa-form/' . $person['pers_id']); ?>" class="btn btn-primary btn-sm">
                                        <i class="bx bx-edit-alt me-1"></i> ทำแบบประเมิน
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#personnel-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
        }
    });
});
</script>
<?= $this->endSection() ?>
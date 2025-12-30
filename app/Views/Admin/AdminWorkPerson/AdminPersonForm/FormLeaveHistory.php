<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold text-primary mb-0 d-flex align-items-center">
            <i class='bx bx-calendar-x fs-4 me-2'></i> ประวัติการลา
        </h6>
        <button type="button" class="btn btn-outline-primary btn-sm fw-bold px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#leaveModal">
            <i class='bx bx-plus me-1'></i> เพิ่มประวัติการลา
        </button>
    </div>
    
    <div class="table-responsive rounded-4 border overflow-hidden">
        <table class="table table-hover mb-0" id="leaveTable">
            <thead class="bg-light">
                <tr class="text-uppercase small fw-bold">
                    <th class="ps-3 py-3">วัน/เดือน/ปี</th>
                    <th class="py-3">ประเภทการลา</th>
                    <th class="py-3 text-center">จำนวนวัน</th>
                    <th class="py-3">หมายเหตุ</th>
                    <th class="text-center py-3">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <!-- Load via AJAX -->
            </tbody>
        </table>
    </div>
</div>

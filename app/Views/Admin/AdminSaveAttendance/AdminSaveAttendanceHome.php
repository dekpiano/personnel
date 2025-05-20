<!-- Layout container -->
<div class="layout-page">
    <?php echo view('Admin/AdminLeyout/AdminNavbar'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="">
                <h2 class="mb-4">แดชบอร์ดสรุปการมาทำงานบุคลากร</h2>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="dateType" class="form-label">เลือกประเภทช่วงเวลา</label>
                        <select id="dateType" class="form-select">
                            <option value="day" selected>รายวัน</option>
                            <option value="month">รายเดือน</option>
                            <option value="year">รายปี</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div id="datePickerContainer">
                            <label for="dateInput" class="form-label">เลือกเดือน</label>
                            <input type="date" id="dateInput" class="form-control" value="<?=date('Y-m-d')?>">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end justify-content-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#attendanceModal">+
                            บันทึกการมาทำงาน</button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <!-- กราฟวงกลมสรุปสถิติ -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3" id="statTitle">สถิติการมาทำงาน</h5>
                                <div id="chart"></div>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <button class="btn btn-info w-100" data-bs-toggle="modal"
                                    data-bs-target="#leaveSummaryModal">
                                    รายงานสรุปวันลาบุคลากร
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <!-- ตัวเลขสรุป -->
                        <div class="row g-3" id="summaryBox">

                        </div>

                        <!-- ตารางสรุปการมาทำงานรายวัน -->
                        <div class="card shadow-sm mt-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3" id="tableTitle">ตารางการมาทำงาน</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="TbDashboradAttendance">
                                        <thead class="table-light">
                                            <tr>
                                                <th>วันที่</th>
                                                <th>ชื่อ-สกุล</th>
                                                <th>สถานะ</th>
                                                <th>หมายเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
</div>
<!-- / Layout page -->

<style>
td.td-name {
    white-space: nowrap;
}
</style>

<!-- Bootstrap Modal for Attendance Form -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="attendance-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceModalLabel">บันทึกการมาทำงานบุคลากร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="att_date" class="form-label">วันที่</label>
                        <input type="date" id="att_date" name="att_date" class="form-control"
                            value="<?=date('Y-m-d')?>">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center" id="TbSaveAttendance">
                            <thead class="table-primary">
                                <tr>
                                    <th>ชื่อ-สกุล</th>
                                    <th>ตำแหน่ง</th>
                                    <th>มา</th>
                                    <th>สาย</th>
                                    <th>ขาด</th>
                                    <th>ลากิจ</th>
                                    <th>ลาป่วย</th>
                                    <th>ไปราชการ</th>
                                    <th>อื่นๆ</th>
                                    <th>หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody id="personnel-tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="leaveSummaryModal" tabindex="-1" aria-labelledby="leaveSummaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="leaveSummaryModalLabel">สรุปวันลา</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="leaveDateStart" class="form-label">ตั้งแต่วันที่</label>
            <input type="date" id="leaveDateStart" class="form-control" value="<?=date('Y-m-d')?>">
          </div>
          <div class="col-md-4">
            <label for="leaveDateEnd" class="form-label">ถึงวันที่</label>
            <input type="date" id="leaveDateEnd" class="form-control" value="<?=date('Y-m-d')?>">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100" id="btnSearchLeave">ค้นหา</button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered align-middle" id="LeaveSummaryTable">
            <thead>
              <tr>
                <th>ชื่อ-สกุล</th>
                <th>ตำแหน่ง</th>
                <th>จำนวนวันทั้งหมด</th>
                <th>มา</th>
                <th>สาย</th>
                <th>ขาด</th>
                <th>ลากิจ</th>
                <th>ลาป่วย</th>
                <th>ไปราชการ</th>
                <th>อื่นๆ</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be loaded by JS -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

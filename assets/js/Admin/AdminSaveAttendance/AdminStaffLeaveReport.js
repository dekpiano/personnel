let leaveTable = null;

function loadLeaveSummary() {
    const start = document.getElementById('leaveDateStart').value;
    const end = document.getElementById('leaveDateEnd').value;
    fetch(`../Admin/SaveAttendance/DB/Select/LeaveSummary?start=${start}&end=${end}`)
        .then(res => res.json())
        .then(data => {
            if (leaveTable) {
                leaveTable.destroy();
                $('#LeaveSummaryTable tbody').empty();
            }
            if (!Array.isArray(data)) {
                console.error('Data is not an array:', data);
                return;
            }
            let rows = '';
            data.forEach(row => {
                rows += `
          <tr>
            <td>
                <div class="fw-bold">${row.pers_prefix}${row.pers_firstname} ${row.pers_lastname}</div>
            </td>
            <td class="text-muted small">${row.posi_name}</td>
            <td class="text-center fw-bold">${row.total_days}</td>
            <td class="text-center text-success fw-bold">${row.present}</td>
            <td class="text-center text-info fw-bold">${row.late}</td>
            <td class="text-center text-danger fw-bold">${row.absent}</td>
            <td class="text-center fw-bold" style="color: #fd7e14;">${row.personal_leave}</td>
            <td class="text-center text-warning fw-bold">${row.sick_leave}</td>
            <td class="text-center text-primary fw-bold">${row.official_leave}</td>
            <td class="text-center text-secondary">${row.other_leave}</td>
          </tr>
        `;
            });

            $('#LeaveSummaryTable tbody').html(rows);
            leaveTable = $('#LeaveSummaryTable').DataTable({
                ordering: false,
                language: {
                    search: "ค้นหา:",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                    paginate: { first: "หน้าแรก", last: "หน้าสุดท้าย", next: "ถัดไป", previous: "ก่อนหน้า" }
                }
            });
        }).catch(err => {
            console.error('Error fetching data:', err);
        });
}

document.getElementById('leaveSummaryModal').addEventListener('show.bs.modal', function () {
    loadLeaveSummary();
});

document.getElementById('btnSearchLeave').addEventListener('click', loadLeaveSummary);

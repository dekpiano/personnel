let leaveByPositionTable = null;

function loadLeaveSummaryByDay() {
    const date = document.getElementById('leaveSummaryDate').value;

    fetch(`../Admin/SaveAttendance/DB/Select/leaveSummaryByPositionDay?date=${date}`)
        .then(res => res.json())
        .then(data => {
            if (leaveByPositionTable) {
                leaveByPositionTable.destroy();
                $('#LeaveSummaryByPositionTable tbody').empty();
            }
            if (!Array.isArray(data)) {
                console.error('Data is not an array:', data);
                return;
            }
            let rows = '';
            data.forEach(row => {
                rows += `
          <tr>
            <td class="fw-bold">${row.posi_name}</td>
            <td class="text-center">${row.total_person}</td>
            <td class="text-center text-success fw-bold">${row.attend_person}</td>
            <td class="text-center text-warning fw-bold">${row.sick_leave}</td>
            <td class="text-center fw-bold" style="color: #fd7e14;">${row.personal_leave}</td>
            <td class="text-center text-primary fw-bold">${row.official_leave}</td>
            <td class="text-center text-secondary">${row.other_leave}</td>
          </tr>
        `;
            });
            rows += `      
            <tr class="table-light text-center fw-bold">
                <td class="text-start">รวมทั้งหมด</td>
                <td id="sum_total_person"></td>
                <td id="sum_attend_person" class="text-success"></td>
                <td id="sum_sick_leave" class="text-warning"></td>
                <td id="sum_personal_leave" style="color: #fd7e14;"></td>
                <td id="sum_official_leave" class="text-primary"></td>
                <td id="sum_other_leave" class="text-secondary"></td>
            </tr>
      `;
            $('#LeaveSummaryByPositionTable tbody').html(rows);

            leaveByPositionTable = $('#LeaveSummaryByPositionTable').DataTable({
                ordering: false,
                paging: false,
                searching: false,
                info: false
            });
            calcTableSummary();
            zeroToDashInTable('LeaveSummaryByPositionTable');
        });
}

document.getElementById('btnSearchLeaveByDay').addEventListener('click', loadLeaveSummaryByDay);
window.addEventListener('DOMContentLoaded', loadLeaveSummaryByDay);

function calcTableSummary() {
    const tbody = document.querySelector('#LeaveSummaryByPositionTable tbody');
    let sum_total_person = 0;
    let sum_attend_person = 0;
    let sum_personal_leave = 0;
    let sum_sick_leave = 0;
    let sum_official_leave = 0;
    let sum_other_leave = 0;

    for (let row of tbody.rows) {
        if (row.cells[0].textContent === 'รวมทั้งหมด') continue;
        sum_total_person += Number(row.cells[1].textContent) || 0;
        sum_attend_person += Number(row.cells[2].textContent) || 0;
        sum_sick_leave += Number(row.cells[3].textContent) || 0;
        sum_personal_leave += Number(row.cells[4].textContent) || 0;
        sum_official_leave += Number(row.cells[5].textContent) || 0;
        sum_other_leave += Number(row.cells[6].textContent) || 0;
    }

    document.getElementById('sum_total_person').textContent = sum_total_person;
    document.getElementById('sum_attend_person').textContent = sum_attend_person;
    document.getElementById('sum_sick_leave').textContent = sum_sick_leave;
    document.getElementById('sum_personal_leave').textContent = sum_personal_leave;
    document.getElementById('sum_official_leave').textContent = sum_official_leave;
    document.getElementById('sum_other_leave').textContent = sum_other_leave;
}

function zeroToDashInTable(tableId) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    for (let row of tbody.rows) {
        for (let i = 1; i < row.cells.length; i++) {
            if (row.cells[i].textContent.trim() === '0') {
                row.cells[i].textContent = '-';
            }
        }
    }
}

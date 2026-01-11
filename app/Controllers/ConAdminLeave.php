<?php

namespace App\Controllers;

use App\Models\LeaveRequestModel;
use App\Models\LeaveTypeModel;
use App\Models\HolidayModel;
use App\Models\LeaveYearModel;

class ConAdminLeave extends BaseController
{
    protected $leaveTypeModel;
    protected $leaveRequestModel;
    protected $holidayModel;
    protected $leaveYearModel;

    public function __construct()
    {
        $session = session();
        if (!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "manager"])) {
            header("Location:" . base_url());
            exit();
        }
        $this->leaveTypeModel = new LeaveTypeModel();
        $this->leaveRequestModel = new LeaveRequestModel();
        $this->holidayModel = new HolidayModel();
        $this->leaveYearModel = new LeaveYearModel();
    }

    public function DataMain()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri');
        return $data;
    }

    /**
     * หน้าหลัก รายการขอลาทั้งหมด
     */
    public function index()
    {
        $data = $this->DataMain();
        $data['title'] = "จัดการข้อมูลการลาบุคลากร";
        
        // ดึงรายการลาทั้งหมด
        $data['leaveRequests'] = $this->leaveRequestModel->getLeaveDetails();
        
        // ดึงประเภทการลาสำหรับใช้กรอง
        $data['leaveTypes'] = $this->leaveTypeModel->findAll();

        return view('Admin/PageAdminLeave/AdminLeaveMain', $data);
    }

    /**
     * หน้าตั้งค่าประเภทการลา
     */
    public function Settings()
    {
        $data = $this->DataMain();
        $data['title'] = "ตั้งค่าประเภทการลา";
        $data['leaveTypes'] = $this->leaveTypeModel->findAll();
        $data['leaveYears'] = $this->leaveYearModel->orderBy('ly_name', 'DESC')->findAll();

        return view('Admin/PageAdminLeave/AdminLeaveSettings', $data);
    }

    /**
     * บันทึก/แก้ไข ประเภทการลา (AJAX)
     */
    public function SaveLeaveType()
    {
        $id = $this->request->getPost('leave_type_id');
        $data = [
            'leave_type_name'  => $this->request->getPost('leave_type_name'),
            'leave_type_quota' => $this->request->getPost('leave_type_quota'),
            'leave_type_status' => $this->request->getPost('leave_type_status'),
        ];

        if ($id) {
            $this->leaveTypeModel->update($id, $data);
            $msg = "อัปเดตประเภทการลาสำเร็จ";
        } else {
            $this->leaveTypeModel->insert($data);
            $msg = "เพิ่มประเภทการลาสำเร็จ";
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบประเภทการลา (AJAX)
     */
    public function DeleteLeaveType($id)
    {
        if ($this->leaveTypeModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบประเภทการลาสำเร็จ']);
        }
        return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * บันทึก/แก้ไข ปีการศึกษา (AJAX)
     */
    public function SaveLeaveYear()
    {
        $id = $this->request->getPost('ly_id');
        $data = [
            'ly_name'       => $this->request->getPost('ly_name'),
            'ly_start_date' => $this->request->getPost('ly_start_date'),
            'ly_end_date'   => $this->request->getPost('ly_end_date'),
        ];

        if ($id) {
            $this->leaveYearModel->update($id, $data);
            $msg = "อัปเดตปีการศึกษาสำเร็จ";
        } else {
            // ถ้าเพิ่มใหม่ และยังไม่มี active เลย ให้เป็น active ไปก่อน
            if ($this->leaveYearModel->where('ly_status', 'active')->countAllResults() == 0) {
                $data['ly_status'] = 'active';
            }
            $this->leaveYearModel->insert($data);
            $msg = "เพิ่มปีการศึกษาสำเร็จ";
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * เปลี่ยนปีการศึกษาที่ใช้งาน (AJAX)
     */
    public function SetActiveLeaveYear($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        // ปิดทั้งหมดก่อน
        $db->table('tb_leave_years')->update(['ly_status' => 'inactive']);
        // เปิดตัวที่เลือก
        $db->table('tb_leave_years')->where('ly_id', $id)->update(['ly_status' => 'active']);
        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถเปลี่ยนปีการศึกษาได้']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'เปลี่ยนปีการศึกษาที่ใช้งานเรียบร้อยแล้ว']);
    }

    /**
     * ลบปีการศึกษา (AJAX)
     */
    public function DeleteLeaveYear($id)
    {
        $year = $this->leaveYearModel->find($id);
        if ($year && $year['ly_status'] == 'active') {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบปีการศึกษาที่กำลังใช้งานอยู่ได้']);
        }

        if ($this->leaveYearModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบปีการศึกษาสำเร็จ']);
        }
        return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    /**
     * อัปเดตสถานะการลา (อนุมัติ/ไม่อนุมัติ) (AJAX)
     */
    public function UpdateStatus()
    {
        $id = $this->request->getPost('leave_id');
        $status = $this->request->getPost('leave_status');
        $comment = $this->request->getPost('leave_comment');

        $leave = $this->leaveRequestModel->find($id);
        if (!$leave) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }

        $updateData = [
            'leave_status'  => $status,
            'leave_comment' => $comment,
            'approved_by'   => session()->get('id'),
            'approved_at'   => date('Y-m-d H:i:s')
        ];

        if ($this->leaveRequestModel->update($id, $updateData)) {
            // หากอนุมัติ ให้ลงเวลาใน tb_personnel_attendance อัตโนมัติ
            if ($status === 'approved') {
                $this->syncToAttendance($leave);
            }

            // ส่งอีเมลแจ้งเตือน
            $this->sendNotificationEmail($leave, $status, $comment);

            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกผลการพิจารณาเรียบร้อยแล้ว']);
        }

        return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

    /**
     * ลงเวลาใน tb_personnel_attendance อัตโนมัติ (ข้ามวันหยุด)
     */
    private function syncToAttendance($leave)
    {
        $db = \Config\Database::connect();
        $startDate = new \DateTime($leave['leave_start_date']);
        $endDate = new \DateTime($leave['leave_end_date']);
        $endDate->modify('+1 day'); // เพื่อให้ครอบคลุมวันสุดท้าย

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($startDate, $interval, $endDate);

        $leaveType = $this->leaveTypeModel->find($leave['leave_type_id']);
        $statusName = $leaveType['leave_type_name'] ?? 'ลา';

        foreach ($daterange as $date) {
            $currentDate = $date->format('Y-m-d');
            
            // ตรวจสอบว่าเป็นวันทำงานหรือไม่ (ไม่เป็น ส.-อา. และไม่เป็นวันหยุดราชการ)
            if ($this->isWorkingDay($currentDate)) {
                $data = [
                    'att_person_id' => $leave['pers_id'],
                    'att_date'      => $currentDate,
                    'att_status'    => $statusName,
                    'att_reason'    => $leave['leave_topic'],
                    'att_adminid'   => session()->get('id')
                ];
                $db->table('tb_personnel_attendance')->replace($data);
            }
        }
    }

    /**
     * ตรวจสอบว่าวันที่ระบุเป็นวันทำงานหรือไม่
     */
    private function isWorkingDay($dateStr)
    {
        $timestamp = strtotime($dateStr);
        $dayOfWeek = date('N', $timestamp);
        
        // 1. ถ้าเป็น เสาร์ (6) หรือ อาทิตย์ (7) ไม่ใช่ราชการ (สำหรับโรงเรียนส่วนใหญ่)
        if ($dayOfWeek >= 6) {
            return false;
        }

        // 2. ตรวจสอบในตารางวันหยุด
        if ($this->holidayModel->isHoliday($dateStr)) {
            return false;
        }

        return true;
    }

    /**
     * ส่งอีเมลแจ้งเตือนผู้ลา
     */
    private function sendNotificationEmail($leave, $status, $comment)
    {
        // 1. ดึงข้อมูลผู้ลาเพื่อเอาอีเมล
        $db = \Config\Database::connect();
        $person = $db->table('tb_personnel')->where('pers_id', $leave['pers_id'])->get()->getRowArray();
        if (!$person || empty($person['pers_username'])) return;

        $email = \Config\Services::email();
        $to = $person['pers_username']; // ในระบบนี้ใช้ username เป็นอีเมล
        $subject = "แจ้งผลพิจารณาการลา: " . $leave['leave_topic'];
        
        $statusText = ($status === 'approved') ? "✅ อนุมัติ" : "❌ ไม่อนุมัติ";
        $type = $this->leaveTypeModel->find($leave['leave_type_id']);
        $typeName = $type['leave_type_name'] ?? 'การลา';

        $message = "
            <h3>แจ้งผลการพิจารณาการลาออนไลน์</h3>
            <p>เรียนคุณ {$person['pers_prefix']}{$person['pers_firstname']} {$person['pers_lastname']}</p>
            <p>ตามที่ท่านได้ส่งคำขอลา <b>{$typeName}</b> เรื่อง <b>{$leave['leave_topic']}</b></p>
            <p>ช่วงวันที่: <b>{$this->DateThai($leave['leave_start_date'])} ถึง {$this->DateThai($leave['leave_end_date'])}</b></p>
            <p>รวมจำนวน: <b>" . floatval($leave['leave_total_days']) . " วัน</b></p>
            <hr>
            <p><b>ผลการพิจารณา: <span style='font-size: 1.2rem; color: " . ($status === 'approved' ? 'green' : 'red') . "'>{$statusText}</span></b></p>
            <p><b>ความเห็นจากผู้อนุมัติ:</b> " . ($comment ?: '-') . "</p>
            <hr>
            <p><small>ระบบบริหารจัดการบุคลากร SKJ</small></p>
        ";

        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        
        // พยายามส่ง (ถ้าหน้างานคอนฟิกเมลไว้แล้วจะส่งออกจริง)
        try {
            @$email->send();
        } catch (\Exception $e) {
            // ล็อกข้อผิดพลาดแต่ไม่ต้องหยุดการทำงาน
            log_message('error', 'Email failed: ' . $e->getMessage());
        }
    }

    /**
     * รายละเอียดการลา (AJAX สำหรับ Modal)
     */
    public function GetLeaveRequest($id)
    {
        $db = \Config\Database::connect();
        $leave = $db->table('tb_leave_requests')
            ->select('tb_leave_requests.*, tb_leave_types.leave_type_name, tb_leave_types.leave_type_quota, tb_personnel.pers_prefix, tb_personnel.pers_firstname, tb_personnel.pers_lastname')
            ->join('tb_leave_types', 'tb_leave_types.leave_type_id = tb_leave_requests.leave_type_id')
            ->join('tb_personnel', 'tb_personnel.pers_id = tb_leave_requests.pers_id')
            ->where('leave_id', $id)
            ->get()->getRowArray();

        if (!$leave) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'ไม่พบข้อมูล']);
        }

        // ดึงปีการศึกษาที่ active
        $activeYear = $this->leaveYearModel->getActiveYear();
        $startDate = $activeYear['ly_start_date'] ?? null;
        $endDate = $activeYear['ly_end_date'] ?? null;

        // ดึงสถิติโควตา โดยใช้ช่วงวันที่ของปีการศึกษา
        $usedDays = $this->leaveRequestModel->getLeaveStats($leave['pers_id'], $leave['leave_type_id'], $startDate, $endDate);
        $leave['used_days'] = floatval($usedDays);
        $leave['quota_total'] = intval($leave['leave_type_quota']);
        $leave['quota_remaining'] = $leave['quota_total'] - $leave['used_days'];
        $leave['active_year_name'] = $activeYear['ly_name'] ?? 'ไม่ได้กำหนดปีการศึกษา';
        
        $leave['start_date_th'] = $this->DateThai($leave['leave_start_date']);
        $leave['end_date_th'] = $this->DateThai($leave['leave_end_date']);

        return $this->response->setJSON($leave);
    }

    private function DateThai($strDate)
    {
        if (!$strDate) return "";
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
}

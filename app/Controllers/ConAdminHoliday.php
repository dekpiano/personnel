<?php

namespace App\Controllers;

use App\Models\HolidayModel;

class ConAdminHoliday extends BaseController
{
    protected $holidayModel;

    public function __construct()
    {
        $session = session();
        if (!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "manager"])) {
            header("Location:" . base_url()); exit();
        }
        $this->holidayModel = new HolidayModel();
    }

    public function index()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri');
        $data['title'] = "จัดการวันหยุดราชการ";

        // Query distinct available years from holiday_date
        $yearsQuery = $this->holidayModel->select("DISTINCT(YEAR(holiday_date)) as h_year", false)->orderBy('h_year', 'DESC')->findAll();
        $available_years = array_filter(array_column($yearsQuery, 'h_year'));
        
        $currentYear = date('Y');
        if (!in_array($currentYear, $available_years)) {
            array_unshift($available_years, (int)$currentYear);
        }
        rsort($available_years);
        $data['available_years'] = $available_years;

        // Selected year filter (default: current year, or 'all')
        $selected_year = $this->request->getGet('year');
        if ($selected_year === null) {
            $selected_year = (string)$currentYear;
        }
        $data['selected_year'] = $selected_year;

        $builder = $this->holidayModel->orderBy('holiday_date', 'ASC');
        if ($selected_year !== 'all' && !empty($selected_year)) {
            $builder->where("YEAR(holiday_date)", $selected_year);
        }
        $data['holidays'] = $builder->findAll();

        return view('Admin/PageAdminHoliday/AdminHolidayMain', $data);
    }

    public function Save()
    {
        $id = $this->request->getPost('holiday_id');
        $data = [
            'holiday_date' => $this->request->getPost('holiday_date'),
            'holiday_name' => $this->request->getPost('holiday_name'),
        ];

        if ($id) {
            $this->holidayModel->update($id, $data);
            $msg = "แก้ไขวันหยุดสำเร็จ";
        } else {
            $this->holidayModel->insert($data);
            $msg = "เพิ่มวันหยุดสำเร็จ";
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    public function Delete($id)
    {
        if ($this->holidayModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบวันหยุดสำเร็จ']);
        }
        return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }
}

<?php

namespace App\Controllers;

use App\Models\BoardModel;
use App\Models\BoardRowModel;
use CodeIgniter\Controller;

class ConAdminBoard extends BaseController
{
    public function __construct()
    {
        $session = session();
        if (!$session->get('username') || !in_array($session->get('status'), ["superadmin", "admin", "manager"])) {
            header("Location:" . base_url());
            exit();
        }
    }

    public function DataMain()
    {
        $data['full_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['uri'] = service('uri');
        return $data;
    }

    public function index()
    {
        $boardModel = new BoardModel();
        $rowModel = new BoardRowModel();
        
        $data = $this->DataMain();
        $data['title'] = "จัดการแถวและคณะกรรมการ";
        
        // Fetch rows ordered by sort
        $rows = $rowModel->orderBy('row_sort', 'ASC')->findAll();
        
        // Fetch members for each row
        foreach ($rows as &$row) {
            $row['members'] = $boardModel->where('row_id', $row['row_id'])
                                         ->orderBy('board_sort', 'ASC')
                                         ->findAll();
        }
        
        // Unassigned members
        $data['unassigned'] = $boardModel->where('row_id', NULL)
                                         ->orWhere('row_id', 0)
                                         ->orderBy('board_sort', 'ASC')
                                         ->findAll();
        
        $data['rows'] = $rows;

        return view('Admin/AdminBoard/BoardMain', $data);
    }

    public function save()
    {
        try {
            $model = new BoardModel();
            $id = $this->request->getPost('board_id');
            
            $data = [
                'row_id'          => $this->request->getPost('row_id') ?: NULL,
                'board_prefix'    => $this->request->getPost('board_prefix'),
                'board_firstname' => $this->request->getPost('board_firstname'),
                'board_lastname'  => $this->request->getPost('board_lastname'),
                'board_position'  => $this->request->getPost('board_position'),
                'board_sort'      => $this->request->getPost('board_sort') ?: 0,
            ];

            $image = $this->request->getFile('board_img');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'uploads/admin/Board/', $newName);
                $data['board_img'] = $newName;
            }

            if ($id) {
                if ($model->update($id, $data)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตข้อมูลสำเร็จ']);
                }
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตข้อมูลได้']);
            } else {
                if ($model->insert($data)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มข้อมูลสำเร็จ']);
                }
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถเพิ่มข้อมูลได้']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดความผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function get($id)
    {
        $model = new BoardModel();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    public function delete()
    {
        $id = $this->request->getPost('board_id');
        $model = new BoardModel();
        $board = $model->find($id);
        
        if ($board && !empty($board['board_img'])) {
            $path = ROOTPATH . 'uploads/admin/Board/' . $board['board_img'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลสำเร็จ']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }

    public function updateOrder()
    {
        $model = new BoardModel();
        $orders = $this->request->getPost('order');
        $rowId = $this->request->getPost('row_id');
        
        foreach ($orders as $index => $id) {
            $model->update($id, [
                'board_sort' => $index,
                'row_id' => $rowId ?: NULL
            ]);
        }
        return $this->response->setJSON(['status' => 'success']);
    }

    // --- Row Management ---

    public function saveRow()
    {
        try {
            $model = new BoardRowModel();
            $id = $this->request->getPost('row_id');
            $data = [
                'row_title' => $this->request->getPost('row_title') ?: 'แถวใหม่',
                'row_cols'  => $this->request->getPost('row_cols') ?: 1,
                'row_sort'  => $this->request->getPost('row_sort') ?: 0,
            ];

            if ($id && $id !== "") {
                if ($model->update($id, $data)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตแถวสำเร็จ']);
                }
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตแถวได้']);
            } else {
                if ($model->insert($data)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'เพิ่มแถวใหม่สำเร็จ']);
                }
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถเพิ่มแถวลงในฐานข้อมูลได้']);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'เกิดความผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function getRow($id)
    {
        $model = new BoardRowModel();
        return $this->response->setJSON($model->find($id));
    }

    public function deleteRow()
    {
        $id = $this->request->getPost('row_id');
        $rowModel = new BoardRowModel();
        $boardModel = new BoardModel();

        // Optional: Move members back to unassigned instead of deleting them
        $boardModel->where('row_id', $id)->set(['row_id' => NULL])->update();

        if ($rowModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบแถวสำเร็จ']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบแถวได้']);
    }

    public function updateRowOrder()
    {
        $model = new BoardRowModel();
        $orders = $this->request->getPost('order');
        foreach ($orders as $index => $id) {
            $model->update($id, ['row_sort' => $index]);
        }
        return $this->response->setJSON(['status' => 'success']);
    }
}

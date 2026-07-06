<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;

class OwnerQAAdminController extends AdminBaseController
{
    public function index()
    {
        $tableExists = $this->db->tableExists('owner_questions');
        $questions   = [];
        if ($tableExists) {
            $questions = $this->db->table('owner_questions oq')
                ->select('oq.*, v.name AS vehicle_name, v.slug AS vehicle_slug')
                ->join('vehicles v', 'v.id = oq.vehicle_id', 'left')
                ->orderBy('oq.created_at', 'DESC')
                ->get()->getResultArray();
        }
        return view('admin/qa/index', [
            'questions'  => $questions,
            'title'      => 'Owner Q&A',
            'page_title' => 'Owner Q&A',
        ]);
    }

    public function approve(int $id)
    {
        if ($this->db->tableExists('owner_questions')) {
            $this->db->table('owner_questions')->where('id', $id)->update(['is_approved' => 1]);
        }
        return redirect()->to('/admin/qa')->with('success', 'Question approved.');
    }

    public function answer(int $id)
    {
        $answer = $this->request->getPost('answer');
        if ($answer && $this->db->tableExists('owner_questions')) {
            $this->db->table('owner_questions')->where('id', $id)->update([
                'answer'      => htmlspecialchars($answer),
                'is_approved' => 1,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->to('/admin/qa')->with('success', 'Answer saved.');
    }

    public function delete(int $id)
    {
        if ($this->db->tableExists('owner_questions')) {
            $this->db->table('owner_questions')->where('id', $id)->delete();
        }
        return redirect()->to('/admin/qa')->with('success', 'Question deleted.');
    }
}

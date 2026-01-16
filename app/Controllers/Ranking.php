<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Services\SelectionEngine;

class Ranking extends BaseController
{
    protected $studentModel;
    protected $selectionEngine;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->selectionEngine = new SelectionEngine();
    }

    public function index($major = null)
    {
        $majors = ['IPA', 'IPS', 'Bahasa'];
        $data = ['title' => 'Ranking Siswa'];
        
        if ($major && in_array($major, $majors)) {
            $data['selected_major'] = $major;
            $data['rankings'] = $this->studentModel
                ->where('accepted_major', $major)
                ->orWhere('pilihan_1', $major)
                ->orderBy('final_score', 'DESC')
                ->findAll();
        } else {
            $data['selected_major'] = 'all';
            foreach ($majors as $m) {
                $data['rankings'][$m] = $this->studentModel
                    ->where('accepted_major', $m)
                    ->orderBy('final_score', 'DESC')
                    ->findAll(20);
            }
        }
        
        $data['quota'] = [
            'IPA' => 20,
            'IPS' => 20,
            'Bahasa' => 20
        ];
        
        return view('ranking', $data);
    }
}
<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Services\SelectionEngine;
use Config\PPDB;

class Home extends BaseController
{
    protected StudentModel $studentModel;
    protected SelectionEngine $selectionEngine;
    protected PPDB $ppdbConfig;

    public function __construct()
    {
        $this->studentModel     = new StudentModel();
        $this->selectionEngine  = new SelectionEngine();
        $this->ppdbConfig       = new PPDB();
    }

    public function index()
    {
        $nisn = session()->get('nisn');
        $student = $nisn ? $this->studentModel->where('nisn', $nisn)->first() : null;

        $cooldownUntil = null;
        $cooldownRemaining = null;

        if ($student && $student->last_register_at) {
            $lastRegister = strtotime($student->last_register_at);
            $cooldownEnd = $lastRegister + (48 * 3600);

            if (time() < $cooldownEnd) {
                $cooldownUntil = $cooldownEnd;
                $cooldownRemaining = (int) ceil(($cooldownEnd - time()) / 3600);
            }
        }

        $data = [
            'title'              => 'PPDB Online - Beranda',
            'isPpdbActive'       => $this->ppdbConfig->isActive,

            // cooldown
            'cooldownRemaining'  => $cooldownRemaining, // jam (fallback / teks)
            'cooldownUntil'      => $cooldownUntil,     // timestamp (countdown)

            // insight
            'thresholds'         => $this->getThresholdValues(),
            'total_registered'   => $this->studentModel->countAll()
        ];

        return view('home', $data);
    }

    /**
     * Ambil threshold aman per jurusan (peringkat ke-10)
     */
    private function getThresholdValues(): array
    {
        $thresholds = [];
        $majors = ['IPA', 'IPS', 'Bahasa'];

        foreach ($majors as $major) {
            $students = $this->studentModel
                ->where('accepted_major', $major)
                ->orderBy('final_score', 'DESC')
                ->findAll(10);

            $thresholds[$major] = (count($students) >= 10)
                ? (float) ($students[9]->final_score ?? 0)
                : 0;
        }

        return $thresholds;
    }
}

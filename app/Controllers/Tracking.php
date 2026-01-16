<?php

namespace App\Controllers;

use App\Models\StudentModel;

class Tracking extends BaseController
{
    protected $studentModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        return view('tracking', [
            'title' => 'Cek Status Pendaftaran'
        ]);
    }

    public function check()
    {
        $nisn = trim((string) $this->request->getPost('nisn'));

        // 1. Validasi input WAJIB dulu
        if ($nisn === '') {
            return redirect()->to('/tracking')
                ->with('error', 'NISN wajib diisi');
        }

        // 2. Cari siswa
        $student = $this->studentModel
            ->where('nisn', $nisn)
            ->first();

        if (!$student) {
            return redirect()->to('/tracking')
                ->with('error', 'NISN tidak ditemukan');
        }

        // 3. Hitung posisi (sekali saja)
        $position = $this->getPositionInMajor($student);

        // 4. Tentukan status
        $status = $this->determineStatus($student, $position);

        // 5. Tampilkan hasil
        return view('/tracking_result', [
            'title'    => 'Status Pendaftaran',
            'student'  => $student,
            'status'   => $status,
            'position' => $position
        ]);
    }

    private function determineStatus($student, $position)
    {
        if (empty($student->accepted_major)) {
            return [
                'label'   => 'MENUNGGU',
                'color'   => 'secondary',
                'message' => 'Seleksi belum ditentukan'
            ];
        }

        if ($position === null) {
            return [
                'label'   => 'MENUNGGU',
                'color'   => 'secondary',
                'message' => 'Peringkat belum tersedia'
            ];
        }

        if ($position <= 15) {
            return [
                'label'   => 'AMAN',
                'color'   => 'success',
                'message' => 'Posisi Anda aman di kuota 15 besar'
            ];
        }

        if ($position <= 20) {
            return [
                'label'   => 'RAWAN',
                'color'   => 'warning',
                'message' => 'Posisi Anda di batas kuota, waspada pergeseran'
            ];
        }

        return [
            'label'   => 'TERGESER',
            'color'   => 'danger',
            'message' => 'Anda telah tergeser dari kuota utama'
        ];
    }

    private function getPositionInMajor($student)
    {
        if (empty($student->accepted_major)) {
            return null;
        }

        $students = $this->studentModel
            ->where('accepted_major', $student->accepted_major)
            ->orderBy('final_score', 'DESC')
            ->findAll();

        foreach ($students as $index => $s) {
            if ((int) $s->id === (int) $student->id) {
                return $index + 1;
            }
        }

        return null;
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\StudentModel;
use App\Services\SelectionEngine;

class DummySeeder extends Seeder
{
    public function run()
    {
        $studentModel = new StudentModel();
        $selectionEngine = new SelectionEngine();
        
        // Data dummy 60 siswa
        $dummyData = $this->generateDummyData(60);
        
        foreach ($dummyData as $data) {
            $studentModel->insert($data);
        }
        
        // Jalankan engine seleksi untuk data dummy
        $selectionEngine->process();
        
        echo "✅ 60 data dummy berhasil ditambahkan dan diproses\n";
    }
    
    private function generateDummyData($count)
    {
        $data = [];
        $names = $this->getDummyNames();
        $schools = $this->getDummySchools();
        
        $majors = ['IPA', 'IPS', 'Bahasa'];
        
        for ($i = 1; $i <= $count; $i++) {
            $name = $names[array_rand($names)] . ' ' . $names[array_rand($names)];
            $nisn = '1000' . str_pad($i, 6, '0', STR_PAD_LEFT);
            
            // Generate nilai random yang realistis
            $scores = [
                'matematika' => rand(70, 98),
                'bahasa_indonesia' => rand(75, 95),
                'bahasa_inggris' => rand(70, 96),
                'ipa' => rand(72, 97),
                'ips' => rand(75, 95)
            ];
            
            // Hitung nilai akhir dengan bobot
            $finalScore = round(
                ($scores['matematika'] * 0.3) +
                ($scores['bahasa_indonesia'] * 0.25) +
                ($scores['bahasa_inggris'] * 0.2) +
                ($scores['ipa'] * 0.15) +
                ($scores['ips'] * 0.1), 2
            );
            
            // Tentukan pilihan jurusan
            $majorChoice = $majors[array_rand($majors)];
            $pilihan = $this->generateMajorChoices($majorChoice);
            
            $data[] = [
                'nisn' => $nisn,
                'name' => $name,
                'birth_date' => date('Y-m-d', strtotime('-' . rand(15, 18) . ' years')),
                'school_origin' => $schools[array_rand($schools)],
                'phone' => '08' . rand(100000000, 999999999),
                'matematika' => $scores['matematika'],
                'bahasa_indonesia' => $scores['bahasa_indonesia'],
                'bahasa_inggris' => $scores['bahasa_inggris'],
                'ipa' => $scores['ipa'],
                'ips' => $scores['ips'],
                'final_score' => $finalScore,
                'pilihan_1' => $pilihan['pilihan_1'],
                'pilihan_2' => $pilihan['pilihan_2'],
                'pilihan_3' => $pilihan['pilihan_3'],
                'status' => 'verified',
                'created_at' => date('Y-m-d H:i:s', strtotime("-$i hours"))
            ];
        }
        
        return $data;
    }
    
    private function generateMajorChoices($primaryChoice)
    {
        $allMajors = ['IPA', 'IPS', 'Bahasa'];
        
        if ($primaryChoice === 'IPA') {
            return [
                'pilihan_1' => 'IPA',
                'pilihan_2' => 'IPS',
                'pilihan_3' => 'Bahasa'
            ];
        } elseif ($primaryChoice === 'IPS') {
            return [
                'pilihan_1' => 'IPS',
                'pilihan_2' => 'Bahasa',
                'pilihan_3' => null
            ];
        } else {
            return [
                'pilihan_1' => 'Bahasa',
                'pilihan_2' => null,
                'pilihan_3' => null
            ];
        }
    }
    
    private function getDummyNames()
    {
        return [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko',
            'Kartika', 'Lina', 'Mega', 'Nina', 'Oki', 'Putra', 'Rani', 'Sari', 'Tono', 'Wati',
            'Yudi', 'Zahra', 'Agus', 'Bambang', 'Cindy', 'Dodi', 'Elsa', 'Fandi', 'Gunawan', 'Hana'
        ];
    }
    
    private function getDummySchools()
    {
        return [
            'SMP Negeri 1 Kota',
            'SMP Negeri 2 Kota',
            'SMP Negeri 3 Kota',
            'SMP Al-Azhar',
            'SMP Muhammadiyah',
            'SMP Katolik',
            'SMP Kristen',
            'SMP Plus',
            'SMP Unggulan',
            'SMP Terpadu'
        ];
    }
}
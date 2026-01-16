<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nisn', 'name', 'birth_date', 'school_origin', 'phone',
        'matematika', 'bahasa_indonesia', 'bahasa_inggris', 'ipa', 'ips',
        'final_score', 'pilihan_1', 'pilihan_2', 'pilihan_3',
        'accepted_major', 'status', 'verification_log', 'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'nisn' => 'required',
        'name' => 'required',
        'final_score' => 'required|numeric'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    public function getRanking($major = null)
    {
        $builder = $this->orderBy('final_score', 'DESC');
        
        if ($major) {
            $builder->where('accepted_major', $major);
        }
        
        return $builder->findAll();
    }
    
    public function getByMajor($major)
    {
        return $this->where('accepted_major', $major)
                    ->orWhere('pilihan_1', $major)
                    ->orderBy('final_score', 'DESC')
                    ->findAll();
    }
    
    public function getTopThreshold($major, $position = 10)
    {
        return $this->where('accepted_major', $major)
                    ->orderBy('final_score', 'DESC')
                    ->limit($position)
                    ->findAll();
    }
}
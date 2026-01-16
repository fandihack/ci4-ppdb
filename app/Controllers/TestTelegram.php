<?php

namespace App\Controllers;

use App\Services\TelegramNotifier;

class TestTelegram extends BaseController
{
    public function index()
    {
        $notifier = new TelegramNotifier();

        // 1. Simulasi Data Siswa
        $student = (object) [
            'name'        => 'Budi Test Pelajar',
            'nisn'        => '1234567890',
            'final_score' => '78.50'
        ];

        // 2. Panggil fungsi reject
        $response = $notifier->sendRejectedNotification($student);

        if ($response && $response['ok']) {
            return "Berhasil! Cek Channel Telegram kamu.";
        } else {
            return "Gagal mengirim. Cek log di writable/logs/";
        }
    }
}
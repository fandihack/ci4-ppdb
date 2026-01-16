<?php

namespace App\Services;

use Config\PPDB;

class TelegramNotifier
{
    protected $ppdbConfig;

    public function __construct()
    {
        $this->ppdbConfig = new PPDB();
    }

    /**
     * Kirim notifikasi gabungan dengan urutan:
     * Siswa Baru -> Siswa Tergeser -> Siswa Keluar
     * Data setiap siswa tampil lengkap: Nama, NISN, Nilai, Pilihan, Status
     */
    public function sendCombinedNotification($newStudents = [], $shiftedStudents = [], $rejectedStudents = [])
    {
        if (!$this->ppdbConfig->telegramConfig['enabled']) {
            return false;
        }

        $message = "📢 <b>UPDATE PPDB TERBARU</b>\n\n";

        // =========================
        // 1️⃣ Siswa Baru
        // =========================
        if (!empty($newStudents)) {
            $message .= "📝 <b>SISWA BARU</b>\n";
            foreach ($newStudents as $s) {
                $s = (object) $s;
                $pilihan = $s->pilihan_1 ?? '-';
                if (!empty($s->pilihan_2)) $pilihan .= " → " . $s->pilihan_2;
                if (!empty($s->pilihan_3)) $pilihan .= " → " . $s->pilihan_3;

                $message .= "─────────────────────\n";
                $message .= "Nama: <b>" . htmlspecialchars($s->name ?? '-') . "</b>\n";
                $message .= "NISN: " . ($s->nisn ?? '-') . "\n";
                $message .= "Nilai Akhir: <b>" . ($s->final_score ?? 0) . "</b>\n";
                $message .= "Pilihan: " . $pilihan . "\n";
                $message .= "Status: <b>" . strtoupper($s->status ?? 'Pending') . "</b>\n\n";
            }
        }

        // =========================
        // 2️⃣ Siswa Tergeser
        // =========================
        if (!empty($shiftedStudents)) {
            $message .= "🔄 <b>SISWA TERGESER</b>\n";
            foreach ($shiftedStudents as $s) {
                $s = (object) $s;
                $statusBaru = ($s->status === 'waitlisted') ? "Waiting List" : ($s->accepted_major ?? 'Waiting List');

                $pilihan = $s->pilihan_1 ?? '-';
                if (!empty($s->pilihan_2)) $pilihan .= " → " . $s->pilihan_2;
                if (!empty($s->pilihan_3)) $pilihan .= " → " . $s->pilihan_3;

                $message .= "─────────────────────\n";
                $message .= "Nama: <b>" . htmlspecialchars($s->name ?? '-') . "</b>\n";
                $message .= "NISN: " . ($s->nisn ?? '-') . "\n";
                $message .= "Nilai: " . ($s->final_score ?? 0) . "\n";
                $message .= "Pilihan Lama: " . $pilihan . "\n";
                $message .= "Status Sekarang: <b>" . $statusBaru . "</b>\n\n";
            }
        }

        // =========================
        // 3️⃣ Siswa Keluar Kuota
        // =========================
        if (!empty($rejectedStudents)) {
            $message .= "❌ <b>SISWA KELUAR KUOTA</b>\n";
            foreach ($rejectedStudents as $s) {
                $s = (object) $s;

                $pilihan = $s->pilihan_1 ?? '-';
                if (!empty($s->pilihan_2)) $pilihan .= " → " . $s->pilihan_2;
                if (!empty($s->pilihan_3)) $pilihan .= " → " . $s->pilihan_3;

                $message .= "─────────────────────\n";
                $message .= "Nama: <b>" . htmlspecialchars($s->name ?? '-') . "</b>\n";
                $message .= "NISN: " . ($s->nisn ?? '-') . "\n";
                $message .= "Nilai: " . ($s->final_score ?? 0) . "\n";
                $message .= "Pilihan: " . $pilihan . "\n";
                $message .= "📍 Status: <b>WAITING LIST</b>\n\n";
            }
        }

        $message .= "⏰ " . date('d/m/Y H:i:s');

        return $this->sendMessage($message);
    }

    private function sendMessage($text)
    {
        $config = $this->ppdbConfig->telegramConfig;

        if (empty($config['bot_token']) || empty($config['channel_id'])) {
            log_message('error', 'Telegram Config is missing token or channel_id');
            return false;
        }

        $url = "https://api.telegram.org/bot{$config['bot_token']}/sendMessage";

        $data = [
            'chat_id'    => $config['channel_id'],
            'text'       => $text,
            'parse_mode' => 'HTML'
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
                'timeout' => 10
            ]
        ];

        $context = stream_context_create($options);

        try {
            $result = @file_get_contents($url, false, $context);
            if ($result === false) {
                $error = error_get_last();
                throw new \Exception($error['message'] ?? 'Connection error');
            }
            return json_decode($result, true);
        } catch (\Exception $e) {
            log_message('error', 'Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }
}

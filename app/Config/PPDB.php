<?php

namespace Config;

class PPDB
{
    /**
     * Status global PPDB
     */
    public bool $isActive = true;

    /**
     * Kuota per jurusan
     * Tips: Set ke angka kecil (misal: 2) untuk simulasi Domino Effect
     */
    public array $quota = [
        'IPA'    => 20,
        'IPS'    => 20,
        'Bahasa' => 20
    ];

    /**
     * Bobot nilai
     */
    public array $weights = [
        'matematika'       => 0.3,
        'bahasa_indonesia' => 0.25,
        'bahasa_inggris'   => 0.2,
        'ipa'              => 0.15,
        'ips'              => 0.1
    ];

    /**
     * Prioritas jurusan
     */
    public array $majorPriority = ['IPA', 'IPS', 'Bahasa'];

    /**
     * NISN bypass (testing / admin)
     */
    public string $thresholdNISN = '9999999999';

    /**
     * Telegram notifier
     * Mengambil data dari .env agar lebih aman
     */
    public array $telegramConfig = [
        'enabled'    => true,
        'bot_token'  => '', // Akan diisi via constructor
        'channel_id' => '', // Akan diisi via constructor
    ];

    /**
     * Aturan seleksi
     */
    public array $selectionRules = [
        'tie_breaker'      => 'created_at',
        'auto_recalculate' => true,
        'domino_effect'    => true
    ];

    public function __construct()
    {
        // Membaca token dan ID dari file .env yang kamu edit tadi
        $this->telegramConfig['bot_token']  = env('TELEGRAM_BOT_TOKEN');
        $this->telegramConfig['channel_id'] = env('TELEGRAM_CHANNEL_ID');
    }
}
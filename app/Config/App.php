<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * REVISI TERPENTING:
     * Langsung masukkan URL Railway kamu di sini. 
     * Jangan biarkan defaultnya 'http://localhost:8080'.
     */
    public string $baseURL = 'https://ci4-ppdb-production.up.railway.app'; 

    public array $allowedHostnames = [];
    public string $indexPage = '';
    public string $uriProtocol = 'REQUEST_URI';
    public string $permittedURIChars = 'a-z 0-9~%.:_\-';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'Asia/Jakarta';
    public string $charset = 'UTF-8';

    /**
     * Harus TRUE untuk Railway agar tidak ada konflik antara HTTP dan HTTPS
     */
    public bool $forceGlobalSecureRequests = true;

    /**
     * Konfigurasi Reverse Proxy Railway
     */
    public array $proxyIPs = [
        '0.0.0.0/0' => 'X-Forwarded-For',
    ];

    public bool $CSPEnabled = false;

    public function __construct()
    {
        parent::__construct();

        /**
         * Logika ini tetap dipertahankan sebagai cadangan,
         * tapi sekarang baseURL defaultnya sudah bukan localhost.
         */
        $siteURL = getenv('app.baseURL') ?: getenv('APP_BASEURL') ?: env('app.baseURL');

        if ($siteURL) {
            $this->baseURL = rtrim($siteURL, '/');
        }
    }
}
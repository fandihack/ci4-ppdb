<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     */
    // Default tetap localhost untuk development lokal
    public string $baseURL = 'http://localhost:8080'; 

    /**
     * Allowed Hostnames
     */
    public array $allowedHostnames = [];

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     */
    public string $indexPage = '';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     */
    public string $uriProtocol = 'REQUEST_URI';

    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    public string $defaultLocale = 'en';

    public bool $negotiateLocale = false;

    public array $supportedLocales = ['en'];

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     */
    public string $appTimezone = 'Asia/Jakarta';

    public string $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * Force Global Secure Requests
     * --------------------------------------------------------------------------
     */
    // REVISI: Set true agar semua link otomatis menggunakan HTTPS
    public bool $forceGlobalSecureRequests = true;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     */
    public array $proxyIPs = [
        '0.0.0.0/0' => 'X-Forwarded-For',
    ];

    public bool $CSPEnabled = false;

    public function __construct()
    {
        parent::__construct();

        /**
         * REVISI KRUSIAL:
         * Railway menyuntikkan variabel environment. Kita coba ambil beberapa variasi
         * untuk memastikan baseURL tidak jatuh ke default 'localhost'.
         */
        $siteURL = env('app.baseURL') ?? env('APP_BASEURL') ?? getenv('app.baseURL') ?? getenv('APP_BASEURL');

        if ($siteURL) {
            // Hilangkan trailing slash jika ada agar tidak double slash di URL
            $this->baseURL = rtrim($siteURL, '/');
        }
    }
}
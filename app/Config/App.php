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
    // REVISI: Menggunakan env() agar dinamis. 
    // Di lokal akan pakai localhost, di Railway akan ambil dari variable app.baseURL
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Allowed Hostnames in the Site URL other than the hostname in the baseURL.
     */
    public array $allowedHostnames = [];

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     */
    // REVISI: Dikosongkan agar URL bersih (tanpa index.php/) karena kita pakai Apache Rewrite
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
    public string $appTimezone = 'Asia/Jakarta'; // Sesuaikan ke WIB

    public string $charset = 'UTF-8';

    public bool $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     */
    // REVISI: Sangat penting untuk Railway agar HTTPS dan IP terdeteksi dengan benar
    public array $proxyIPs = [
        '0.0.0.0/0' => 'X-Forwarded-For',
    ];

    public bool $CSPEnabled = false;

    public function __construct()
    {
        parent::__construct();

        // Ambil baseURL dari Environment Variable Railway jika ada
        if ($envBaseURL = env('app.baseURL')) {
            $this->baseURL = $envBaseURL;
        }
    }
}
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
    // REVISI: Mengambil URL dari environment variable atau default ke '/' agar fleksibel
    public string $baseURL = '/'; 

    /**
     * Allowed Hostnames in the Site URL other than the hostname in the baseURL.
     */
    public array $allowedHostnames = [];

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     * REVISI: Kosongkan jika menggunakan Apache Rewrite (mod_rewrite) agar URL bersih 
     * tanpa index.php
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
    public string $appTimezone = 'Asia/Jakarta'; // REVISI: Sesuaikan ke Waktu Indonesia

    public string $charset = 'UTF-8';

    public bool $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     * Railway menggunakan Proxy, jadi kita perlu mempercayai header dari proxy.
     */
    public array $proxyIPs = [
        '0.0.0.0/0' => 'X-Forwarded-For', // Mempercayai header proxy Railway
    ];

    public bool $CSPEnabled = false;
}
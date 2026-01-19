<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\BaseHandler;
use CodeIgniter\Session\Handlers\FileHandler;

class Session extends BaseConfig
{
    public string $driver = FileHandler::class;
    public string $cookieName = 'ci_session';
    public int $expiration = 7200;
    public string $savePath = WRITEPATH . 'session';
    public bool $matchIP = false;
    public int $timeToUpdate = 300;
    public bool $regenerateDestroy = false;
    public ?string $DBGroup = null;
    public int $lockRetryInterval = 100_000;
    public int $lockMaxRetries = 300;

    /**
     * REVISI TAMBAHAN:
     * Paksa session menggunakan secure cookie agar tidak memental di HTTPS Railway.
     */
    public bool $cookieSecure = true; 

    public function __construct()
    {
        parent::__construct();

        // Ambil path dari ENV Railway
        $envPath = env('session.savePath') ?? getenv('session.savePath');

        if ($envPath) {
            if (strpos($envPath, '/') !== 0) {
                $this->savePath = ROOTPATH . $envPath;
            } else {
                $this->savePath = $envPath;
            }
        }

        // Penyesuaian otomatis untuk Secure Cookie (Lokal vs Production)
        if (ENVIRONMENT !== 'production' && ENVIRONMENT !== 'development') {
            $this->cookieSecure = false;
        }

        // Pastikan folder session tersedia
        if ($this->driver === FileHandler::class && !is_dir($this->savePath)) {
            mkdir($this->savePath, 0777, true);
        }
    }
}
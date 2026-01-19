<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cookie extends BaseConfig
{
    public string $prefix = '';
    public $expires = 0;
    public string $path = '/';
    public string $domain = '';

    /**
     * REVISI KRUSIAL:
     * Harus TRUE karena Railway menggunakan HTTPS. 
     * Jika FALSE, browser tidak akan menyimpan cookie login kamu.
     */
    public bool $secure = true; 

    public bool $httponly = true;

    /**
     * REVISI:
     * 'Lax' adalah standar keamanan modern untuk redirect antar domain.
     */
    public string $samesite = 'Lax';

    public bool $raw = false;

    public function __construct()
    {
        parent::__construct();

        /**
         * Otomatis set secure ke FALSE jika di localhost (agar tetap bisa login di laptop),
         * dan TRUE jika di Railway (production/development).
         */
        if (ENVIRONMENT !== 'production' && ENVIRONMENT !== 'development') {
            $this->secure = false;
        }
    }
}
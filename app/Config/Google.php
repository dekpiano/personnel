<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    /**
     * Google Client ID
     */
    public string $clientId = '';

    /**
     * Google Client Secret
     */
    public string $clientSecret = '';

    /**
     * Default Redirect URI for OAuth
     */
    public string $redirectUri = '';

    public function __construct()
    {
        parent::__construct();

        $this->clientId = env('google.clientId', '110650460520-35k7ea69727vjqv11jise3ihm7g3vrah.apps.googleusercontent.com');
        $this->clientSecret = env('google.clientSecret', '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');
        $this->redirectUri = base_url('LoginOfficerPersonnel');
    }
}

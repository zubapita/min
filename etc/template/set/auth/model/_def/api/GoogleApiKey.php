<?php
/**
 * GoogleApiKey API
 */
class GoogleApiKey {

    public static function getParams() {

        $config = [
            "enabled" => true,
            "keys"    => [
                "id" => "PUT_YOUR_APP_ID", 
                "secret" => "PUT_YOUR_SECRET_KEY"
            ],
            "scope" =>    "https://www.googleapis.com/auth/userinfo.profile ". // optional
                        "https://www.googleapis.com/auth/userinfo.email"   , // optional
            "access_type"        => "offline",   // optional
            "approval_prompt"    => "force",     // optional
            "hd"                => "domain.com" // optional
        ];

    
        return $config;
    }
    
}

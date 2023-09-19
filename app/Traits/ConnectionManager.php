<?php
namespace App\Traits;

trait ConnectionManager{

    public function connect(string $username = "", string $password = "", array $headers = [], $payload , string $host) {
        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if($username !== "" && $password !== ""){
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($ch);
        curl_close($ch);

        return $return;
    }
}

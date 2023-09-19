<?php

namespace App\Classes\Registries;

use App\Contracts\TransactionValidator;

class ValidatorRegistry {

    protected $validators = [];

    function register ($paymentGateway, TransactionValidator $instance) {
        $this->validators[$paymentGateway] = $instance;
        return $this;
    }

    function get($paymentGateway) {
        if (array_key_exists($paymentGateway, $this->validators)) {
            return $this->validators[$paymentGateway];
        } else {
            throw new \Exception("Invalid vendor validator.");
        }
    }
}

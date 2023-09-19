<?php

namespace App\Requests\ParameterBuilder\Report;

class FilterBuilder
{
    public $filterFrom;
    public $filterTo;
    public $machineAddressId;
    public $paymentMode;
    public $withPayment;
    public $transactionType;

    function __construct(
        bool $withPayment = false,
        string $filterFrom = null,
        string $filterTo = null,
        array $machineAddressId = [],
        string $paymentMode = null,
        string $transactionType = null
    ) {
        $this->filterFrom = $filterFrom;
        $this->filterTo = $filterTo;
        $this->machineAddressId = $machineAddressId;
        $this->paymentMode = $paymentMode;
        $this->withPayment = $withPayment;
        $this->transactionType = $transactionType;
    }

    function getFilterFrom() {
        return $this->filterFrom;
    }
    function getFilterTo() {
        return $this->filterTo;
    }
    function getMachineAddressId() {
        return $this->machineAddressId;
    }
    function getPaymentMode() {
        return $this->paymentMode;
    }
    function getwithPayment() {
        return $this->withPayment;
    }
    function getTransactionType() {
        return $this->transactionType;
    }

}

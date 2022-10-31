<?php

namespace App\Contracts\PaymentGateway;


use Illuminate\Support\MessageBag;

interface PaymentGateway
{
    public function validateData(): MessageBag;

    public function checkSignature(): bool;

    public function writeToBase();
}

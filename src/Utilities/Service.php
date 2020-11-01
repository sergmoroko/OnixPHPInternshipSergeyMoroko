<?php


namespace App\Utilities;


class Service
{
    const FEE_AMOUNT = 0.05;

    public static function calculateFee($amount){
        return round($amount * self::FEE_AMOUNT, 2);
    }
}

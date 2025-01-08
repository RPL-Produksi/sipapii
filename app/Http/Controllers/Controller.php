<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function calculateDistance($latUser, $longUser, $latInstansi, $longInstansi)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($latInstansi - $latUser);
        $dLong = deg2rad($longInstansi - $longUser);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latUser)) * cos(deg2rad($latInstansi)) *
            sin($dLong / 2) * sin($dLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        $distanceInMeters = $distance * 1000;

        if ($distanceInMeters >= 1000) {
            return round($distance, 1) . ' KM';
        } else {
            return round($distanceInMeters) . ' M';
        }
    }
}

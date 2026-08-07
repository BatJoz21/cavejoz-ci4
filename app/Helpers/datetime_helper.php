<?php

use CodeIgniter\I18n\Time;

if(!function_exists('get_relative_time')) {
    function get_relative_time(?string $timestamp): string
    {
        if(empty($timestamp)) {
            return '';
        }

        try {
            $past = new Time($timestamp);
        } catch(Exception $e) {
            return '';
        }

        $second = Time::now()->getTimestamp() - $past->getTimestamp();

        if($second < 60) {
            return 'just now';
        }

        $units = [
            31536000    => 'year',
            2592000     => 'month',
            604800      => 'week',
            86400       => 'day',
            3600        => 'hour',
            60          => 'minutes',
        ];

        foreach($units as $unitSecond => $label) {
            if($second >= $unitSecond) {
                $count = (int) floor($second / $unitSecond);
                return $count . ' ' . $label . ($count > 1 ? 's' : '') . ' ago';
            }
        }

        return 'just now';
    }
}
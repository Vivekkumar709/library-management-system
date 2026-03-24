<?php

if (!function_exists('generate_random_password')) {
    function generate_random_password(int $length = 12): string
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers   = '0123456789';
        $symbols   = '!@#$%^&*()_-+=<>?';

        // Ensure at least one from each category
        $password  = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Fill the rest with random mix
        $all = $lowercase . $uppercase . $numbers . $symbols;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        // Shuffle to avoid predictable order
        return str_shuffle($password);
    }
}

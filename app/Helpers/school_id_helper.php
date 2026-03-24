<?php 
if (!function_exists('generate_school_id')) {
    /**
     * Generate a 7-digit unique school ID with SCH prefix
     * 
     * @return string Format: SCH1234567
     */
    function generate_school_id(): string
    {
        return 'SCH' . mt_rand(1000000, 9999999);
    }
}
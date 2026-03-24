<?php 

if (!function_exists('format_time_range')) {
    function format_time_range($startTime, $endTime) {
        return date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($endTime));
    }
}
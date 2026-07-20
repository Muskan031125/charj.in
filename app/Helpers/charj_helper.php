<?php

/**
 * Charj.in - India's EV Marketplace
 * Custom Helper Functions
 *
 * Load in controller or view with: helper('charj');
 * Or autoload in app/Config/Autoload.php:
 *   $helpers = ['charj', 'url', 'form'];
 */

if (! defined('BASEPATH')) {
    // CI4 uses ROOTPATH, not BASEPATH - just a safety guard
}

// ============================================================
// format_inr($amount)
// Format a numeric amount as Indian Rupee with ₹ symbol.
// Uses Indian number system: lakhs and crores grouping.
//
// Examples:
//   format_inr(139000)    => "₹1,39,000"
//   format_inr(1449000)   => "₹14,49,000"
//   format_inr(18980000)  => "₹1,89,80,000"
//   format_inr(1234.50)   => "₹1,234.50"  (under 1000 - standard)
// ============================================================
if (! function_exists('format_inr')) {
    function format_inr(float|int|string $amount, int $decimals = 0): string
    {
        $amount = (float) $amount;

        // Format with Indian grouping
        $formatted = india_number_format($amount, $decimals);

        return '₹' . $formatted;
    }
}

// ============================================================
// format_inr_compact($amount)
// Format large Indian currency amounts in compact notation.
//
// Examples:
//   format_inr_compact(142750)    => "₹1.43 L"
//   format_inr_compact(1449000)   => "₹14.49 L"
//   format_inr_compact(10000000)  => "₹1.00 Cr"
//   format_inr_compact(18980000)  => "₹1.90 Cr"
//   format_inr_compact(5000)      => "₹5,000"
// ============================================================
if (! function_exists('format_inr_compact')) {
    function format_inr_compact(float|int|string $amount): string
    {
        $amount = (float) $amount;

        if ($amount >= 10000000) {
            // Crore (1 Cr = 1,00,00,000)
            $value = $amount / 10000000;
            return '₹' . number_format($value, 2) . ' Cr';
        }

        if ($amount >= 100000) {
            // Lakh (1 L = 1,00,000)
            $value = $amount / 100000;
            return '₹' . number_format($value, 2) . ' L';
        }

        // Below 1 lakh - show in standard Indian format
        return '₹' . india_number_format($amount, 0);
    }
}

// ============================================================
// india_number_format($number, $decimals)
// Format a number using the Indian numbering system.
// First group of 3 digits from right, then groups of 2.
//
// Examples:
//   india_number_format(1000)      => "1,000"
//   india_number_format(100000)    => "1,00,000"
//   india_number_format(1234567)   => "12,34,567"
//   india_number_format(10000000)  => "1,00,00,000"
//   india_number_format(1234.56,2) => "1,234.56"
// ============================================================
if (! function_exists('india_number_format')) {
    function india_number_format(float|int|string $number, int $decimals = 0): string
    {
        $number = (float) $number;

        // Handle negative numbers
        $negative = $number < 0;
        $number   = abs($number);

        // Split integer and decimal parts
        if ($decimals > 0) {
            $rounded  = number_format($number, $decimals, '.', '');
            $parts    = explode('.', $rounded);
            $intPart  = $parts[0];
            $decPart  = '.' . $parts[1];
        } else {
            $intPart  = (string) (int) round($number);
            $decPart  = '';
        }

        // Apply Indian grouping: last 3 digits, then groups of 2
        $len = strlen($intPart);

        if ($len <= 3) {
            $formatted = $intPart;
        } else {
            // Last 3 digits
            $last3 = substr($intPart, -3);
            $rest  = substr($intPart, 0, $len - 3);

            // Remaining digits in groups of 2 from right
            $groups  = [];
            while (strlen($rest) > 0) {
                if (strlen($rest) >= 2) {
                    array_unshift($groups, substr($rest, -2));
                    $rest = substr($rest, 0, strlen($rest) - 2);
                } else {
                    array_unshift($groups, $rest);
                    $rest = '';
                }
            }

            $formatted = implode(',', $groups) . ',' . $last3;
        }

        return ($negative ? '-' : '') . $formatted . $decPart;
    }
}

// ============================================================
// format_date_in($date)
// Format a date string or timestamp in Indian style.
// Output: "22 Jun 2026"
//
// Examples:
//   format_date_in('2026-06-22')           => "22 Jun 2026"
//   format_date_in('2026-06-22 14:30:00')  => "22 Jun 2026"
//   format_date_in(1750000000)             => "15 Jun 2025"  (timestamp)
// ============================================================
if (! function_exists('format_date_in')) {
    function format_date_in(string|int $date): string
    {
        if (is_numeric($date)) {
            $timestamp = (int) $date;
        } else {
            $timestamp = strtotime($date);
        }

        if ($timestamp === false || $timestamp === 0) {
            return '';
        }

        return date('j M Y', $timestamp);
    }
}

// ============================================================
// format_range($km)
// Format a range value in kilometres with unit.
//
// Examples:
//   format_range(146)  => "146 km"
//   format_range(465)  => "465 km"
//   format_range(0)    => "N/A"
//   format_range(null) => "N/A"
// ============================================================
if (! function_exists('format_range')) {
    function format_range(int|float|null $km): string
    {
        if ($km === null || $km <= 0) {
            return 'N/A';
        }

        return (int) $km . ' km';
    }
}

// ============================================================
// calc_emi($principal, $rate_annual, $months)
// Calculate EMI using the standard reducing balance formula.
//
// Formula: EMI = P * r * (1+r)^n / ((1+r)^n - 1)
// Where:
//   P = principal loan amount
//   r = monthly interest rate (annual_rate / 12 / 100)
//   n = loan tenure in months
//
// Parameters:
//   $principal    - loan amount in INR
//   $rate_annual  - annual interest rate as percentage e.g. 8.5
//   $months       - loan tenure in months e.g. 60
//
// Returns: monthly EMI as float (round to nearest rupee for display)
//
// Examples:
//   calc_emi(1000000, 8.5, 60)  => ~20517.99
//   calc_emi(139000, 10, 24)    => ~6413.50
// ============================================================
if (! function_exists('calc_emi')) {
    function calc_emi(float $principal, float $rate_annual, int $months): float
    {
        if ($principal <= 0 || $months <= 0) {
            return 0.0;
        }

        // Zero interest edge case
        if ($rate_annual <= 0) {
            return $principal / $months;
        }

        $monthly_rate = $rate_annual / 12 / 100;
        $power        = pow(1 + $monthly_rate, $months);
        $emi          = $principal * $monthly_rate * $power / ($power - 1);

        return round($emi, 2);
    }
}

// ============================================================
// time_ago_in($datetime)
// Return a human-readable "time ago" string for a datetime.
// Uses Indian-friendly phrasing.
//
// Examples:
//   time_ago_in('2026-06-22 10:00:00')  => "2 hours ago"
//   time_ago_in('2026-06-20 10:00:00')  => "2 days ago"
//   time_ago_in('2026-05-22 10:00:00')  => "1 month ago"
//   time_ago_in('2025-06-22 10:00:00')  => "1 year ago"
//   time_ago_in(null)                   => ""
// ============================================================
if (! function_exists('time_ago_in')) {
    function time_ago_in(string|null $datetime): string
    {
        if ($datetime === null || $datetime === '') {
            return '';
        }

        $timestamp = strtotime($datetime);
        if ($timestamp === false) {
            return '';
        }

        $now  = time();
        $diff = $now - $timestamp;

        if ($diff < 0) {
            // Future date
            $diff = abs($diff);
            $suffix = 'from now';
        } else {
            $suffix = 'ago';
        }

        if ($diff < 60) {
            return 'just now';
        }

        if ($diff < 3600) {
            $mins = (int) floor($diff / 60);
            return $mins === 1 ? '1 minute ' . $suffix : $mins . ' minutes ' . $suffix;
        }

        if ($diff < 86400) {
            $hours = (int) floor($diff / 3600);
            return $hours === 1 ? '1 hour ' . $suffix : $hours . ' hours ' . $suffix;
        }

        if ($diff < 604800) {
            $days = (int) floor($diff / 86400);
            return $days === 1 ? '1 day ' . $suffix : $days . ' days ' . $suffix;
        }

        if ($diff < 2592000) {
            $weeks = (int) floor($diff / 604800);
            return $weeks === 1 ? '1 week ' . $suffix : $weeks . ' weeks ' . $suffix;
        }

        if ($diff < 31536000) {
            $months = (int) floor($diff / 2592000);
            return $months === 1 ? '1 month ' . $suffix : $months . ' months ' . $suffix;
        }

        $years = (int) floor($diff / 31536000);
        return $years === 1 ? '1 year ' . $suffix : $years . ' years ' . $suffix;
    }
}

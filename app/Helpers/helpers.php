<?php

/**
 * Global Helper Functions
 * 
 * This file loads the privilege helpers which provide:
 * - checkPrivilege($menuId, $action)
 * - canAccessMenu($menuId)
 * - getMenuPrivileges($menuId)
 * - authorizePrivilege($menuId, $action)
 * - hasAnyPrivilege($menuId, $actions)
 * - hasAllPrivileges($menuId, $actions)
 * - getUserAccessibleMenus()
 */

require_once __DIR__ . '/privilege_helpers.php';

// ═══════════════════════════════════════════════════════════════════════════
// Currency Formatting Helpers
// ═══════════════════════════════════════════════════════════════════════════

if (!function_exists('formatCurrency')) {
    /**
     * Format a number as currency
     * 
     * @param float $amount The amount to format
     * @param string|null $currency Currency code (default: LKR)
     * @param bool $displaySymbol Whether to display symbol/code (default: true)
     * @return string Formatted currency string
     */
    function formatCurrency($amount, $currency = null, $displaySymbol = true)
    {
        $formatted = number_format((float) $amount, 2, '.', ',');
        
        if (!$displaySymbol) {
            return $formatted;
        }
        
        return "Rs. {$formatted}";
    }
}

if (!function_exists('getCurrencyCode')) {
    function getCurrencyCode()
    {
        return 'LKR';
    }
}

if (!function_exists('getCurrencySymbol')) {
    function getCurrencySymbol($currency = null)
    {
        return 'Rs.';
    }
}

if (!function_exists('currencyAmount')) {
    function currencyAmount($amount)
    {
        return formatCurrency($amount);
    }
}

if (!function_exists('statusBadge')) {
    /**
     * Render central standardized status badge HTML.
     */
    function statusBadge($status): string
    {
        return view('components.status-badge', ['status' => $status])->render();
    }
}


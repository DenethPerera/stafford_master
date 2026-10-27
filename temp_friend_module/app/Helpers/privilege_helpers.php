<?php

use App\Services\PrivilegeService;

if (!function_exists('checkPrivilege')) {
    /**
     * Check if user has privilege for a menu and action
     *
     * @param int $menuId Menu ID
     * @param string $action Action: 'add', 'edit', 'statuschange', 'remove'
     * @return bool
     */
    function checkPrivilege($menuId, $action)
    {
        return PrivilegeService::check($menuId, $action);
    }
}

if (!function_exists('canAccessMenu')) {
    /**
     * Check if user can access a menu
     *
     * @param int $menuId Menu ID
     * @return bool
     */
    function canAccessMenu($menuId)
    {
        return PrivilegeService::canAccess($menuId);
    }
}

if (!function_exists('getMenuPrivileges')) {
    /**
     * Get all privileges for a menu
     *
     * @param int $menuId Menu ID
     * @return array
     */
    function getMenuPrivileges($menuId)
    {
        return PrivilegeService::getPrivilegeArray($menuId);
    }
}

if (!function_exists('authorizePrivilege')) {
    /**
     * Authorize privilege or abort with 403
     *
     * @param int $menuId Menu ID
     * @param string|null $action Action to check
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    function authorizePrivilege($menuId, $action = null)
    {
        PrivilegeService::authorize($menuId, $action);
    }
}

if (!function_exists('hasAnyPrivilege')) {
    /**
     * Check if user has any of the specified actions
     *
     * @param int $menuId Menu ID
     * @param array $actions Array of actions
     * @return bool
     */
    function hasAnyPrivilege($menuId, array $actions)
    {
        return PrivilegeService::checkAny($menuId, $actions);
    }
}

if (!function_exists('hasAllPrivileges')) {
    /**
     * Check if user has all specified actions
     *
     * @param int $menuId Menu ID
     * @param array $actions Array of actions
     * @return bool
     */
    function hasAllPrivileges($menuId, array $actions)
    {
        return PrivilegeService::checkMultiple($menuId, $actions);
    }
}

if (!function_exists('getUserAccessibleMenus')) {
    /**
     * Get all menus the user has access to
     *
     * @return \Illuminate\Support\Collection
     */
    function getUserAccessibleMenus()
    {
        return PrivilegeService::getAccessibleMenus();
    }
}

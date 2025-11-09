<?php

/**
 * define global function same as helper function.
 * check env of request.
 */
if (!function_exists('isAdminEnv')) {
	function isAdminEnv(): bool
	{
		/**
		 * @/var \Illuminate\Routing\UrlGenerator $currentUrl
		 */
		// $currentUrl = url();

		/**
		 * check if path of url start with: adminhtml is admin environment
		 */
		if (strpos(request()->path(), ADMIN_PREFIX) === 0) {
			return true;
		}
		return false;
	}
}

/**
 * define global function for check api environment
 */
if (!function_exists('isApiEnv')) {
	function isApiEnv(): bool
	{
		if (strpos(request()->path(), 'api') === 0) {
			return true;
		}
		return false;
	}
}

/**
 * define global function for check api environment
 */
if (!function_exists('isTestEnv')) {
	function isTestEnv(): bool
	{
		if (strpos(request()->path(), 'test') === 0) {
			return true;
		}
		return false;
	}
}

if (!function_exists('urlToStoragePath')) {
	function urlToStoragePath($url): string
	{
		return $url ? parse_url($url)['path'] : '';
	}
}

if (!function_exists('storagePathToUrl')) {
	function storagePathToUrl($path): string
	{
		return asset($path);
	}
}

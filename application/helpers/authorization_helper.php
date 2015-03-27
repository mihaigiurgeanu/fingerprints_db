<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_user_authorized'))
{
	/**
	 * Check if current user has authorization for a certain service.
	 *
	 * @param	string  the requested service code
	 * @return	bool    whether or not the user is authorized
	 */
	function is_user_authorized($service_code)
	{
        // TODO - add proper service authorization
        switch($service_code) {
            case "tokens.create":
            return TRUE;

            case "tokens.read":
            return TRUE;

            case "tokens.update":
            return TRUE;

            case "scans.read":
            return TRUE;
            
            case "scans.create":
            return TRUE;
            
            case "scans.update":
            return FALSE;
            
            default:
            return FALSE;
        }

	}
}

?>
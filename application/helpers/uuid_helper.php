<?php

if ( ! function_exists('uuid_is_valid'))
{
    /**
	 * Verify if a uuid is valid.
	 *
	 * @param	string  the uuid
	 * @return	bool    whether or not the uuid is valid
	 */

    function uuid_is_valid($uuid) {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                          '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }

}

if ( ! function_exists('uuid_v5'))
{
    /**
	 * Generates UUID v5.
	 *
	 * @param	string  the namespace in which to generate uuids
	 * @param   @string a random value
	 * @return	bool    whether or not the user is authorized
	 */
    function uuid_v5($namespace, $name) {
        if(!uuid_is_valid($namespace)) return false;

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-','{','}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for($i = 0; $i < strlen($nhex); $i+=2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
        }

        // Calculate hash value
        $hash = sha1($nstr . $name);

        return sprintf('%08s-%04s-%04x-%04x-%12s',

                       // 32 bits for "time_low"
                       substr($hash, 0, 8),

                       // 16 bits for "time_mid"
                       substr($hash, 8, 4),

                       // 16 bits for "time_hi_and_version",
                       // four most significant bits holds version number 5
                       (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

                       // 16 bits, 8 bits for "clk_seq_hi_res",
                       // 8 bits for "clk_seq_low",
                       // two most significant bits holds zero and one for variant DCE1.1
                       (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

                       // 48 bits for "node"
                       substr($hash, 20, 12)
                      );
    }
    
}

?>
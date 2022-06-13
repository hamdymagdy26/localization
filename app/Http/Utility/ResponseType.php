<?php

namespace App\Http\Utility;

use Dev\Application\Utility\UserStatus;
use Dev\Application\Exceptions\InvalidArgumentException;

/**
 *
 */
final class ResponseType
{
    /**
     * @param string $message
     * @param bool $success
     * @param array $aux
     * @return array
     */
	public static function simpleResponse(string $message, bool $success = true, array $aux = [])
	{
		return array_merge([   
			"data" => [], 
            "message" => $message,
            "success" => $success
        ], $aux);	
	}
}
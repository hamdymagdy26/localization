<?php
namespace Dev\Application\Exceptions;

use App\Http\Resources\ErrorResponseResource;
use App\Http\Utility\ResponseType;
use Exception;

class InvalidLocalizationException extends Exception
{
    /**
     * @return ErrorResponseResource
     */
    public function render()
    {
        return new ErrorResponseResource(ResponseType::simpleResponse($this->getMessage(), false));
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ErrorResponseResource
 * @package App\Http\Resources
 */
class ErrorResponseResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'errors';

    public function withResponse($request, $response)
    {
        $response->setStatusCode(422);
    }
}

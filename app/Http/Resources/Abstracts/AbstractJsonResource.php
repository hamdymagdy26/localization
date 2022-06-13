<?php

namespace App\Http\Resources\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AbstractJsonResource is a base class for all json responses
 * @package Dev\Application\Response\Abstracts
 */
abstract class AbstractJsonResource extends JsonResource
{
    /**
     * AbstractJsonResource constructor.
     * @param $resource
     */
	public function __construct($resource)
	{
		parent::__construct($resource);
	}

    /**
     * @param Request $request
     * @return array
     */
	abstract protected function modelResponse(Request $request) : array;

	/**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
	public function toArray($request)
	{
        if (is_null($this->resource)) {
            return [];
        }
        if (is_array($this->resource)) {
        	return $this->resource;
        }

        return $this->modelResponse($request);
	}
}

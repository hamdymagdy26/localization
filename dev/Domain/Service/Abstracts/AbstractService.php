<?php

namespace Dev\Domain\Service\Abstracts;

use Dev\Infrastructure\Repository\Abstracts\AbstractRepository;

/**
 * Class AbstractService base class for all services
 * @package Dev\Domain\Service\Abstracts
 */
abstract class AbstractService
{
	/**
	 *
	 */
	protected $repository;

    /**
     * @var string $locale
     */
    private $locale;

    /**
     * AbstractService constructor.
     * @param AbstractRepository $repository
     */
    public function __construct(AbstractRepository $repository)
	{
		$this->repository = $repository;
	}

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return  $this->repository->{$method}(...$arguments);
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
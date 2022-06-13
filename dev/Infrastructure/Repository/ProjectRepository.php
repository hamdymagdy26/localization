<?php

namespace Dev\Infrastructure\Repository;

use Dev\Infrastructure\Models\Plan;
use Dev\Infrastructure\Models\Project;
use Dev\Infrastructure\Repository\Abstracts\AbstractRepository;

/**
 * Class PlanRepository
 * @package Dev\Infrastructure\Repository
 */
class ProjectRepository extends AbstractRepository
{
    /**
     * FacultyRepository constructor.
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }
}

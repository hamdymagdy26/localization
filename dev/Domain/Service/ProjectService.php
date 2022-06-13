<?php

namespace Dev\Domain\Service;

use Dev\Domain\Service\Abstracts\AbstractService;
use Dev\Infrastructure\Models\Project;
use Dev\Infrastructure\Repository\ProjectRepository;
use Illuminate\Support\Arr;

/**
 * Class ProjectService
 * @package Dev\Domain\Service
 */
class ProjectService extends AbstractService
{
    /**
     * ProjectService constructor.
     * @param ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param array $data
     * @return Project
     */
    public function create($data = [])
    {
        $projectData = Arr::only(
            $data,
            [
                'management_id',
                'faculty_id',
                'project_type',
                'internal_executive_role',
                'external_executive_role',
                'nationality_id',
                'participants_number'
            ]
        );

        $insertProject = $this->repository->create($projectData);

        if (isset($data['participant_information'])) {
            $participants = $data['participant_information'];

            foreach ($participants as $participant) {
                $insertProject->studentGenders()->create(
                    Arr::only(
                        $participant,
                        [
                            'student_type',
                            'from',
                            'to'
                        ]
                    )
                );
            }
        }

        if (isset($data['translation'])) {
            $translations = $data['translation'];
            foreach ($translations as $translation) {
                $insertProject->translations()->create(
                    Arr::only(
                        $translation,
                        [
                            'name',
                            'description',
                            'locale'
                        ]
                    )
                );
            }
        }

        return $insertProject->refresh();
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function index(array $filter = [])
    {
        $repository = $this->repository;
        if (isset($filter['limit'])) {
            return $repository->paginate($filter['limit']);
        }
        return $repository->get();
    }

    /**
     * @param int $id
     * @return StudentCarePermission
     */
    public function show(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Project
     */
    public function update(int $id, $data = [])
    {
        $project = $this->repository->find($id);

        $projectData = Arr::only(
            $data,
            [
                'project_type',
                'faculty_id',
                'internal_executive_role',
                'external_executive_role',
                'nationality_id',
                'participants_number'
            ]
        );

        $project->update($projectData);

        if (isset($data['translation'])) {
            $translations = $data['translation'];
            foreach ($translations as $translation) {
                $project->translations()->updateOrCreate(
                    Arr::only($translation, ['locale']),
                    Arr::only(
                        $translation,
                        [
                            'name',
                            'description',
                            'locale'
                        ]
                    )
                );
            }
        }

        if (isset($data['participant_information'])) {
            $participants = $data['participant_information'];
            foreach ($participants as $participant) {
                $project->studentGenders()->updateOrCreate(
                    Arr::only($participant, ['student_type']),
                    Arr::only(
                        $participant,
                        [
                            'from',
                            'to'
                        ]
                    )
                );
            }
        }

        return $this->show($id);
    }

    /**
     * @param Project $project
     * @throws \Exception
     */
    public function delete(Project $project)
    {
        $project->translations()->delete();
        $project->studentGenders()->delete();
        $project->delete();
    }


    /**
     * Delete translation
     * @param int $id
     * @param string $locale
     * @return mixed
     */
    public function deleteTranslation(int $id, string $locale)
    {
        $project = $this->show($id);
        return $project->translations()->where('locale', $locale)->delete();
    }
}

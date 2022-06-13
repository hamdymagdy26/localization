<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Utility\ResponseType;
use Dev\Domain\Service\ProjectService;
use Dev\Infrastructure\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    private $projectService;

    /**
     * ProjectController constructor.
     * @param Request $request
     * @param ProjectService $projectService
     */
    public function __construct(Request $request, ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->projectService->setLocale($request->header('localization'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  ProjectFormRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(ProjectFormRequest $request)
    {
        $filters = $request->validated();
        $project = $this->projectService->index($filters);
        return ProjectResource::collection($project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProjectFormRequest $request
     * @return ProjectResource
     */
    public function store(ProjectFormRequest $request)
    {
        $data = $request->validated();
        $project = $this->projectService->create($data);
        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  ProjectFormRequest $request
     * @param  Project $project
     * @return ProjectResource
     */
    public function show(Project $project, ProjectFormRequest $request)
    {
        $project = $this->projectService->show($project->id);
        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProjectFormRequest  $request
     * @param  Project $project
     * @return ProjectResource
     */
    public function update(ProjectFormRequest $request, Project $project)
    {
        $data = $request->validated();
        $project = $this->projectService->update($project->id, $data);
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project $project
     * @return ProjectResource
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $this->projectService->delete($project);
        return new ProjectResource(ResponseType::simpleResponse('Project has been deleted', true));
    }

    /**
     * Delete service type translation
     *
     * @param ProjectFormRequest $request
     * @param  Project $project
     * @return ProjectResource
     */
    public function deleteTranslation(Project $project, ProjectFormRequest $request)
    {
        $data = $request->validated();
        $this->projectService->deleteTranslation($project->id, $data['locale']);
        return new ProjectResource(ResponseType::simpleResponse('Project translation has been deleted', true));
    }
}

<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService; 
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);
       
       $filters = $request->only(['min_budget', 'this_month']);

    $projects = $this->projectService->getFilteredProjects($filters);

    return ProjectResource::collection($projects);

    } 

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->storeProject($request->validated());
        return response()->json([
        'message' => 'The project created successfully',
        'data' => $project 
    ], 201);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load(['user', 'tags', 'offers.user']);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
       
     $this->authorize('update' , $project);

        $updatedProject = $this->projectService->updateProject($project, $request->validated());
        return new ProjectResource($updatedProject);
    }

     public function destroy(Project $project)
    {
       $this->authorize('delete' , $project);

        $this->projectService->deleteProject($project);
        return response()->json(['message' => 'The project has been successfully deleted']);
    }
}
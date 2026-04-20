<?php 

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService 
{
    protected $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    /**
     * display list of projects
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredProjects(array $filters)
    {
        return Project::query()
            ->open()
            ->with(['user.city.country', 'tags']) 
            ->withCount('offers')
            ->when($filters['min_budget'] ?? null, function($query, $minBudget) {
                $query->highBudget($minBudget);
            })
            ->when($filters['this_month'] ?? false, function($query) {
                $query->whereMonth('created_at', now()->month);
            })
            ->latest()
            ->paginate(5);
    }
    /**
     * create new project
     * @param array $data
     * @return Project
     */
  public function storeProject(array $data)
    {
        return DB::transaction(function () use ($data) {
            $tagIds = $data['tags'] ?? [];
            $files = $data['attachments'] ?? [];

    
            $projectData = collect($data)->except(['tags', 'attachments'])->toArray();
            
          
            $projectData['user_id'] = auth('sanctum')->id(); 
            
            $project = Project::create($projectData);

            if (!empty($tagIds)) {
                $project->tags()->attach($tagIds);
            }

            if (!empty($files)) {
                $this->fileService->uploadMultiple($project, $files, 'project_files');
            }

            return $project->load(['tags', 'attachments', 'user']);
        });
    }
    /**
     * update project
     * @param Project $project
     * @param array $data
     * @return Project
     */
    public function updateProject(Project $project, array $data)
    {
        $project->update($data);
        return $project;
    }
 
    /**
     * delete project
     * @param Project $project
     * @return bool|int|mixed|null
     */
    public function deleteProject(Project $project)
    {
        return DB::transaction(function () use ($project) {
             // delte the files
            foreach ($project->attachments as $attachment) {
                $this->fileService->delete($attachment);
            }
            return $project->delete();
        });
    }

    
}

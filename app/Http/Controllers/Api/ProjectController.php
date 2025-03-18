<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Project::query()
            ->with(['users', 'attributes']);

        // Filter by regular attributes
        if ($request->has('filters')) {
            $filters = $request->filters;
            foreach ($filters as $field => $value) {
                if (in_array($field, ['name', 'status'])) {
                    $query->where($field, 'like', "%{$value}%");
                }
            }
        }

        // Filter by EAV attributes
        if ($request->has('filters')) {
            $filters = $request->filters;
            foreach ($filters as $field => $value) {
                if (!in_array($field, ['name', 'status'])) {
                    $query->whereHas('attributeValues', function ($q) use ($field, $value) {
                        $q->whereHas('attribute', function ($q) use ($field) {
                            $q->where('name', $field);
                        })->where('value', 'like', "%{$value}%");
                    });
                }
            }
        }

        return ProjectResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        if ($request->has('user_ids')) {
            $project->users()->attach($request->user_ids);
        }

        if ($request->has('attributes')) {
            foreach ($request->attributes as $attribute) {
                $project->attributeValues()->create([
                    'attribute_id' => $attribute['id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        return response()->json(new ProjectResource($project->load(['users', 'attributes'])), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json(new ProjectResource($project->load(['users', 'attributes'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        if ($request->has('user_ids')) {
            $project->users()->sync($request->user_ids);
        }

        if ($request->has('attributes')) {
            foreach ($request->attributes as $attribute) {
                $project->attributeValues()
                    ->updateOrCreate(
                        ['attribute_id' => $attribute['id']],
                        ['value' => $attribute['value']]
                    );
            }
        }

        return response()->json(new ProjectResource($project->load(['users', 'attributes'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }
}

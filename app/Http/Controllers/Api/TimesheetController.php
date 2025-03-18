<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TimesheetRequest;
use App\Http\Resources\TimesheetResource;
use App\Models\Timesheet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Timesheet::query()
            ->with(['user', 'project'])
            ->where('user_id', auth()->id());

        if ($request->has('filters')) {
            $filters = $request->filters;
            foreach ($filters as $field => $value) {
                if (in_array($field, ['task_name', 'date', 'hours', 'project_id'])) {
                    if ($field === 'date') {
                        $query->whereDate($field, $value);
                    } else {
                        $query->where($field, 'like', "%{$value}%");
                    }
                }
            }
        }

        return TimesheetResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimesheetRequest $request): JsonResponse
    {
        $timesheet = auth()->user()->timesheets()->create($request->validated());

        return response()->json(new TimesheetResource($timesheet->load(['user', 'project'])), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Timesheet $timesheet): JsonResponse
    {
        $this->authorize('view', $timesheet);

        return response()->json(new TimesheetResource($timesheet->load(['user', 'project'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimesheetRequest $request, Timesheet $timesheet): JsonResponse
    {
        $this->authorize('update', $timesheet);

        $timesheet->update($request->validated());

        return response()->json(new TimesheetResource($timesheet->load(['user', 'project'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet): JsonResponse
    {
        $this->authorize('delete', $timesheet);

        $timesheet->delete();

        return response()->json(null, 204);
    }
}

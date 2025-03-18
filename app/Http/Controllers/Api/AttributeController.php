<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Attribute::query();

        if ($request->has('filters')) {
            $filters = $request->filters;
            foreach ($filters as $field => $value) {
                if (in_array($field, ['name', 'type'])) {
                    $query->where($field, 'like', "%{$value}%");
                }
            }
        }

        return AttributeResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request): JsonResponse
    {
        $attribute = Attribute::create($request->validated());

        return response()->json(new AttributeResource($attribute), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute): JsonResponse
    {
        return response()->json(new AttributeResource($attribute));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute): JsonResponse
    {
        $attribute->update($request->validated());

        return response()->json(new AttributeResource($attribute));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute): JsonResponse
    {
        $attribute->delete();

        return response()->json(null, 204);
    }
}

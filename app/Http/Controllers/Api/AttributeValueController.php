<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AttributeValueRequest;
use App\Http\Resources\AttributeValueResource;
use App\Models\AttributeValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = AttributeValue::query()
            ->with(['attribute', 'project']);

        if ($request->has('filters')) {
            $filters = $request->filters;
            foreach ($filters as $field => $value) {
                if (in_array($field, ['attribute_id', 'project_id', 'value'])) {
                    $query->where($field, 'like', "%{$value}%");
                }
            }
        }

        return AttributeValueResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeValueRequest $request): JsonResponse
    {
        $attributeValue = AttributeValue::create($request->validated());

        return response()->json(new AttributeValueResource($attributeValue->load(['attribute', 'project'])), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AttributeValue $attributeValue): JsonResponse
    {
        return response()->json(new AttributeValueResource($attributeValue->load(['attribute', 'project'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeValueRequest $request, AttributeValue $attributeValue): JsonResponse
    {
        $attributeValue->update($request->validated());

        return response()->json(new AttributeValueResource($attributeValue->load(['attribute', 'project'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttributeValue $attributeValue): JsonResponse
    {
        $attributeValue->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\OperationResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CategoryRequest;

/**
 * @group Category management
 *
 * Endpoints for managing categories
 */
class CategoryController extends Controller
{
    /**
     * Return all categories.
     *
     * @return AnonymousResourceCollection<CategoryResource>
     */
    public function categories(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::get());
    }

    /**
     * Create a new category.
     *
     * @param CategoryRequest $request
     * @return CategoryResource
     */
    public function create(CategoryRequest $request): CategoryResource
    {
        $category = Category::create($request->validated());

        return CategoryResource::make($category);
    }

    /**
     * Return all operations having this category, paginated, 100 per page.
     *
     * @param Category $category
     * @return AnonymousResourceCollection<OperationResource>
     */
    public function operations(Category $category): AnonymousResourceCollection
    {
        return OperationResource::collection($category->operations()->paginate(100));
    }

    /**
     * Update a category.
     *
     * @param Category $category
     * @param CategoryRequest $request
     * @return CategoryResource
     */
    public function update(Category $category, CategoryRequest $request): CategoryResource
    {
        $category->update($request->validated());

        return CategoryResource::make($category);
    }

    /**
     * Soft-delete a category.
     *
     * @param Category $category
     * @return Response
     * @response 204 {}
     */
    public function delete(Category $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}

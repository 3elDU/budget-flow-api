<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Return all registered categories
     */
    public function categories(): JsonResponse
    {
        return response()->json(Category::get());
    }

    /**
     * Create a new category.
     * Returns created category object
     */
    public function create(CategoryCreateRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json($category);
    }

    /**
     * Return all operations having this category, paginated, 100 per page.
     */
    public function operations(Category $category): JsonResponse
    {
        return response()->json(
            $category->operations()->paginate(100)
        );
    }

    /**
     * Update a category.
     * Returns updated category object
     */
    public function update(Category $category, CategoryUpdateRequest $request): JsonResponse
    {
        $category->update($request->validated());

        return response()->json($category);
    }

    /**
     * Soft-delete a category.
     * Returns deleted category object
     */
    public function delete(Category $category): Response
    {
        $category->delete();

        return response()->noContent();
    }
}

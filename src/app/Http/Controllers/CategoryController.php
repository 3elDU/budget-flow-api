<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Return all registered categories
     */
    public function categories()
    {
        return response()->json(
            Category::get()
        );
    }

    /**
     * Craete a new category.
     * Returns created category object
     */
    public function create(CategoryCreateRequest $request)
    {
        $data = $request->validated();

        $category = Category::create($data);

        return response()->json($category);
    }

    /**
     * Return all incomes having this category, paginated, 100 per page.
     */
    public function incomes(Category $category)
    {
        return response()->json(
            $category->incomes()->paginate(100)
        );
    }

    /**
     * Return all expenses having this category, paginated, 100 per page.
     */
    public function expenses(Category $category)
    {
        return response()->json(
            $category->expenses()->paginate(100)
        );
    }

    /**
     * Update a category.
     * Returns updated category object
     */
    public function update(Category $category, CategoryUpdateRequest $request)
    {
        $data = $request->validated();

        $category->update($data);

        return response()->json($category);
    }

    /**
     * Soft-delete a category.
     * Returns deleted category object
     */
    public function delete(Category $category)
    {
        $category->delete();

        return response()->json($category);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use app\Models\User;
use App\Models\Budget;
use Brick\Money\Money;
use App\Models\Operation;
use Brick\Math\RoundingMode;
use Illuminate\Routing\Controller;
use App\Services\FiltrationService;
use App\Http\Requests\FiltersRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\OperationResource;
use App\Http\Requests\OperationCreateRequest;
use App\Http\Requests\OperationUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Operation management
 *
 * Endpoints for managing operations
 */
class OperationController extends Controller
{
    /**
     * Return all operations, paginated, 100 per page.
     */
    public function index(FiltersRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $query = Operation::query()->with('categories')->whereHas('budget', function ($query) use ($user) {
            $query->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        });

        $data = $request->validated();
        $filtersDTO = FiltrationService::makeDTO($data);

        // If the user is querying 'budget_id', check if they have permission for the budget(s)
        foreach ($filtersDTO->filters as $filter) {
            if ($filter->field !== 'budget_id') {
                continue;
            }

            /** @var Budget $budget */
            $budget = Budget::query()->find($filter->value)->first();

            if (!$budget->users->contains($user)) {
                // If a user is not a member of this budget, return 403 forbidden
                return response(['message' => 'You are not a member of this budget'], Response::HTTP_FORBIDDEN);
            }
        }

        FiltrationService::performFiltration($query, $filtersDTO);

        $query->orderBy('created_at', 'desc');

        return OperationResource::collection($query->paginate($data['per_page'] ?? 10));
    }

    /**
     * Return a specific operation by id
     */
    public function get(Operation $operation): OperationResource
    {
        return new OperationResource($operation);
    }

    /**
     * Create a new operation for a budget.
     * Returns newly created operation.
     */
    public function create(Budget $budget, OperationCreateRequest $request): OperationResource
    {
        $data = $request->validated();

        $operation = Operation::create([
            'budget_id' => $budget->id,
            'user_id' => auth()->user()->id,
            'amount' => Money::of($data['amount'], $budget->currency, roundingMode: RoundingMode::HALF_CEILING),
        ] + $data);

        $categories = Category::whereIn('id', $data['categories'])->get();

        $operation->categories()->attach($categories);

        return new OperationResource($operation);
    }

    /**
     * Update a specific operation
     */
    public function update(Operation $operation, OperationUpdateRequest $request): OperationResource
    {
        $data = $request->validated();

        $operation->update([
            'amount' => Money::of($data['amount'], $operation->budget->currency, roundingMode: RoundingMode::HALF_CEILING),
        ] + $data);

        // Invalidate cache for this budget
        Cache::tags("budget:{$operation->budget->id}")->flush();

        return new OperationResource($operation);
    }

    /**
     * Delete a specified operation.
     */
    public function delete(Operation $operation): \Illuminate\Http\Response
    {
        $operation->delete();

        // Invalidate cache for this budget
        Cache::tags("budget:{$operation->budget->id}")->flush();

        return response()->noContent();
    }
}

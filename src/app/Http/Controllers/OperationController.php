<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Operation;
use app\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Services\FiltrationService;
use App\Http\Requests\FiltersRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\OperationResource;
use App\Http\Requests\OperationCreateRequest;
use App\Http\Requests\OperationUpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class OperationController extends Controller
{
    /**
     * Return all operations, paginated, 100 per page.
     */
    public function index(FiltersRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $query = Operation::query();

        $filters = $request->validated();
        $filtersDTO = FiltrationService::makeDTO($filters);

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

        return OperationResource::collection($query->paginate(100));
    }

    /**
     * Return a specific operation by id
     */
    public function get(Operation $operation): JsonResponse
    {
        return response()->json($operation);
    }

    /**
     * Create a new operation for a budget.
     * Returns newly created operation.
     */
    public function create(Budget $budget, OperationCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $operation = Operation::create([
            'budget_id' => $budget->id,
            'user_id' => auth()->user()->id,
            ...$data,
        ]);

        return response()->json($operation);
    }

    /**
     * Update a specific operation
     */
    public function update(Operation $operation, OperationUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $operation->update($data);

        // Invalidate cache for this budget
        Cache::tags("budget:{$operation->budget->id}")->flush();

        return response()->json($operation);
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

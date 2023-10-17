<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Operation;
use Illuminate\Routing\Controller;
use App\Services\FiltrationService;
use App\Http\Requests\FiltersRequest;
use App\Http\Resources\OperationResource;
use App\Http\Requests\OperationCreateRequest;
use App\Http\Requests\OperationUpdateRequest;

class OperationController extends Controller
{
    /**
     * Return all operations, paginated, 100 per page.
     */
    public function index(FiltersRequest $request)
    {
        /** @var \app\Models\User $user */
        $user = auth()->user();
        $query = Operation::query();

        $filters = $request->validated();
        $filtersDTO = FiltrationService::makeDTO($filters);

        // If the user is querying 'budget_id', check if they have permission for the budget(s)
        foreach ($filtersDTO->filters as $filter) {
            if ($filter->field !== 'budget_id') {
                continue;
            }

            /** @var \App\Models\Budget $budget */
            $budget = Budget::query()->find($filter->value)->first();

            if (!$budget->users->contains($user)) {
                // If a user is not a member of this budget, return 403 unauthorized
                return response('not authorized', 403);
            }
        }

        FiltrationService::performFiltration($query, $filtersDTO);

        return OperationResource::collection($query->paginate(100));
    }

    /**
     * Return a specific operation by id
     */
    public function get(Budget $budget, Operation $operation)
    {
        return response()->json($operation);
    }

    /**
     * Create a new operation for a budget.
     * Returns newly created operation.
     */
    public function create(Budget $budget, OperationCreateRequest $request)
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
    public function update(Budget $budget, Operation $operation, OperationUpdateRequest $request)
    {
        $data = $request->validated();

        $operation->update($data);

        return response()->json($operation);
    }

    /**
     * Delete a specified operation.
     */
    public function delete(Budget $budget, Operation $operation)
    {
        $operation->delete();

        return response()->noContent();
    }
}

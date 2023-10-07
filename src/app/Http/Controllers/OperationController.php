<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiltersRequest;
use App\Http\Requests\OperationCreateRequest;
use App\Http\Requests\OperationUpdateRequest;
use App\Models\Budget;
use App\Models\Operation;
use App\Services\FiltrationService;
use Illuminate\Auth\Access\AuthorizationException;

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

        // Verify that user is authorized to make a request
        $lacksPermission = $filtersDTO
            ->filters
            ->filter(
                fn ($filter) =>
                // Check if the user is querying budget(s), and if the user isn't member of the budget
                $filter->field === "budget_id" && !$user->budgets()->has($filter->value)
            )
            ->count() > 0;

        if ($lacksPermission) {
            return response('not authorized', 403);
        }

        FiltrationService::performFiltration($query, $filtersDTO);

        return response()->json(
            $query->paginate(100)
        );
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
     * Returns deleted operation object.
     */
    public function delete(Budget $budget, Operation $operation)
    {
        $operation->delete();

        return response()->json($operation);
    }
}

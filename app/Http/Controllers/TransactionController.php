<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountException;
use App\Http\Requests\CreateTransactionRequest;
use app\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    private TransactionRepository $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     * @throws AccountException
     */
    public function create(CreateTransactionRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $response = null;
        if ($payload['to_account_id'] === $payload['from_account_id']) {
            $response = $this->repository->internalTranfer(
                $payload['from_account_id'],
                $payload['to_account_id'],
                $payload['amount']
            );
        }
        $response = $this->repository->externalTranfer(
            $payload['from_account_id'],
            $payload['to_account_id'],
            $payload['amount']
        );
        return response()->json($response, Response::HTTP_CREATED);
    }
}

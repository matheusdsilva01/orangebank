<?php

namespace App\Http\Controllers;

use App\Actions\CreateInternalTransfer;
use App\Http\Requests\CreateTransactionRequest;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    private TransactionRepository $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function internalTransfer(CreateTransactionRequest $request, CreateInternalTransfer $createInternalTransfer)
    {
        try {
            $transaction = $createInternalTransfer->handle($request->validated());
            return response()->json($transaction->toArray(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function create(CreateTransactionRequest $request): JsonResponse
    {
        try {
            $payload = $request->validated();
            $response = $this->repository->createTransfer($payload);

            return response()->json($response, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

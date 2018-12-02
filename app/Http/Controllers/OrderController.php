<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private $orderRepository;

    /**
     * OrderController constructor.
     * @param OrderRepository $sr
     */
    public function __construct(OrderRepository $sr)
    {
        $this->orderRepository = $sr;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        return response()->json($this->orderRepository->findOneByIdWithMock($id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'status' => 'required|string',
            'user_id' => 'required|int',
            'products' => 'required|array'
        ]);

        return response()->json($this->orderRepository->create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $this->validate($request, [
            'status' => 'required|string'
        ]);

        return response()->json($this->orderRepository->updateStatus($id, $request->get('status')));
    }
}


<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $orderRepository;

    /**
     * ReportController constructor.
     * @param OrderRepository $sr
     */
    public function __construct(OrderRepository $sr)
    {
        $this->orderRepository = $sr;
    }

    public function getGeneralReport(): JsonResponse
    {
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getCustomerReport(Request $request, int $id): JsonResponse
    {
        return response()->json($this->orderRepository->getCustomerReport($id, $this->getQueryParams($request->getQueryString())));
    }


    public function getProductReport(Request $request, int $id): JsonResponse
    {
        return response()->json($this->orderRepository->getProductReport($id, $this->getQueryParams($request->getQueryString())));
    }

    /**
     * @param string $queryString
     * @return array
     */
    private function getQueryParams(string $queryString = null): array
    {
        if (!$queryString) {
            return [];
        }

        $parameters = [];
        $explodedQueryString = explode('&', $queryString);

        foreach ($explodedQueryString as $string) {
            $values = explode('=', $string);
            $key = $values[0];
            $val = $values[1];
            $parameters[$key] = $val;
        }

        return $parameters;
    }
}


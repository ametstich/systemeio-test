<?php

namespace App\Controller;

use App\Requests\CalculatePriceRequest;
use App\Requests\PurchaseRequest;
use App\Services\PriceService;
use App\Services\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class AppController extends AbstractController
{
    const ERROR_CODE = 400;

    #[Route(path: 'calculate-price', name: 'calculate-price', methods: 'post')]
    public function priceCalculateAction(CalculatePriceRequest $request, PriceService $priceService): JsonResponse
    {
        if (!$request->validate()) {
            return $this->json($request->prepareResponse(), self::ERROR_CODE);
        }

        try {
            $response['data'] = $priceService->calculatePrice($request);
        } catch (Throwable $th) {
            $errors = $priceService->getErrors();
            $errors[] = $th->getMessage();
            $response['data'] = ['error' => $errors];
        }

        return $this->json($response['data'], $response['code'] ?? 200);
    }

    #[Route(path: 'purchase', name: 'purchase', methods: 'post')]
    public function purchaseAction(PurchaseRequest $request, PriceService $priceService, PurchaseService $purchaseService): JsonResponse
    {
        if (!$request->validate()) {
            return $this->json($request->prepareResponse(), self::ERROR_CODE);
        }
        try {
            $product = $priceService->calculatePrice($request);
            $purchaseData = [
                'product' => $product['data']['id'],
                'price' => $product['data']['total'],
                'paymentProcessor' => $request->paymentProcessor
            ];
            $test = $purchaseService->purchase($purchaseData);
            if ($test === null) {
                $test = 'ok';
            }
        } catch (Throwable $th) {
            return $this->json([
                'error' => [
                    'message' => $th->getMessage(),
                    'code' => self::ERROR_CODE
                ]
            ]);
        }

        return $this->json($test);
    }
}
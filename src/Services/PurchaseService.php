<?php

namespace App\Services;

use App\PaymentProcessor\PaypalPaymentProcessor;
use App\PaymentProcessor\StripePaymentProcessor;
use Exception;

class PurchaseService
{
    const PAYMENT_PROCESSORS = [
        'paypal' => ['class' => PaypalPaymentProcessor::class, 'method' => 'pay'],
        'stripe' => ['class' => StripePaymentProcessor::class, 'method' => 'processPayment']
    ];
    private ?string $paymentProcessorClass;
    private string $paymentProcessorMethod;

    /**
     * @throws Exception
     */
    public function purchase(array $data = []): ?bool
    {
        $this->checkAvailablePayment($data['paymentProcessor']);
        $method = $this->paymentProcessorMethod;
        $class = new $this->paymentProcessorClass();

        return $class->$method($data['price']);
    }

    /**
     * @throws Exception
     */
    protected function checkAvailablePayment(?string $name): void
    {
        if (!isset(self::PAYMENT_PROCESSORS[$name])) {
            throw new Exception('Payment system not available');
        }
        $this->paymentProcessorClass = self::PAYMENT_PROCESSORS[$name]['class'];
        $this->paymentProcessorMethod = self::PAYMENT_PROCESSORS[$name]['method'];
    }
}
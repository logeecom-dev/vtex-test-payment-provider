<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\Manifest;
use App\Models\PaymentMethodInfo;
use App\Models\PaymentResponse;
use App\Models\RedirectRequest;
use App\Services\BusinessLogic\OrderTransformerService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidCartItemsException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidDateException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidDurationException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidGuiLayoutValueException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidQuantityException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidServiceEndTimeException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidTimestampException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidUrlException;
use SeQura\Core\BusinessLogic\Domain\Order\Models\GetAvailablePaymentMethodsRequest;
use SeQura\Core\BusinessLogic\Domain\Order\Models\SeQuraOrder;
use SeQura\Core\BusinessLogic\Domain\Order\ProxyContracts\OrderProxyInterface;
use SeQura\Core\BusinessLogic\SeQuraAPI\Order\OrderProxy;
use SeQura\Core\Infrastructure\Http\Exceptions\HttpRequestException;
use SeQura\Core\Infrastructure\Http\HttpClient;
use SeQura\Core\Infrastructure\ORM\Exceptions\RepositoryNotRegisteredException;
use SeQura\Core\Infrastructure\ServiceRegister;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function manifest(): JsonResponse
    {
        $manifest = new Manifest();
        $manifest->paymentMethods = [
            new PaymentMethodInfo('Logeecom Test Payment Method')
        ];
        $manifest->customFields = [
            new CustomField('merchantId', 'text'),
        ];

        return response()->json($manifest->toArray());
    }

    /**
     * @throws InvalidDateException
     * @throws InvalidServiceEndTimeException
     * @throws InvalidQuantityException
     * @throws InvalidDurationException
     * @throws InvalidUrlException
     * @throws InvalidGuiLayoutValueException
     * @throws RepositoryNotRegisteredException
     * @throws InvalidTimestampException
     * @throws InvalidCartItemsException
     * @throws HttpRequestException
     */
    public function payments(Request $request): JsonResponse
    {
        $redirectRequest = RedirectRequest::fromArray($request->post());

        $createdOrder = $this->createOrder($redirectRequest);

        $paymentResponse = new PaymentResponse();
        $paymentResponse->paymentId = $redirectRequest->paymentId;
        $paymentResponse->status = 'undefined';
        $paymentResponse->authorizationId = '456';
        $paymentResponse->paymentUrl = route('getPaymentMethods', ['orderId' => $createdOrder->getReference()]);
        $paymentResponse->tid = $createdOrder->getReference();
        $paymentResponse->nsu = '123';
        $paymentResponse->acquirer = null;

        $callbackUrl = $redirectRequest->callbackUrl;
        $ch = curl_init($callbackUrl);
        $jsonData = json_encode([
            'paymentId' => $redirectRequest->paymentId,
            'status' => 'approved',
            'authorizationId' => '456',
            'nsu' => '123',
            'tid' => $createdOrder->getReference(),
            'acquirer' => null,
        ]);
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-VTEX-API-Is-TestSuite: true',
            'X-VTEX-API-AppKey: ' . config('services.vtex.app_key'),
            'X-VTEX-API-AppToken: ' . config('services.vtex.app_token'),
        ];

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return response()->json($paymentResponse->toArray());
    }

    /**
     * @param Request $request
     *
     * @return View
     *
     * @throws HttpRequestException
     */
    public function getPaymentMethods(Request $request): View
    {
        /** @var OrderProxy $orderProxy */
        $orderProxy = ServiceRegister::getService(OrderProxyInterface::class);

        $paymentMethodsRequest = new GetAvailablePaymentMethodsRequest($request->get('orderId'));
        $paymentMethodCategories = $orderProxy->getAvailablePaymentMethodsInCategories($paymentMethodsRequest);

        return view('payment-methods', [
            'wixMerchantId' => 'logeecom',
            'orderId' => $request->input('orderId'),
            'paymentMethodCategories' => $paymentMethodCategories,
            'hasAvailablePaymentMethods' => true
        ]);
    }

    /**
     * @throws InvalidServiceEndTimeException
     * @throws InvalidDateException
     * @throws InvalidQuantityException
     * @throws InvalidDurationException
     * @throws InvalidUrlException
     * @throws InvalidGuiLayoutValueException
     * @throws RepositoryNotRegisteredException
     * @throws InvalidTimestampException
     * @throws InvalidCartItemsException
     * @throws HttpRequestException
     */
    private function createOrder(RedirectRequest $request): SeQuraOrder
    {
        /** @var OrderTransformerService $orderTransformerService */
        $orderTransformerService = ServiceRegister::getService(OrderTransformerService::CLASS_NAME);
        /** @var OrderProxy $orderProxy */
        $orderProxy = ServiceRegister::getService(OrderProxyInterface::class);

        $orderRequest = $orderTransformerService->transformOrderRequest($request);
        $order = $orderProxy->createOrder($orderRequest);

        /*$repository = RepositoryRegistry::getRepository(SeQuraOrder::class);
        $repository->save($order);*/

        return $order;
    }
}

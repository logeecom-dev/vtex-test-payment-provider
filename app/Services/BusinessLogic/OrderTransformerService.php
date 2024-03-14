<?php

namespace App\Services\BusinessLogic;

use App\Models\Address;
use App\Models\BuyerInfo;
use App\Models\MiniCart;
use App\Models\RedirectRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PDO;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidCartItemsException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidDateException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidDurationException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidGuiLayoutValueException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidQuantityException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidServiceEndTimeException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidTimestampException;
use SeQura\Core\BusinessLogic\Domain\Order\Exceptions\InvalidUrlException;
use SeQura\Core\BusinessLogic\Domain\Order\Models\OrderRequest\CreateOrderRequest;
use SeQura\Core\BusinessLogic\Domain\Order\Models\OrderRequest\Item\ItemType;
use SeQura\Core\Infrastructure\Singleton;

class OrderTransformerService extends Singleton
{
    /**
     * Fully qualified name of this class.
     */
    public const CLASS_NAME = __CLASS__;
    /**
     * Singleton instance of this class.
     *
     * @var static
     */
    protected static $instance;

    /**
     * @throws InvalidServiceEndTimeException
     * @throws InvalidDateException
     * @throws InvalidDurationException
     * @throws InvalidQuantityException
     * @throws InvalidUrlException
     * @throws InvalidGuiLayoutValueException
     * @throws InvalidTimestampException
     * @throws InvalidCartItemsException
     */
    public function transformOrderRequest(RedirectRequest $request): CreateOrderRequest
    {
        return CreateOrderRequest::fromArray([
            'state' => '',
            'merchant' => $this->getMerchant($request),
            'merchant_reference' => [
                'order_ref_1' => $request->orderId,
            ],
            'cart' => $this->getCart($request),
            'delivery_method' => [
                'name' => '',
            ],
            'delivery_address' => $this->getAddress($request->miniCart->shippingAddress),
            'invoice_address' => $this->getAddress($request->miniCart->billingAddress),
            'customer' => $this->getCustomer($request->miniCart->buyer),
            'instore' => [
                'code' => $request->orderId,
            ],
            'gui' => [
                'layout' => 'desktop',
            ],
            'platform' => $this->getPlatform()
        ]);
    }

    /**
     * Returns merchant.
     *
     * @param RedirectRequest $request
     *
     * @return array
     */
    private function getMerchant(RedirectRequest $request): array
    {
        return [
            'id' => 'logeecom',
            'notify_url' => 'https://google.com',
            'notification_parameters' => [
                'test' => 'test',
            ],
            'return_url' => $request->returnUrl,
            'abort_url' => $request->callbackUrl,
            'events_webhook' => [
                'url' => 'https://google.com',
                'parameters' => [
                    'test' => 'test',
                ],
            ]
        ];
    }

    /**
     * Returns cart.
     *
     * @param RedirectRequest $request
     *
     * @return array
     */
    private function getCart(RedirectRequest $request): array
    {
        return [
            'currency' => 'EUR',
            'gift' => false,
            'order_total_with_tax' => $request->value,
            'cart_ref' => $request->paymentId,
            'items' => $this->getOrderItems($request->miniCart)
        ];
    }

    /**
     * Returns order items.
     *
     * @param MiniCart $miniCart
     *
     * @return array
     */
    private function getOrderItems(MiniCart $miniCart): array
    {
        $items = [];

        $orderItems = $miniCart->items;

        foreach ($orderItems as $orderItem) {
            $items[] = [
                'type' => ItemType::TYPE_PRODUCT,
                'reference' => $orderItem->id,
                'name' => $orderItem->name,
                'price_with_tax' => $orderItem->price,
                'quantity' => $orderItem->quantity,
                'total_with_tax' => $orderItem->price * $orderItem->quantity,
                'downloadable' => false,
                'description' => '',
            ];
        }

        return $items;
    }

    /**
     * Returns address.
     *
     * @param Address|null $address
     *
     * @return array
     */
    private function getAddress(?Address $address): array
    {
        if ($address === null) {
            return [];
        }

        return [
            'given_names' => '',
            'surnames' => '',
            'company' => '',
            'address_line_1' => $address->street,
            'address_line_2' => $address->number,
            'postal_code' => '28001',
            'city' => 'Madrid',
            'country_code' => 'ES',
            'phone' => '',
            'state' => $address->state,
        ];
    }

    private function getCustomer(BuyerInfo $buyerInfo): array
    {
        return [
            'given_names' => $buyerInfo->firstName,
            'surnames' => $buyerInfo->lastName,
            'email' => $buyerInfo->email,
            'logged_in' => 'unknown',
            'language_code' => 'es_ES',
            'ip_number' => request()?->ip(),
            'user_agent' => request()?->header('User-Agent'),
            'company' => '',
        ];
    }

    /**
     * Returns a platform.
     *
     * @return array
     */
    private function getPlatform(): array {
        return [
            'name' => 'vtex',
            'version' => '1.0.0',
            'integration_version' => config('version'),
            'uname' => php_uname(),
            'db_name' => Config::get('database.connections.mysql.database'),
            'db_version' => '1.0.0',
            'php_version' => PHP_VERSION
        ];
    }
}

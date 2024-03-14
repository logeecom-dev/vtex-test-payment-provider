<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeQura Wix</title>
    <link href="{{ config('app.url') . '/css/payment-methods.css' }}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="sw-modal">
    <div class="sw-container">
        <div class="sw-logo" >
            <img src="{{ config('app.url') . '/images/logo.svg' }}" alt="SeQura logo"/>
        </div>
        @if($hasAvailablePaymentMethods)
            <div class="sw-box">
                <label class="sw-merchant">MERCHANT</label>
                <label class="sw-total">Total</label>
                <label class="sw-price">
                    {{ number_format($order->getCart()->getOrderTotalWithTax()/100, 2, ',', '') . ' €'}}
                </label>
            </div>
        @endif
        <form class="sw-form" action="{{ config('app.url') . route('submitPaymentMethod', [], false) }}" method="POST">
            @csrf
            <input type="hidden" name="wixMerchantId" value="{{ $wixMerchantId }}"/>
            <input type="hidden" name="orderId" value="{{ $orderId }}" />

            @if($hasAvailablePaymentMethods)
                <div class="sw-categories" required>
                    @foreach ($paymentMethodCategories as $categoryIndex => $paymentMethodCategory)
                        @if(!empty($paymentMethodCategory->getMethods()))
                            <div class="sw-category">
                                <div class="sw-category-title">{{ $paymentMethodCategory->getTitle() }}</div>
                                <div class="sw-category-description">{{ $paymentMethodCategory->getDescription() }}</div>
                                <div class="sw-payment-method-list">
                                    @foreach($paymentMethodCategory->getMethods() as $index => $paymentMethod)
                                        <input type="radio"
                                               id="{{ $paymentMethod->getProduct() }}"
                                               name="paymentMethodCode"
                                               value="{{ $paymentMethod->getProduct() }}"
                                               required
                                               @if($index === 0 && $categoryIndex === array_search(false, array_map(
                                                    static function ($category) {
                                                        return empty($category->getMethods());
                                                    }, $paymentMethodCategories), true))
                                                   checked
                                            @endif
                                        >
                                        <label class="sw-payment-method" for="{{ $paymentMethod->getProduct() }}">
                                            <div class="sw-button-and-title">
                                                <div class="sw-radio-button"></div>
                                                <div class="sw-payment-method-title">
                                                    {{ $paymentMethod->getTitle() }}
                                                </div>
                                            </div>
                                            <div class="sw-payment-method-cost">
                                                {{ $paymentMethod->getCostDescription() ?? 'Sin coste adicinal' }}
                                            </div>
                                        </label>
                                        @if($paymentMethod->getDescription())
                                            <div class="sw-payment-method-description">
                                                {{ $paymentMethod->getDescription() }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="sw-methods-unavailable">
                    {{__('messages.noMethodsAvailable')}}
                </div>
            @endif
            <div class="sw-controls">
                <a
                    href="{{ config('app.url') . route('cancel', ['wixMerchantId' => $wixMerchantId, 'orderId' => $orderId, 'reasonCode' => $hasAvailablePaymentMethods ? 3030 : 2007], false) }}"
                    class="sw-cancel"
                >
                    <img src="{{ config('app.url') . '/images/arrow.svg' }}" alt="Left arrow"/>
                    <label>{{__('labels.back')}}</label>
                </a>
                @if($hasAvailablePaymentMethods)
                    <input class="sw-continue" type="submit" value="{{__('labels.continue')}}">
                @endif
            </div>
        </form>
    </div>
</div>
</body>
</html>

@extends('public.layout')

@section('title', trans('storefront::checkout.checkout'))

@section('content')
    <checkout-create
        :gateways="{{ $gateways }}"
        :countries="{{ json_encode($countries) }}"
        inline-template
    >
        <section class="checkout-wrap">
            <div class="container">
                @include('public.cart.index.steps')

                <form @submit.prevent="placeOrder" @input="errors.clear($event.target.name)">
                    <div class="checkout">
                        <div class="checkout-inner">
                            <div class="checkout-left">
                                <div class="checkout-form">
                                    @include('public.checkout.create.form.account_details')
                                    @include('public.checkout.create.form.billing_details')
                                    @include('public.checkout.create.form.shipping_details')
                                    @include('public.checkout.create.form.order_note')
                                </div>
                            </div>

                            <div class="checkout-right">
                                @include('public.checkout.create.payment')
                                @include('public.checkout.create.coupon')
                            </div>
                        </div>

                        @include('public.checkout.create.order_summary')
                    </div>
                </form>
            </div>
        </section>
    </checkout-create>
@endsection

@push('pre-scripts')
    @if (setting('paypal_enabled'))
        <script src="https://www.paypal.com/sdk/js?client-id={{ setting('paypal_client_id') }}&disable-funding=credit,card"></script>
    @endif

    @if (setting('stripe_enabled'))
        <script src="https://js.stripe.com/v3/"></script>
    @endif
@endpush

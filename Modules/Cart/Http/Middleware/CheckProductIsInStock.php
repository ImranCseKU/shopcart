<?php

namespace Modules\Cart\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;
use Modules\FlashSale\Entities\FlashSale;

class CheckProductIsInStock
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $product = Product::withName()
            ->addSelect('id', 'in_stock', 'manage_stock', 'qty')
            ->where('id', $request->product_id)
            ->firstOrFail();

        if ($product->isOutOfStock()) {
            abort(400, trans('cart::messages.out_of_stock'));
        }

        if (! $product->hasFlashSaleStockFor($request->qty)) {
            $remainingFlashSaleQty = FlashSale::remainingQty($product);

            abort(400, trans('cart::messages.not_have_enough_quantity', ['stock' => $remainingFlashSaleQty]));
        }

        if (! $product->hasStockFor($request->qty)) {
            abort(400, trans('cart::messages.not_have_enough_quantity', ['stock' => $product->qty]));
        }

        return $next($request);
    }
}

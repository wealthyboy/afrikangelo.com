<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Cart;

class CartIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => $this->product,
            'image' => optional($this->product)->image_tn,
            'quantity' => $this->quantity,
            'price' => Cart::ConvertCurrencyRate($this->price),
            'currency' => optional($this->product)->currency,
            'title' => optional($this->product)->title,
        ];
    }
}

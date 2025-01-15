<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'product_name' => $this->product->name ?? 'N/A',
            'order_status' => $this->order->status,
            'estimated_delivery_date' => $this->order->estimated_delivery_date,
            'tracking_number' => $this->order->tracking_number,
            'shipment_status' => $this->order->shipment_status,
            'user_name' => $this->order->userAddress->user->name ?? 'N/A',
            'updated_at' => $this->order->updated_at,
        ];
    }
}

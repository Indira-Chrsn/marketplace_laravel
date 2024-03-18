<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public $isDetail;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category->name,
            'brand' => $this->brand->name
        ];

        if ($this->isDetail) {
            $data['created_at'] = $this->created_at;
            $data['updated_at'] = $this->updated_at;
            $data['deleted_at'] = $this->deleted_at;
        }

        return $data;
    }

    public function withDetail()
    {
        $this->isDetail = true;

        return $this;
    }
}

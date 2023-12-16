<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'sku' => $this->sku,
            'thumbnail_image' => asset('storage/'. $this->thumbnail_image),
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'tags' => json_decode($this->tags),
            'highlights' => json_decode($this->highlights),
            'meta_title'=> $this->meta_title,
            'meta_keywords' => json_decode($this->meta_keywords),
            'meta_description' => $this->meta_description,
            'price_original' => $this->price_original,
            'price_discounted' => $this->price_discounted,
            'tax_percentage' => $this->tax_percentage,
            'availability' => $this->availability,
            'parent_category' => [
                'id' => $this->parent_category->id,
                'name' => $this->parent_category->name,
                'slug' => $this->parent_category->slug
            ],
            'child_category' => [
                'id' => $this->child_category->id,
                'name' => $this->child_category->name,
                'slug' => $this->child_category->slug
            ],
            'sizes' => $this->sizes,
            'media' => $this->media,
            'variants' => $this->variants
        ];
        
    }
}

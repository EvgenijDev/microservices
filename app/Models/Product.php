<?php

namespace App\Models;

use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Searchable;

    public $fillable = ['name', 'price', 'stock', 'description', 'image_path', 'thumb_path'];

    public function toSearchArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at->toIso8601String(),
            'stock' => $this->stock,
            'image_path' => $this->image_path,
            'thumb_path' => $this->thumb_path,
            'price' => $this->price,
        ];
    }
}

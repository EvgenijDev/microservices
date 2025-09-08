<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
        $this->onQueue('media');
    }

    public function handle(): void
    {
        Log::info('Generating thumbnail for product', ['product_id' => $this->productId]);

        $product = Product::find($this->productId);
        if (!$product || !$product->image_path) {
            return;
        }

        // Примитивная "генерация" превью: скопируем файл в thumbs/
        $source = $product->image_path; // disk: public
        $thumb = str_replace('products/', 'products/thumbs/', $source);

        try {
            $stream = Storage::disk('public')->readStream($source);
            Storage::disk('public')->put($thumb, $stream);
            $product->thumb_path = $thumb;
            $product->save();
            Log::info('Thumbnail created', ['product_id' => $this->productId, 'thumb' => $thumb]);
        } catch (\Throwable $e) {
            Log::error('Thumbnail generation failed', ['product_id' => $this->productId, 'error' => $e->getMessage()]);
        }
    }
}



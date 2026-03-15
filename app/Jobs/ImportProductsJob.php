<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $filePath;
    public string $importId;
    public function __construct(string $filePath, string $importId)
    {
        $this->filePath = $filePath;
        $this->importId = $importId;
        $this->onConnection('redis');
        $this->onQueue('imports');
    }

    public function handle(): void
    {
        $fullPath = Storage::disk('local')->path($this->filePath);
        try {
            Excel::import(new ProductsImport, $fullPath);
            Log::info('Import completed, writing success to cache', ['import_id' => $this->importId]);
            Cache::put('import_errors:' . $this->importId, [['status' => 'success']], now()->addHour());        } catch (ValidationException $e) {
            $errors = [];
            foreach ($e->failures() as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'field' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values()
                ];
            }
            Cache::put('import_errors:' . $this->importId, $errors, now()->addHour());
        } catch (\Throwable $e) {
            Log::error('ImportProductsJob failed', ['import_id' => $this->importId, 'error' => $e->getMessage()]);
            Cache::put('import_errors:' . $this->importId, [['status' => 'error', 'message' => $e->getMessage()]], now()->addHour());
        } finally {
            Storage::disk('local')->delete($this->filePath);
        }
    }
}
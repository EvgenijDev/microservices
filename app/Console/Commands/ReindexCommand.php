<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex {model?}';
    protected $description = 'Reindex all searchable models';

    public function handle(ElasticsearchService $elasticsearch)
    {
        $model = $this->argument('model') ?? Product::class;

        $this->info('Indexing all models...');

        $model::chunk(100, function ($models) use ($elasticsearch) {
            foreach ($models as $model) {
                $elasticsearch->indexDocument(
                    $model->getSearchIndex(),
                    $model->toSearchArray(),
                    $model->getKey()
                );
            }
        });

        $this->info('Done!');
    }
}
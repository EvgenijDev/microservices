<?php

namespace App\Models\Traits;

use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\App;

trait Searchable
{
    public static function bootSearchable()
    {
        static::created(function ($model) {
            $model->addToIndex();
        });

        static::updated(function ($model) {
            $model->updateInIndex();
        });

        static::deleted(function ($model) {
            $model->removeFromIndex();
        });
    }

    public function addToIndex()
    {
        if (!$this->shouldBeSearchable()) {
            return;
        }

        $elasticsearch = App::make(ElasticsearchService::class);
        $elasticsearch->indexDocument(
            $this->getSearchIndex(),
            $this->toSearchArray(),
            $this->getKey()
        );
    }

    public function updateInIndex()
    {
        $this->addToIndex();
    }

    public function removeFromIndex()
    {
        $elasticsearch = App::make(ElasticsearchService::class);
        $elasticsearch->deleteDocument(
            $this->getSearchIndex(),
            $this->getKey()
        );
    }

    public function shouldBeSearchable()
    {
        return true;
    }

    public function getSearchIndex()
    {
        return $this->getTable();
    }

    public function toSearchArray()
    {
        return $this->toArray();
    }

    public static function search($query = null, $inStock = false)
    {
        $elasticsearch = App::make(ElasticsearchService::class);
    
        $boolQuery = [
            'must'   => [],
            'filter' => [],
            'should' => [],
        ];
    
        if ($query) {
            $boolQuery['should'][] = [
                'multi_match' => [
                    'query'     => (string) $query,
                    'fields'    => ['name^3', 'description^1'],
                    'fuzziness' => 'AUTO',
                ],
            ];
            $boolQuery['should'][] = [
                'wildcard' => [
                    'name' => [
                        'value' => "*" . strtolower($query) . "*",
                        'boost' => 2.0
                    ]
                ]
            ];
    
            if (is_numeric($query)) {
                $boolQuery['should'][] = [
                    'term' => ['id' => (int) $query],
                ];
            }
    
            $boolQuery['minimum_should_match'] = 1;
        }
    
        if ($inStock) {
            $boolQuery['filter'][] = [
                'range' => [
                    'stock' => ['gt' => 0],
                ],
            ];
        }
    
        $searchQuery = [
            'bool' => $boolQuery,
        ];
    
        // для отладки можно залогировать
        \Log::info('ES query', $searchQuery);
    
        return $elasticsearch->search(static::make()->getSearchIndex(), $searchQuery);
    }
    
    
}

<?php

namespace App\Services;

use Elasticsearch\Client;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function indexDocument(string $index, array $document, string $id = null)
    {
        try {
            $params = [
                'index' => $index,
                'body'  => $document
            ];

            if ($id) {
                $params['id'] = $id;
            }

            return $this->client->index($params);
        } catch (\Exception $e) {
            Log::error('Elasticsearch index error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function search(string $index, array $query)
    {
        try {
            $params = [
                'index' => $index,
                'body'  => [
                    'query' => $query
                ]
            ];

            return $this->client->search($params);
        } catch (\Exception $e) {
            Log::error('Elasticsearch search error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteDocument(string $index, string $id)
    {
        try {
            return $this->client->delete([
                'index' => $index,
                'id'    => $id
            ]);
        } catch (\Exception $e) {
            Log::error('Elasticsearch delete error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createIndex(string $index, array $mappings = [])
    {
        try {
            $params = [
                'index' => $index
            ];

            if (!empty($mappings)) {
                $params['body'] = ['mappings' => $mappings];
            }

            return $this->client->indices()->create($params);
        } catch (\Exception $e) {
            Log::error('Elasticsearch create index error: ' . $e->getMessage());
            throw $e;
        }
    }
}
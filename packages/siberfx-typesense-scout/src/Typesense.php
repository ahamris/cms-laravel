<?php

namespace Siberfx\Typesense;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Config;
use Laravel\Scout\Builder;
use Laravel\Scout\EngineManager;
use Siberfx\Typesense\Classes\TypesenseDocumentIndexResponse;
use Siberfx\Typesense\Engines\TypesenseEngine;
use Siberfx\Typesense\Mixin\BuilderMixin;
use Typesense\Client;
use Typesense\Collection;
use Typesense\Document;
use Typesense\Exceptions\ConfigError;
use Typesense\Exceptions\ObjectNotFound;
use Typesense\Exceptions\TypesenseClientError;

/**
 * Class Typesense
 *
 * @date    4/5/20
 *
 * @author  Selim Görmüş <info@siberfx.com>
 */
class Typesense
{
    private Client $client;

    /**
     * Typesense constructor.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @throws BindingResolutionException
     * @throws \ReflectionException
     * @throws ConfigError
     */
    public function setScopedApiKey($key): void
    {
        $config = Config::get('scout.typesense.client-settings');
        $config['api_key'] = $key;
        $client = new Client($config);

        app()[EngineManager::class]->extend('typesense', function () use ($client) {
            return new TypesenseEngine(new Typesense($client));
        });
        Builder::mixin(app()->make(BuilderMixin::class));
    }

    /**
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    private function getOrCreateCollectionFromModel($model): Collection
    {
        $index = $this->client->getCollections()->{$model->searchableAs()};

        try {
            $index->retrieve();

            return $index;
        } catch (ObjectNotFound $exception) {
            $this->client->getCollections()
                ->create($model->getCollectionSchema());

            return $this->client->getCollections()->{$model->searchableAs()};
        }
    }

    /**
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function getCollectionIndex($model): Collection
    {
        return $this->getOrCreateCollectionFromModel($model);
    }

    /**
     * @throws ObjectNotFound
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function upsertDocument(Collection $collectionIndex, $array): TypesenseDocumentIndexResponse
    {
        /**
         * @var $document Document
         */
        $document = $collectionIndex->getDocuments()[$array['id']];

        try {
            $document->retrieve();
            $document->delete();

            return new TypesenseDocumentIndexResponse(200, true, null, $collectionIndex->getDocuments()
                ->create($array));
        } catch (ObjectNotFound) {
            return new TypesenseDocumentIndexResponse(200, true, null, $collectionIndex->getDocuments()
                ->create($array));
        }
    }

    /**
     * @throws ObjectNotFound
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function deleteDocument(Collection $collectionIndex, $modelId): array
    {
        /**
         * @var $document Document
         */
        $document = $collectionIndex->getDocuments()[(string) $modelId];

        try {
            $document->retrieve();

            return $document->delete();
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function deleteDocuments(Collection $collectionIndex, array $query): array
    {
        return $collectionIndex->getDocuments()
            ->delete($query);
    }

    /**
     * @throws \JsonException
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function importDocuments(Collection $collectionIndex, $documents, string $action = 'upsert'): \Illuminate\Support\Collection
    {
        $importedDocuments = $collectionIndex->getDocuments()
            ->import($documents, ['action' => $action]);

        $result = [];
        foreach ($importedDocuments as $importedDocument) {
            if (! $importedDocument['success']) {
                throw new TypesenseClientError("Error importing document: {$importedDocument['error']}");
            }

            $result[] = new TypesenseDocumentIndexResponse($importedDocument['code'] ?? 0, $importedDocument['success'], $importedDocument['error'] ?? null, json_decode($importedDocument['document'] ?? '[]', true, 512, JSON_THROW_ON_ERROR));
        }

        return collect($result);
    }

    /**
     * @throws ObjectNotFound
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function deleteCollection(string $collectionName): array
    {
        return $this->client->getCollections()->{$collectionName}->delete();
    }

    /**
     * @throws TypesenseClientError
     * @throws \Http\Client\Exception
     */
    public function multiSearch(array $searchRequests, array $commonSearchParams): array
    {
        return $this->client->multiSearch->perform($searchRequests, $commonSearchParams);
    }
}

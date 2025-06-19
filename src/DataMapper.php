<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage;

use Illuminate\Http\Request;
use Wundii\DataMapper\DataMapper as BaseDataMapper;
use Wundii\DataMapper\LaravelPackage\Enum\MapStatusEnum;

class DataMapper extends BaseDataMapper
{
    private ?string $errorMessage = null;
    private MapStatusEnum $mapStatusEnum = MapStatusEnum::SUCCESS;

    public function getMapStatusEnum(): MapStatusEnum
    {
        return $this->mapStatusEnum;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * Map Laravel Request to object
     */
    public function request(Request $request, string $className): object
    {
        $contentType = $request->header('Content-Type', '');
        $content = $request->getContent();

        try {
            if (str_contains($contentType, 'application/json')) {
                return $this->dataMapper->json($content, $className);
            }

            if (str_contains($contentType, 'application/xml') || str_contains($contentType, 'text/xml')) {
                return $this->dataMapper->xml($content, $className);
            }

            if (str_contains($contentType, 'application/x-yaml') || str_contains($contentType, 'text/yaml')) {
                return $this->dataMapper->yaml($content, $className);
            }

            // Fallback zu JSON
            return $this->dataMapper->json($content, $className);
        } catch (\Exception $e) {
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            $this->errorMessage = $e->getMessage();
            throw $e;
        }
    }

    /**
     * Try to map Laravel Request to object without throwing exceptions
     */
    public function tryRequest(Request $request, string $className): ?object
    {
        try {
            return $this->request($request, $className);
        } catch (\Exception $e) {
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            $this->errorMessage = $e->getMessage();
            return null;
        }
    }

    /**
     * Delegate alle anderen Methoden an den BaseDataMapper
     */
    public function __call(string $method, array $arguments): mixed
    {
        try {
            $result = $this->dataMapper->$method(...$arguments);
            $this->mapStatusEnum = MapStatusEnum::SUCCESS;
            $this->errorMessage = null;
            return $result;
        } catch (\Exception $e) {
            $this->mapStatusEnum = MapStatusEnum::ERROR;
            $this->errorMessage = $e->getMessage();
            throw $e;
        }
    }
}

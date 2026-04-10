<?php

declare(strict_types=1);

namespace Seven\TYPO3\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Model extends AbstractEntity
{
    protected string $config = '';
    protected int $created = 0;
    protected ?string $response = null;
    protected string $type = '';

    public function __construct()
    {
        $this->created = time();
    }

    public function getCreated(): int
    {
        return $this->created;
    }

    public function getConfigArray(): array
    {
        return (array)json_decode($this->getConfig(), true);
    }

    public function getConfig(): string
    {
        return $this->config;
    }

    public function setConfig(string|array|object $config): void
    {
        $this->config = is_string($config) ? $config : json_encode($config);
    }

    public function getResponseArray(): ?array
    {
        $response = $this->getResponse();
        if ($response === null) {
            return null;
        }

        return (array)json_decode($response, true);
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): void
    {
        $this->response = is_string($response) ? $response : json_encode($response);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}

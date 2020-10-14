<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Message extends AbstractEntity {
    /** @var string $config */
    protected $config;

    /** @var int $created */
    protected $created;

    /** @var string|null $response */
    protected $response;

    /** @var string $type */
    protected $type;

    public function __construct() {
        $this->created = time();
    }

    /** @return int */
    public function getCreated(): int {
        return $this->created;
    }

    /** @return array */
    public function getConfigArray(): array {
        return $this->toArray($this->getConfig());
    }

    private function toArray(string $obj): array {
        return (array)json_decode($obj);
    }

    /** @return string */
    public function getConfig(): string {
        return $this->config;
    }

    /** @param string|array|object $config */
    public function setConfig($config): void {
        $this->config = $this->stringify($config);
    }

    /**
     * @param mixed $arg
     * @return string
     */
    private function stringify($arg): string {
        return is_string($arg) ? $arg : (string)json_encode($arg);
    }

    /** @return array|null */
    public function getResponseArray(): ?array {
        return $this->toArray($this->getResponse());
    }

    /** @return string|null */
    public function getResponse(): ?string {
        return $this->response;
    }

    /** @param string $response */
    public function setResponse(string $response): void {
        $this->response = $this->stringify($response);
    }

    /** @return string */
    public function getType(): string {
        return $this->type;
    }

    /** @param string $type */
    public function setType(string $type): void {
        $this->type = $type;
    }
}

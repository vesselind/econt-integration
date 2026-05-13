<?php

declare(strict_types=1);

namespace Econt\EcontApi\Configuration;

class EcontConfiguration
{
    public const DEFAULT_DEMO_URL = 'https://demo.econt.com/ee/services/';
    public const DEFAULT_PRODUCTION_URL = 'https://ee.econt.com/services/';
    public const DEFAULT_TIMEOUT = 30;
    public const DEFAULT_LANGUAGE = 'bg';

    private string $username;
    private string $password;
    private string $baseUrl;
    private int $timeout;
    private string $language;

    public function __construct(
        string $username,
        string $password,
        string $baseUrl = self::DEFAULT_DEMO_URL,
        int $timeout = self::DEFAULT_TIMEOUT,
        string $language = self::DEFAULT_LANGUAGE
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->baseUrl = rtrim($baseUrl, '/') . '/';
        $this->timeout = $timeout;
        $this->language = $language;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function withUsername(string $username): self
    {
        $clone = clone $this;
        $clone->username = $username;
        return $clone;
    }

    public function withPassword(string $password): self
    {
        $clone = clone $this;
        $clone->password = $password;
        return $clone;
    }

    public function withBaseUrl(string $baseUrl): self
    {
        $clone = clone $this;
        $clone->baseUrl = rtrim($baseUrl, '/') . '/';
        return $clone;
    }

    public function withTimeout(int $timeout): self
    {
        $clone = clone $this;
        $clone->timeout = $timeout;
        return $clone;
    }

    public function withLanguage(string $language): self
    {
        $clone = clone $this;
        $clone->language = $language;
        return $clone;
    }

    public static function demo(): self
    {
        return new self(
            'iasp-dev',
            '1Asp-dev',
            self::DEFAULT_DEMO_URL,
            self::DEFAULT_TIMEOUT,
            self::DEFAULT_LANGUAGE
        );
    }

    public static function production(string $username, string $password): self
    {
        return new self(
            $username,
            $password,
            self::DEFAULT_PRODUCTION_URL,
            self::DEFAULT_TIMEOUT,
            self::DEFAULT_LANGUAGE
        );
    }
}
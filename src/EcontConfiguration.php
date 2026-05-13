<?php

declare(strict_types=1);

namespace Econt\EcontApi;

/**
 * Configuration DTO for the Econt API client.
 */
class EcontConfiguration
{
    public const DEMO_URL = 'https://demo.econt.com/ee/services/';
    public const PRODUCTION_URL = 'https://ee.econt.com/services/';

    public function __construct(
        private readonly string $username,
        private readonly string $password,
        private readonly string $baseUrl = self::DEMO_URL,
        private readonly int $timeout = 30,
        private readonly string $language = 'en',
    ) {
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
        return rtrim($this->baseUrl, '/') . '/';
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Creates a configuration instance pre-configured for the Econt demo/test endpoint
     * using the official demo credentials.
     */
    public static function forDemo(
        string $username = 'iasp-dev',
        string $password = '1Asp-dev',
        int $timeout = 30,
        string $language = 'en',
    ): self {
        return new self(
            username: $username,
            password: $password,
            baseUrl: self::DEMO_URL,
            timeout: $timeout,
            language: $language,
        );
    }
}

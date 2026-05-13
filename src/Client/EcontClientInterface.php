<?php

declare(strict_types=1);

namespace Econt\EcontApi\Client;

use Econt\EcontApi\Configuration\EcontConfiguration;
use Econt\EcontApi\Model\Response\EcontResponse;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Serializer\SerializerInterface;

interface EcontClientInterface
{
    public function request(string $service, array $data = []): EcontResponse;

    public function getConfiguration(): EcontConfiguration;

    public function getSerializer(): SerializerInterface;
}
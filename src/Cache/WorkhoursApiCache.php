<?php

declare(strict_types=1);

namespace App\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Marshaller\MarshallerInterface;

class WorkhoursApiCache extends FilesystemAdapter
{
    public function __construct(
        string $namespace = '',
        int $defaultLifetime = 0,
        string $directory = null,
        MarshallerInterface $marshaller = null
    ) {
        parent::__construct($namespace, $defaultLifetime, $directory, $marshaller);
    }
}
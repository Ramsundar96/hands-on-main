<?php

declare(strict_types=1);

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTestCase extends WebTestCase
{
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(
            server: [
                'CONTENT_TYPE' => 'application/json',
            ]
        );
    }
}

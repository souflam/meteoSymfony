<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\MeteoService;
use App\Service\ContainerParametersHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MeteoTest extends KernelTestCase {

    public function testGetQuery() {
        self::bootKernel();
        $container = self::$container;

        $meteoService = $container->get(MeteoService::class);
        $exepted = [
            'location' => 'rabat',
            'u' => 'c',
            'format' => 'json',
        ];

        $result = $meteoService->getQuery('rabat');

        $this->assertEquals($exepted, $result);
    }
}
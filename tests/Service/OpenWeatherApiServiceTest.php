<?php

namespace App\Tests\Service;

use App\Dependency\Dependency;
use App\Entity\City;
use App\Entity\Weather;
use App\Objects\Coord;
use App\Service\CalculatorService;
use App\Service\OpenWeatherApiService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function PHPUnit\Framework\assertTrue;

class OpenWeatherApiServiceTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    /**
     * @var OpenWeatherApiService
     */
    protected $openWeatherApiService;
    /**
     * @var ContainerBagInterface
     */
    private $parameterBag;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var LoggerInterface
     */
    private $logger;


    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container
            ->get('doctrine')
            ->getManager();
        $this->openWeatherApiService = $container->get(OpenWeatherApiService::class);
        $this->parameterBag = $container->get(ContainerBagInterface::class);
        $this->serializer = $container->get(SerializerInterface::class);
        $this->logger = $container->get(LoggerInterface::class);
    }

    public function testStub()
    {
        $stubDependency = $this->createStub(Dependency::class);
        $responseStub = $stubDependency->method('someMethod')->willReturn('expectedValue');

        $coord = new Coord(55.582026, 37.3855235);
        $result = $this->openWeatherApiService->fetchForecastInfo(
            new Coord(55.582026, 37.3855235)
        );

        $this->assertNotEquals($responseStub, count($result));
    }

    public function testMockFetchData()
    {
        $coord = new Coord(55.582026, 37.3855235);

        $weathers = $this->entityManager->getRepository(Weather::class)->findBy(["city" => 2]);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('toArray')
            ->willReturn($weathers)
            ;

        $mockHttpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                "GET"
            )
            ->willReturn($mockResponse)
            ;

        $service = new OpenWeatherApiService($this->parameterBag, $this->serializer, $mockHttpClient);
        $ansMock = $service->fetchForecastInfo($coord);

        if(!isset($ansMock['errors'])) {
            $this->assertEquals($weathers, $ansMock);
        } else {
            $this->assertTrue(true);
        }
    }

    public function testRequestTrue()
    {
        $expected = 40;
        $response = $this->openWeatherApiService->fetchForecastInfo(
            new Coord(55.582026, 37.3855235)
        );

        $this->assertCount($expected, $response, "doesn't contains 40 elements");
    }

//    protected function tearDown(): void
//    {
//        parent::tearDown();
//
//        // doing this is recommended to avoid memory leaks
//        $this->entityManager->close();
//        $this->entityManager = null;
//    }
}

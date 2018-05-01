<?php

namespace TiendaNube\Checkout\Service\Shipping;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use TiendaNube\Checkout\Model\Address;
use TiendaNube\Checkout\Model\Store;
use TiendaNube\Checkout\Service\Store\StoreServiceInterface;
use TiendaNube\Config\ConfigInterface;
use TiendaNube\Exceptions\HttpRequestException;
use TiendaNube\Http\ClientInterface;
use TiendaNube\Http\Code;

class AddressServiceTest extends TestCase
{
    public function testBetaTesterGetAddressByZipcodeWithSuccessService()
    {
        // expected address
        $address = [
            'address' => 'Avenida da França',
            'neighborhood' => 'Comércio',
            'city' => 'Salvador',
            'state' => 'BA',
        ];

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(true);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking response
        $response = $this->getResponseInstance(Code::OK, $this->getServiceArrayResponse());

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        $client->method('url')->willReturn($client);
        $client->method('headers')->willReturn($client);
        $client->method('verb')->willReturn($client);
        $client->method('send')->willReturn($response);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // test
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNotNull($result);
        $this->assertEquals($address, $result);
    }

    public function testBetaTesterGetAddressByZipcodeWithNotFoundService()
    {
        // mocking pdo
        $pdo = $this->createMock(\PDO::class);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(true);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking response
        $response = $this->getResponseInstance(Code::NOT_FOUND, null);

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        $client->method('url')->willReturn($client);
        $client->method('headers')->willReturn($client);
        $client->method('verb')->willReturn($client);
        $client->method('send')->willReturn($response);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // test
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertEquals(null, $result);
    }

    public function testBetaTesterGetAddressByZipcodeWithErrorService()
    {
        // mocking pdo
        $pdo = $this->createMock(\PDO::class);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(true);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking response
        $response = $this->getResponseInstance(Code::ERROR, null);

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        $client->method('url')->willReturn($client);
        $client->method('headers')->willReturn($client);
        $client->method('verb')->willReturn($client);
        $client->method('send')->willReturn($response);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // test
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertEquals(null, $result);
    }

    public function testNotBetaTesterGetExistentAddressByZipcode()
    {
        // expected address
        $address = [
            'address' => 'Avenida da França',
            'neighborhood' => 'Comércio',
            'city' => 'Salvador',
            'state' => 'BA',
        ];

        // mocking statement
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('rowCount')->willReturn(1);
        $stmt->method('fetch')->willReturn($address);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(false);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNotNull($result);
        $this->assertEquals($address, $result);
    }

    public function testNotBetaTesterGetNonExistentAddressByZipcode()
    {
        // mocking statement
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('rowCount')->willReturn(0);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(false);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // testing
        $result = $service->getAddressByZip('40010001');

        // asserts
        $this->assertNull($result);
    }

    public function testGetAddressByZipcodeWithHttpClientException()
    {
        // mocking pdo
        $pdo = $this->createMock(\PDO::class);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(true);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        $client->method('url')->willReturn($client);
        $client->method('headers')->willReturn($client);
        $client->method('verb')->willReturn($client);
        $client->method('send')->willThrowException(new HttpRequestException('An error occurred'));

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNull($result);
    }

    public function testGetAddressByZipcodeWithPdoException()
    {
        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willThrowException(new \PDOException('An error occurred'));

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willReturn(false);

        // mocking config
        $config = $this->getConfigInstance();

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNull($result);
    }

    public function testGetAddressByZipcodeWithException()
    {
        // mocking pdo
        $pdo = $this->createMock(\PDO::class);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking store service
        $storeService = $this->createMock(StoreServiceInterface::class);
        $store = $this->createMock(Store::class);

        $storeService->method('getCurrentStore')->willReturn($store);
        $store->method('isBetaTester')->willThrowException(new \Exception('An error occurred'));

        // mocking config
        $config = $this->getConfigInstance();

        // mocking http client
        $client = $this->createMock(ClientInterface::class);

        // creating address model
        $addressModel = new Address($pdo);

        // creating service
        $service = new AddressService($logger, $storeService, $config, $client, $addressModel);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNull($result);
    }

    private function getResponseInstance(string $status, ?array $body)
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn(json_encode($body ?? []));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($status);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }

    private function getServiceArrayResponse(): array
    {
        return [
            'altitude' => 7.0,
            'cep' => '40010000',
            'latitude' => '-12.967192',
            'longitude' => '-38.5101976',
            'address' => 'Avenida da França',
            'neighborhood' => 'Comércio',
            'city' => [
                'ddd' => 71,
                'ibge' => '2927408',
                'name' => 'Salvador',
            ],
            'state' => [
                'acronym' => 'BA'
            ]
        ];
    }

    private function getConfigInstance()
    {
        $config = $this->createMock(ConfigInterface::class);

        $config->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($key){
                if($key == 'services.base_url'){
                    return 'https://shipping.tiendanube.com/v1/';
                }

                if($key == 'services.address'){
                    return [
                        'method'        => \TiendaNube\Http\Verbs::GET,
                        'content_type'  => 'application/json',
                        'endpoint'      => 'address/{zip}'
                    ];
                }

                return null;
            }));

        return $config;
    }
}

<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Service\Shipping;

use Psr\Log\LoggerInterface;
use TiendaNube\Checkout\Model\Address;
use TiendaNube\Checkout\Service\Store\StoreServiceInterface;
use TiendaNube\Config\Config;
use TiendaNube\Config\ConfigInterface;
use TiendaNube\Exceptions\HttpRequestException;
use TiendaNube\Http\ClientInterface;
use TiendaNube\Http\Code;

/**
 * Class AddressService
 *
 * @package TiendaNube\Checkout\Service\Shipping
 */
class AddressService
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Current store instance
     *
     * @var \TiendaNube\Checkout\Model\Store
     */
    private $store;

    /**
     * Config manipulation class
     *
     * @var ConfigInterface
     */
    private $config;

    /**
     * Http client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * Address Model
     *
     * @var Address
     */
    private $address;

    /**
     * AddressService constructor.
     *
     * @param LoggerInterface $logger
     * @param StoreServiceInterface $storeService
     * @param ConfigInterface $config
     * @param ClientInterface $client
     * @param Address $address
     */
    public function __construct(LoggerInterface $logger, StoreServiceInterface $storeService, ConfigInterface $config, ClientInterface $client, Address $address)
    {
        $this->logger = $logger;
        $this->store = $storeService->getCurrentStore();
        $this->config = $config;
        $this->client = $client;
        $this->address = $address;
    }

    /**
     * Get an address by its zipcode (CEP)
     *
     * The expected return format is an array like:
     * [
     *      "address" => "Avenida da França",
     *      "neighborhood" => "Comércio",
     *      "city" => "Salvador",
     *      "state" => "BA"
     * ]
     * or false when not found.
     *
     * @param string $zip
     * @return array|null
     */
    public function getAddressByZip(string $zip): ?array
    {
        try {
            if(!$this->store->isBetaTester()){
                return $this->databaseRetriveByZip($zip);
            }

            return $this->serviceRetrieveByZip($zip);
        } catch (\PDOException $e) {
            $this->logger->error(
                'An error occurred at try to fetch the address from the database, exception with message was caught: ' .
                $e->getMessage()
            );

            return null;
        } catch(HttpRequestException $e){
            $this->logger->error(
                'An error occurred at try to fetch the address from the API, exception with message was caught: ' .
                $e->getMessage()
            );

            return null;
        } catch (\Exception $e) {
            $this->logger->error(
                'An error occurred at try to fetch the address, exception with message was caught: ' .
                $e->getMessage()
            );

            return null;
        }
    }

    /**
     * Get the address from database using the zip code
     *
     * @param string $zip
     * @return array|null
     */
    private function databaseRetriveByZip(string $zip): ?array
    {
        $this->logger->debug('Getting address for the zipcode [' . $zip . '] from database');

        return $this->address->find($zip, 'zipcode');
    }

    /**
     * Get the address from API using the zip code
     *
     * @param string $zip
     * @return array|null
     * @throws HttpRequestException
     */
    private function serviceRetrieveByZip(string $zip): ?array
    {
        $this->logger->debug('Getting address for the zipcode [' . $zip . '] from API');

        $baseUrl = $this->config->get('services.base_url');
        $configs = $this->config->get('services.address');

        $response = $this->client
                ->url("{$baseUrl}{$configs['endpoint']}", ['zip' => $zip])
                ->headers([
                    'Content-Type' => $configs['content_type'],
                    'Authentication bearer' => 'YouShallNotPass'
                ])
                ->method($configs['method'])
                ->send();

        if($response->getStatusCode() != Code::OK){
            return null;
        }

        $content = json_decode($response->getBody()->getContents(), true);
        return [
            'address'       => $content['address'],
            'neighborhood'  => $content['neighborhood'],
            'city'          => $content['city']['name'],
            'state'         => $content['state']['acronym'],
        ];
    }
}

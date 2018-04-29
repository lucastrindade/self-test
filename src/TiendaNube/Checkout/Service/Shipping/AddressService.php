<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Service\Shipping;

use Psr\Log\LoggerInterface;
use TiendaNube\Checkout\Service\Store\StoreServiceInterface;
use TiendaNube\Config\Config;
use TiendaNube\Config\ConfigInterface;

/**
 * Class AddressService
 *
 * @package TiendaNube\Checkout\Service\Shipping
 */
class AddressService
{
    /**
     * The database connection link
     *
     * @var \PDO
     */
    private $connection;

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
     * AddressService constructor.
     * @param \PDO $pdo
     * @param LoggerInterface $logger
     * @param StoreServiceInterface $storeService
     * @param ConfigInterface $config
     */
    public function __construct(\PDO $pdo, LoggerInterface $logger, StoreServiceInterface $storeService, ConfigInterface $config)
    {
        $this->connection = $pdo;
        $this->logger = $logger;
        $this->store = $storeService->getCurrentStore();
        $this->config = $config;
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
        } catch (\PDOException $ex) {
            $this->logger->error(
                'An error occurred at try to fetch the address from the database, exception with message was caught: ' .
                $ex->getMessage()
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

        // =========== ALTERAR PARA CONSULTAR DA MODEL OU REPOSITORY ===========
        // =========== ALTERAR PARA CONSULTAR DA MODEL OU REPOSITORY ===========
        // =========== ALTERAR PARA CONSULTAR DA MODEL OU REPOSITORY ===========
        // =========== ALTERAR PARA CONSULTAR DA MODEL OU REPOSITORY ===========
        // getting the address from database
        $stmt = $this->connection->prepare('SELECT * FROM `addresses` WHERE `zipcode` = ?');
        $stmt->execute([$zip]);

        // checking if the address exists
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        return null;
    }

    /**
     *
     * @param string $zip
     * @return array|null
     */
    private function serviceRetrieveByZip(string $zip): ?array
    {
        $this->logger->debug('Getting address for the zipcode [' . $zip . '] from API');

        $baseUrl = $this->config->get('services.base_url');
        $configs = $this->config->get('services.address');

        return null;
    }
}

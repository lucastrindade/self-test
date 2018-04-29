<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Model;

use PDO;

/**
 * Class AbstractModel
 *
 * @package TiendaNube\Checkout\Model
 */
abstract class AbstractModel
{
    /**
     * The database connection link
     *
     * @var \PDO
     */
    private $connection;

    /**
     * AbstractModel constructor.
     *
     * @param PDO $pdo
     * @throws \Exception
     */
    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;

        if(empty($this->table)){
            throw new \Exception('Define a table for the model');
        }
    }

    /**
     * Find data by a given key and value
     * If key is null, value is the primary key
     *
     * @param $value
     * @param null|string $key
     * @return array|null
     */
    public function find($value, ?string $key = null): ?array
    {
        $key = $key ?? $this->primary;

        $sql = "SELECT * FROM {$this->table} WHERE {$key} = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$value]);

        // checking if the address exists
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        return null;
    }
}

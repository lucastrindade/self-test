<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Model;

/**
 * Class Store
 *
 * @package TiendaNube\Checkout\Model
 */
class Address extends AbstractModel
{
    /**
     * Table of the model
     *
     * @var string
     */
    protected $table = 'addresses';

    /**
     * Primary key of the table
     *
     * @var string
     */
    protected $primary = 'id';
}

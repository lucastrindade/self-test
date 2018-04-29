<?php

declare(strict_types=1);

namespace TiendaNube\Config;

/**
 * Interface ConfigInterface
 *
 * @package TiendaNube\Config
 */
interface ConfigInterface
{
    /**
     * Get the value of requested config
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
}

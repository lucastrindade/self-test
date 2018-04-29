<?php

declare(strict_types=1);

namespace TiendaNube\Http;

/**
 * Interface ClientInterface
 *
 * @package TiendaNube\Http
 */
interface ClientInterface
{
    public function url();

    public function params();

    public function request();
}

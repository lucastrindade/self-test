<?php

declare(strict_types=1);

namespace TiendaNube\Http;

use Psr\Http\Message\ResponseInterface;
use TiendaNube\Exceptions\HttpRequestException;

/**
 * Interface ClientInterface
 *
 * @package TiendaNube\Http
 */
interface ClientInterface
{
    /**
     * Set the url to be requested
     *
     * @param string $url
     * @param array|null $params path parameters
     * @return ClientInterface
     */
    public function url(string $url, ?array $params): ClientInterface;

    /**
     * Set the headers of the request
     *
     * @param array $headers
     * @return ClientInterface
     */
    public function headers(array $headers): ClientInterface;

    /**
     * Set the body parameters of the request
     *
     * @param array|null $params
     * @return ClientInterface
     */
    public function params(?array $params): ClientInterface;

    /**
     * Request method
     *
     * @param string $verb
     * @return ClientInterface
     */
    public function verb(string $verb): ClientInterface;

    /**
     * Performs the request
     *
     * @throws HttpRequestException
     * @return ResponseInterface
     */
    public function send(): ResponseInterface;
}

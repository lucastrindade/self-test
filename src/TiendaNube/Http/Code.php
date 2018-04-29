<?php

declare(strict_types=1);

namespace TiendaNube\Http;

/**
 * Class Code
 *
 * @package TiendaNube\Http
 */
class Code
{
    /** @var string OK constante para identificar sucesso */
    const OK = 200;

    /** @var string CREATED constante para identificar sucesso/criado */
    const CREATED = 201;

    /** @var string BAD_REQUEST constante para identificar parametrização incorreta */
    const BAD_REQUEST = 400;

    /** @var string UNAUTHORIZED constante para identificar problema com autenticação */
    const UNAUTHORIZED = 401;

    /** @var string NOT_FOUND constante para identificar url não encontrada */
    const NOT_FOUND = 404;

    /** @var string CONFLICT constante para identificar conflito de parâmetros */
    const CONFLICT = 409;

    /** @var string UNPROCESSABLE_ENTITY constante para identificar falha na validação */
    const UNPROCESSABLE_ENTITY = 422;

    /** @var string ERROR constante para identificar erros */
    const ERROR = 500;
}
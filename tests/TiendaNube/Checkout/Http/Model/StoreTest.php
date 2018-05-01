<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Http\Model;

use PHPUnit\Framework\TestCase;
use TiendaNube\Checkout\Model\Address;
use TiendaNube\Checkout\Model\Store;

class StoreTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $name = 'Lucas';
        $email = 'lucas@teste.com';

        $store = new Store();
        $store->setName($name);
        $store->setEmail($email);

        // asserts
        $this->assertEquals($name, $store->getName());
        $this->assertEquals($email, $store->getEmail());
    }

    public function testBetaTester()
    {
        $store = new Store();

        $store->enableBetaTesting();
        $this->assertEquals(true, $store->isBetaTester());

        $store->disableBetaTesting();
        $this->assertEquals(false, $store->isBetaTester());
    }
}
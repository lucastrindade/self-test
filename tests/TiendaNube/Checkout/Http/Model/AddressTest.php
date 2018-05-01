<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Http\Model;

use PHPUnit\Framework\TestCase;
use TiendaNube\Checkout\Model\Address;

class AddressTest extends TestCase
{
    public function testExistentAddress()
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

        $addressModel = new Address($pdo);

        // test
        $result = $addressModel->find('40010000', 'zipcode');

        // asserts
        $this->assertNotNull($result);
        $this->assertEquals($address, $result);
    }

    public function testNonExistentAddress()
    {
        // mocking statement
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('rowCount')->willReturn(0);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $addressModel = new Address($pdo);

        // test
        $result = $addressModel->find('40010000', 'zipcode');

        // asserts
        $this->assertNull($result);
    }

    public function testAddressWithUncaughtException()
    {
        // expects
        $this->expectException(\Exception::class);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willThrowException(new \Exception('An error occurred'));

        $addressModel = new Address($pdo);

        // testing
        $addressModel->find('40010000', 'zipcode');
    }
}
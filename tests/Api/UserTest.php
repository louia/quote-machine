<?php

// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class UserTest extends ApiTestCase
{
    public function testFilterCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertJsonContains([
            [
            'username' => 'louis',
            'exp' => 200,
            ],
            [
            'username' => 'admin',
            'exp' => 2000,
            ],
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }
}

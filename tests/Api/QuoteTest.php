<?php

// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Citation;

class QuoteTest extends ApiTestCase
{
    public function testFilterCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/citations?content=beau&author=Louis', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertJsonContains([[
            'content' => 'je suis beau',
            'meta' => 'CHOVANECK Louis',
            'categorie' => [
                '/api/categories/16',
            ],
            'author' => '/api/users/1',
        ]]);

        $this->assertMatchesResourceCollectionJsonSchema(Citation::class);
    }

    public function testOneQuote(): void
    {
        $response = static::createClient()->request('GET', '/api/citations/1', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertJsonContains([
            'content' => 'je suis beau',
            'meta' => 'CHOVANECK Louis',
            'categorie' => [
                '/api/categories/16',
            ],
            'author' => '/api/users/1',
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(Citation::class);
    }
}

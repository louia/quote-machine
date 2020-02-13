<?php

// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Categorie;

class CategorieTest extends ApiTestCase
{
    public function testOneCategorie(): void
    {
        $response = static::createClient()->request('GET', '/api/categories/16', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertJsonContains([
            'name' => 'Voyage',
            'slug' => 'voyage',
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(Categorie::class);
    }
}

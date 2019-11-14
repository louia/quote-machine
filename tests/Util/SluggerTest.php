<?php

namespace App\Tests\Util;

use App\Util\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSlugify()
    {
        $slug = new Slugger();

        $result = $slug->slugify('kaamelott');
        $this->assertEquals('kaamelott', $result);

        $result = $slug->slugify('Kaamelott');
        $this->assertEquals('kaamelott', $result);

        $result = $slug->slugify('kaamelott-livre-3');
        $this->assertEquals('kaamelott-livre-3', $result);

        $result = $slug->slugify('Kaamelott - livre 3');
        $this->assertEquals('kaamelott---livre-3', $result);

        $result = $slug->slugify('');
        $this->assertEquals('n-a', $result);
    }
}

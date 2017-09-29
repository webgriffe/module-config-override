<?php

namespace Webgriffe\ConfigOverride\Test\Unit;

use Webgriffe\ConfigOverride\Source;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFromSource()
    {
        $source = new Source(new AdditionalConfigStub());
        $config = $source->get();
        $this->assertTrue($config['default']['additional']['config']['stub']);
    }
}

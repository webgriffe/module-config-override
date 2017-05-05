<?php

namespace Webgriffe\ConfigOverride\Test\Unit\Model\Config;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Filesystem\DirectoryList;
use Webgriffe\ConfigOverride\Model\Config\AggregateYamlFiles;

class AggregateYamlFilesTest extends TestCase
{
    private $directoryList;

    protected function setUp()
    {
        $this->directoryList = $this->prophesize(DirectoryList::class);
        $this->directoryList->getPath(DirectoryList::CONFIG)->willReturn(vfsStream::url('root'));
    }

    public function testAsArrayAggregatesYamlFilesInDirectory()
    {
        $defaultYmlDist = <<<YML
design:
  head:
    default_title: My Title
YML;
        $defaultYml = <<<YML
design:
  head:
    default_title: Another title
    default_other: Other
other:
  config:
    setting: value
YML;
        vfsStream::setup('root', null, ['default.yml.dist' => $defaultYmlDist, 'default.yml' => $defaultYml]);
        $aggregateYamlFiles = new AggregateYamlFiles($this->directoryList->reveal());
        $this->assertEquals(
            [
                'design' => ['head' => ['default_title' => 'Another title', 'default_other' => 'Other']],
                'other' => ['config' => ['setting' => 'value']]
            ],
            $aggregateYamlFiles->asArray()
        );
    }

    public function testAsFlattenArrayAggregatesYamlFilesInDirectory()
    {
        $defaultYmlDist = <<<YML
design:
  head:
    default_title: My Title
YML;
        $defaultYml = <<<YML
design:
  head:
    default_title: Another title
    default_other: Other
other:
  config:
    setting: value
YML;
        vfsStream::setup('root', null, ['default.yml.dist' => $defaultYmlDist, 'default.yml' => $defaultYml]);
        $aggregateYamlFiles = new AggregateYamlFiles($this->directoryList->reveal());
        $this->assertEquals(
            [
                'design/head/default_title' => 'Another title',
                'design/head/default_other' => 'Other',
                'other/config/setting' => 'value',
            ],
            $aggregateYamlFiles->asFlattenArray()
        );
    }
}

<?php

namespace Webgriffe\ConfigOverride\Test\Unit\Model\Config;

use Magento\Framework\App\Request\Http;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Magento\Framework\App\Filesystem\DirectoryList;
use Webgriffe\ConfigOverride\Model\Config\AggregateYamlFiles;

class AggregateYamlFilesTest extends TestCase
{
    private $directoryList;
    /**
     * @var Http
     */
    private $request;
    private $aggregateYamlFiles;

    protected function setUp()
    {
        vfsStream::setup();
        $this->directoryList = $this->prophesize(DirectoryList::class);
        $this->directoryList->getPath(DirectoryList::CONFIG)->willReturn(vfsStream::url('root'));
        $this->request = $this->prophesize(Http::class);
        $this->aggregateYamlFiles = new AggregateYamlFiles($this->directoryList->reveal(), $this->request->reveal());
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
        $this->assertEquals(
            [
                'design' => ['head' => ['default_title' => 'Another title', 'default_other' => 'Other']],
                'other' => ['config' => ['setting' => 'value']]
            ],
            $this->aggregateYamlFiles->asArray()
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
        $this->assertEquals(
            [
                'design/head/default_title' => 'Another title',
                'design/head/default_other' => 'Other',
                'other/config/setting' => 'value',
            ],
            $this->aggregateYamlFiles->asFlattenArray()
        );
    }

    public function testLoadsEvenConfigForSpecifiedEnvironment()
    {
        $this->request->getServer('MAGE_ENVIRONMENT')->willReturn('dev');
        $defaultYml = <<<YML
design:
  head:
    default_title: My Title
YML;
        $devConfigYml = <<<YML
dev:
  config:
    setting: value
YML;
        $devConfigDistYml = <<<YML
dev:
  config:
    setting: dist value
    other: setting
YML;
        $prodConfigDistYml = <<<YML
prod:
  config:
    setting: value
YML;

        vfsStream::setup(
            'root',
            null,
            [
                'default.yml' => $defaultYml,
                'default-dev.yml' => $devConfigYml,
                'default-dev.yml.dist' => $devConfigDistYml,
                'default-prod.yml.dist' => $prodConfigDistYml
            ]
        );
        $this->assertEquals(
            [
                'design' => ['head' => ['default_title' => 'My Title']],
                'dev' => ['config' => ['setting' => 'value', 'other' => 'setting']],
            ],
            $this->aggregateYamlFiles->asArray()
        );
    }
}

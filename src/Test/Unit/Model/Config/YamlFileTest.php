<?php

namespace Webgriffe\ConfigOverride\Test\Unit\Model\Config;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Webgriffe\ConfigOverride\Model\Config\YamlFile;

class YamlFileTest extends TestCase
{
    public function testIsInitializableWithNonExistentFile()
    {
        vfsStream::setup();
        $defaultYamlFile = new YamlFile(vfsStream::url('root/non-existent.yml'));
        $this->assertInstanceOf(YamlFile::class, $defaultYamlFile);
        $this->assertEmpty($defaultYamlFile->asArray());
    }

    public function testIsInitializableWithSomeData()
    {
        $filename = 'default.yml';
        $content = <<<YML
design:
  head:
    default_title: My Title
YML;
        vfsStream::setup('root', null, ['app' => ['etc' => [$filename => $content]]]);
        $defaultYamlFile = new YamlFile(vfsStream::url('root/app/etc/default.yml'));
        $this->assertInstanceOf(YamlFile::class, $defaultYamlFile);
        $this->assertEquals(['design' => ['head' => ['default_title' => 'My Title']]], $defaultYamlFile->asArray());
    }

    public function testAsFlattenArray()
    {
        $filename = 'default.yml';
        $content = <<<YML
design:
  head:
    default_title: My Title
other:
  config:
    setting: value
YML;
        vfsStream::setup('root', null, ['app' => ['etc' => [$filename => $content]]]);
        $defaultYamlFile = new YamlFile(vfsStream::url('root/app/etc/default.yml'));
        $this->assertInstanceOf(YamlFile::class, $defaultYamlFile);
        $this->assertEquals(
            ['design/head/default_title' => 'My Title', 'other/config/setting' => 'value'],
            $defaultYamlFile->asFlattenArray()
        );
    }
}

<?php


namespace Webgriffe\ConfigFileReader\Test\Unit\Model\Config;


use Magento\Framework\App\Filesystem\DirectoryList;
use org\bovigo\vfs\vfsStream;
use Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile;

class DefaultYamlFileTest extends \PHPUnit_Framework_TestCase
{
    public function testIsInitializableWithNonExistentFile()
    {
        vfsStream::setup();
        $defaultYamlFile = new DefaultYamlFile(
            'non-existent.yml',
            $this->mockDirectoryList(vfsStream::url('root/app/etc'))
        );
        $this->assertInstanceOf('Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile', $defaultYamlFile);
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
        $defaultYamlFile = new DefaultYamlFile($filename, $this->mockDirectoryList(vfsStream::url('root/app/etc')));
        $this->assertInstanceOf('Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile', $defaultYamlFile);
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
        $defaultYamlFile = new DefaultYamlFile($filename, $this->mockDirectoryList(vfsStream::url('root/app/etc')));
        $this->assertInstanceOf('Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile', $defaultYamlFile);
        $this->assertEquals(
            ['design/head/default_title' => 'My Title', 'other/config/setting' => 'value'],
            $defaultYamlFile->asFlattenArray()
        );
    }

    private function mockDirectoryList($configPath)
    {
        $mock = $this
            ->getMockBuilder('Magento\Framework\App\Filesystem\DirectoryList')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $mock
            ->expects($this->once())
            ->method('getPath')
            ->with(DirectoryList::CONFIG)
            ->willReturn($configPath)
        ;
        return $mock;
    }
}

<?php


namespace Webgriffe\ConfigFileReader\Test\Unit\Model\Config;


use Magento\Framework\App\Filesystem\DirectoryList;
use Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile;

class DefaultYamlFileTest extends \PHPUnit_Framework_TestCase
{
    public function testIsInitializableWithNonExistentFile()
    {
        $defaultYamlFile = new DefaultYamlFile('non-existent.yml', $this->mockDirectoryList('/magento/app/etc'));
        $this->assertInstanceOf('Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile', $defaultYamlFile);
        $this->assertEmpty($defaultYamlFile->asArray());
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

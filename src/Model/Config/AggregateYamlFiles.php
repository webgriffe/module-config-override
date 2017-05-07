<?php

namespace Webgriffe\ConfigOverride\Model\Config;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;

class AggregateYamlFiles implements AdditionalInterface
{
    const BASE_FILE_NAME = 'default';

    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(DirectoryList $directoryList, Http $request)
    {
        $this->directoryList = $directoryList;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $data = [];
        foreach ($this->getYmlFiles() as $ymlFile) {
            $data = array_replace_recursive($data, $ymlFile->asArray());
        }
        return $data;
    }

    /**
     * @return array
     */
    public function asFlattenArray()
    {
        $data = [];
        foreach ($this->getYmlFiles() as $ymlFile) {
            $data = array_replace_recursive($data, $ymlFile->asFlattenArray());
        }
        return $data;
    }

    /**
     * @return array
     */
    private function getYmlFiles()
    {
        $configPath = $this->directoryList->getPath(DirectoryList::CONFIG);
        $ymlFiles = [];
        $ymlFiles[] = new YamlFile($configPath . DIRECTORY_SEPARATOR . self::BASE_FILE_NAME . '.yml.dist');
        $ymlFiles[] = new YamlFile($configPath . DIRECTORY_SEPARATOR . self::BASE_FILE_NAME . '.yml');
        $env = $this->request->getServer('MAGE_ENVIRONMENT');
        if ($env) {
            $envBaseFileName = sprintf('%s-%s', self::BASE_FILE_NAME, $env);
            $ymlFiles[] = new YamlFile($configPath . DIRECTORY_SEPARATOR . $envBaseFileName . '.yml.dist');
            $ymlFiles[] = new YamlFile($configPath . DIRECTORY_SEPARATOR . $envBaseFileName . '.yml');
        }
        return $ymlFiles;
    }
}

<?php

namespace Webgriffe\ConfigOverride\Model\Config;

use Magento\Framework\App\Filesystem\DirectoryList;

class AggregateYamlFiles implements AdditionalInterface
{
    const BASE_FILE_NAME = 'default';

    /**
     * @var YamlFile[]
     */
    private $ymlFiles;

    public function __construct(DirectoryList $directoryList)
    {
        $configPath = $directoryList->getPath(DirectoryList::CONFIG);
        $this->ymlFiles[] = new YamlFile($configPath . DIRECTORY_SEPARATOR . self::BASE_FILE_NAME . '.yml.dist');
        $this->ymlFiles[] = new YamlFile($configPath . DIRECTORY_SEPARATOR . self::BASE_FILE_NAME . '.yml');
    }

    /**
     * @return array
     */
    public function asArray()
    {
        $data = [];
        foreach ($this->ymlFiles as $ymlFile) {
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
        foreach ($this->ymlFiles as $ymlFile) {
            $data = array_replace_recursive($data, $ymlFile->asFlattenArray());
        }
        return $data;
    }
}

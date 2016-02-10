<?php


namespace Webgriffe\ConfigFileReader\Model\Config;


use Magento\Framework\App\Filesystem\DirectoryList;
use Symfony\Component\Yaml\Yaml;

class DefaultYamlFile
{
    /**
     * @var array
     */
    private $data = array();

    private $flattenData = null;

    public function __construct($filename, DirectoryList $directoryList)
    {
        $filePath = $directoryList->getPath(DirectoryList::CONFIG) . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($filePath) && is_readable($filePath)) {
            $this->data = Yaml::parse($filePath);
        }
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function asFlattenArray()
    {
        if (is_null($this->flattenData)) {
            $this->flattenData = $this->doFlatten($this->data);
        }
        return $this->flattenData;
    }

    private function doFlatten($array, $prefix = '') {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->doFlatten($value, $prefix . $key . '/');
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
}

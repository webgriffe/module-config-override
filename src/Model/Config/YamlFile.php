<?php

namespace Webgriffe\ConfigOverride\Model\Config;

use Symfony\Component\Yaml\Yaml;

class YamlFile implements AdditionalInterface
{
    /**
     * @var array
     */
    private $data = array();

    private $flattenData = null;

    /**
     * YamlFile constructor.
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        if (file_exists($filePath) && is_readable($filePath)) {
            $this->data = Yaml::parse(file_get_contents($filePath)) ?: [];
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

    private function doFlatten($array, $prefix = '')
    {
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

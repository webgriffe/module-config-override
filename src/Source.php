<?php


namespace Webgriffe\ConfigOverride;


use Magento\Framework\App\Config\ConfigSourceInterface;
use Webgriffe\ConfigOverride\Model\Config\DefaultYamlFile;

class Source implements ConfigSourceInterface
{
    /**
     * @var DefaultYamlFile
     */
    private $defaultYamlFile;

    public function __construct(DefaultYamlFile $defaultYamlFile)
    {
        $this->defaultYamlFile = $defaultYamlFile;
    }

    /**
     * Retrieve configuration raw data array.
     *
     * @param string $path
     * @return array
     */
    public function get($path = '')
    {
        return ['default' => $this->defaultYamlFile->asArray()];
    }
}

<?php

namespace Webgriffe\ConfigOverride;

use Magento\Framework\App\Config\ConfigSourceInterface;
use Webgriffe\ConfigOverride\Model\Config\AdditionalInterface;
use Webgriffe\ConfigOverride\Model\Config\YamlFile;

class Source implements ConfigSourceInterface
{
    /**
     * @var AdditionalInterface
     */
    private $additionalConfig;

    public function __construct(AdditionalInterface $additionalConfig)
    {
        $this->additionalConfig = $additionalConfig;
    }

    /**
     * Retrieve configuration raw data array.
     *
     * @param string $path
     * @return array
     */
    public function get($path = '')
    {
        return ['default' => $this->additionalConfig->asArray()];
    }
}

<?php


namespace Webgriffe\ConfigFileReader\Model\Config\Reader;

use Magento\Framework\App\Config\Scope\ReaderInterface;
use Webgriffe\ConfigFileReader\Model\Config\DefaultYamlFile;

class Plugin
{
    /**
     * @var DefaultYamlFile
     */
    private $defaultYamlFile;

    /**
     * Plugin constructor.
     * @param DefaultYamlFile $defaultYamlFile
     */
    public function __construct(DefaultYamlFile $defaultYamlFile)
    {
        $this->defaultYamlFile = $defaultYamlFile;
    }

    public function afterRead(ReaderInterface $subject, $result)
    {
        return array_replace_recursive($result, $this->defaultYamlFile->asArray());
    }
}

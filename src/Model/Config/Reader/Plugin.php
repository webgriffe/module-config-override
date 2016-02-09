<?php


namespace Webgriffe\ConfigFileReader\Model\Config\Reader;

use Magento\Framework\App\Config\Scope\ReaderInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Symfony\Component\Yaml\Yaml;

class Plugin
{
    const CONFIG_FILENAME = 'default.yml';

    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var Yaml
     */
    private $yaml;

    /**
     * Plugin constructor.
     * @param DirectoryList $directoryList
     * @param Yaml $yaml
     */
    public function __construct(DirectoryList $directoryList, Yaml $yaml)
    {
        $this->directoryList = $directoryList;
        $this->yaml = $yaml;
    }

    public function afterRead(ReaderInterface $subject, $result)
    {
        $filename = $this->directoryList->getPath(DirectoryList::CONFIG) . DIRECTORY_SEPARATOR . self::CONFIG_FILENAME;
        if (!file_exists($filename) || !is_readable($filename)) {
            return $result;
        }
        $fileConfig = $this->yaml->parse($filename);
        $array_replace_recursive = array_replace_recursive($result, $fileConfig);
        $this->log(print_r($array_replace_recursive['design'], true));
        return $array_replace_recursive;
    }

    /**
     * @param $data
     */
    private function log($data)
    {
        file_put_contents('/tmp/plugin.log', date('c') . ': ' . $data, FILE_APPEND);
    }
}

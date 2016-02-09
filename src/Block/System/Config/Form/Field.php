<?php


namespace Webgriffe\ConfigFileReader\Block\System\Config\Form;


use Magento\Config\Block\System\Config\Form\Field as BaseField;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Field extends BaseField
{
    protected function _getElementHtml(AbstractElement $element)
    {
        // TODO System config field have to show that config value is overridden by file
        return parent::_getElementHtml($element);
    }

}

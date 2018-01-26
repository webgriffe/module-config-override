<?php

namespace Webgriffe\ConfigOverride\Block\System\Config\Form;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field as BaseField;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Webgriffe\ConfigOverride\Model\Config\AdditionalInterface;

class Field extends BaseField
{
    const ACTION_SCOPE_TYPE = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;

    /**
     * @var array
     */
    private $flatOverriddenConfig;

    public function __construct(Context $context, AdditionalInterface $additionalConfig, array $data = [])
    {
        parent::__construct($context, $data);
        $this->flatOverriddenConfig = $additionalConfig->asFlattenArray();
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        if ($element->getData('scope') === self::ACTION_SCOPE_TYPE) {
            if ($this->isElementValueOverridden($element)) {
                $element->setValue($this->getElementOverriddenValue($element));
                $element->setData('disabled', true);
                $element->setComment(
                    sprintf(
                        '<strong><em>The value for this configuration setting, for scope "%s", comes from file ' .
                        'and cannot be modified.</em></strong><br/>',
                        self::ACTION_SCOPE_TYPE
                    ) .
                    $element->getComment()
                );
            }
        }
        return parent::_getElementHtml($element);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function isElementValueOverridden(AbstractElement $element)
    {
        $fieldConfig = $element->getData('field_config');
        $path = $fieldConfig['path'] . '/' . $fieldConfig['id'];
        return array_key_exists($path, $this->flatOverriddenConfig);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function getElementOverriddenValue(AbstractElement $element)
    {
        $fieldConfig = $element->getData('field_config');
        $path = $fieldConfig['path'] . '/' . $fieldConfig['id'];
        return $this->flatOverriddenConfig[$path];
    }
}

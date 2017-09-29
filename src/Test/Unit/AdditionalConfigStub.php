<?php

namespace Webgriffe\ConfigOverride\Test\Unit;

use Webgriffe\ConfigOverride\Model\Config\AdditionalInterface;

class AdditionalConfigStub implements AdditionalInterface
{
    /**
     * @return array
     */
    public function asArray()
    {
        return ['additional' => ['config' => ['stub' => true]]];
    }

    /**
     * @return array
     */
    public function asFlattenArray()
    {
        return ['additional/config/stub' => true];
    }
}

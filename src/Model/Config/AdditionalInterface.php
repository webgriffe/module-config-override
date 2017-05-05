<?php

namespace Webgriffe\ConfigOverride\Model\Config;

interface AdditionalInterface
{
    /**
     * @return array
     */
    public function asArray();

    /**
     * @return array
     */
    public function asFlattenArray();
}

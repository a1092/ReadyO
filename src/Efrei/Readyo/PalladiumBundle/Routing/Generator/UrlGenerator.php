<?php

namespace Efrei\Readyo\PalladiumBundle\Routing\Generator;

use Symfony\Component\Routing\Generator\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    protected function doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens)
    {
        if(isset($defaults['_url'])) {
        	$this->context->setPathInfo('');
        	$path = parent::doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, self::RELATIVE_PATH, $hostTokens);
            return $defaults['_url'].$path;
        }

        return parent::doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens);
    }
}
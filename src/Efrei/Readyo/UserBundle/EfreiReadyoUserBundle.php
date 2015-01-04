<?php

namespace Efrei\Readyo\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EfreiReadyoUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}

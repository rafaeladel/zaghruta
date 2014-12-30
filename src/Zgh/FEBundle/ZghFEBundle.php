<?php

namespace Zgh\FEBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZghFEBundle extends Bundle
{
    public function getParent()
    {
        return "FOSUserBundle";
    }
}

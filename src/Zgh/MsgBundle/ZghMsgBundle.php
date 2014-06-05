<?php

namespace Zgh\MsgBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ZghMsgBundle extends Bundle
{
    public function getParent()
    {
        return "FOSMessageBundle";
    }

}

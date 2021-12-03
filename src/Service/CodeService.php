<?php

namespace App\Service;

class CodeService
{
    public function generate()
    {
        return mt_rand(1000, 9999);
    }
}
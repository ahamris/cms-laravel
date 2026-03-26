<?php

namespace App\Enums;

enum PageLayoutRowKind: string
{
    case Element = 'element';
    case ShortBody = 'short_body';
    case LongBody = 'long_body';
}

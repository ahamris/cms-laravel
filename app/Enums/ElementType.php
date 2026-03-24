<?php

namespace App\Enums;

enum ElementType: string
{
    case Cta = 'cta';
    case Faq = 'faq';
    case RelatedContent = 'related_content';
    case CardGrid = 'card_grid';
    case HeroVideo = 'hero_video';
    case Newsletter = 'newsletter';
    case Feature = 'feature';
}

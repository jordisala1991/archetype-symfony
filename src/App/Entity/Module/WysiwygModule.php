<?php

namespace App\Entity\Module;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/** @ORM\Entity */
class WysiwygModule extends Module implements TranslatableInterface
{
    use ORMBehaviors\Translatable\TranslatableTrait;

    public const TYPE = 'wysiwyg';

    public function getText(?string $locale = null): ?string
    {
        return $this->translate($locale, false)->getText();
    }
}

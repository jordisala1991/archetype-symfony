<?php

namespace App\Entity\Module;

use App\Entity\Page;
use App\Repository\ModuleRepository;
use Doctrine\ORM\Mapping as ORM;
use Runroom\SortableBehaviorBundle\Behaviors\Sortable;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     WysiwygModule::TYPE = WysiwygModule::class,
 * })
 */
abstract class Module
{
    use Sortable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publish;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="modules")
     */
    private $page;

    public function __toString(): string
    {
        return 'Module';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPublish(?bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getType(): string
    {
        return 'modules.type.' . static::TYPE;
    }

    public function isTranslated(?string $locale = null): string
    {
        return !$this->translate($locale, false)->isEmpty();
    }
}

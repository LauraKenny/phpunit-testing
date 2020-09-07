<?php


namespace AppBundle\Entity;


use AppBundle\Exception\DinosaursAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="enclosure")
 */
class Enclosure
{
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Dinosaur", mappedBy="enclosure", cascade={"persist"})
     */
    private $dinosaurs;

    /**
     * @var Collection|Security[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Security", mappedBy="enclosure", cascade={"persist"})
     */
    private $securities;

    public function __construct(bool $withBasicSecurity = false)
    {
        $this->securities = new ArrayCollection();
        $this->dinosaurs = new ArrayCollection();

        if ($withBasicSecurity) {
            $this->addSecurity(new Security('Fence', true, $this));
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getDinosaurs(): ArrayCollection
    {
        return $this->dinosaurs;
    }

    /**
     * @param Dinosaur $dinosaur
     */
    public function addDinosaur(Dinosaur $dinosaur)
    {
        if (!$this->canAddDinosaur($dinosaur)) {
            throw new NotABuffetException();
        }

        if (!$this->isSecurityActive()) {
            throw new DinosaursAreRunningRampantException('Are you crazy?');
        }

        $this->dinosaurs[] = $dinosaur;
    }

    public function addSecurity(Security $security)
    {
        $this->securities[] = $security;
    }

    public function isSecurityActive(): bool
    {
        foreach ($this->securities as $security) {
            if ($security->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection|Security[]
     */
    public function getSecurities()
    {
        return $this->securities;
    }

    public function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        return count($this->dinosaurs) === 0
            || $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();
    }

}
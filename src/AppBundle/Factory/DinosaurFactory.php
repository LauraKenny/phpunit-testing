<?php

namespace AppBundle\Factory;
use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;

class DinosaurFactory
{
    /**
     * @var DinosaurLengthDeterminator
     */
    private $lengthDeterminator;

    public function __construct(DinosaurLengthDeterminator $lengthDeterminator)
    {
        $this->lengthDeterminator = $lengthDeterminator;
    }
    
    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        // defaults
        $codeName = 'InG-' . random_int(1, 99999);
        $length = $this->lengthDeterminator->getLengthFromSpecification($specification);
        $isCarnivorous = false;

        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        return $this->createDinosaur($codeName, $isCarnivorous, $length);
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);

        $dinosaur->setLength($length);

        return $dinosaur;
    }


}
<?php

namespace Tests\AppBundle\Factory;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{

    /**
     * @var DinosaurFactory
     */
    private $factory;

    /**
     * @var MockObject
     */
    private $lengthDeterminator;

    public function setUp(): void
    {
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);

        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }

    public function testItGrowsALargeVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertInternalType('string', $dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());

    }

    public function testItGrowsATriceratops()
    {
        $this->markTestIncomplete('Waiting for confirmation');
    }

    public function testItGrowsABabyVelociraptor()
    {
        if (!class_exists('Nanny')) {
            $this->markTestSkipped('Nobody to look after the baby');
        }

        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength());

    }

    /**
     * @param string $spec
     * @param bool $expectedIsLarge
     * @param bool $expectedIsCarnivorous
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromASpecification(string $spec, bool $expectedIsCarnivorous)
    {
        $this->lengthDeterminator->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);

        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Diets do not match');
        $this->assertSame(20, $dinosaur->getLength());
    }

    public function getSpecificationTests()
    {
        return [
            // specification, is carnivorous
            ['large carnivorous dinosaur', true],
            'default response' => ['this makes no sense', false],
            ['large herbivore', false],
        ];
    }

    public function getHugeDinosaurSpecTests()
    {
        return [
            ['huge dinosaur'],
            ['huge dino'],
            ['huge'],
            ['OMG'],
            ['Ahhhhh'],
        ];
    }
}
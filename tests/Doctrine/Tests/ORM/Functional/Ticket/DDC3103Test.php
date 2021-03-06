<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Annotation as ORM;
use Doctrine\ORM\Mapping\ClassMetadataBuildingContext;
use Doctrine\Tests\OrmFunctionalTestCase;
use function serialize;
use function unserialize;

/**
 * @group DDC-3103
 * @group embedded
 */
class DDC3103Test extends OrmFunctionalTestCase
{
    /**
     * @covers \Doctrine\ORM\Mapping\ClassMetadata::__sleep
     */
    public function testIssue() : void
    {
        $driver = $this->createAnnotationDriver();

        $metadataBuildingContext = new ClassMetadataBuildingContext(
            $this->em->getMetadataFactory(),
            $this->em->getMetadataFactory()->getReflectionService(),
            $this->em->getConnection()->getDatabasePlatform()
        );

        $classMetadata = $driver->loadMetadataForClass(DDC3103ArticleId::class, null, $metadataBuildingContext);

        self::assertTrue(
            $classMetadata->isEmbeddedClass,
            'The isEmbeddedClass property should be true from the mapping data.'
        );

        self::assertTrue(
            unserialize(serialize($classMetadata))->isEmbeddedClass,
            'The isEmbeddedClass property should still be true after serialization and unserialization.'
        );
    }
}

/**
 * @ORM\Embeddable
 */
class DDC3103ArticleId
{
    /**
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @var string
     */
    protected $nameValue;
}

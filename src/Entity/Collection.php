<?php

namespace rsanchez\Deep\Entity;

use rsanchez\Deep\Entity\Entity;
use rsanchez\Deep\Entity\Factory as EntityFactory;
use rsanchez\Deep\Fieldtype\Repository as FieldtypeRepository;
use rsanchez\Deep\Fieldtype\CollectionFactory as FieldtypeCollectionFactory;
use rsanchez\Deep\Property\CollectionFactoryInterface as PropertyCollectionFactory;
use SplObjectStorage;

class Collection extends SplObjectStorage
{
    protected $entityIds = array();

    /**
     * @var rsanchez\Deep\Entity\Factory
     */
    protected $factory;

    /**
     * @var rsanchez\Deep\Fieldtype\Repository
     */
    protected $fieldtypeRepository;

    /**
     * @var rsanchez\Deep\Fieldtype\CollectionFactory
     */
    protected $fieldtypeCollectionFactory;

    /**
     * @var rsanchez\Deep\Property\CollectionFactoryInterface
     */
    protected $propertyCollectionFactory;

    public function __construct(
        EntityFactory $factory,
        FieldtypeRepository $fieldtypeRepository,
        FieldtypeCollectionFactory $fieldtypeCollectionFactory,
        PropertyCollectionFactory $propertyCollectionFactory
    ) {
        $this->factory = $factory;
        $this->fieldtypeRepository= $fieldtypeRepository;
        $this->fieldtypeCollectionFactory = $fieldtypeCollectionFactory;
        $this->propertyCollectionFactory = $propertyCollectionFactory;
    }

    /**
     * Fill this collection with entries created from a DB result set
     * @param stdClass[] $result 
     * @return void
     */
    public function fill(array $result)
    {
    }

    public function attach(Entity $entity)
    {
        $this->entityIds[] = $entity->id();
        return parent::attach($entity);
    }
}

<?php

class CollectionNestedCollectionPropertyHasAllValuesConstraint extends PHPUnit_Framework_Constraint
{
    /**
     * @var mixed
     */
    protected $expected;

    public function __construct($expected, $mainProperty, $secondaryProperty)
    {
        parent::__construct();

        $this->expected = $expected;
        $this->mainProperty = $mainProperty;
        $this->secondaryProperty = $secondaryProperty;
    }

    public function matches($input)
    {
        foreach ($input as $row) {
            $expected = $this->expected;

            foreach ($row->{$this->mainProperty} as $secondaryRow) {
                $index = array_search($secondaryRow->{$this->secondaryProperty}, $expected);

                if ($index !== false) {
                    unset($expected[$index]);
                }
            }

            if ($expected) {
                return false;
            }
        }

        return true;
    }

    public function toString()
    {
        return sprintf('%s.%s does not have all the values %s', $this->mainProperty, $this->secondaryProperty, json_encode($this->expected));
    }
}

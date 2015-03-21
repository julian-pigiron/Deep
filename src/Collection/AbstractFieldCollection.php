<?php

/**
 * Deep
 *
 * @package      rsanchez\Deep
 * @author       Rob Sanchez <info@robsanchez.com>
 */

namespace rsanchez\Deep\Collection;

use rsanchez\Deep\Model\AbstractField;
use Illuminate\Database\Eloquent\Model;

/**
 * Collection of \rsanchez\Deep\Model\AbstractField
 */
abstract class AbstractFieldCollection extends AbstractModelCollection
{
    /**
     * array of field_name => \rsanchez\Deep\Model\AbstractField
     * @var array
     */
    protected $fieldsByName = array();

    /**
     * {@inheritdoc}
     */
    protected function prepareModel(Model $item)
    {
        $this->fieldsByName[$item->field_name] = $item;
    }

    /**
     * Get the field_id for the specified field name
     *
     * @param  string $field name of the field
     * @return string
     */
    public function getFieldId($field)
    {
        return $this->fieldsByName[$field]->field_id;
    }

    /**
     * Check if this collection has the specified field name
     *
     * @param  string  $field the name of the field
     * @return boolean
     */
    public function hasField($field)
    {
        return array_key_exists($field, $this->fieldsByName);
    }
}

<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Class Model_Abstract_ORM
 *
 * Extended by injecting function just before update database record
 */
class Model_Abstract_ORM extends ORM
{
    /**
     * Updates a single record or multiple records
     *
     * @chainable
     * @param  Validation $validation Validation object
     * @throws Kohana_Exception
     * @return ORM
     */
    public function update(Validation $validation = NULL)
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot update :model model because it is not loaded.', array(':model' => $this->_object_name));

        // Run validation if the model isn't valid or we have additional validation rules.
        if ( ! $this->_valid OR $validation)
        {
            $this->check($validation);
        }

        if (empty($this->_changed))
        {
            // Nothing to update
            return $this;
        }

        $data = array();
        foreach ($this->_changed as $column)
        {
            // Compile changed data
            $data[$column] = $this->_object[$column];
        }

        if (is_array($this->_updated_column))
        {
            // Fill the updated column
            $column = $this->_updated_column['column'];
            $format = $this->_updated_column['format'];

            $data[$column] = $this->_object[$column] = ($format === TRUE) ? time() : date($format);
        }

        // inject any operation, for example back up old values
        $this->_beforeUpdate();

        // Use primary key value
        $id = $this->pk();

        // Update a single record
        DB::update($this->_table_name)
            ->set($data)
            ->where($this->_primary_key, '=', $id)
            ->execute($this->_db);

        if (isset($data[$this->_primary_key]))
        {
            // Primary key was changed, reflect it
            $this->_primary_key_value = $data[$this->_primary_key];
        }

        // Object has been saved
        $this->_saved = TRUE;

        // All changes have been saved
        $this->_changed = array();
        $this->_original_values = $this->_object;

        return $this;
    }

    /**
     * Inject operation just before update database record (but after validation, etc)
     */
    protected function _beforeUpdate()
    {
        // nothing to do by default
    }
}

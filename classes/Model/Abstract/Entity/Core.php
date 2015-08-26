<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_Abstract_Entity_Core
 *
 * @property int $id
 * @property string $name
 */
abstract class Model_Abstract_Entity_Core extends Model_Abstract_Record implements Model_Interface_Urlable
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['name'] = array
        (
            array('not_empty'),
            array('Model_Abstract_Record::alnum'),
            array('max_length', array(':value', 50)),
        );

        return $rules;
    }

    /**
     * @return array
     */
    public function filters()
    {
        $filters = parent::filters();

        $filters['name'][] = array('Model_Abstract_Record::wordWrapUtf8', array(':value'));
        $filters['name'] = $this->_defaultFilters;

        return $filters;
    }

    /**
     * Get name of entity in plural
     * Result string must match controller name - part of URL used to show the entity, e.g.:
     * "events" in "http://example.com/events/123-great-tournament"
     * "imprezy" in "http://example.pl/imprezy/123-wielki-turniej"
     *
     * @return string
     */
    public function getPluralName()
    {
        return $this->object_plural();
    }

    /**
     * Get full URL for entity
     *
     * @return string
     */
    public function getURL()
    {
        $result = null;

        if ($this->loaded())
        {
            $result = URL::site($this->getPluralName().'/'.$this->id.'-'.Helper_URLMaker::getURLName($this->name));
        }
        else
        {
            $result = URL::site($this->getPluralName());
        }

        return $result;
    }

    /**
     * Returns admin (author/owner/administrator) of entity
     *
     * @return null|Model_User
     */
    public function getAdmin()
    {
        return null;
    }

    /**
     * Calls parent class' method
     * (this method is declared only for PHPDoc type compatibility)
     *
     * @chainable
     * @param   string  $model  Model name
     * @param   mixed   $id     Parameter for find()
     * @return  Model_Abstract_Entity
     */
    public static function factory($model, $id = NULL)
    {
        return parent::factory($model, $id);
    }

    /**
     * @param string $model
     * @param int $id
     * @param int|null $lifetime
     * @return Model_Abstract_Entity
     */
    public static function factoryCached($model, $id, $lifetime = null)
    {
        $entity = parent::factory($model);
        $entity->cached($lifetime);
        $entity->where('id', '=', $id)->find();

        return $entity;
    }
}

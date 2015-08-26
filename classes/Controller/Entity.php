<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Controller_Entity
 */
class Controller_Entity extends Controller_Core
{
    /**
     * @var Model_Abstract_Entity|null
     */
    protected $_entity = null;

    /**
     * @return Model_Abstract_Entity|null
     */
    public function getEntity()
    {
        return $this->_entity;
    }
}

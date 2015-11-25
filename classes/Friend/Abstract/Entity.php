<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Friend_Abstract_Entity
 */
abstract class Friend_Abstract_Entity extends Friend_Abstract
{
    /**
     * @var Controller_Entity
     */
    protected $_controller;

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

    /**
     * @param Model_Abstract_Entity|null $entity
     */
    public function setEntity(Model_Abstract_Entity $entity = null)
    {
        $this->_entity = $entity;
    }

    /**
     * @param Controller_Entity $controller
     */
    public function __construct(Controller_Entity $controller)
    {
        parent::__construct($controller);

        $this->setEntity($controller->getEntity());
    }
}

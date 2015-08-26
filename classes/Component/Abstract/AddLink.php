<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_Abstract_AddLink
 */
abstract class Component_Abstract_AddLink extends Container
{
    /**
     * @var Model_Abstract_Entity
     */
    protected $_entity;

    /**
     * @param Model_Abstract_Entity $entity
     */
    public function __construct(Model_Abstract_Entity $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * @return string
     */
    protected function _render()
    {
        if (!Auth::instance()->logged_in())
        {
            $addLink = new Tag_Paragraph($this->_textNotLoggedIn());
            $addLink->addCSSClass('light');
        }
        else
        {
            $addLink = new Tag_HyperLink($this->_textLink().'...', $this->_entity->getURL().'/'.$this->_link());
        }

        $addLink->addCSSClass('add_entity_link');
        $this->add($addLink);

        return parent::_render();
    }

    /**
     * @return string
     */
    protected abstract function _textNotLoggedIn();

    /**
     * @return string
     */
    protected abstract function _textLink();

    /**
     * @return string
     */
    protected abstract function _link();
}

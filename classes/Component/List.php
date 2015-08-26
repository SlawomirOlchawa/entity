<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_List
 */
class Component_List extends Tag_Block
{
    /**
     * @var Model_Abstract_Entity
     */
    protected $_entities;

    /**
     * @var string
     */
    protected $_noResultsInfo = 'Brak wyników do wyświetlenia.';

    /**
     * @param Model_Abstract_Entity $entities
     */
    public function __construct(Model_Abstract_Entity $entities)
    {
        parent::__construct();

        $this->_entities = $entities;
    }

    /**
     * @param Model_Abstract_Entity $entity
     * @return Tag_HyperLink
     */
    protected function _getListItem(Model_Abstract_Entity $entity)
    {
        return new Tag_HyperLink($entity->name, $entity->getURL());
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $entityList = $this->_entities->findAll();

        if ($entityList->count() === 0)
        {
            $info = new Tag_Paragraph($this->_noResultsInfo);
            $info->addCSSClass('light');
            $this->add($info);
        }
        else
        {
            $list = new Tag_List();
            $list->addCSSClass('light');
            $this->add($list);

            foreach ($entityList as $entity)
            {
                $link = $this->_getListItem($entity);
                $list->add($link);
            }
        }

        return parent::_render();
    }
}

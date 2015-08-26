<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Component_TagCloud
 */
class Component_TagCloud extends Tag_Block
{
    /**
     * @var Model_Abstract_Entity
     */
    protected $_articles;

    /**
     * @param Model_Abstract_Entity $articles
     */
    public function __construct(Model_Abstract_Entity $articles)
    {
        parent::__construct();

        $this->_articles = $articles;
        Helper_Includer::addCSS('media/mod/entity/css/tagcloud.css');
    }

    /**
     * @return string
     */
    protected function _render()
    {
        $this->addCSSClass('tag_cloud');

        foreach ($this->_articles->findAll() as $article)
        {
            /** @var Model_Abstract_Entity $article */
            $rank = $article->getRank();
            $link = new Tag_HyperLink(str_replace(' ', '&nbsp;', $article->name), $article->getURL());
            $link->addCSSClass('size'.$this->_calculateSize($rank));
            $this->add($link);
        }

        return parent::_render();
    }

    /**
     * @param int $count
     * @return int
     */
    protected function _calculateSize($count)
    {
        $size = (int)(log($count+1, 3))+1;

        if ($size > 4)
        {
            $size = 4;
        }

        return $size;
    }
}

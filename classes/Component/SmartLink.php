<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Component_SmartLink
 */
class Component_SmartLink extends Tag
{
    /**
     * @param string $caption
     * @param string $url
     */
    public function __construct($caption, $url)
	{
		parent::__construct();
		
		$this->_html = $caption;

        if (!empty($url))
        {
            $this->_htmlTag = 'a';
            $this->_htmlParams['href'] = $url;
        }
        else
        {
            $this->_htmlTag = 'span';
        }
	}				
}

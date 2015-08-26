<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Friend_EntityRequester
 */
class Friend_EntityRequester extends Friend_Abstract_Entity
{
    /**
     * Returns entity if correct id was send in URL and redirects if needed
     *
     * @param string $modelName
     * @param bool $cached
     * @param int|null $lifetime
     * @param string|null $param
     * @param bool $autoRedirect
     * @return Model_Abstract_Entity|null
     * @throws HTTP_Exception_404
     */
    public function get($modelName, $cached = false, $lifetime = null, $param='id', $autoRedirect = false)
    {
        $id = null;
        $urlName = null;

        $params = explode('-', $this->_controller->request->param($param));

        if (!empty($params))
        {
            $id = array_shift($params);
        }

        if (!empty($params))
        {
            $urlName = implode('-', $params);
        }

        if (empty($id)) return null;

        if (!$cached)
        {
            $entity = Model_Abstract_Entity::factory($modelName, $id);
        }
        else
        {
            $entity = Model_Abstract_Entity::factoryCached($modelName, $id, $lifetime);
        }

        if (!$entity->loaded())
        {
            throw new HTTP_Exception_404('Nie znaleziono strony o podanym adresie');
        }

        if (Helper_URLMaker::getURLName($entity->name) != $urlName)
        {
            if ($autoRedirect)
            {
                //$controller->redirect($entity->getURL(), 301);
                $requestParams = array($param => $id.'-'.Helper_URLMaker::getURLName($entity->name));
                $url = $this->_requestURL($requestParams);
                $this->_controller->redirect($url, 301);
            }
            else
            {
                // prevents intruders from looping ids for stealing entities data
                throw new HTTP_Exception_404('Nie znaleziono strony o podanym adresie');
            }
        }

        $this->setEntity($entity);

        return $entity;
    }

    /**
     * Get correct URL for current request
     *
     * @param array $params
     * @return string
     */
    protected function _requestURL($params=array())
    {
        $request = $this->_controller->request;

        if (empty($params['id']))
        {
            $params['id'] = $request->param('id');
        }

        if (empty($params['params']))
        {
            $params['params'] = $request->param('params');
        }

        $uriParams = array(
            'controller' => strtolower($request->controller()),
            'id' => $params['id'],
            'action' => $request->action(),
            'params' => $params['params'],
        );

        return URL::site(Route::get('default-index')->uri($uriParams));
    }
}

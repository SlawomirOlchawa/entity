<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Friend_EntityDeleter
 */
class Friend_EntityDeleter extends Friend_Abstract_Entity
{
    /**
     * @param string|null $redirectURL
     */
    public function delete($redirectURL = null)
    {
        $entity = $this->getEntity();

        if (empty($redirectURL))
        {
            $redirectURL = URL::site();
        }

        if (Auth::instance()->logged_in('admin'))
        {
            try
            {
                $token = $this->_controller->request->post('token');

                if (empty($token) OR (!Helper_Token::valid($token)))
                {
                    throw new Exception('Invalid token');
                }

                $entity->delete();
                $this->_controller->redirect($redirectURL);
            }
            catch (Exception $e) { }
        }

        // if not deleted, back to entity page
        $this->_controller->redirect($entity->getURL());
    }
}

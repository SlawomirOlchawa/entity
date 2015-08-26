<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Friend_Subscriber
 */
class Friend_Subscriber extends Friend_Abstract_Entity
{
    /**
     * @param Model_User $user
     */
    public function subscribe(Model_User $user)
    {
        $entity = $this->getEntity();

        if ($entity->loaded())
        {
            if (($user) AND (!$entity->has('users', $user)))
            {
                $entity->add('users', $user);
            }

            $this->_controller->redirect($entity->getURL());
        }
    }

    /**
     * @param Model_User $user
     */
    public function unsubscribe(Model_User $user)
    {
        $entity = $this->getEntity();

        if ($entity->loaded())
        {
            if (($user) AND ($entity->has('users', $user)))
            {
                $entity->remove('users', $user);
            }

            $this->_controller->redirect($entity->getURL());
        }
    }
}

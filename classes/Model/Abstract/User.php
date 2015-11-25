<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_User
 *
 * @property int $id
 * @property string $username
 * @property string $name (alias for 'username')
 * @property string $email
 * @property string $password
 * @property int $logins
 * @property int $last_login
 * @property int $created
 */
class Model_Abstract_User extends Model_Abstract_User_Core
{
    /**
     * Alias for "username" - getter
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if ($key === 'name')
        {
            $key = 'username';
        }

        return parent::get($key);
    }

    /**
     * Alias for "username" - setter
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        if ($key === 'name')
        {
            $key = 'username';
        }

        return parent::set($key, $value);
    }
}

<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_Abstract_Record
 */
abstract class Model_Abstract_Record extends Model_Abstract_ORM
{
    /**
     * Set to false for enable cache reading
     *
     * @var bool
     */
    protected $_reload_on_wakeup = false;

    /**
     * @var bool
     */
    protected $_noCache = false;

    /**
     * @var array
     */
    protected $_defaultFilters = array
    (
        array('trim'),
        array('htmlspecialchars'),
    );

    /**
     * @var string
     */
    protected static $_archiveDatabase = null;

    /**
     * @var bool
     */
    protected static $_autoArchiveEnabled = false;

    /**
     * @return array
     */
    public function rules()
    {
        return array();
    }

    /**
     * @return array
     */
    public function filters()
    {
        return array();
    }

    /**
     * @param string $databaseName
     */
    public static function setArchiveDatabase($databaseName)
    {
        static::$_archiveDatabase = $databaseName;
    }

    /**
     * Enable backup (when delete or update rows)
     */
    public static function enableAutoArchive()
    {
        static::$_autoArchiveEnabled = true;
    }

    /**
     * Disable backup (when delete or update rows)
     */
    public static function disableAutoArchive()
    {
        static::$_autoArchiveEnabled = false;
    }

    /**
     * Check if value is > 0
     * Used for validation
     *
     * @param $value
     * @return bool
     */
    public static function positive($value)
    {
        $result = false;

        if ($value > 0)
        {
            $result = true;
        }

        return $result;
    }

    /**
     * Check if value is >= 0
     * Used for validation
     *
     * @param $value
     * @return bool
     */
    public static function notNegative($value)
    {
        $result = false;

        if ($value >= 0)
        {
            $result = true;
        }

        return $result;
    }

    /**
     * Check datetime format
     * Used for validation
     *
     * @param string $dateTime
     * @return bool
     */
    public static function isValidDateTime($dateTime)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})( ([0-1][0-9]|2[0-3]):([0-5][0-9])(:([0-9][0-9]))?)?$/", $dateTime, $matches))
        {
            if (checkdate($matches[2], $matches[3], $matches[1]))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Check date format
     * Used for validation
     *
     * @param string $date
     * @return bool
     */
    public static function isValidDate($date)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches))
        {
            if (checkdate($matches[2], $matches[3], $matches[1]))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if string contains only allowed characters
     * Used for validation
     *
     * @param string $value
     * @return bool
     */
    public static function alnum($value)
    {
        $expression = '/^[a-zA-Z0-9 \-\.…_ĄĆĘŁŃÓŚŹŻąćęłńóśźż]*[a-zA-Z0-9ĄĆĘŁŃÓŚŹŻąćęłńóśźż]+[a-zA-Z0-9 \-\.…_ĄĆĘŁŃÓŚŹŻąćęłńóśźż]*$/';

        return (bool) preg_match($expression, (string) $value);
    }

    /**
     * Wraps word if longer that $maxWidth
     * Used for filtering
     *
     * @param string $value
     * @param int $maxWidth
     * @return string
     */
    public static function wordWrapUtf8($value, $maxWidth=25)
    {
        $maxWidth = (int)$maxWidth;
        $search = '/(.{1,'.$maxWidth.'})(?:\s|$)|(.{'.$maxWidth.'})/uS';
        $replace = '$1$2'.' ';

        return preg_replace($search, $replace, $value);
    }

    /**
     * Check if field can be empty
     * Used for validation
     *
     * @param  string $field
     * @return bool
     */
    public function allowEmpty($field)
    {
        return !$this->_ruleExists($field, 'not_empty');
    }

    /**
     * Add where condition for set (if array) or single value (else)
     *
     * @param string $field
     * @param mixed $setOrValue
     * @return $this
     */
    public function whereIn($field, $setOrValue)
    {
        $operator = '=';

        if (is_array($setOrValue))
        {
            $operator = 'in';
        }

        return $this->where($field, $operator, $setOrValue);
    }

    /**
     * "Hacked" function count_all - prevents query from resetting after execution
     *
     * @return int
     */
    public function countAll()
    {
        return $this->reset(false)->count_all();
    }

    /**
     * "Hacked" function find_all - prevents query from resetting after execution
     *
     * @return Database_Result
     */
    public function findAll()
    {
        return $this->reset(false)->find_all();
    }

    /**
     * @param int $lifetime
     * @return $this
     */
    public function cached($lifetime = NULL)
    {
        if ($this->_noCache) return $this;

        return parent::cached($lifetime);
    }

    /**
     * @return ORM
     */
    public function delete()
    {
        if (!$this->_loaded) return null;

        if (!empty(static::$_archiveDatabase) AND static::$_autoArchiveEnabled)
        {
            $this->_archive('delete');
        }

        return parent::delete();
    }

    /**
     * Inject operation just before update database record (but after validation, etc)
     */
    protected function _beforeUpdate()
    {
        if (!empty(static::$_archiveDatabase) AND static::$_autoArchiveEnabled)
        {
            $this->_archive('update');
        }
    }

    /**
     * Copy deleted or updated row to corresponding table in backup database
     *
     * @param string $type
     */
    protected function _archive($type = null)
    {
        if (empty(static::$_archiveDatabase)) return;

        $database = $this->_getDatabaseName();

        try
        {
            $sql = 'insert into '.static::$_archiveDatabase.'.'.$this->_table_name.
                ' select *, "'.$type.'", null, null from '.$database.'.'.$this->_table_name.
                ' where '.$database.'.'.$this->_table_name.'.'.$this->_primary_key.' = '.$this->pk();

            DB::query(null, $sql)->execute();
        }
        catch (Exception $e) {}
    }

    /**
     * @return string
     */
    protected function _getDatabaseName()
    {
        $config = Kohana::$config->load('database')->get(Database::$default);

        return $config['connection']['database'];
    }

    /**
     * Check if validation rule exists
     *
     * @param  string $field
     * @param  string $ruleName
     * @return bool
     */
    protected function _ruleExists($field, $ruleName)
    {
        $result = false;

        try
        {
            $fields = $this->rules();
            $rules = $fields[$field];

            foreach ($rules as $rule)
            {
                if (strcmp($ruleName, array_shift($rule)) === 0)
                {
                    $result = true;
                    break;
                }
            }
        }
        catch (Exception $e) {}

        return $result;
    }
}

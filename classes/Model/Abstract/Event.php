<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Model_Abstract_Event
 */
abstract class Model_Abstract_Event extends Model_Abstract_Entity
{
    /**
     * @return Model_Abstract_Event
     */
    abstract public function onlyIncoming();

    /**
     * @return Model_Abstract_Event
     */
    abstract public function onlyPast();

    /**
     * @param string $date
     * @return Model_Abstract_Event
     */
    abstract public function olderThan($date);

    /**
     * @param string $date
     * @return Model_Abstract_Event
     */
    abstract public function newerThan($date);

    /**
     * @param string $order
     * @return Model_Abstract_Event
     */
    abstract public function orderByDate($order = null);
}

<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Interface Model_Interface_Rankable
 */
interface Model_Interface_Rankable
{
    /**
     * Returns rank of entity, e.g. number of citizens can be rank of city
     *
     * @return int
     */
    public function getRank();
}

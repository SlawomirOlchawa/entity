<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Interface Model_Interface_Urlable
 */
interface Model_Interface_Urlable
{
    /**
     * Get name of entity in plural
     * Result string must match controller name - part of URL used to show the entity, e.g.:
     * "events" in "http://example.com/events/123-great-tournament"
     * "imprezy" in "http://example.pl/imprezy/123-wielki-turniej"
     *
     * @return string
     */
    public function getPluralName();
}

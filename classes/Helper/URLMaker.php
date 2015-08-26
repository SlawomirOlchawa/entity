<?php
/**
 * @author Sławomir Olchawa <slawooo@gmail.com>
 */

/**
 * Class Helper_URLMaker
 */
class Helper_URLMaker
{
    /**
     * Get escaped name (used as part of URL address)
     *
     * @param string $value
     * @return string
     */
    public static function getURLName($value)
    {
        $value = str_replace('&raquo;','-',$value);
        $value = str_replace(':','-',$value);
        $value = preg_replace('/&[#0-9a-zA-Z]+;/','', $value);
        $trans = array("ą"=>"a","ę"=>"e","ś"=>"s","ż"=>"z","ź"=>"z","ć"=>"c","ń"=>"n","ł"=>"l","ó"=>"o",
            "Ą"=>"a","Ę"=>"e","Ś"=>"s","Ż"=>"z","Ź"=>"z","Ć"=>"c","Ń"=>"n","Ł"=>"l","Ó"=>"o");
        $value = trim(strtr($value, $trans));
        $value = preg_replace('/\s\s+/', '', $value);
        $value = preg_replace('/[^a-zA-Z0-9]+/','-',$value);

        if (strlen($value)>50)
        {
            $value = substr($value,0,50);
        }

        return strtolower($value);
    }
}

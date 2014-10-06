<?php

class OA_View_Helper_LinkRoot
{
	
	protected static $baseurl = null;
 
    public function linkRoot($path)
    {
        if (self::$baseurl === null) {
            $root = '/' . 
                trim(Zend_Controller_Front::getInstance()
                ->getBaseUrl(), '/');
            $root = str_replace("www", "", $root);
             $root = str_replace("WWW", "", $root);    
            if ($root == '/') $root = '';
            self::$baseurl = $root . '/';
        }
        
        return self::$baseurl . ltrim($path, '/');
    }



}
?>
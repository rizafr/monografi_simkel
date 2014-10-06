<?php 

class OA_View_Helper_MenuCreator
{
    public $view;
    protected $_count = 0;
    public function  menuCreator()
    {
        $this->_count++;
        $output = "I have seen 'The Jerk' {$this->_count} time(s).";
        return htmlspecialchars($output);
    }
	   

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}



?>
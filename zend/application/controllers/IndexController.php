<?php
class IndexController extends Zend_Controller_Action
{

   public function init()
	 {
   $this->view->headTitle()->set('Zend Framework Quickstart Application');
		}
  public function indexAction()
  {
   $this->view->assign('title', 'Hello, World!');
 	 $this->view->assign('wellcome','Wellcome to my site. This site is built using Zend Framework. Enjoy it!');
  }
    
}
?>
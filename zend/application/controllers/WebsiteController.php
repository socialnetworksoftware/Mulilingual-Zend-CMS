<?php
class  WebsiteController extends Zend_Controller_Action
{

  public function init()
	 {
			$translate = new Zend_Translate(
				array(
									'adapter' => 'gettext',
									'content' => '../application/language/website',
									'scan' => Zend_Translate::LOCALE_FILENAME
									)
				 );
   Zend_Registry::set('Zend_Translate', $translate);
			$this->view->translate = Zend_Registry::get('Zend_Translate');
			$currLanguage = new Zend_Session_Namespace('currLanguage');
   if(isset($_GET['lang']))
			{
    $lang=tep_db_prepare_input($_GET['lang']);
    if($translate->isAvailable($lang))
				$currLanguage->language=$lang;
			}
			if(!$currLanguage->language)
			{
					$currLanguage->language='en';
			}			
			$currLanguage=$currLanguage->language;
			$translate->setLocale($currLanguage);
			if($currLanguage=='en')
			{
			 $currLanguageLink='<a href="?lang=fr">French</a>';
			}
			else
			 $currLanguageLink='<a href="?lang=en">English</a>';
 		$this->view->assign('currLanguage',$currLanguageLink);

 	 $this->view->headTitle()->set('User Website Page');
 	 $ajaxContext = $this->_helper->getHelper('AjaxContext');
   $ajaxContext->addActionContext('list', 'html')
	                ->initContext();
		}
  public function indexAction()
  { 
    $request = $this->getRequest();  
    $auth		= Zend_Auth::getInstance(); 
	   if(!$auth->hasIdentity()){
	   $this->_redirect('/user/loginform');
	   }else{
      $this->_redirect('/website/list');
	   }
  }
   public function addAction()
		{
				$auth		= Zend_Auth::getInstance(); 			
				if(!$auth->hasIdentity())
				{
						$this->_redirect('/user/loginform');
				}
			
				$request = $this->getRequest();
				$error_message=$this->_getParam('error_message');
				if($error_message!='')
				{
					$this->view->assign('error_message',($error_message));
					$website_name           = tep_db_prepare_input($request->getPost('website_name'));
					$website_url            = tep_db_prepare_input($request->getPost('website_url'));
					$this->view->assign('website_name',$website_name);
					$this->view->assign('website_url',$website_url);
				}
		 	$registry 	= Zend_Registry::getInstance();
    $translate =$registry['Zend_Translate'];

				
				$this->view->assign('action',"process");
				$this->view->assign('title',$translate->_('Add Website'));
				$this->view->assign('label_wname',$translate->_('Website  Name'));
				$this->view->assign('label_wurl',$translate->_('Website  Url'));	
				$this->view->assign('label_submit',$translate->_('Add'));		
				$this->view->assign('description',$translate->_('Please enter this form completely:'));		
		}  
  public function editAction()
  {
   $auth		= Zend_Auth::getInstance(); 
			if(!$auth->hasIdentity())
			{
	   $this->_redirect('/admin/loginform');
  	}
   $user_session = new Zend_Session_Namespace('UserSession');
   if(!$user_session->UserID)
	  $this->_redirect('/user/loginform');
 		$user_id =$user_session->UserID;
			$registry = Zend_Registry::getInstance();  
			$DB = $registry['DB'];
   $translate =$registry['Zend_Translate'];


   $request = $this->getRequest();
   $id 	  = $request->getParam("id");
		 $validator = new Zend_Validate_Digits();
			if (!$validator->isValid($id))
			{
				$this->_redirect('/website/list');
			}
			else
   {
				$sql = $DB->select()
            ->from(array('w' => WEBSITE_TABLE))
							    ->where($DB->quoteInto('id = ? ',$id))
							    ->where($DB->quoteInto('user_id = ? ',$user_id));
				$result = $DB->fetchRow($sql);
				if(!$result->id)
				{
 				$this->_redirect('/website/list');
				}
			}

   if ($request->isPost() &&  $request->getParam("error_message") !='')
			{
				$website_title 	     = tep_db_prepare_input($request->getPost('website_title'));
				$website_url 	       = tep_db_prepare_input($request->getPost('website_url'));
				$error_message = $request->getParam("error_message");
				$post_data=array('website_id'=>$id,
																					'website_title'=>$website_title,
																					'website_url'=>$website_url,
																									);
				$this->view->assign('error_message',$error_message);

			}
		 else
			{
				///////////////
			
				$sql = $DB->select()
													->from(array('u' => WEBSITE_TABLE))
													->where($DB->quoteInto('id = ? ',$id))
													->where($DB->quoteInto('user_id = ? ',$user_id));
				$result = $DB->fetchRow($sql);
				//print_r($result);die();
		 	$this->view->assign('website_id',$result->id);
		 	$this->view->assign('website_title',$result->title);
		 	$this->view->assign('website_url',$result->url);
			}
			$this->view->assign('action', $request->getBaseURL()."/website/processedit");
			$this->view->assign('title',$translate->_('Edit Website'));
			$this->view->assign('label_wname',$translate->_('Website  Name'));
			$this->view->assign('label_wurl',$translate->_('Website  Url'));	
			$this->view->assign('label_submit',$translate->_('Update'));		
			$this->view->assign('description',$translate->_('Please update this form completely:'));		
  }  
  
  public function processAction()
  {
 		$auth		= Zend_Auth::getInstance(); 			
			if(!$auth->hasIdentity())
			{
					$this->_redirect('/user/loginform');
			}
   $user_session = new Zend_Session_Namespace('UserSession');
   if(!$user_session->UserID)
	  $this->_redirect('/user/loginform');
 		$user_id =$user_session->UserID;
  
    $request 	= $this->getRequest();
			 $registry = Zend_Registry::getInstance();  
    $DB = $registry['DB'];
    $translate =$registry['Zend_Translate'];

 		//print_r($this->_helper);
    if ($this->getRequest()->isPost())
	   {
			  $website_title 	    = tep_db_prepare_input($request->getPost('website_title'));
			  $website_url 	      = tep_db_prepare_input($request->getPost('website_url'));
					$error_message ='';
     if($website_title =='')
					{
				 	$error_message .=$translate->_('Please enter website title.')."\n";
    	}
					$element_url = new Zend_Form_Element_Text('website_url');
     $element_url->setRequired(true)
								->addFilter('StringTrim')
        ;
			 	if (!$element_url->isValid($website_url))
					{
 				 	$error_message .=$translate->_('Please enter Website Url.')."\n";
					}
					else
					{
						if(!preg_match('/^(http|https):\/\//i',$website_url))
      $website_url="http://".$website_url;
					}
	   }
				else
			 {
				  $this->_redirect('/website/add');
   	}
				$error_message=tep_db_prepare_input($error_message);
    if($error_message!='')
			 {
			 	$this->_forward('add',null, null, array('error_message' => $error_message));
				}
				else
			 {
	    $data = array('title' => $website_title,
	                  'url' => $website_url,
				               'user_id' => $user_id,
	                 );
     $DB->insert(WEBSITE_TABLE, $data);
			  $this->_redirect('/website/list');
			 }	
  }
  
  public function listAction()
  {
			$auth		= Zend_Auth::getInstance(''); 
			if(!$auth->hasIdentity())
			{
				$this->_redirect('/user/loginform');
			}

			$user_session = new Zend_Session_Namespace('UserSession');
			if(!$user_session->UserID)
			$this->_redirect('/user/loginform');
			$user_id =$user_session->UserID;
  
 $registry = Zend_Registry::getInstance();  
	$DB = $registry['DB'];
 $translate =$registry['Zend_Translate'];

	$DB->setFetchMode(Zend_Db::FETCH_BOTH);
//$sql = "SELECT * FROM `user` ORDER BY user_email_address ASC";
	$sql = $DB->select()
            ->from(array('u' => WEBSITE_TABLE))
								    ->where($DB->quoteInto('user_id = ? ',$user_id))
            ->order('title');
//	echo $sql ;die();
	$adapter = new Zend_Paginator_Adapter_DbSelect($sql);
	$paginator = new Zend_Paginator($adapter);
	$paginator->setItemCountPerPage(2);
	$paginator->setCurrentPageNumber($this->_getParam('page',1));
 $result =  $paginator->getCurrentItems();
	$request = $this->getRequest();
 $base_url= $request->getBaseURL()."/website/list";
 $ajax_url= $request->getBaseURL()."/jscript/jquery-1.7.2.min.js";

	$paginator->setPageRange(5);
	$paginator->getPages('Sliding');
	$page =	$paginator->getPages();
	//	print_r($page);
		
//		die();
		$display_page='';
		//print_r($page);
		if(isset($page->previous))
  $display_page='&lt;&lt; <a  onclick="getContents(\''.$base_url.'/page/'.$page->previous.'\');">'.$translate->_('Previous Page').'</a>&nbsp;|&nbsp;';
 if($page->pageCount>1)
	  foreach ($page->pagesInRange as $page1ist)
			{

		 	if($page1ist != $page->current)
				{
  	   $display_page.=' <a  onclick="getContents(\''.$base_url.'/page/'.$page1ist.'\');"   >'.$page1ist.'</a>&nbsp;|&nbsp;';
				}
				else 
				{
				 $display_page.='<b>'.$page1ist.'</b>&nbsp;';
					if($page1ist != $page->last)
		   $display_page.='|&nbsp;';
				}
			}
		if(isset($page->next))
 	 $display_page.='&nbsp;&nbsp;<a onclick="getContents(\''.$base_url.'/page/'.$page->next.'.\',\'use_list_data\');" >'.$translate->_('Next Page').'</a>&gt;&gt;';

  if($page->pageCount>0)
		{
  //$display_page.='<br>Display Page '.$page->current.' out of '.$page->last.' page';
		 if($page->last=1)
    $display_page.='<br>'.sprintf($translate->_('Display Page %1$d out of %1$d page'),$page->current,$page->last);
		 else
    $display_page.='<br>'.sprintf($translate->_('Display Page %1$d out of %1$d pages'),$page->current,$page->last);
		}
//
//	print_r($result);
//print_r($_SERVER);
   $user_page_link= '<a href="'.$request->getBaseURL().'/user/">'.$translate->_('User').'</a>';

    $this->view->assign('user_page_link',$user_page_link);
    $this->view->assign('base_url',dirname($base_url));
    $this->view->assign('ajax_url',$ajax_url);
    $this->view->assign('title',$translate->_('Website List'));
	   $this->view->assign('description',$translate->_('Below, our Websites:'));
    $this->view->assign('datas',$result);		  
    $this->view->assign('display_page',$display_page);		  
  }
   public function processeditAction()
  {
   $auth		= Zend_Auth::getInstance(); 
   if(!$auth->hasIdentity())
			{
	   $this->_redirect('/admin/loginform');
			}
   $user_session = new Zend_Session_Namespace('UserSession');
   if(!$user_session->UserID)
	  $this->_redirect('/user/loginform');
 		$user_id =$user_session->UserID;


			$registry = Zend_Registry::getInstance();  
	  $DB = $registry['DB'];
   $translate =$registry['Zend_Translate'];

			$request = $this->getRequest();
			$website_title      = tep_db_prepare_input($request->getPost('website_title'));
			$website_url        = tep_db_prepare_input($request->getPost('website_url'));
	  $website_id 	       = tep_db_prepare_input($request->getPost('id'));
			$validator = new Zend_Validate_Digits();
	  if (!$validator->isValid($website_id))
	 	{
		  $this->_redirect('/wbsite/list');
   }
			else
			{
				$sql = $DB->select()
            ->from(array('w' => WEBSITE_TABLE))
							    ->where($DB->quoteInto('id = ? ',$website_id))
							    ->where($DB->quoteInto('user_id = ? ',$user_id));
				$result = $DB->fetchRow($sql);
				if(!$result->id)
				{
 				$this->_redirect('/website/list');
				}
			}
 		$error_message ='';
			if($website_title =='')
			{
				$error_message .=$translate->_('Please enter website title.')."\n";
			}					
			if($website_url =='')
			{
				$error_message .=$translate->_('Please enter website url.')."\n";
			}
			else
			{
					if(!preg_match('/^(http|https):\/\//i',$website_url))
     $website_url="http://".$website_url;
			}			
			$error_message=tep_db_prepare_input($error_message);
   if($error_message!='')
			{
				$this->_forward('edit',null, null, array('error_message' => $error_message));
   }
			else
			{
   	$data = array('title' => $website_title,
	              'url' => $website_url,
	             );
  	 $where[]=	$DB->quoteInto('id = ? ', $website_id);
    $where[]=	$DB->quoteInto('user_id = ? ', $user_id);
    $DB->update(WEBSITE_TABLE, $data,$where);	
		  $this->_redirect('/website/list');
			}		
  }
  
  public function delAction()
  {
   $auth		= Zend_Auth::getInstance(); 
		  if(!$auth->hasIdentity())
			{
				$this->_redirect('/user/loginform');
			}
			$website_id 	       = tep_db_prepare_input($request->getParam('id'));
			$validator = new Zend_Validate_Digits();
	  if (!$validator->isValid($website_id))
	 	{
		  $this->_redirect('/wbsite/list');
   }
   $user_session = new Zend_Session_Namespace('UserSession');
   if(!$user_session->UserID)
	  $this->_redirect('/user/loginform');
 		$user_id =$user_session->UserID;

   $registry = Zend_Registry::getInstance();  
  	$DB = $registry['DB'];
   $translate =$registry['Zend_Translate'];

			$sql = $DB->select()
											->from(array('w' => WEBSITE_TABLE))
										->where($DB->quoteInto('id= ? ',$wbsite_id))
										->where($DB->quoteInto('user_id = ? ',$user_id));
			$result = $DB->fetchRow($sql);
			if(!$result->id)
			{
				$this->_redirect('/website/list');
			}
	  $request = $this->getRequest();
	  
   $DB->delete(WEBSITE_TABLE, 'id = '.$request->getParam('id'));	
	
    $this->view->assign('title',$translate->_('Delete Data'));
	   $this->view->assign('description',$translate->_('Deleting success'));  		  
    $this->view->assign('list',$request->getBaseURL()."/user/list");    
  }
	}
?>

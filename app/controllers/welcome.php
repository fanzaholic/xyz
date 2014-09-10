<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'core/APP_Frontend.php';
class Welcome extends APP_Frontend {

	public function __construct()
	{
		parent::__construct();

		if(!$this->_check_session()){
			redirect('auth/login');
			exit;
		}
	}

	public function index()
	{
		$css = "
		body{ background-color: #f00; }
		";
		$this->_addStyle($css,'embed');

		$js = "
		alert('keren bro');
		";
		$this->_addScript($js,'embed');

		$data = array('pesan' => 'Hahahahaha ini home ya!');
		$this->_addContent('home',$data);
		$this->_render();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
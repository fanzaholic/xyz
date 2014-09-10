<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class APP_Core extends CI_Controller {

	protected $_FOLDER = '';

	protected $_tmp;

	protected $_tmp_object = array(
		'styles' => '',
		'scripts' => '',
		'content' => ''
	);

    protected $_isLog = false;

    protected $_session_name = '';

    protected $_user; //pastikan $this->_user->id untuk id user guna keperluan log

    function __construct($param=array())
    {
        parent::__construct();

        /* load helper/library/model */
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');

        /* Load App config*/
        $this->config->load('app');

        $this->_session_name = $this->config->item('app_session_name');

        $this->_FOLDER = isset($param['folder']) ? $param['folder'] : '' ;

        $this->_isLog = isset($param['log']) ? $param['log'] : FALSE;

        $this->_tmp = (object) $this->_tmp_object;

        if( $this->_isLog )
        {
            $this->_log();
        }
    }

    protected function _render()
    {
    	$data = array();
    	foreach($this->_tmp as $label => $value){
    		$data[$label] = $value;
    	}

    	$this->load->view($this->_FOLDER.'/master',$data);
    }

    protected function _addContent($data)
    {
        $template = strtolower($this->router->class.'_'.$this->router->method);
    	$this->_tmp->content = $this->load->view($this->_FOLDER.'/'.$template,$data,TRUE);
    }

    protected function _addStyle($data,$type='inline')
    {
    	if($type=='embed'){
    		$html = '
    			<style type="text/css">
    			'.$data.'
    			</style>
 			'; 
    	}else{
    		$html = '<link rel="stylesheet" type="text/css" href="'.base_url().$data.'" />';
    	}

    	$this->_tmp->styles .= $html;
    }

    protected function _addScript($data,$type='inline')
    {
    	if($type=='embed'){
    		$html = '
    			<script type="text/javascript">
    			'.$data.'
    			</script>
 			'; 
    	}else{
    		$html = '<script src="'.base_url().$data.'"></script>';
    	}

    	$this->_tmp->scripts .= $html;
    }

    protected function _log()
    {
        $raw_data = array(
            'url'       => current_url(),
            'request'   => $_REQUEST,
            'server'    => $_SERVER
        );

        $data = array(
            'user_id'   =>  '',
            'ip_address'=>  $this->input->ip_address(),
            'controller'=>  $this->router->class,
            'function'  =>  $this->router->method,
            'raw_data'  =>  json_encode($raw_data),
            'created_date' => date('Y-m-d H:i:s')
        );

        $this->db->insert('logs',$data);
    }

    protected function _set_session($data)
    {
        $this->session->set_userdata($this->_session_name, $data);   
    }

    protected function _unset_session()
    {
        $this->session->sess_destroy();
    }

    protected function _check_session()
    {
        $this->_user = $this->session->userdata($this->_session_name);
        if(isset($this->_user->id)){
            return intval($this->_user->id);
        }else{
            return FALSE;
        }
    }
}
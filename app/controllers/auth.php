<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'core/APP_Frontend.php';
class Auth extends APP_Frontend {

	public function __construct()
	{
		parent::__construct();
	}

	public function login()
	{
		$data = array('msg' => $this->session->flashdata('msg'));

		$data['fb_login_url'] = $this->facebook->getLoginUrl(array(
			'scope' => $this->_social_config['facebook']['scope'],
			'redirect_uri' => base_url().$this->_social_config['facebook']['callback']
		));

		$this->_addContent($data);
		$this->_render();
	}

	public function login_process()
	{
		$provider = $this->input->post('provider');
		$provider_uid = $this->input->post('provider_uid');
		$token = $this->input->post('token');

		/* Jika pake email */
		if($provider=='email'){
			if($token=='' || $provider_uid=='')
			{
				$this->session->set_flashdata('msg','Invalid login!');
				redirect('auth/login');
			}			
		}

		$user = $this->user_model->check_user($provider,$provider_uid);

		if(!$user){
			$this->session->set_flashdata('msg','Invalid login!');
			redirect('auth/login');
		}

		/* Jika pake email */
		if($provider=='email')
		{
			if( md5($token)!=$user->token )
			{
				$this->session->set_flashdata('msg','Invalid login!');
				redirect('auth/login');
			}
		}

		/* berhasil login, bikin session */
		$this->_set_session($user);
		redirect('welcome');

	}

	public function register()
	{
		$data = array('msg' => $this->session->flashdata('msg'));
		$this->_addContent($data);
		$this->_render();
	}

	public function register_process()
	{
		$provider = $this->input->post('provider');
		$provider_uid = $this->input->post('provider_uid');
		$email = $this->input->post('email');
		$token = $this->input->post('token');

		/* Jika pake email */
		if($provider=='email'){
			if($token=='' || $provider_uid=='' || $email=='')
			{
				$this->session->set_flashdata('msg','Fill all field!');
				redirect('auth/register');
			}			
		}

		if( $this->user_model->add($provider,$provider_uid,$token,'',array()) ){
			$this->session->set_flashdata('msg','Register success');
			redirect('auth/register');
		}else{
			$this->session->set_flashdata('msg','Register failed');
			redirect('auth/register');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
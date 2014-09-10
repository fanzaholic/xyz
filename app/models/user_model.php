<?php
class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function check_user($provider,$provider_uid)
    {
    	$this->db->select('*');
    	$this->db->from('user_auth');
    	$this->db->join('user','user_auth.user_id=user.id','left');
    	$this->db->where('user_auth.provider_uid',$provider_uid);
		$this->db->where('user_auth.provider',$provider);
		$this->db->limit(1);
		$user = $this->db->get()->row();

		if( isset($user->user_id) && intval($user->user_id) )
		{
			return $user;
		}else{
			return false;
		}
    }

    function add($provider,$provider_uid,$token='',$secret='',$additional=array())
    {
    	$user = $this->check_user($provider,$provider_uid);
    	if( $user ){
    		return false;
    	}

    	$d = array(
    		'uid' => md5(date('U').$provider_uid),
    		'created_date' => date('Y-m-d H:i:s')
    	);

    	if( $this->db->insert('user',$d) ){
    		$new_id = $this->db->insert_id();
    		if( $this->new_auth($new_id,$provider,$provider_uid,$token,$secret,$additional,1) )
    		{
    			return true;
    		}else{
    			$this->db->where('id',$new_id);
    			$this->db->delete('user');
    			return false;
    		}
    	}else{
    		return false;
    	}
    }

    function new_auth($user_id,$provider,$provider_uid,$token='',$secret='',$additional=array(),$primary=0)
    {
    	$user = $this->check_user($provider,$provider_uid);
    	if( $user ){
    		return false;
    	}

        if($provider=='email'){
            $token = md5($token);
        }

    	$d = array(
    		'user_id' => $user_id,
    		'provider' => $provider,
    		'provider_uid' => $provider_uid,
    		'token' => $token,
    		'secret' => $secret,
    		'primary' => $primary,
    		'created_date' => date('Y-m-d H:i:s')
    	);

    	if( $this->db->insert('user_auth',$d) ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    function insert_entry()
    {
        $this->title   = $_POST['title']; // please read the below note
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->insert('entries', $this);
    }

    function update_entry()
    {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }

}
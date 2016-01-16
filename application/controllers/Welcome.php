<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();

        $this->load->library('authentication');
    }

    public function index() {
        echo "hello, world";
//        $user = $this->db->get_where('users', array('username' => "admin"))->row();
//
//        if (!$user) {
//            //用户不存在
//            echo -1;
//        }
//
//        $this->curren_user = $user;
//
//
//        if ($this->curren_user->status !== "active") {
//            echo -3;
//        }
//
//        if (password_verify("hello", $this->curren_user->password)) {
//            echo 0;
//        }
    }
}

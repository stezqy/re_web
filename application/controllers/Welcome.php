<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();

        $this->load->library('authentication');
        $this->load->model("user_model");
    }

    public function index() {
        echo "hello, world\n";
        if ($this->authentication->is_admin()) {
            echo "Manager!\n";
        }
        else {
            echo "user!\n";
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();

//        $this->load->library('authentication');
    }

    public function index()
	{
        echo $this->input->server("REQUEST_URI") ?: $this->uri->uri_string();
	}
}

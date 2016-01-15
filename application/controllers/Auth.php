<?php

/**
 * Created by PhpStorm.
 * User: arvin
 * Date: 2016/1/14
 * Time: 20:54
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $ret_url = $this->input->get("ret");
        if (!$ret_url) {
            $ret_url = "/";
        }

        $this->load->library("authentication", array("force_login" => false));
        if ($this->authentication->is_login()) {
            redirect($ret_url);
        }

        $this->load->view('header/header_not_login.php');
        $this->load->view('login.php');
        $this->load->view('footer/footer.php');
    }

    public function captcha() {
        $this->load->library("captcha/CaptchaBuilder");

        $this->captchabuilder->setBackgroundColor(85,85,85);
        $this->captchabuilder->setTextColor(238,238,238);
        $this->captchabuilder->setDistortion(false);

        $this->captchabuilder->build(80, 35);

        $content = $this->captchabuilder->getPhrase();

        $this->load->library("session");
        $this->session->set_userdata( array(
            'captcha1'		=> $content,
            'captcha1_time'	=> time()
        ) );

        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type: image/jpeg");
        $this->captchabuilder->output();
    }


}
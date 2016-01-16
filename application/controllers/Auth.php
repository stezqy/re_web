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
        $this->load->view('login.php', array("ret" => $ret_url));
        $this->load->view('footer/footer.php');
    }

    public function do_login() {
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $captcha = $this->input->post('captcha');

        $username = trim($username);
        $password = trim($password);
        $captcha = trim($captcha);

        $ret_url = $this->input->post("ret");

        if (!$ret_url) {
            $ret_url = "/";
        }

        if (strlen($username) === 0 || strlen($password) === 0 || strlen($captcha) === 0) {
            $get_array = array(
                "ret" => $ret_url
            );
            redirect('auth' . "?" . http_build_query($get_array));
        }

        $error = null;
        $error_no = 0;

        // 检查验证码
        $this->load->library("session");
        $captcha1 = $this->session->userdata("captcha1");
        $captcha_time = $this->session->userdata("captcha1_time");

        // 验证码已使用，销毁
        $this->session->unset_userdata("captcha1");
        $this->session->unset_userdata("captcha1_time");

        if (!$captcha_time || !$captcha1) {
            // 验证码失效
            $error = "验证码失效";
            $error_no = -12;
        }
        else if ($captcha_time + 600 < time()) {
            // 验证码超过600s有效期
            $error = "验证码已过期";
            $error_no = -11;
        }
        else {
            $this->load->library("captcha/CaptchaBuilder");
            $this->captchabuilder->setPhrase($captcha1);

            if (!$this->captchabuilder->testPhrase($captcha)) {
                $error = "验证码错误";
                $error_no = -13;
            }
        }

        header("Content-Type: application/json");

        if ($error_no != 0) {
            // ajax请求
            echo json_encode(array(
                "error" => $error,
                "error_no" => $error_no
            ));

            return false;
        }

        //检查用户名和密码
        $this->load->library("authentication", array('force_login' => false));
        $error_no = $this->authentication->login($username, $password);

        // int 0:登陆成功, -1:用户不存在, -2:密码错误, -3:用户被禁用
        switch ($error_no) {
            case -1:
            case -2:
                $error = "用户或密码错误";
                break;
            case -3:
                $error = "用户被禁用";
                break;
        }

        echo json_encode(array(
            "errno" => $error,
            "error_no" => $error_no
        ));

    }

    public function logout() {
        $this->load->library("authentication", array("force_login" => false));
        $this->authentication->logout();
        redirect("auth");
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
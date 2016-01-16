<?php

/**
 * Created by PhpStorm.
 * User: arvin
 * Date: 2016/1/15
 * Time: 12:55
 */
class Authentication {
    private $default_config = array(
        'force_login' => true,
        'login_page' => 'auth',
        'json_output' => false
    );

    private $ci;

    public function __construct($config_array = null) {
        if (is_array($config_array)) {
            $this->default_config = array_merge($this->default_config, $config_array);
        }

        $this->ci = &get_instance();
        $this->ci->load->library('session');
        $this->ci->load->model('user_model');

        if ($this->default_config['force_login']) {
            $this->ensure_login();
        }
    }

    /**
     * 确定用户已经登陆，则跳转至返回连接，否则强制跳转至登陆界面
     * @param null $ret_url 确定登陆后跳转链接
     * @return bool true: 用户已登陆
     */
    public function ensure_login($ret_url = null) {
        if (!trim($ret_url)) {
            $ret_url = $this->ci->input->server("REQUEST_URI") ?: $this->ci->uri->uri_string();
            if (!$ret_url) {
                $ret_url = "/";
            }
        }

        if ($this->is_login()) {
            return true;
        }

        if ($this->default_config['json_output']) {
            $ret_data = array(
                "error_no" => -1,
                "error" => "need login"
            );

            header("Content-Type: application/json");
            echo json_encode($ret_data);
            die();
        }
        else {
            $get_array = array(
                "ret" => $ret_url
            );

            redirect($this->default_config['login_page'] . "?" . http_build_query($get_array));
        }
    }

    /**
     * 检查session中uid值确定用户时候登陆
     * @return bool true: 用户已登陆, false: 用户未登陆
     */
    public function is_login() {
        if ($uid = $this->ci->session->userdata("uid")) {
            if ($this->ci->user_model->validate_uid($uid) == 0) {
                return true;
            }

            $this->ci->session->unset_userdata("uid");
        }

        return false;
    }

    public function login($username, $password) {
        $username = trim($username);
        $password = trim($password);

        $login_result = $this->ci->user_model->login($username, $password);

        if ($login_result == 0) {
            $this->ci->session->set_userdata("uid", $this->ci->user_model->get_uid());
        }

        return $login_result;
    }

    public function logout() {
        $this->ci->session->unset_userdata("uid");
        return true;
    }
}
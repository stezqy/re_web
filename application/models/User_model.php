<?php

/**
 * Created by PhpStorm.
 * User: arvin
 * Date: 2016/1/15
 * Time: 17:20
 */
class User_model extends CI_Model{
    /*
     * 用户数据库格式
     * id           1
     * username     "admin"
     * password     password_hash("hello",PASSWORD_BCRYPT)
     * status       "active"
     *              "freeze"
     * role         "admin"
     * update       time()
     */

    //缓存当前用户，避免多次查询
    private $curren_user = null;

    function __construct() {
        parent::__construct();
    }


    /**
     * 验证用户登陆
     * @param $username 传入用户名
     * @param $password 传入密码
     * @return int 0:登陆成功, -1:用户不存在, -2:密码错误, -3:用户被禁用
     */
    public function login($username, $password) {
        if (is_null($this->curren_user)) {
            $user = $this->db->get_where('users', array('username' => $username))->row();

            if (!$user) {
                //用户不存在
                return -1;
            }

            $this->curren_user = $user;

        }

        if ($this->curren_user->status !== "active") {
            return -3;
        }

        if (password_verify($password, $this->curren_user->password)) {
            return 0;
        }
        else {
            return -2;
        }
    }

    /**
     * 增加一个用户
     * @param $username
     * @param $password
     * @param $status
     * @param $role
     * @param $update
     * @return int 0:添加成功, -1:添加失败
     */
    public function add_user($username, $password, $status, $role, $update) {
        $m_username = $username;
        $m_password = password_hash($password, PASSWORD_DEFAULT);
        $m_status = ($status !== "active") ? "freeze": "active";
        $m_role = ($role !== "admin") ? "user": "admin";
        $m_update = $update;
        $data = array(
            'username' => $m_username,
            'password' => $m_password,
            'status' => $m_status,
            'role' => $m_role,
            'update' => $m_update,
        );

        $this->db->insert('users', $data);
    }

    public function get_uid() {
        if(is_null($this->curren_user)) {
            return null;
        }

        return strval($this->curren_user->id);
    }

    public function get_role() {
        if(is_null($this->curren_user)) {
            return null;
        }

        return strval($this->curren_user->role);
    }

    public function get_username() {
        if(is_null($this->curren_user)) {
            return null;
        }

        return strval($this->curren_user->username);
    }

    public function validate_uid($uid) {
        if (is_null($this->curren_user) || $uid !== strval($this->curren_user->id)) {
            //$current_user没有缓存或者不符合$uid，重新在数据库中查找
            $user = $this->db->get_where('users', array("id" => $uid))->row();

            if (!$user) {
                return -1;
            }

            $this->curren_user = $user;
        }

        if ($this->curren_user->status !== "active") {
            return -3;
        }

        return 0;

    }

}
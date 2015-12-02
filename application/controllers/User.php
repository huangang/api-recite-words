<?php
/**
 * ReciteWords API framework
 *
 * 此版本API为V1版本API
 *
 * @package	ReciteWords
 * @author	ReciteWords Dev Team
 * @copyright	Copyright (c) 2015 - 2016, ReciteWords Co,Ltd. (http://www.huangang.net/)
 * @link	http://www.huangang.net/
 * @since	Version 1.0.0
 * @filesource
 */


/**
 * User Controller Class
 * 用户操作接口
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      User
 * @author        huangang
 * @link
 */
class User extends MY_Controller{



    /**
     * 登录
     *
     *
     * @internal param string $mobile `required` 用户注册手机号
     * @internal param string $password `required` 用户密码，md5加密后
     *
     *
     * ------
     *
     * @return json
     *
     * ```
     * 返回结果
     *  {
     *  }
     * ```
     *
     *------------
     * @throws Exception
     * @version 1.0.0
     * @author  huangang
     */
    public function login(){
        $mobile = $this->input->post_get('mobile');
        $password = $this->input->post_get('password');
    }


    /**
     * 微信登录
     *
     *
     * @internal param string $mobile `required` 用户注册手机号
     * @internal param string $password `required` 用户密码，md5加密后
     *
     *
     * ------
     *
     * @return json
     *
     * ```
     * 返回结果
     *  {
     *  }
     * ```
     *
     *------------
     * @throws Exception
     * @version 1.0.0
     * @author  huangang
     */
    public function wx_login(){

    }

    public function register(){

    }



}
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
        $open_id = $this->input->get_post('openid');
        $user_model = $this->Model_bus->get_user_model();
        $user = $user_model->get_user(array('openid' => $open_id));
        if($user){
            dump($user);
        }else{
            $this->load->helper('url');
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=http://" .$_SERVER['HTTP_HOST']. "/user/oauth/&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            redirect($url);
        }
    }


    public function oauth(){
        $code = $this->input->get_post('code');
        $user_info = $this->get_wx_user_info($code);
        $user_data = array('openid' => $user_info['openid'],'nickname' => $user_info['nickname'],
            'head' => $user_info['headimgurl']);
        $user_model = $this->Model_bus->get_user_model();
        $user_id = $user_model->create($user_data);
        $user = $user_model->get_user(array('id' => $user_id, 'openid' => $user_info['openid']));
        dump($user);
    }

    public function register(){

    }


    /**
     * 获取微信用户
     *
     *
     * @param string $code `required` 微信code
     *
     *
     * ------
     *
     * @return array
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
    private function get_wx_user_info($code){
        $app_id = APPID;
        $app_secret = APPSECRET;
        //oauth2的方式获得openid和access_token
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$app_id&secret=$app_secret&code=$code&grant_type=authorization_code";
        $access_token_json = https_request($access_token_url);
        $access_token_array = json_decode($access_token_json, true);
        $openid = $access_token_array['openid'];
        $access_token = $access_token_array['access_token'];
        $user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $user_info_json = https_request($user_info_url);
        $user_info_array = json_decode($user_info_json, true);
        return $user_info_array;
    }




}
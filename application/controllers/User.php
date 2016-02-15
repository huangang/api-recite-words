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
     * app登录
     *
     *
     * @param string $mobile `required` 用户注册手机号
     * @param string $password `required` 用户密码，md5加密后
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
        $mobile = (int)$this->input->post_get('mobile', TRUE);
        $this->check_parameter(array('mobile' => $mobile));
        $password = (string)$this->input->post_get('password',TRUE);
        $this->check_parameter(array('password' => $password));
        $user_model = $this->Model_bus->get_user_model();
        $user = $user_model->get_user(array('mobile' => $mobile, 'password' => $password));
        if($user){
            $this->output($user,self::success, JSON_NUMERIC_CHECK);
        }else{
            $this->output(array('msg' => 'login fail'), ErrorCodes::logic_error);
        }
    }


    /**
     * 微信登录
     *
     *
     * @param string $openid `required` 微信用户openid
     *
     *
     * ------
     *
     * @return void
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
        $openid = $this->input->get_post('openid', TRUE);
        $this->check_parameter(array('openid' => $openid));
        $user_model = $this->Model_bus->get_user_model();
        $user = $user_model->get_user(array('openid' => $openid));
        $this->load->helper('url');
        if($user){
            redirect('http://app.pupued.com/#/wx-login?openid='.$openid.'&uid='.$user->id.'&nickname='.$user->nickname.'&head='.$user->head);
        }else{
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=http://" .$_SERVER['HTTP_HOST']. "/user/oauth/&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            redirect($url);
        }
    }



    /**
     * 微信授权注册
     *
     *
     * @param string $code `required` 微信code
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
     * @version 1.0.0
     * @author  huangang
     */
    public function oauth(){
        $code = $this->input->get_post('code', TRUE);
        $this->check_parameter(array('code' => $code));
        $user_info = $this->get_wx_user_info($code);
        $user_data = array('openid' => $user_info['openid'],'nickname' => $user_info['nickname'],
            'head' => $user_info['headimgurl']);
        $user_model = $this->Model_bus->get_user_model();
        $user_id = $user_model->create($user_data);
        $user = $user_model->get_user(array('id' => $user_id, 'openid' => $user_info['openid']));
        $this->load->helper('url');
        redirect('http://app.pupued.com/#/wx-login?openid='.$user_info['openid'].'&uid='.$user->id.'&nickname='.$user->nickname.'&head='.$user->head);
    }

    /**
     * app注册
     *
     * @param string $mobile `required` 用户注册手机号
     * @param string $nickname `required` 昵称
     * @param string $password `required` 用户密码，md5加密后
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
     * @version 1.0.0
     * @author  huangang
     */
    public function register(){
        $mobile = $this->input->post_get('mobile', TRUE);
        $this->check_parameter(array('mobile' => $mobile));
        if($this->Model_bus->get_user_model()->check_value('mobile',$mobile)){
            $this->output(array('msg' => '手机号已注册请直接登录', ErrorCodes::API_ERROR));
            exit;
        }
        $nickname = $this->input->post_get('nickname', TRUE);
        $nickname = trim($nickname);
        $this->check_parameter(array('nickname' => $nickname));
        if($this->Model_bus->get_user_model()->check_value('nickname',$nickname)){
            $this->output(array('msg' => '昵称已经存在,请更换', ErrorCodes::API_ERROR));
            exit;
        }
        $password = $this->input->post_get('password', TRUE);
        $this->check_parameter(array('password' => $password));
        $data = array('nickname' => $nickname,'mobile' => $mobile,'password' => $password);
        $ret = $this->Model_bus->get_user_model()->create($data);
        if(!empty($ret)){
            $this->output(array('id' => $ret ,'nickname' => $nickname));
        }else{
            $this->output(array('msg' => '注册失败', ErrorCodes::API_ERROR));
        }
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


    /**
     * 微信获取用户信息
     *
     *
     * @param string $openid `required` openid
     * @param int $uid `required` 用户ID
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
     * @version 1.0.0
     * @author  huangang
     */
    public function wx_get_user_info(){
        $openid = $this->input->get_post('openid',TRUE);
        $this->check_parameter(array('openid' => $openid));
        $uid = (int)$this->input->get_post('uid',TRUE);
        $this->check_parameter(array('uid' => $uid));
        $user_model = $this->Model_bus->get_user_model();
        $user = $user_model->get_user(array('openid' => $openid,'id' => $uid));
        $this->output($user, self::success, JSON_NUMERIC_CHECK);
    }



    /**
     * 更新用户信息
     *
     *
     * @param int $uid `required` 用户ID
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
     * @version 1.0.0
     * @author  huangang
     */
    public function update(){
        $uid = (int)$this->input->post_get('uid', TRUE);
        $this->check_parameter(array('uid' => $uid));
        $nickname = $this->input->post_get('nickname', TRUE);
        $this->load->library('QiniuUp.php');
        $avatar = isset($_FILES['avatar']) ? $_FILES['avatar']['tmp_name'] : null;
        $data = array('nickname' => $nickname);
        if(!empty($avatar)){
            $data['head'] = QiniuUp::uploadImg($avatar);
        }
        $this->Model_bus->get_user_model()->update($uid, $data);
        $user = $this->Model_bus->get_user_model()->get_user(array('id' => $uid));
        $this->output(array('head' => $user->head,'nickname' => $user->nickname));
    }

    /**
     * 获取用户信息
     *
     *
     * @param int $uid `required` 用户ID
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
     * @version 1.0.0
     * @author  huangang
     */
    public function get_user(){
        $uid = (int)$this->input->get_post('uid', TRUE);
        $this->check_parameter(array('uid' => $uid));
        $user = $this->Model_bus->get_user_model()->get_user(array('id' => $uid));
        unset($user->password);
        $this->output($user, self::success, JSON_NUMERIC_CHECK);
    }


    /**
     * 获取用户的单词本
     *
     *
     * @param int $uid `required` 用户ID
     * @param int $num `optional` 数量
     * @param int $since_id `optional` 起始ID
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
     * @version 1.0.0
     * @author  huangang
     */
    public function get_vocabulary(){
        $uid = (int)$this->input->post_get('uid', TRUE);
        $num = (int)$this->input->post_get('num', TRUE);
        $num = empty($num) ? 10 : $num;
        $since_id = (int)$this->input->post_get('since_id', TRUE);
        $ret = $this->Model_bus->get_vocabulary_model()->gets($uid, $since_id, $num);
        dump($ret);
    }
}
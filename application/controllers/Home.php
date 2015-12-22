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
 * Home Controller Class
 * 微信主入口
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      Home
 * @author        huangang
 * @link
 */
class Home extends MY_Controller{


    private $content = null;
    private $open_id = null;
    private $weObj   = null;

    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
        @$this->load->library('Wechat');
        if(empty($this->weObj)){
            $options = array(
                'token'=> Token, //填写你设定的key
                //'encodingaeskey'=> ENCODINGAESKEY, //填写加密用的EncodingAESKey，如接口为明文模式可忽略
                'appid' => APPID,
                'appsecret' => APPSECRET,
                'debug' => true,
            );
            $this->weObj = new Wechat($options);
        }
        $this->load->library('Reply');
    }

    /**
     * 主入口
     *
     *
     * ------
     *
     * @return  void
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    public function index(){
        $this->weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $this->open_id = $this->weObj->getRev()->getRevFrom();
        $this->content = $this->weObj->getRev()->getRevContent();
        $this->content = $this->safe_replace($this->content);
        $type = $this->weObj->getRev()->getRevType();
        switch($type) {
            /*文本事件回复*/
            case Wechat::MSGTYPE_TEXT:
                $reply = $this->reply_func($this->content);
                if(is_string($reply)){
                    $this->weObj->text($reply)->reply();
                } else if(is_array($reply)) {
                    $this->weObj->news($reply)->reply();
                }
                exit;
                break;
            /*关注事件回复*/
            case Wechat::EVENT_SUBSCRIBE:
                $this->weObj->text("welcome, I'm wechat")->reply();
                exit;
                break;
            /*地理事件回复*/
            case Wechat::MSGTYPE_LOCATION:
                exit;
                break;
            /*图片事件处理*/
            case Wechat::MSGTYPE_IMAGE:
                $reply = '图片处理';
                $value = $this->Model_bus->get_common_model()->get_lock_value($this->open_id);
                if(!empty($value)){
                    switch($value){
                        case Reply::ModifyAvatar:
                            $pic = $this->weObj->getRevPic();
                            $this->Model_bus->get_user_model()->update_by_wx($this->open_id,array('head' => $pic['picurl']));
                            $this->Model_bus->get_common_model()->unlocking($this->open_id, Reply::ModifyAvatar);
                            $reply = '头像修改成功';
                            break;
                    }
                }
                $this->weObj->text($reply)->reply();
                exit;
                break;
            /*语音识别*/
            case Wechat::MSGTYPE_VOICE:
                exit;
                break;
            /*事件*/
            case Wechat::MSGTYPE_EVENT:
                $rev_data = $this->weObj->getRevEvent();
                $event = $rev_data['event'];
                $key = $rev_data['key'];
                switch($event){
                    case Wechat::EVENT_MENU_CLICK:
                        $word = $this->Model_bus->get_study_model()->rand_word();
                        $word_info = file_get_contents(QUERY_WORD_API.$word['word']);
                        $word_info = json_decode($word_info);
                        $word['meaning'] = str_replace("<br>",'',$word['meaning']);
                        $content = '单词:'.$word['word'] . "\n"
                                   .'释义:'.$word['meaning'] ."\n"
                                   .'音标:'.$word_info->data->pronunciation . "\n"
                                   .'';
                        if(!empty($word['example'])){
                            $word['example'] = str_replace('/r/n',"",$word['example']);
                            $word['example'] = str_replace(' ',"",$word['example']);
                            $content = $content . "\n" .
                                "例子:".$word['example'];
                        }
                        $this->weObj->text($content)->reply();
                        exit;
                        break;
                    default:
                        $this->weObj->text($event)->reply();
                }
                exit;
                break;
            default:
                $this->weObj->text($type)->reply();
        }
    }



    /**
     * 特殊字符串处理
     *
     * @param string $string `required` 文本内容
     *
     * ------
     *
     * @return  string
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    private function safe_replace($string) {
        $string = str_replace('%20','',$string);
        $string = str_replace('%27','',$string);
        $string = str_replace('%2527','',$string);
        $string = str_replace('*','',$string);
        $string = str_replace('"','&quot;',$string);
        $string = str_replace("'",'',$string);
        $string = str_replace('"','',$string);
        $string = str_replace(';','',$string);
        $string = str_replace('<','&lt;',$string);
        $string = str_replace('>','&gt;',$string);
        $string = str_replace("{",'',$string);
        $string = str_replace('}','',$string);
        $string = str_replace(' ','',$string);
        return $string;
    }


    /**
     * 图灵机器人
     *
     * @param string $keyword `required` 用户发起的文本内容
     *
     * ------
     *
     * @return array | string
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    private function tu_ling($keyword) {
        $key = "94e556076c912d2a9f72ba752f8c4750";
        $api_url = "http://www.tuling123.com/openapi/api?key=".$key."&info=". $keyword;
        $result = file_get_contents ( $api_url );
        $result = json_decode ( $result, true );
        $articles = array();
        switch ($result ['code']) {
            case '200000' :
                $text = $result ['text'] . ',<a href="' . $result ['url'] . '">点击进入</a>';
                return $text;
                break;
            case '301000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => $result['list'][$i]['author'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '302000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['article'],
                        'Description' => $result['list'][$i]['source'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '304000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i< $length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => $result['list'][$i]['count'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '305000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['start'] . '--' . $result['list'][$i]['terminal'],
                        'Description' => $result['list'][$i]['starttime'] . '--' . $result['list'][$i]['endtime'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '306000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['flight'] . '--' . $result['list'][$i]['route'],
                        'Description' => $result['list'][$i]['starttime'] . '--' . $result['list'][$i]['endtime'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '307000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => $result['list'][$i]['info'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '308000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => $result['list'][$i]['info'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '309000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => '价格 : ' . $result['list'][$i]['price'] . ' 满意度 : ' . $result['list'][i]['satisfaction'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '310000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['number'],
                        'Description' => $result['list'][$i]['info'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '311000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => '价格 : ' . $result['list'][$i]['price'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            case '312000' :
                $length = count($result['list']) > 9 ? 9 :count($result['list']);
                for($i= 0;$i<$length;$i++){
                    $articles [$i] = array (
                        'Title' => $result['list'][$i]['name'],
                        'Description' => '价格 : ' . $result['list'][$i]['price'],
                        'PicUrl' => $result['list'][$i]['icon'],
                        'Url' => $result['list'][$i]['detailurl']
                    );
                }
                return $articles;
                break;
            default :
                if (empty ( $result ['text'] )) {
                    return false;
                } else {
                    return $result ['text'] ;
                }
        }

    }


    /**
     * 文本回复方法
     *
     * @param string $text `required` 用户发起的文本内容
     *
     * ------
     *
     * @return array | string
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    private function reply_func($text){
        $common_model = $this->Model_bus->get_common_model();
        $value = $common_model->get_lock_value($this->open_id);
        if(!empty($value)){
            $reply = $this->lock_reply($value, $text);
        }else{
            switch ($text){
                case Reply::Login:
                    $reply = "<a href='http://api.pupued.com/user/wx_login?openid=$this->open_id' >登录地址</a>";
                    break;
                case Reply::ModifyNickname:
                    $common_model->lock_reply($this->open_id, Reply::ModifyNickname);
                    $reply = "请输入新的昵称";
                    break;
                case Reply::ModifyAvatar:
                    $common_model->lock_reply($this->open_id, Reply::ModifyAvatar);
                    $reply = "请输入图片";
                    break;
                case Reply::IdiomsSolitaire:
                    $common_model->lock_reply($this->open_id, Reply::IdiomsSolitaire);
                    $reply = "请输入一个成语开始接龙吧~比如：一马当先\n回复【退出】即可退出和成语接龙模式";
                    break;
                default:
                    $reply = $this->tu_ling($text);
            }
        }

        return $reply;
    }



    /**
     * 文本回复方法
     *
     * @param string $value `required` 用户锁定的值
     * @param string $text `required` 用户发起的文本内容
     *
     * ------
     *
     * @return  string
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    private function lock_reply($value, $text){
        $common_model = $this->Model_bus->get_common_model();
        $reply = '找不到回复';
        switch($value){
            case Reply::ModifyNickname:
                $this->Model_bus->get_user_model()->update_by_wx($this->open_id, array('nickname' => $text));
                $common_model->unlocking($this->open_id, Reply::ModifyNickname);
                $reply = '昵称修改成功';
                break;
            case Reply::IdiomsSolitaire:
                $reply = $this->idioms($text);
                break;
        }
        return $reply;
    }


    /**
     * 成语接龙
     *
     * @param string $key `required` 值
     *
     * ------
     *
     * @return  string
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    private function idioms($key){
        $common_model = $this->Model_bus->get_common_model();
        if($key==''){
            $text = "请输入一个成语开始接龙吧~比如：一马当先\n回复【退出】即可退出和成语接龙模式";
        }elseif ($key == '退出') {
            $text = '已退出成语接龙模式，再次发送【成语接龙】即可开启';
            $common_model->unlocking($this->open_id , Reply::IdiomsSolitaire);
        }else{
            $reply=file_get_contents(IDIOMS . $key);
            if($reply == '别来骗人家，不是随便打4个字就是成语哒！' || $reply=='成语必须为4个汉字'){
                $text = $reply.'重新输入一个成语开始接龙,输入【退出】退出成语接龙';
            }else{
                $text = $reply;
            }
            $common_model->lock_reply($this->open_id , Reply::IdiomsSolitaire);
        }
        return $text;
    }

    /**
     * 设置按钮
     *
     *
     * ------
     *
     * @return void
     *
     * ```
     * 返回结果
     *
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    public function  set_menu(){
        $new_menu =  array(
    		"button"=>
  			array(
                array('type'=>'click','name'=>'背单词','key'=>'Recite_Words'),
  				array('type'=>'view', 'name'=>'登录','url'=>'http://app.pupued.com/#/login'),
            )
 		);
        $result = $this->weObj->createMenu($new_menu);
        dump($result);
    }





}
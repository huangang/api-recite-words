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
 * 项目主入口
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      Home
 * @author        huangang
 * @link
 */
class Home extends MY_Controller
{


    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * 主入口
     */
    public function index()
    {
        @$this->load->library('Wechat');
        $options = array(
            'token'=> Token, //填写你设定的key
            //'encodingaeskey'=> ENCODINGAESKEY, //填写加密用的EncodingAESKey，如接口为明文模式可忽略
            'appid' => APPID,
            'appsecret' => APPSECRET,
            'debug' => true,
        );
        $weObj = new Wechat($options);
        $weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $username = $weObj->getRev()->getRevFrom();
        $content = $weObj->getRev()->getRevContent();
        $content = $this->safe_replace($content);
        $type = $weObj->getRev()->getRevType();
        switch($type) {
            /*文本事件回复*/
            case Wechat::MSGTYPE_TEXT:
                $reply = $this->tu_ling($content);
                if(gettype($reply) == string){
                    $weObj->text($reply)->reply();
                } else if(is_array($reply)) {
                    $weObj->news($reply)->reply();
                }
                exit;
                break;
            /*关注事件回复*/
            case Wechat::MSGTYPE_EVENT:
                $weObj->text("hello, I'm wechat")->reply();
                exit;
                break;
            /*地理事件回复*/
            case Wechat::MSGTYPE_LOCATION:
                exit;
                break;
            /*图片事件处理*/
            case Wechat::MSGTYPE_IMAGE:
                break;
            /*语音识别*/
            case Wechat::MSGTYPE_VOICE:
                break;
            default:
                $weObj->text("help info")->reply();
        }
    }

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


    // 图灵机器人
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

}
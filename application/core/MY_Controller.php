<?php

/**
 * ReciteWords API framework
 *
 * 此版本API为V1版本API
 *
 * @package	ReciteWords
 * @author	ReciteWords Dev Team
 * @copyright	Copyright (c) 2015 - 2016, ReciteWords Co,Ltd. (http://www.huangang.net/)
 * @link	http://www.huangang.net/)
 * @since	Version 1.0.0
 * @filesource
 */


/**
 * ReciteWords MY_Controller Class
 *
 * Controller基类
 *
 * @property CI_DB_mysqli_driver | CI_DB $db
 * @property CI_Lang $lang                        Language Class
 * @property CI_Loader $load                      Loads views and files
 * @property CI_Log $log                          Logging Class
 * @property CI_Model $model                      CodeIgniter Model Class
 * @property CI_Output $output                    Responsible for sending final output to browser
 * @property CI_Profiler $profiler                This class enables you to display benchmark, query, and other data<br />in order to help with debugging and optimization.
 * @property CI_Router $router                    Parses URIs and determines routing
 * @property CI_Session $session
 * @property CI_config $config
 * @property CI_input $input
 * @property Model_bus $Model_bus
 * @package        ReciteWords
 * @subpackage    API
 * @category    MY_Controller
 * @author        huangang
 * @link
 */

class MY_Controller extends CI_Controller{

    const success = 200;
    const MSG_SUCCESS = 'success';
    const MSG_FAIL = 'fail';

    /**
     * 初始化
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Model_bus");
        $this->load->library("ErrorCodes");
        $router = $this->router->method;
        $class = $this->router->class;
        $params = array_merge($_GET,$_POST);
        $this->Model_bus->get_common_model()->record_quest($params, $class . '/' .$router);
    }


    /**
     * 返回结果
     * @param array|string $data
     * @param int $code
     * @param bool|int $json_options
     * @throws Exception
     */
    public function output( $data = '' , $code = self::success , $json_options = true){
        $ret['data'] = array();
        if(!empty($data)){
            if(!is_array($data)){
                $ret['data'] =  $data;
            }else{
                $ret['data'] = array_merge($ret['data'] , $data);
            }
        }
        if($code == self::success){
            $ret['result'] = 1;
        }else{
            $ret['result'] = $code;
        }
        //check if it's jsonp format
        if(isset($_REQUEST['format']) && $_REQUEST['format'] == 'jsonp' ){
            header('Content-type: application/x-javascript');
            if(!isset($_GET['callback'])){
                throw new Exception('jsonp应该指定callback');
            }
            echo $_GET['callback']."([".json_encode($ret, $json_options)."])";
        }else{
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            echo json_encode( $ret , $json_options);//JSON_NUMERIC_CHECK
        }
    }

    /**
     * 检查参数
     * @param array $parameter 一维数组
     * @throws Exception
     */
    protected function check_parameter($parameter){
        foreach($parameter as $k => $v){
            if(empty($v)){
                throw new Exception($k . ' parameter is null', ErrorCodes::param_error);
            }
        }

    }


    /**
     * 数据类型处理函数
     * @param array $arr
     * @param array $except
     */
    protected function conversion_data(&$arr , $except = array()){
        $except = array_unique(array_merge($except, array('nickname')));
        if(!empty($arr)){
            foreach($arr as $k  => &$v){
                if(is_array($v) && !empty($v)){
                    $this->conversion_data($v, $except);//如果还是数组,进入递归
                }else{
                    if(!in_array($k, $except)){
                        if(is_numeric($v)){
                            if($v == (int)$v) {
                                $v = (int)$v;
                            } elseif($v == (float) $v){
                                $v = (float)$v;
                            } elseif($v == (double)$v){
                                $v = (double)$v;
                            }
                        }
                    }
                }
            }
        }
    }





}
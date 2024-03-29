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
 * Config Controller Class
 * app配置接口
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      Config
 * @author        huangang
 * @link
 */
class Config extends  MY_Controller{


    /**
     * 设置用户每天学习数量
     *
     *
     * @param int $uid `required` 用户ID
     * @param int $num `required` 学习数量
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
    public function set_study_num(){
        $uid = (int)$this->input->post_get('uid', true);
        $this->check_parameter(array('uid' => $uid));
        $num = (int)$this->input->post_get('num', true);
        $num =  empty($num) ? 20 : $num;
        $ret = $this->Model_bus->get_config_model()->set_study_num($uid, $num);
        if($ret){
            $this->output(array('studyNum' => $num));
        }else{
            $this->output(array('msg' => 'set fails', ErrorCodes::response_error));
        }
    }



}
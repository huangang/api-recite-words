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
     * @internal param int $uid `required` 用户ID
     * @internal param int $num `required` 学习数量
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

    /**
     * 获取用户每天学习数量
     *
     *
     * @internal param int $uid `required` 用户ID
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
    public function get_study_num(){
        $uid = (int)$this->input->get_post('uid', true);
        $this->check_parameter(array('uid' => $uid));
        $config_model = $this->Model_bus->get_config_model();
        $num = $config_model->get_study_num($uid);
        $residue_num = $this->Model_bus->get_study_model()->get_today_study_num($uid);
        if($num){
            $this->output(array('studyNum' => (int)$num, 'residueNum' => $residue_num));
        }else{
            $config_model->set_study_num($uid, $config_model::DEFAULT_DAY);
            $this->output(array('studyNum' => $config_model::DEFAULT_DAY, 'residueNum' => $residue_num));
        }
    }

}
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
 * Study Controller Class
 * 学习接口
 *
 *
 * @package       ReciteWords
 * @subpackage    Controller
 * @category      Study
 * @author        huangang
 * @link
 */
class Study extends MY_Controller{

    /**
     * 获取学习单词
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
    public function get_word(){
        $uid = (int)$this->input->get_post('uid', TRUE);
        $ret = $this->Model_bus->get_study_model()->rand_unfamiliar_word($uid);
        $this->output($ret);
    }



}
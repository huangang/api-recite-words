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
 * Common Model Class
 * 公共函数model
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      Common
 * @author        huangang
 * @link
 */
class Common_model extends MY_Model{

    /**
     * 记录每次请求
     *
     *
     * @param array  $params `required` 请求参数
     * @param string $router `required` 请求路径
     *
     *
     * ------
     *
     * @return void
     *
     * ```
     * 返回结果
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    public function record_quest($params, $router){
        $this->_insert_ignore('app_quest_record',array('params'=> json_encode($params,true) , 'router' => $router));
    }

}
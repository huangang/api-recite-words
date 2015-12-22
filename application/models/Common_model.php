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

    /**
     * 锁定用户的回复
     *
     *
     * @param string  $openid `required` openid
     * @param string $value `required` 值
     *
     *
     * ------
     *
     * @return int
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
    public function lock_reply($openid, $value){
        $data = array(
            'openid' => $openid,
            'value' => $value,
        );
        return  $this->_insert_ignore('app_lock', $data);
    }

    /**
     * 获取用户的锁定的值
     *
     *
     * @param string  $openid `required` openid
     * @param int     $before   `optional` 多久之前的有效时间(默认10分钟)
     *
     *
     * ------
     *
     * @return string | boolean
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
    public function get_lock_value($openid, $before = 600 ){
        $time = time() - $before;
        $this->db->where('unix_timestamp(create_time) >= ' . $time);
        $this->db->where('lock', 1);
        $this->db->order_by('id','desc');
        $row = $this->_select('app_lock','value', array('openid'=> $openid));
        if(!empty($row)){
            return $row->value;
        }
        return false;
    }

    /**
     * 解锁定用户的的值
     *
     *
     * @param string  $openid `required` openid
     * @param string  $value `required` value
     * @param int     $before   `optional` 多久之前的有效时间(默认10分钟)
     *
     *
     * ------
     *
     * @return string | boolean
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
    public function unlocking($openid, $value, $before = 600){
        $time = time() - $before;
        $this->db->where('unix_timestamp(create_time) >= ' . $time);
        return $this->_update('app_lock',array('lock' => 0), array('openid' => $openid,'value' => $value));
    }



}
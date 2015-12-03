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
 * Config Model Class
 * 配置model
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      Config
 * @author        huangang
 * @link
 */
class Config_model extends MY_Model{

    const DEFAULT_DAY = 50;

    /**
     * 设置用户每天学习单词量
     *
     *
     * @param int $uid `required` 用户ID
     * @param int $num `required` 学习数量
     *
     *
     * ------
     *
     * @return  boolean
     *
     * ```
     * 返回结果
     *  成功 true, 失败 false
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    public function set_study_num($uid, $num){
        $ret = $this->get_study_num($uid);
        if($ret == false){
            $affected_rows = $this->_insert_ignore('app_study_plan', array('uid' => $uid, 'day_num' => $num,'update_time' => time()));
        }else{
            $affected_rows = $this->_update('app_study_plan', array('day_num' => $num,'update_time' => time()), array('uid' => $uid));
        }
        return $affected_rows ? true : false;
    }


    /**
     * 获取用户每天学习单词量
     *
     *
     * @param int $uid `required` 用户ID
     *
     *
     * ------
     *
     * @return int | boolean
     *
     * ```
     * 返回结果
     *  成功 12, 失败 false
     *
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    public function get_study_num($uid){
        $this->db->from('app_study_plan');
        $this->db->where('uid', $uid);
        $this->db->select('day_num');
        $ret = $this->db->get()->row();
        return $ret ? $ret->day_num : false;
    }


}
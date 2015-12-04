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
 * Study Model Class
 * 学习model
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      Study
 * @author        huangang
 * @link
 */
class Study_model extends MY_Model{



    /**
     * 随机返回一个不熟练的学习单词
     *
     * @param int  $uid `required` 用户ID
     *
     * ------
     *
     * @return array
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
    public function rand_unfamiliar_word($uid){
        $this->db->from('app_words w');
        $this->db->join('app_study_record r', 'r.word = w.word and r.status != 1 and r.uid ='.$uid,'left');
        $this->db->select('w.word,w.meaning,w.example');
        $this->db->order_by('w.id','RANDOM');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }


    /**
     * 随机返回一个的学习单词
     *
     *
     * ------
     *
     * @return array
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
    public function rand_word(){
        $this->db->from('app_words w');
        $this->db->select('w.word,w.meaning,w.example');
        $this->db->order_by('w.id','RANDOM');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }


    /**
     * 获取今天已经学习的数量
     *
     * @param int  $uid `required` 用户ID
     * @param int  $status `required` 学习的掌握度
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
    public function get_today_study_num($uid, $status = null){
        $time = strtotime(date("Y-m-d"));//今天0点
        $this->db->from('app_study_record');
        $this->db->where('uid', $uid);
        $this->db->where('create_time > '. $time);
        if($status != null){
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results();
    }

}
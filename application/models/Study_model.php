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

    const STATUS_SKILLED = 1;
    const STATUS_UNSKILLED = 0;

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
    public function rand_unskilled_word($uid){
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
        $this->db->where('unix_timestamp(create_time) > '. $time);
        if($status != null){
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results();
    }

    /**
     * 获取本周已经学习的数量
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
    public function get_week_study_num($uid, $status = null){
        $time = strtotime('last monday');//本周一0点
        $this->db->from('app_study_record');
        $this->db->where('uid', $uid);
        $this->db->where('unix_timestamp(create_time) > '. $time);
        if($status != null){
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results();
    }

    /**
     * 获取本月已经学习的数量
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
    public function get_month_study_num($uid, $status = null){
        $time = strtotime(date("Y-m"));//今天0点
        $this->db->from('app_study_record');
        $this->db->where('uid', $uid);
        $this->db->where('unix_timestamp(create_time) > '. $time);
        if($status != null){
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results();
    }

    /**
     * 获取本年已经学习的数量
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
    public function get_year_study_num($uid, $status = null){
        $time = strtotime(date("Y-1-1"));//今天0点
        $this->db->from('app_study_record');
        $this->db->where('uid', $uid);
        $this->db->where('unix_timestamp(create_time) > '. $time);
        if($status != null){
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results();
    }

    /**
     * 获取所有已经学习的数量
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
    public function get_all_study_num($uid, $status = null){
        $this->db->from('app_study_record');
        $this->db->where('uid', $uid);
        if($status != null){
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results();
    }

    /**
     * 记录学习单词
     *
     * @param int     $uid `required`    用户ID
     * @param string  $word `required`   单词
     * @param int     $status `required` 学习的掌握度
     *
     * ------
     *
     * @return int 插入的ID
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
    public function record_study($uid, $word , $status = self::STATUS_UNSKILLED){
        if($this->check_today_record_study($uid, $word)){
            return false;
        }
        $data = array('uid' => $uid, 'word' => $word, 'status' => $status);
        $affected_rows = $this->_insert_ignore('app_study_record', $data);
        return $affected_rows ? $this->db->insert_id() : false;
    }

    /**
     * 验证今天是否学过一样的单词
     *
     * @param int     $uid `required`    用户ID
     * @param string  $word `required`   单词
     *
     * ------
     *
     * @return boolean
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
    public function check_today_record_study($uid, $word){
        $time = strtotime(date("Y-m-d"));//今天0点
        $this->db->from('app_study_record');
        $this->db->where('uid', $uid);
        $this->db->where('word', $word);
        $this->db->where('create_time > '. $time);
        $ret = $this->db->get()->row();
        return $ret ? true : false;
    }


    /**
     * 插入一个新的单词
     *
     * @param string  $word    `required`   单词
     * @param string  $meaning `required`   单词意思
     * @param string  $example `optional`   单词例子
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
    public function add_word($word, $meaning, $example = null){
        $data = array('word' => $word, 'meaning' => $meaning, 'example' => $example);
        $this->_insert_ignore('app_words', $data);
    }

    /**
     * 学习统计
     *
     *
     * @param int $uid `required` 用户ID
     *
     *
     * ------
     *
     * @return array
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
    public function statistics($uid){
        $data['day'] = $this->get_today_study_num($uid);
        $data['week'] = $this->get_week_study_num($uid);
        $data['month'] = $this->get_month_study_num($uid);
        $data['year'] = $this->get_year_study_num($uid);
        $data['all'] = $this->get_all_study_num($uid);
        return $data;
    }
}
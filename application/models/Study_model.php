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
     *@param int  $uid `required` 用户ID
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

}
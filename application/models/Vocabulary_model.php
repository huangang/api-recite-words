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
 * Vocabulary Model Class
 * 生词model
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      Vocabulary
 * @author        huangang
 * @link
 */
class Vocabulary_model extends MY_Model
{
    /**
     * 获取生词
     * @param $uid
     * @return array
     */
    public function gets($uid){
        $this->db->from('app_vocabulary v');
        $this->db->join('app_words w','w.word = v.word','left');
        $this->db->select('w.meaning');
        $this->db->select('v.word,v.id');
        $this->db->where('v.uid', $uid);
        $this->db->order_by('v.create_time','desc');
        return $this->db->get()->result_array();
    }

    /**
     * 添加一个新的生词语
     * @param $uid
     * @param $word
     * @return int
     */
    public function add($uid, $word){
        $data = ['uid' => $uid, 'word' => $word];
        return $this->_insert_ignore('app_vocabulary', $data);
    }

    /**
     * 删除一个新的生词语
     * @param $uid
     * @param $vid
     * @return int
     */
    public function remove($uid, $vid){
        $where = ['uid' => $uid, 'id' => $vid];
        return $this->_delete('app_vocabulary', $where);
    }

}
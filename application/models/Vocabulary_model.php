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
     * @param $since_id
     * @param $num
     * @return mixed
     */
    public function gets($uid, $since_id, $num){
        $this->db->from('app_vocabulary v');
        $this->db->select('v.id , v.word');
        $this->db->where('v.uid', $uid);
        if($since_id <= 0){
            $this->db->order_by('v.id', 'DESC');
        }else{
            if ($num > 0) {
                $this->db->where('v.id > ' . $since_id);
                $this->db->order_by('v.id', 'ASC');
            } else{
                $this->db->where('v.id < ' . $since_id);
                $this->db->order_by('v.id', 'DESC');
            }
        }
        $this->db->limit(abs($num));
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
     * @param $word
     * @return int
     */
    public function remove($uid, $word){
        $where = ['uid' => $uid, 'word' => $word];
        return $this->_delete('app_vocabulary', $where);
    }

}
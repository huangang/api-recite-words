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


    public function test(){

        $this->db->from('app_words');
        $this->db->limit(10);
        $this->db->order_by('id','desc');
        $this->db->select('word');
        $ret = $this->db->get()->result_array();
        dump($ret);
    }


    public function record_quest($params, $router){
        $this->_insert_ignore('app_quest_record',array('params'=> json_encode($params,true) , 'router' => $router));
    }

}
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
 * User Model Class
 * 用户model
 *
 *
 * @package       ReciteWords
 * @subpackage    Model
 * @category      User
 * @author        huangang
 * @link
 */
class User_model extends MY_Model{

    private $_user_info = 'id,openid,nickname,mobile,head,status';


    /**
     * 查询用户
     *
     *
     * @param array $where `required` 查询条件
     * @param array | string  $select `optional` 查询数据
     *
     *
     * ------
     *
     * @return object | boolean
     *
     * ```
     * 返回结果
     *  成功 用户信息, 失败 false
     *
     * ```
     *
     *------------
     * @throws Exception
     * @version 1.0.0
     * @author  huangang
     */
    public function get_user($where, $select = null){
        if(empty($select)){
            $select = $this->_user_info;
        }
        $user = $this->_select('app_user', $select, $where);
        return $user ? $user : false;
    }




    /**
     * 创建新的用户
     *
     * @param array $data `required` 用户数据
     *
     *
     * ------
     *
     * @return int | boolean
     *
     * ```
     * 返回结果
     * 成功 用户ID,  失败 false
     *
     * ```
     *
     *------------
     * @throws Exception
     * @version 1.0.0
     * @author  huangang
     */
    public function create($data){
        $must_array = array('nickname');
        foreach($must_array as $v ){
            if(empty($data[$v])){
                throw new Exception("必要参数为空", ErrorCodes::param_error);
            }
        }
        $need_array = array('openid','mobile','head','password','nickname');
        foreach($data as $k => $v){
            if(!in_array($k, $need_array)){
                throw new Exception("参数不符合", ErrorCodes::db_error);
            }
        }
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['update_time'] = time();
        $data['status'] = self::NORMAL_STATUS;
        $affected_rows = $this->_insert_ignore('app_user', $data);
        return $affected_rows ? $this->db->insert_id() : false;
    }

    /**
     * 验证用户字段某个值的唯一性
     *
     * @param string $k `required` 字段名
     * @param mixed $v `required` 值
     *
     * ------
     *
     * @return  boolean
     *
     * ```
     * 返回结果
     *  有 true, 无 false
     *
     * ```
     *
     *------------
     * @throws Exception
     * @version 1.0.0
     * @author  huangang
     */
    public function check_value($k, $v){
        $this->db->from('app_user');
        $this->db->where($k, $v);
        $this->db->select();
        $ret = $this->db->get()->row();
        return $ret ? true : false;
    }
}
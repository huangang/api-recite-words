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
 * ReciteWords MY_Model Class
 *
 * Model基类
 *
 * @property CI_DB_mysqli_driver | CI_DB  | CI_DB_result $db
 * @property CI_Lang $lang                        Language Class
 * @property CI_Loader $load                      Loads views and files
 * @property CI_Log $log                          Logging Class
 * @property CI_Model $model                      CodeIgniter Model Class
 * @property CI_Output $output                    Responsible for sending final output to browser
 * @property CI_Profiler $profiler                This class enables you to display benchmark, query, and other data<br />in order to help with debugging and optimization.
 * @property CI_Router $router                    Parses URIs and determines routing
 * @property CI_Session $session
 * @property CI_config $config
 *
 * @package        ReciteWords
 * @subpackage    API
 * @category    MY_Model
 * @author        huangang
 * @link
 */
class MY_Model extends CI_Model{

    //构造方法
    public function __construct(){
        parent::__construct();
        $this->load->library("ErrorCodes");
    }

    /**
     * select 单表查询方法
     *
     * -------
     *
     * @param string $table `required` 查询表名
     * @param string $select `required` 查询数据
     * @param array $where `required` 查询条件
     * @param boolean $return_array `optional` 是否返回多个
     * @param array $like `optional` 相似条件
     *
     * ------
     *
     * @return object
     *
     * ```
     * 返回结果
     *
     * ```
     *
     *------------
     * @version 2.0.0
     * @author  huangang
     */
    public function _select($table, $select = "*", $where , $return_array = false, $like = null){
        $this->db->from($table);
        $this->db->select($select);
        foreach($where as $k => $v){
            $this->db->where($k, $v);
        }
        if($like != null){
            foreach($like as $k => $v){
                $this->db->like($k, $v);
            }
        }
        $this->db->order_by('id', 'DESC');
        if($return_array){
            $ret =  $this->db->get()->row_array();
        }else{
            $ret = $this->db->get()->row();
        }
        return $ret ? $ret : false;
    }


    /**
     * insert ignore 方法
     *
     * -------
     *
     * @param string $table `required` 插入的表名
     * @param array $data `required` 数据
     *
     * ------
     *
     * @return int affected_nums 返回的影响行数
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
    protected function _insert_ignore($table, $data)
    {
        $_prepared = array();
        foreach ($data as $col => $val)
            $_prepared[$col] = $this->db->escape($val);

        $this->db->query('INSERT IGNORE INTO ' . $table . ' ( ' . implode(',', array_keys($_prepared)) . ') VALUES(' . implode(',', array_values($_prepared)) . ');');

        return $this->db->affected_rows();
    }


    /**
     * update 方法
     *
     * -------
     *
     * @param string $table `required` 更新的表名
     * @param array $data `required` 数据
     * @param array $where `required` 更新条件
     * @param array $like `optional` 相似条件
     *
     * ------
     *
     * @return int affected_nums 返回的影响行数
     *
     * ```
     * 返回结果
     * 1
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    protected function _update($table, $data, $where, $like = null){
        foreach($where as $k => $v){
            $this->db->where($k, $v);
        }
        if($like != null){
            foreach($like as $k => $v){
                $this->db->like($k, $v);
            }
        }
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }


    /**
     * delete 方法
     *
     * -------
     *
     * @param string $table `required` 删除的表名
     * @param array $where `required` 删除条件
     * @param array $like `optional` 相似条件
     *
     * ------
     *
     * @return int affected_nums 返回的影响行数
     *
     * ```
     * 返回结果
     * 1
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    protected function _delete($table, $where, $like = null){
        foreach($where as $k => $v){
            $this->db->where($k, $v);
        }
        if($like != null){
            foreach($like as $k => $v){
                $this->db->like($k, $v);
            }
        }
        $this->db->delete($table);
        return $this->db->affected_rows();
    }



    /**
     * 验证某个表里面是否存在这个值
     *
     * -------
     *
     * @param string $table `required` 查询的表名
     * @param array $where `required` 查询条件
     * @param array $like `optional` 相似条件
     *
     * ------
     *
     * @return boolean
     *
     * ```
     * 返回结果
     * true || false
     * ```
     *
     *------------
     * @version 1.0.0
     * @author  huangang
     */
    protected function _is_exists($table, $where, $like = null){
        foreach($where as $k => $v){
            $this->db->where($k, $v);
        }
        if($like != null){
            foreach($like as $k => $v){
                $this->db->like($k, $v);
            }
        }
        $this->db->from($table);
        $ret = $this->db->get()->row();
        return $ret ? true : false;
    }
}
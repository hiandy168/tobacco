<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 模型 基类，其他模型需要先继承本类
 */
class Content_model extends CI_Model
{
    public $table = ''; // 数据库表名称

    function __construct()
    {
        parent::__construct();
    }

    public function set_table($table)
    {
        $this->table = $table;
        return 1;
    }

    /**
     * 获取某个字段的一条信息
     *
     * @param string $column   数据库的某个字段
     * @param string $where_arr   查询的条件（数组）
     * @return array 一维数组
     */
    function get_column_row($column,$where_arr){
        $this->db->select($column);
        $res = $this->db->get_where($this->table, $where_arr)->row_array();
        return $res;
    }

    /**
     * 获取某个字段的所有信息
     *
     * @param int $id
     * @return array 二维数组
     */
    function get_column_result($column, $where = '')
    {
        if ($where) {
            $where = "where $where";
        }
        $query = $this->db->query("select $column from $this->table  $where")->result_array();
        return $query;
    }

    /**
     * 获取一条信息
     *
     * @param int $id
     * @return array 一维数组
     */
    function get_one($id, $field = 'id')
    {
        $this->db->where($field, $id);
        $query = $this->db->get($this->table, 1);
        $value = $query->row_array();
        return $value;
    }

    /**
     * 根据条件，获取记录条数
     *
     * @param string $where
     * @return array 二维数组
     */
    function get_count($where = '')
    {
        $wheresql = '';
        if ($where) {
            $wheresql = "WHERE $where";
        }
        $query = $this->db->query("SELECT COUNT(*) AS num FROM $this->table $wheresql");
        $value = $query->row_array();
        return $value ['num'];
    }

    /**
     * 获取一组信息
     *
     * @param
     *            多个参数
     * @return array 二维数组
     */
    function get_list($field = '*', $where = '', $order = 'id', $offset = 0, $limit = 20)
    {
        $wheresql = '';
        if ($where) {
            $wheresql = "WHERE $where";
        }
        $sql = "SELECT $field FROM $this->table $wheresql ORDER BY $order limit $offset,$limit";
        $query = $this->db->query($sql);
        $list = $query->result_array();

        return $list;
    }

    /**
     * 获取一组信息
     *
     * @param array $data
     * @return array 二维数组
     */
    function insert($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * 删除一条或多条信息
     *
     * @param mix $ids
     *            整数或者数组
     * @return array 二维数组
     */
    function delete($ids, $field = 'id')
    {
        if (is_numeric($ids)) {
            //如果删除的是活动，则删除活动对应的资源和规则
            if ($field == 'ActiveID') {
                //删除资源列表
                $query = $this->db->query("delete from zy_gamelist_resources where ActiveID=$ids AND IsBase=0");
                //删除规则列表
                $query = $this->db->query("delete from zy_gamelist_rule where ActiveID=$ids AND IsBase=0");
                //删除黑名单列表
                $query = $this->db->query("delete from zy_active_blacklist where ActiveID=$ids ");
                //删除zy_active_game列表
                $query = $this->db->query("delete from zy_active_game where ActiveID=$ids ");
                //删除zy_gamelist_more列表
                $query = $this->db->query("delete from zy_gamelist_more where ActiveID=$ids ");
                //删除zy_gamelist_record列表
                $query = $this->db->query("delete from zy_gamelist_record where ActiveID=$ids ");
                //删除zy_gamelist_user列表
                $query = $this->db->query("delete from zy_gamelist_user where ActiveID=$ids ");
            }
            $query = $this->db->query("delete from $this->table where $field=$ids");
        } else {
            $ids_arr = $ids;
            foreach ($ids_arr as $id) {
                //删除资源列表
                $query = $this->db->query("delete from zy_gamelist_resources where ActiveID=$id AND IsBase=0");
                //删除规则列表
                $query = $this->db->query("delete from zy_gamelist_rule where ActiveID=$id AND IsBase=0");
                //删除黑名单列表
                $query = $this->db->query("delete from zy_active_blacklist where ActiveID=$id ");
                //删除zy_active_game列表
                $query = $this->db->query("delete from zy_active_game where ActiveID=$id ");
                //删除zy_gamelist_more列表
                $query = $this->db->query("delete from zy_gamelist_more where ActiveID=$id ");
                //删除zy_gamelist_record列表
                $query = $this->db->query("delete from zy_gamelist_record where ActiveID=$id ");
                //删除zy_gamelist_user列表
                $query = $this->db->query("delete from zy_gamelist_user where ActiveID=$id ");
            }
            $ids = implode(",", $ids);
            $query = $this->db->query("delete from $this->table where $field in ($ids)");
        }
        return $this->db->affected_rows();
    }

    /**
     * 获取一组信息
     *
     * @param int $id
     * @return array 二维数组
     */
    function update($data, $where_arr)
    {
        $this->db->update($this->table,$data,$where_arr);
        return $this->db->affected_rows();
    }

    /**
     * 更新 访问量
     *
     * @param int $id
     * @return array 二维数组
     */
    function update_visits($id)
    {
        if ($id == 0)
            return false;
        $query = $this->db->query("update $this->table set visits=visits+1 where id='$id' limit 1");
    }

    /**
     * 更新 访问量
     *
     * @param int $id
     * @param int $status
     * @return array 二维数组
     */
    function update_status($id, $status) {
        if ($id == 0)
            return false;
        $query = $this->db->query ( "update $this->table set status='$status' where id='$id' limit 1" );
    }

}

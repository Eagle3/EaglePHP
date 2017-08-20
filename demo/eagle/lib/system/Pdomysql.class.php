<?php
/**
 * PDO-MySQL操作类
 *
 */

namespace lib\system;
class Pdomysql{
	//单例模式Pdomysql对象
	private static $instance = NULL;
	//数据源名称 DSN
	private $dsn = '';
	//PDO对象
	private $pdo = NULL;
	//PDOStatement对象
	private $pdoStmt = NULL;
	//错误信息
	private $errMsg = '';
	//表前缀
	private $prefix = '';
	//最后执行SQL
	private $last_sql = '';
	
	private static $dbConfig = array();
	
	public function __construct(){
	    $dbConfig = getConfig('PDO_CONFIG');
		try {
			$this->prefix = $dbConfig['prefix'];
			$this->dsn = 'mysql:host='.$dbConfig['host'].';dbname='.$dbConfig['dbName'].';port='.$dbConfig['port'];
			$this->pdo = new \PDO($this->dsn, $dbConfig['userName'], $dbConfig['passWord'],$dbConfig['driverOptions']);
			$this->pdo->query("set names {$dbConfig['charSet']}");
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);//设置错误处理模式
			$this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);//关闭或开启自动提交
			$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);// 禁用模拟预处理语句
		} catch (\PDOException $e) {
			$this->errMsg = $e->getMessage();
		}
	}
	
	/**
	 * 单例模式获取Pdomysql对象
	 * @return object Pdomysql对象
	 */
	public static function getInstance(){
		if(is_null(self::$instance) || !is_object(self::$instance)){
			return new self();
		}
		return self::$instance;
	}
	
	/**
	 * 设置PDO连接相关属性
	 */
	public function setAttr($param,$val){
		$this->pdo->setAttribute($param, $val);
	}
	
	/**
	 * 获取PDO连接相关属性
	 */
	public function getAttr($param){
		$this->pdo->getAttribute($param);
	}
	
	/**
	 * 最后插入ID
	 */
	public function lastInsertId(){
	    return $this->pdo->lastInsertId();
	}
	
	/**
	 * 最后执行SQL
	 */
	public function lastSql(){
	    return $this->last_sql;
	}
	
	/**
	 * 插入单条数据
	 * @param  string  $table   表名
	 * @param  array   $data    插入的数据，必须是关联数组(必须是一维数组)
	 * @param  boolean $replace 存在时是否更新操作，此时$data数据中的字段必须有一个是主键或者唯一
	 * @return mixed 		          影响的行数或布尔值
	 */
	public function insert($table, $data, $replace = false){
		if(empty($table) || empty($data)){
			return false;
		}
		$values_arr = array();//value值  ? 作为占位符
		$fields_arr = array();//字段
		$params_arr = array();//替换 ? 的数组
		foreach ($data as $key => $val) {
			if(is_scalar($val) || is_null($val)) {
				$fields_arr[] =  $key;
				$values_arr[] = '?'; //使用预处理模式  ? 作为占位符
				$params_arr[] = $val;
			}
		}
		$insertSql = ($replace ? 'REPLACE' : 'INSERT') . ' INTO ' . $this->prefix . $table . ' (' . implode(', ', $fields_arr) . ') VALUES (' . implode(',', $values_arr) . ')';
		return $this->execute($insertSql, $params_arr, true);
	}
	
	/**
	 * 批量插入数据
	 * @param  string  $table   表名
	 * @param  array   $data    插入的数据，必须是二维数组（子数组必须是关联数组）
	 * @param  boolean $replace 存在时是否更新操作，此时$data数据中的字段必须有一个是主键或者唯一
	 * @return mixed 		           影响的行数或布尔值
	 */
	public function insertAll($table, $data, $replace = false){
		//取出插入字段
		$fields_arr = reset($data);
		if(empty($table) || empty($data) || !is_array($data) || !is_array($fields_arr)){
			return false;
		}
		
		//组装批量插入SQL
		$insertSqlAll = ($replace ? " REPLACE " : " INSERT ") . " INTO " . $this->prefix.$table . "(";
		foreach ($fields_arr as $field=>$val){
			$insertSqlAll .= "`$field`,";
		}
		$insertSqlAll = substr($insertSqlAll, 0, -1) . ") VALUES (";
		foreach($data as $vals) {
			foreach($vals as $k => $v) {
				$insertSqlAll .= "'$v',";
			}
			$insertSqlAll = substr($insertSqlAll, 0, -1) . "),(";
		}
		$insertSqlAll = substr($insertSqlAll, 0 , -2);
		
		return $this->execute($insertSqlAll, array(), true);
	}
	
	/**
	 * 删除数据
	 * @param string  $table   		  表名
	 * @param string  $where   		 where条件 如：'id > ? and name = ? ' 或者 'id > :id and name = :name '
	 * @param array   $params_arr    替换占位符的数组（关联数组或者索引数组，$where是 ? 这里就是索引数组，反之，关联数组）  如： array(10,'张三') 或者 array(':id'=>10,':name'=>'张三')
	 * @return mixed 		                        影响的行数或布尔值
	 */
	public function delete($table, $where = '', $params_arr = array()){
		if(empty($table) || !is_array($params_arr) || (empty($where) && !empty($params_arr)) || (!empty($where) && empty($params_arr))){
			return false;
		}
		
		$delete_params_arr = array();
		foreach ($params_arr as $key => $value) {
			$delete_params_arr[] = $value;
			if(is_numeric($key)) {
				continue;
			}
			if(!strstr($key, ':')) {
				$key = ':'.$key;
			}
			$where = str_replace($key, '?', $where);
		}
		$delete_sql = 'DELETE FROM ' . $this->prefix . $table . (empty($where) ? '' : ' WHERE ' . $where);
		
		return $this->execute($delete_sql, $delete_params_arr);
	}
	
	/**
	 * 修改数据
	 * @param string  $table   		  表名
	 * @param array   $update_data   更新的数据（一维数组）
	 * @param string  $where   		 where条件 如：'id > ? and name = ? ' 或者 'id > :id and name = :name '
	 * @param array   $params_arr    替换占位符的数组（关联数组或者索引数组，$where是 ? 这里就是索引数组，反之，关联数组）  如： array(10,'张三') 或者 array(':id'=>10,':name'=>'张三')
	 * @return mixed 		                        影响的行数或布尔值
	 */
	public function update($table, $update_data = array(), $where = '',  $params_arr = array()){
		if(empty($table) || empty($update_data) || (!empty($update_data) && !is_array($update_data))  || (empty($where) && !empty($params_arr)) || (!empty($where) && empty($params_arr)) || (!empty($params_arr) && !is_array($params_arr))){
			return false;
		}
		
		$update_params_arr = array();
		foreach($update_data as $key => $value) {
			if (is_scalar($value) || is_null($value)) {
				if (is_numeric($key)) {
					$update[] = $value;
				} else {
					$update[] = $key.' = ?';
					$update_params_arr[] = $value;
				}
			}
		}
		
		foreach ($params_arr as $key => $value) {
			$update_params_arr[] = $value;
			if(is_numeric($key)) {
				continue;
			}
			if(!strstr($key, ':')) {
				$key = ':'.$key;
			}
			$where = str_replace($key, '?', $where);
		}
		$update_sql = 'UPDATE ' . $this->prefix . $table . ' SET ' . implode(', ', $update) . (empty($where) ? '' : ' WHERE ' . $where);
		return $this->execute($update_sql, $update_params_arr, true);
	}
	
	public function get($sql, $params_arr = array()){
	    if($this->query($sql, $params_arr)){
	        return $this->fetch();
	    }
	    return array();
	}
	
	public function getAll($sql, $params_arr = array()){
	    if($this->query($sql, $params_arr)){
	        return $this->fetchAll();
	    }
	    return array();
	}
	
	/**
	 * 执行SQL操作（增、删、改、查）
	 * @param string  $sql         sql语句
	 * @param array   $params_arr  替换占位符的数组
	 * @param boolean $affect      是否返回影响行数
	 */
	private function execute($sql, $params_arr, $affect = false){
	    $this->pdoStmt = $this->pdo->prepare($sql);
	    $status = $this->pdoStmt->execute($params_arr);
	    $this->last_sql = $this->formatSql($sql,$params_arr);
	    return $affect ? $this->pdoStmt->rowCount() : $status;
	}
	
	/**
	 * 执行SQL操作（一般用于查询）
	 * @param string $sql 		  sql语句	
	 * @param array  $params_arr  where条件（数组格式）
	 * @return mixed
	 */
	private function query($sql, $params_arr = array()){
		if (empty($params_arr)) {
			$this->pdoStmt = $this->pdo->query($sql);
			$this->last_sql = $this->formatSql($sql, $params_arr);
			return $this->pdoStmt;
		} else {
			if (false!=$this->pdoStmt = $this->pdo->prepare($sql)) {
				$this->pdoStmt->execute($params_arr);
				$this->last_sql = $this->formatSql($sql, $params_arr);
				return $this->pdoStmt;
			}
		}
		return false;
	}
	
	/**
	 * 获取所有结果集（必须先执行query，再执行fetchAll）
	 */
	private function fetchAll(){
		if (is_object($this->pdoStmt)) {
			return $this->pdoStmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		return array();
	}
	
	/**
	 * 获取一条数据（必须先执行query，再执行fetch）
	 */
	private function fetch(){
		if (is_object($this->pdoStmt)) {
			return $this->pdoStmt->fetch(\PDO::FETCH_ASSOC);
		}
		return array();
	}
	
	/**
	 *  返回受上一个 SQL 语句影响的行数 
	 */
	public function count(){
		if (is_object($this->pdoStmt)) {
			return $this->pdoStmt->rowCount();
		}
		return false;
	}
	
	/**
	 * 获取错误信息
	 * @return string
	 */
	public function getErrMsg(){
		return $this->errMsg;
	}
	
	/**
	 * 开启事务
	 */
	public function beginTransaction(){
		$this->pdo->beginTransaction();
	}
	
	/**
	 * 提交事务
	 */
	public function commit(){
		$this->pdo->commit();
	}
	
	/**
	 * 回滚事务
	 */
	public function rollBack(){
		$this->pdo->rollBack();
	}
	
	/**
	 * 处理预处理SQL语句并返回拼接后的SQL（并不是PDO实际执行的sql，只是为了调试对应）
	 * @param string $sql        预处理SQL
	 * @param array  $params_arr 参数数组
	 * @return string
	 */
	private function formatSql($sql,$params_arr){
		if(empty($params_arr) || !is_array($params_arr)){
			return $sql;
		}
		
		//PDOStatement自带的方法，打印一条 SQL 预处理命令
		//return $this->pdoStmt->debugDumpParams();
		
		//写法1
		$sql = preg_replace('/\?/', '%s', $sql);
		array_walk($params_arr,function(&$val,$key){
			$val = '\''.$this->escapeString($val).'\'';
		});
		return trim(vsprintf($sql,$params_arr),'\'');
		
		//写法2
		array_unshift($params_arr,preg_replace('/\?/', '%s', $sql));
		array_walk($params_arr,function(&$val,$key){
			if($key > 0){
				$val = '\''.$this->escapeString($val).'\'';
			}
		});
		return trim(call_user_func_array('sprintf',$params_arr),'\'');
	}
		
	/**
	 * SQL指令安全过滤
	 * @access public
	 * @param string $str  SQL字符串
	 * @return string
	 */
	private function escapeString($str) {
		return addslashes($str);
	}
	
	/**
	 * 清除PDO、PDOstatment对象
	 */
	private function close(){
		if ($this->pdo) {
			$this->pdo = NULL;
		}
		if ($this->pdoStmt) {
			$this->pdoStmt = NULL;
		}
	}
	
	/**
	 * __construct
	 * @access public
	 * @param void
	 * @return void
	 */
	public function __destruct()
	{
		$this->close();
	}
}

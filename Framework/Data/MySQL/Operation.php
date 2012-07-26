<?php

namespace Framework\Data\MySQL
{
	/**
	 * MySQL数据处理接口
	 * @author	Lucky
	 */
	interface Operation
	{
		/**
		 * 选择数据库
		 * @param	string	$name	数据库名称
		 * @return	boolean
		 */
		public function selectDb( $name );
	
		/**
		 * 查询
		 * @param	string	$sql	SQL语句
		 * @return	resource | boolean
		 */
		public function execute( $sql );
	
		/**
		 * 执行查询并把数据返回
		 * @param	string	$sql	SQL语句
		 * @return	array
		 */
		public function getDatas( $sql );
	
		/**
		 * 获取一条记录
		 * @param	string	$sql	SQL语句
		 * @return	array | false
		 */
		public function getOneRecord( $sql );
	
		/**
		 * 获取最后的SQL语句执行后，影响的行数
		 * @return	int
		 */
		public function getAffectedRows();
	
		/**
		 * 获取最后插入记录的ID
		 * @return	int
		 */
		public function getInsertId();
	
		/**
		 * 获取记录数
		 * @param	string	$tables	表名
		 * @param	string	$condition	条件
		 * @return	int
		 */
		public function getCount( $tables , $condition = "" );
	
		/**
		 * 启用事务处理
		 */
		public function startTransaction();
	
		/**
		 * 提交事务处理
		 */
		public function commitTransaction();
	
		/**
		 * 回滚事务处理
		 */
		public function rollbackTransaction();
	
		/**
		 * 获取错误号码
		 * @return integer
		 */
		public function getErrorNumber();
	
		/**
		 * 获取错误消息
		 * @return string
		 */
		public function getErrorMessage();
	}
}
?>
<?php

namespace Framework\Data\MySQL
{
	/**
	 * 普通数据库连接库
	 * @author Lucky
	 */
	class MysqlDb implements Operation
	{
		/**
		 * 数据库连接资源
		 * @var	resource
		 */
		private $_dbResource;
	
		/**
		 * 实例化
		 * @param	array	$config	array(
		 * 								host:string	//主机地址
		 * 								port:int	//端口
		 * 								user:string	//用户名
		 * 								passwd:string	//密码
		 * 								name:string	//数据库名称
		 * 							)
		 */
		public function __construct( $config )
		{
			if( ( $this->_dbResource = mysql_connect( "{$config['host']}:{$config['port']}" , $config['user'] , $config['passwd'] , true , MYSQL_CLIENT_INTERACTIVE ) ) != null )
			{
				mysql_query( "SET NAMES 'utf8'" );
				if( isset( $config['name'] ) )
				{
					$this->selectDb( $config['name'] );
				}
			}
		}
		
		/**
		 * 关闭数据库连接
		 */
		public function __destruct()
		{
			if( $this->_dbResource !== false )
			{
				mysql_close( $this->_dbResource );
			}
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::selectDb()
		 */
		public function selectDb( $name )
		{
			return mysql_select_db( $name , $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::execute()
		 */
		public function execute( $sql )
		{
			return mysql_query( $sql , $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getDatas()
		 */
		public function getDatas( $sql )
		{
			$result = $this->execute( $sql );
			return $this->_queryResult2Datas( $result );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getOneRecord()
		 */
		public function getOneRecord( $sql )
		{
			$result = $this->execute( $sql );
			return $this->_resolveQueryResource( $result );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getAffectedRows()
		 */
		public function getAffectedRows()
		{
			return mysql_affected_rows( $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getInsertId()
		 */
		public function getInsertId()
		{
			return mysql_insert_id( $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getCount()
		 */
		public function getCount( $tables , $condition = "" )
		{
			$r = $this->getOneRecord( "SELECT COUNT(*) AS `count` FROM `". addslashes( $tables ) ."` " . ( $condition ? " WHERE {$condition}" : "" ) );
			return $r['count'];
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::startTransaction()
		 */
		public function startTransaction()
		{
			mysql_query( "SET AUTOCOMMIT=0" , $this->_dbResource );
			mysql_query( "START TRANSACTION" , $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::commitTransaction()
		 */
		public function commitTransaction()
		{
			mysql_query( "COMMIT" );
			mysql_query( "SET AUTOCOMMIT=1" , $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::rollbackTransaction()
		 */
		public function rollbackTransaction()
		{
			mysql_query( "ROLLBACK" , $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getErrorNumber()
		 */
		public function getErrorNumber()
		{
			return mysql_errno( $this->_dbResource );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getErrorMessage()
		 */
		public function getErrorMessage()
		{
			return mysql_error( $this->_dbResource );
		}
	
		/**
		 * 查询结果转换成数据
		 * @param	resource	$result	查询结果资源
		 * @return	array
		 */
		private function _queryResult2Datas( $result )
		{
			$rows = array();
			while( ( $row = $this->_resolveQueryResource( $result ) ) != null )
			{
				$rows[] = $row;
			}
			return $rows;
		}
	
		/**
		 * 解释一个查询的资源的记录
		 * @param	resource	$result	查询结果
		 * @return	array
		 */
		private function _resolveQueryResource( $result )
		{
			return mysql_fetch_assoc( $result );
		}
	}
}
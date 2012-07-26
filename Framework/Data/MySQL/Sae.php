<?php

namespace Framework\Data\MySQL
{
	class Sae implements Operation
	{
		/**
		 * SAE数据库连接
		 * @var	\SaeMysql
		 */
		private $_dbResource;
		
		/**
		 * 实例化连接
		 */
		public function __construct()
		{
			$this->_dbResource = new \SaeMysql();
		}
		
		/**
		 * 关闭连接
		 */
		public function __destruct()
		{
			$this->_dbResource->closeDb();
		}
		
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::selectDb()
		 */
		public function selectDb( $name )
		{
			return true;
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::execute()
		 */
		public function execute( $sql )
		{
			return $this->_dbResource->runSql( $sql );
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getDatas()
		 */
		public function getDatas( $sql )
		{
			return $this->_dbResource->getData( $sql );
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getOneRecord()
		 */
		public function getOneRecord( $sql )
		{
			return $this->_dbResource->getLine( $sql );
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getAffectedRows()
		 */
		public function getAffectedRows()
		{
			return $this->_dbResource->affectedRows();
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getInsertId()
		 */
		public function getInsertId()
		{
			return $this->_dbResource->lastId();
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getCount()
		 */
		public function getCount( $tables , $condition = "" )
		{
			return $this->_dbResource->getVar( "SELECT COUNT(*) AS `count` FROM `". addslashes( $tables ) ."` " . ( $condition ? " WHERE {$condition}" : "" ) );
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::startTransaction()
		 */
		public function startTransaction()
		{
			;
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::commitTransaction()
		 */
		public function commitTransaction()
		{
			;
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::rollbackTransaction()
		 */
		public function rollbackTransaction()
		{
			;
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getErrorNumber()
		 */
		public function getErrorNumber()
		{
			$this->_dbResource->errno();
		}
	
		/* (non-PHPdoc)
		 * @see Framework\Data\MySQL\Operation::getErrorMessage()
		 */
		public function getErrorMessage()
		{
			$this->_dbResource->error();
		}
	}
}
?>
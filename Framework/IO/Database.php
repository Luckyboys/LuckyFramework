<?php

namespace Framework\IO
{
	use Framework\Core\Common;
	/**
	 * 数据库连续式存储
	 * @author Luckyboys
	 */
	class Database extends Base
	{
		/**
		 * 数据库引擎
		 * @var Framework\Data\MySQL\MysqlDb
		 */
		private $_dbEngine;
		
		/* (non-PHPdoc)
		 * @see IO_Abstract::init()
		 */
		protected function init()
		{
			$this->_dbEngine = Common::getDB( $this->rootPath );
		}
	
		/* (non-PHPdoc)
		 * @see IO_Abstract::write()
		 */
		public function write( $fileName , $content )
		{
			$sql = "INSERT INTO `{$this->rootPath}` ( `folderName` , `fileName` , `content` ) VALUES ( '". addslashes( $this->folderName ) ."' , '". addslashes( $fileName ) ."' , '{$content}' )";
			$this->_dbEngine->execute( $sql );
		}
	
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::readByFolderNameAndFileName()
		 */
		public function readByFolderNameAndFileName( $folderName , $fileName )
		{
			$sql = "SELECT `content` FROM `{$this->rootPath}` WHERE `folderName` = '". addslashes( $folderName ) ."' AND `fileName` = '". addslashes( $fileName ) ."'";
			$content = '';
			foreach( $this->_dbEngine->getDatas( $sql ) as $record )
			{
				$content .= $record['content'];
			}
			return $content;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::getFileNames()
		 */
		public function getFolderFileNames( $folderName )
		{
			$sql = "SELECT DISTINCT `fileName` FROM `{$this->rootPath}` WHERE `folderName` = '". addslashes( $folderName ) ."'";
			$result = $this->_dbEngine->getDatas( $sql );
			$fileNames = array();
			foreach( $result as $record )
			{
				$fileNames[] = $record['fileName'];
			}
			return $fileNames;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::getFolderNames()
		 */
		public function getFolderNames()
		{
			$sql = "SELECT DISTINCT `folderName` FROM `{$this->rootPath}`";
			$result = $this->_dbEngine->getDatas( $sql );
			$folderNames = array();
			foreach( $result as $record )
			{
				$folderNames[] = $record['folderName'];
			}
			return $folderNames;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::deleteByFolderNameAndFileName()
		 */
		public function deleteByFolderNameAndFileName( $folderName , $fileName )
		{
			$sql = "DELETE FROM `{$this->rootPath}` WHERE `folderName` = '". addslashes( $folderName ) ."' AND `fileName` = '". addslashes( $fileName ) ."'";
			$this->_dbEngine->execute( $sql );
			
			return $this->_dbEngine->getAffectedRows() > 0;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::deleteByFolderName()
		 */
		public function deleteByFolderName( $folderName )
		{
			$sql = "DELETE FROM `{$this->rootPath}` WHERE `folderName` = '". addslashes( $folderName ) ."'";
			$this->_dbEngine->execute( $sql );
			
			return $this->_dbEngine->getAffectedRows() > 0;
		}
	}
}
?>
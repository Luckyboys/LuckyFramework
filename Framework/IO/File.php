<?php

namespace Framework\IO
{
	/**
	 * 文件式连续存储
	 * @author Luckyboys
	 */
	class File extends Base
	{
		/**
		 * 存储路径
		 * @var	string
		 */
		private $_path;
		
		/**
		 * 写入数据
		 * @param	string	$fileName	文件名
		 * @param	string	$content	数据
		 */
		public function write( $fileName , $content )
		{
			$fp = fopen( $this->_path . $fileName , "a+" );
			fwrite( $fp , $content );
			fclose( $fp );
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::readByFolderNameAndFileName()
		 */
		public function readByFolderNameAndFileName( $folderName , $fileName )
		{
			if( file_exists( $this->_computePath( $folderName ) . $fileName ) )
			{
				return file_get_contents( $this->_computePath( $folderName ) . $fileName );
			}
			return '';
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::getFolderFileNames()
		 */
		public function getFolderFileNames( $folderName )
		{
			$fileNames = array();
			$directory = dir( $this->_computePath( $folderName ) );
			while( ( $entry = $directory->read() ) !== false )
			{
				if( is_dir( $this->_path . $entry ) )
				{
					continue;
				}
				$fileNames[] = $entry;
			}
			$directory->close();
			return $fileNames;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::getFolderNames()
		 */
		public function getFolderNames()
		{
			$folderNames = array();
			$directory = dir( $this->rootPath );
			while( ( $entry = $directory->read() ) !== false )
			{
				if( !is_dir( $this->rootPath . $entry )
					|| $entry == '.'
					|| $entry == '..' )
				{
					continue;
				}
				$folderNames[] = $entry;
			}
			$directory->close();
			return $folderNames;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::deleteByFolderNameAndFileName()
		 */
		public function deleteByFolderNameAndFileName( $folderName , $fileName )
		{
			if( file_exists( $this->_computePath( $folderName ) . $fileName ) )
			{
				unlink( $this->_computePath( $folderName ) . $fileName );
				return true;
			}
			return false;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::deleteByFolderName()
		 */
		public function deleteByFolderName( $folderName )
		{
			if( file_exists( $this->_computePath( $folderName ) ) )
			{
				foreach( $this->getFolderFileNames( $folderName ) as $fileName )
				{
					$this->deleteByFolderNameAndFileName( $folderName , $fileName );
				}
				rmdir( $this->_computePath( $folderName ) );
				return true;
			}
			return false;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see IO_Abstract::init()
		 */
		protected function init()
		{
			$this->_path = $this->_computePath( $this->folderName );
			
			//判断时间文件夹是否存在
			if( !file_exists( $this->_path ) )
			{
				@mkdir( $this->_path , 0777 , true );
			}
		}
		
		/**
		 * 计算路径
		 * @param	string	$folderName	文件夹名称
		 */
		private function _computePath( $folderName )
		{
			return $this->rootPath . $folderName .'/';
		}
	}
}
?>
<?php

namespace Framework\IO
{
	use Framework\Core\Common;
	/**
	 * 连续式存储器
	 * @author Luckyboys
	 */
	abstract class Base
	{
		/**
		 * IO配置
		 * @var	array
		 */
		protected $ioConfig = array();
		
		/**
		 * 文件夹名称
		 * @var	string
		 */
		protected $folderName;
		
		/**
		 * 存储目录根路径
		 * @var	string
		 */
		protected $rootPath;
		
		/**
		 * 实例化
		 * @param	string	$folderKey	文件夹键
		 * @param	string	$folderName	文件夹名称
		 * @throws	IOException
		 */
		public function __construct( $folderKey , $folderName )
		{
			$this->ioConfig = Common::getConfig()->getIO();
			if( !isset( $this->ioConfig['folder'][$folderKey] )
				|| strlen( $this->ioConfig['folder'][$folderKey] ) <= 0 )
			{
				throw new IOException( IOException::STATUS_FOLDER_PATH_NOT_CONFIG );
			}
			
			$this->rootPath = $this->ioConfig['folder'][$folderKey];
			$this->folderName = $folderName;
			
			$this->init();
		}
		
		/**
		 * 初始化
		 */
		abstract protected function init();
		
		/**
		 * 写入数据
		 * @param	string	$fileName	文件名
		 * @param	string	$content	数据
		 */
		abstract public function write( $fileName , $content );
		
		/**
		 * 读取数据
		 * @param	string	$fileName	文件名
		 * @return	string
		 */
		public function read( $fileName )
		{
			return $this->readByFolderNameAndFileName( $this->folderName , $fileName );
		}
		
		/**
		 * 根据文件夹名称和文件名称读取数据
		 * @param	string	$folderName	文件夹名称
		 * @param	string	$fileName	文件名称
		 * @return	string
		 */
		abstract public function readByFolderNameAndFileName( $folderName , $fileName );
		
		/**
		 * 获取当前文件夹的文件名称
		 * @return	string[]
		 */
		public function getCurrentFolderFileNames()
		{
			return $this->getFolderFileNames( $this->folderName );
		}
		
		/**
		 * 获取所有文件夹名称
		 * @return	string[]
		 */
		abstract public function getFolderNames();
		
		/**
		 * 获取指定文件夹的文件名称
		 * @param	string	$folderName	文件夹名称
		 */
		abstract public function getFolderFileNames( $folderName );
		
		/**
		 * 根据文件夹名称和文件名称删除文件
		 * @param	string	$folderName	文件夹名称
		 * @param	string	$fileName	文件名称
		 * @return	boolean
		 */
		abstract public function deleteByFolderNameAndFileName( $folderName , $fileName );
		
		/**
		 * 删除文件夹
		 * @param	string	$folderName	文件夹名称
		 * @return	boolean
		 */
		abstract public function deleteByFolderName( $folderName );
	}
}
?>
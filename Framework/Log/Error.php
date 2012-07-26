<?php

namespace Framework\Log
{
	use Framework\Core\Common;
	
	class Error
	{
		/**
		 * 使用日志的模块名称
		 * @var	string
		 */
		protected $moduleName;
		
		/**
		 * 是否开启日志功能
		 * @var boolean
		 */
		protected $isOpenLog = false;
		
		/**
		 * 虚拟线程ID
		 * @var	integer
		 */
		protected static $virtualThreadId = 0;
		
		/**
		 * 用户ID
		 * @var	int
		 */
		protected $userId = 0;
		
		/**
		 * 需要记录日志的用户ID
		 * @var	array
		 */
		protected static $needLogUserIds = array();
		
		/**
		 * 单例对象
		 * @var	array(
		 * 			{moduleName:string}:Log_Error
		 * 		)
		 */
		private static $_singletonObjects = array();
		
		/**
		 * 数据读写IO控制器
		 * @var	IO_Abstract
		 */
		private $_io = null;
		
		/**
		 * 获取错误日志模块实例
		 * @param	string $moduleName	模块名称
		 * @param	int	$userId[opitonal]	用户ID
		 * @return	Error
		 */
		public static function getInstance( $moduleName , $userId = 0 )
		{
			if( !isset( self::$_singletonObjects[$moduleName] ) )
			{
				self::$_singletonObjects[$moduleName] = new self( $moduleName );
			}
			
			self::$_singletonObjects[$moduleName]->_setUserId( $userId );
			
			return self::$_singletonObjects[$moduleName];
		}
		
		/**
		 * 设置用户ID
		 * @param	int	$userId	用户ID
		 */
		private function _setUserId( $userId )
		{
			$this->userId = $userId;
		}
		
		/**
		 * 初始化错误日志模块
		 * @param	string $moduleName	模块名称
		 */
		private function __construct( $moduleName )
		{
			$config = Common::getConfig()->getErrorLog();
			$this->isOpenLog = $config["isOpenLog"] ? true : false;
			
			if( $this->isOpenLog )
			{
				self::$needLogUserIds = $config['errorLogUserIds'];
			
				$this->moduleName = $moduleName;
				
				//获取当前时间
				$time = $this->_getCurrentTime();
				$ioConfig = Common::getConfig()->getIO();
				$ioClassName = $ioConfig['ioEngine'];
				$this->_io = new $ioClassName( 'errorLog' , date( "Y-m-d" , $time[1] ) );
				
				if( !self::$virtualThreadId )
				{
					self::$virtualThreadId = mt_rand();
				}
			}
		}
		
		/**
		 * 写入日志
		 * @param	string $message	日志信息
		 */
		public function addLog( $message )
		{
			if( $this->isOpenLog )
			{
				//如果指定了捕获指定用户的数据的话
				if( !empty( self::$needLogUserIds ) )
				{
					if( !in_array( $this->userId , self::$needLogUserIds ) )
					{
						return;
					}
				}
				
				$this->_io->write( $this->_getFileName() , $this->_getContent( $message ) );
			}
		}
		
		/**
		 * 获取所有日志文件夹
		 * @return	string[]
		 */
		public function getLogFolderNames()
		{
			return $this->_io->getFolderNames();
		}
		
		/**
		 * 获取日志文件夹中的日志文件列表
		 * @param	string	$folderName	日志文件夹名称
		 * @return	string[]
		 */
		public function getLogFileNames( $folderName )
		{
			return $this->_io->getFolderFileNames( $folderName );
		}
		
		/**
		 * 获取日志文件中的内容
		 * @param	string	$folderName	日志文件夹名称
		 * @param	string	$fileName	日志文件名
		 */
		public function getLogFileContent( $folderName , $fileName )
		{
			return $this->_io->readByFolderNameAndFileName( $folderName , $fileName );
		}
		
		/**
		 * 删除错误日志
		 * @param	string	$folderName	日志文件夹名称
		 * @param	string	$fileName	日志文件名
		 * @return	boolean
		 */
		public function deleteLog( $folderName , $fileName )
		{
			return $this->_io->deleteByFolderNameAndFileName( $folderName , $fileName );
		}
		
		/**
		 * 删除错误日志
		 * @param	string	$folderName	日志文件夹名称
		 * @return	boolean
		 */
		public function deleteLogs( $folderName )
		{
			return $this->_io->deleteByFolderName( $folderName );
		}
		
		/**
		 * 获取日志内容
		 * @param	string	$message	错误日志信息
		 */
		private function _getContent( $message )
		{
			$time = $this->_getCurrentTime();
			return date( "Y-m-d H:i:s" ) . ltrim( sprintf( "%.6f" , $time[0] ) , "0" ) ." | ThreadID: ". self::$virtualThreadId ." | {$message} \n";
		}
	
		
		/**
		 * 获取文件名
		 * @return	string
		 */
		private function _getFileName()
		{
			$time = $this->_getCurrentTime();
			return "{$this->moduleName}-". date( "Y-m-d-H" , $time[1] ) ."H.txt";
		}
		
		/**
		 * 获取当前时间
		 * @return	array(
		 * 				{timeStamp:int} , {microSecond:int}
		 * 			)
		 */
		private function _getCurrentTime()
		{
			return explode( " " , microtime() );
		}
	
	}
}
<?php

namespace Framework\Exception
{
	use Framework\Core\Common;
	
	/**
	 * 异常基类
	 * @author Lucky
	 */
	class Base extends \Exception 
	{
		/**
		 * 数据库访问出错
		 * @var	int
		 */
		const STATUS_DB_ACCESS_ERROR = 7;
		
		/**
		 * 错误的控制器名称
		 * @var	int
		 */
		const STATUS_CONTROLLER_NAME_ERROR = 50;
		
		/**
		 * 错误的动作器名称
		 * @var	int
		 */
		const STATUS_ACTIONER_NAME_ERROR = 51;
		
		/**
		 * 方法不存在
		 * @var	int
		 */
		const STATUS_METHOD_NOT_EXIST = 52;
		
		/**
		 * 配置了错误的数据库引擎
		 * @var	int
		 */
		const STATUS_DB_ENGINE_CONFIG_ERROR = 53;
		
		/**
		 * 无法对Memcache加锁
		 * @var	int
		 */
		const STATUS_LOCK_MEMCACHE_ERROR = 200;
		
		/**
		 * 用户ID错误
		 * @var	int
		 */
		const STATUS_USER_ID_ERROR = 201;
		
		/**
		 * 索引缓存键错误
		 * @var	int
		 */
		const STATUS_CACHE_INDEX_KEY_ERROR = 202;
		
		/**
		 * 未知Case选项
		 * @var	int
		 */
		const STATUS_UNKNOWN_CASE_OPTION = 203;
		
		public function __construct( $errorCode = 1 )
		{
			$errorDesc = Common::getConfig( 'ErrorCode' );
			$message = $errorDesc->getMessage( $errorCode ) == null ? "ErrorCode: {$errorCode}" : $errorDesc->getMessage( $errorCode );
			parent::__construct( $message , $errorCode );   
		}
	}
}
?>
<?php
namespace Framework\Config
{
	/**
	 * 运行状态（运行中）
	 * @var	int
	 */
	const STATUS_RUNNING = 0;

	/**
	 * 运行状态（维护中）
	 * @var	int
	 */
	const STATUS_MAINTENANCE = 1;
	
	/**
	 * Memcache类名（memcache）
	 * @var	string
	 */
	const MEMCACHE_CLASS_MEMCACHE = '\\Framework\\Data\\Cache\\InuMemcache';
	
	/**
	 * Memcache类名（libMemcache）
	 * @var	string
	 */
	const MEMCACHE_CLASS_MEMCACHED = '\\Framework\\Data\\Cache\\InuMemcached';
	
	/**
	 * Memcache类名（SaeKVClient）
	 * @var	string
	 */
	const MEMCACHE_CLASS_SAEKV = '\\Framework\\Data\\Cache\\SaeKV';
	
	/**
	 * 数据库客户端类名（Sae）
	 * @var	string
	 */
	const DATABASE_CLASS_SAE = '\\Framework\Data\MySQL\Sae';
	
	/**
	 * 数据库客户端类名（普通MySQL）
	 * @var	string
	 */
	const DATABASE_CLASS_NORMAL = 'Framework\Data\MySQL\MysqlDb';
	
	/**
	 * IO存储（文件式存储）
	 * @var	string
	 */
	const IO_ENGINE_FILE = '\\Framework\\IO\\File';
	
	/**
	 * IO存储（数据库式存储）
	 * @var	string
	 */
	const IO_ENGINE_DATABASE = '\\Framework\\IO\\Database';
	
	/**
	 * XHProf客户端类名（Sae）
	 * @var	string
	 */
	const XHPROF_CLASS_SAE = '\\Framework\\XHProf\\Sae';
	
	/**
	 * 运行环境（SAE）
	 * @var	int
	 */
	const ENV_IN_SAE = 1;

	/**
	 * 运行环境（普通服务器）
	 * @var	int
	 */
	const ENV_IN_NORMAL = 2;
	
	/**
	 * 系统配置
	 * @author Lucky
	 */
	abstract class SystemConfig
	{
		/**
		 * 单例对象
		 * @var Framework\Config\SystemConfig
		 */
		private static $_singletonObject = null;
		
		/**
		 * 实例化
		 */
		protected function __construct()
		{
			;
		}
		
		/**
		 * 获取实例
		 * @return	\Framework\Config\SystemConfig
		 */
		protected static function getInstance()
		{
			if( self::$_singletonObject == null )
			{
				$className = get_called_class();
				self::$_singletonObject = new $className();
			}
			
			return self::$_singletonObject;
		}
		
		/**
		 * 获取运行状态
		 * @return	int
		 */
		public function getStatus()
		{
			return STATUS_RUNNING;
		}
		
		/**
		 * 获取memcache客户端类名
		 * @return	string
		 */
		public function getMemcacheClassName()
		{
			if( $this->whereIsHere() == ENV_IN_NORMAL )
			{
				return MEMCACHE_CLASS_MEMCACHE;
			}
			
			return MEMCACHE_CLASS_SAEKV;
		}
		
		/**
		 * 获取数据库客户端类名
		 * @return	string
		 */
		public function getDatabaseClassName()
		{
			if( $this->whereIsHere() == ENV_IN_NORMAL )
			{
				return DATABASE_CLASS_NORMAL;
			}
			
			return DATABASE_CLASS_SAE;
		}
		
		/**
		 * 获取Memcache服务器配置
		 * @param	string	$key	配置键
		 * @return	array(
		 * 				array(
		 * 					host:string
		 * 					port:int
		 * 				) ,
		 * 				...
		 * 			)
		 */
		public function getMemcacheServer( $key )
		{
			return array(
				array(
					"host" => "127.0.0.1" ,
					"port" => 11211 ,
				) ,
			);
		}
		
		/**
		 * 获取数据服务器配置
		 * @param	string	$key	配置键
		 * @return	array(
		 * 				host:string
		 *				port:int
		 *				user:string
		 *				passwd:string
		 *				name:string
		 *			)
		 */
		public function getDatabaseServer( $key )
		{
			return null;
		}
		
		/**
		 * 获取运行日志配置
		 * @return	array(
		 * 				status:boolean
		 * 				logPath:string	//日志记录路径
		 * 			)
		 */
		public function getRunLog()
		{
			return array(
				'status' => 1 ,
				'logPath' => 'D:/tmp/' ,
			);
		}
		
		/**
		 * 获取XHProf配置
		 * @return	array(
		 * 				isOpen:boolean	//是否使用执行效率检查；true => 使用；false => 不使用
		 * 				logDir:string	//效率检查日志输出目录
		 * 			)
		 */
		public function getXHProf()
		{
			return array(
				'isOpen' => true ,	
				'logDir' => '/tmp/xhprof' ,
			);
		}
		
		/**
		 * 获取XHProf客户端类名
		 * @return	string
		 */
		public function getXHProfClass()
		{
			if( $this->whereIsHere() == ENV_IN_NORMAL )
			{
				return '';
			}
				
			return XHPROF_CLASS_SAE;
		}
		
		/**
		 * 获取默认控制器名称
		 * @return	string
		 */
		public function getDefaultControllerName()
		{
			return 'Index';
		}

		/**
		 * 获取默认动作器名称
		 * @return	string
		 */
		public function getDefaultActionerName()
		{
			return 'run';
		}
		
		/**
		 * 获取错误日志配置
		 * @return	array(
		 * 				isOpenLog:boolean
		 * 			)
		 */
		public function getErrorLog()
		{
			return array(
				'isOpenLog' => false ,
			);
		}
		
		/**
		 * 获取IO存储配置
		 * @return	array(
		 * 				ioEngine:string
		 * 				folder:array(
		 * 					{folderKey:string}:{存储位置:string}	//如果是文件式存储，则目录地址；如果是数据库式存储，则表名
		 * 				)
		 * 			)
		 */
		public function getIO()
		{
			if( $this->whereIsHere() == ENV_IN_NORMAL )
			{
				return array(
					'ioEngine' => IO_ENGINE_FILE ,
					
					//文件夹对应的存储位置
					'folder' => array(
						'errorLog' => ROOT_DIR .'errorLog/' ,
					) ,
				);
			}
			
			return array(
				'ioEngine' => IO_ENGINE_DATABASE ,
					
				//文件夹对应的存储位置
				'folder' => array(
					'errorLog' => 'log' ,
				) ,
			);
		}
		
		/**
		 * 获取当前运行环境
		 * @return	int
		 */
		public function whereIsHere()
		{
			if( $_SERVER['HTTP_APPNAME'] == 'mini4wd' )
			{
				return ENV_IN_SAE;
			}
			
			return ENV_IN_NORMAL;
		}
	}
}
?>
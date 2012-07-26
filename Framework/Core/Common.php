<?php

namespace Framework\Core
{
	use Framework\Helper\ArrayHelper;
	use Framework\Net\CURL;
	
	/**
	 * 通用功能类
	 */
	class Common
	{
		/**
		 * 数据库引擎
		 * @var	\Framework\Data\MySQL\MysqlDb[]
		 */
		private static $_dbEngines = array();
		
		/**
		 * 中间件数据库引擎
		 * @var	\Framework\Data\MidWare\iDber[]
		 */
		private static $_dberEngines = array();
		
		/**
		 * 解除转义
		 * @param	mixed $var
		 * @return	mixed
		 */
		public static function prepareGPCData( & $var )
		{
			if( is_array( $var ) )
			{
				while( ( list( $key , $val ) = each( $var ) ) != null )
				{
					$var[$key] = self::prepareGPCData( $val );
				}
			}
			else 
			{
				$var = stripslashes( $var );
			}
			
			return $var;
		}
		
		/**
		 * 获取系统配置信息
		 * @param	string $key		配置文件项
		 * @return	\Framework\Config\SystemConfig
		 */
		public static function getConfig( $key = '' )
		{
			$className = PROJECT_NAME_SPACE .'\\Config\\'. $key;
			if( $key == '' )
			{
				$className .= 'SystemConfig';
			}
			
			return $className::getInstance();
		}
		
		/**
		 * 获取Cache实例
		 * @param	string $param	Cache服务器名称
		 * @return	Framework\Data\Cache\iCache
		 */
		public static function getCache( $param = 'data' )
		{
			static $cache = array();
			if( empty( $cache[$param] ) )
			{
				$config = self::getConfig()->getMemcacheServer( $param );
				$memcacheClass = self::getConfig()->getMemcacheClassName();
				$cache[$param] = new $memcacheClass( $config );
			}
			
			return $cache[$param];
		}
		
		/**
		 * 获取DB实例
		 * @param	string $dbName	DB名称
		 * @return	\Framework\Data\MySQL\MysqlDb
		 */
		public static function getDB( $dataKey )
		{
			if( !isset( self::$_dbEngines[$dataKey] ) )
			{
				$dbConfig = self::getConfig()->getDatabaseServer( $dataKey );
				$dbClassName = self::getConfig()->getDatabaseClassName();
				self::$_dbEngines[$dataKey] = new $dbClassName( $dbConfig );
			}
			
			return self::$_dbEngines[$dataKey];
		}
		
		/**
		 * 计算最小的不重复值
		 * @param	array $ids			数字
		 * @param	int $min			最小值
		 * @return	int
		 */
		public static function computeMinUnique( $ids , $min = 1 )
		{
			array_multisort( $ids , SORT_ASC );
			foreach ( $ids as $item )
			{
				if( $min == $item )
				{
					$min++;
				}
			}
			return $min;
		}
		
		/**
		 * 根据概率计算结果
		 * @param	int $probability( 0 ~ 100 支持两位小数)
		 * @return	boolean
		 */
		public static function computeResult( $probability )
		{
			$seed = rand( 1 , 10000 );
			if( $seed <= $probability * 100 )
				return true;
			return false;
		}
		
		/**
		 * 注册自动加载类
		 */
		public static function registerAutoLoad()
		{
			spl_autoload_register( array( 'Framework\\Core\\Common' , 'loadClass' ) );
		}
		
		/**
		 * 加载类文件
		 * @param string $className
		 */
		public static function loadClass( $className )
		{
			$exchangedClassPath = str_replace( array( '_' , '\\' ) , '/' , $className );
			
			if( is_dir( ROOT_DIR . $exchangedClassPath ) )
			{
				$dir = dir( ROOT_DIR . $exchangedClassPath );
				
				while( ( $fileName = $dir->read() ) !== false )
				{
					if( is_file( ROOT_DIR . $exchangedClassPath .'/'. $fileName ) )
					{
						require_once ROOT_DIR . $exchangedClassPath .'/'. $fileName;
					}
				}
			}
			else
			{
				if( file_exists( ROOT_DIR . "{$exchangedClassPath}.php" ) )
				{
					require_once( ROOT_DIR . "{$exchangedClassPath}.php" );
				}
			}
		}
		
		/**
		 * 获取Post数据
		 * @return	array
		 */
		public static function getPostData()
		{
			if( isset( $_POST['data'] ) )
			{
				$decodedData = json_decode( $_POST['data'] , true );
				
				if( is_array( $decodedData ) )
				{
					return $decodedData;
				}
			}
			
			if( strlen( $postData = file_get_contents( 'php://input' ) ) > 0 )
			{
				$decodedData = json_decode( $postData , true );
				return $decodedData;
			}
			
			$decodedData = array();
			
			if( defined( 'DEBUG' ) && DEBUG && isset( $_GET['data'] ) )
			{
				$decodedData = json_decode( $_GET['data'] , true );
			}
			return $decodedData;
		}
	}
}
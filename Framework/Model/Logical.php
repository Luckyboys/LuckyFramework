<?php

namespace Framework\Model
{
	use Framework\Core\Common;
	abstract class Logical
	{
		/**
		 * 数据对象
		 * @var	Data_Abstract
		 */
		protected static $dataObjects = array();
		
		/**
		 * 数据对象
		 * @var	Data_Abstract
		 */
		protected static $dataObjectsWriteLock = array();
		
		/**
		 * 配置列表
		 * @var	array(
		 * 			{key}:array
		 * 		)
		 */
		protected static $configs = array();
		
		/**
		 * 实例化
		 */
		protected function __construct()
		{
			;
		}
		
		/**
		 * 设置对象
		 * @param object $object	对象
		 */
		public static function setDataObject( $uniqueKey , $object )
		{
			return self::$dataObjects[$uniqueKey][get_class( $object )] = $object;
		}
		
		/**
		 * 设置对象（带锁）
		 * @param object $object	对象
		 */
		public static function setWithLockDataObject( $uniqueKey , $object )
		{
			return self::$dataObjectsWriteLock[$uniqueKey][get_class( $object )] = $object;
		}
		
		/**
		 * 获取配置
		 * @param string $configKey	配置文件键
		 */
		protected function getConfig( $configKey )
		{
			if( !isset( self::$configs[$configKey] ) )
			{
				self::$configs[$configKey] = Common::getConfig( $configKey );
			}
			return self::$configs[$configKey];
		}
		
		/**
		 * 设置配置
		 * @param string $configKey	配置文件键
		 * @param array $config
		 */
		public static function setConfig( $configKey , $config )
		{
			self::$configs[$configKey] = $config;
		}
		
		/**
		 * 获取数据对象
		 * @return	\Framework\Model\Data
		 */
		public function getDataModel()
		{
			if( !isset( self::$dataObjects[$this->getUniqueKey()] )
				|| !isset( self::$dataObjects[$this->getUniqueKey()][get_called_class()] ) )
			{
				self::$dataObjects[$this->getUniqueKey()][get_called_class()] = $this->buildDataObject();
			}
			return self::$dataObjects[$this->getUniqueKey()][get_called_class()];
		}
		
		/**
		 * 获取数据对象（带锁）
		 * @return	\Framework\Model\Data
		 */
		protected function getDataModelWithWriteLock()
		{
			if( !isset( self::$dataObjectsWriteLock[$this->getUniqueKey()] ) || !isset( self::$dataObjectsWriteLock[$this->getUniqueKey()][get_called_class()] ) )
			{
				self::$dataObjects[$this->getUniqueKey()][get_called_class()]
					= self::$dataObjectsWriteLock[$this->getUniqueKey()][get_called_class()]
					= $this->buildDataObjectWithLock();
			}
			return self::$dataObjectsWriteLock[$this->getUniqueKey()][get_called_class()];
		}
		
		/**
		 * 获取数据对象唯一标识符
		 * @return	string
		 */
		abstract protected function getUniqueKey();
		
		/**
		 * 生成数据对象
		 * @return	Data_Abstract
		 */
		abstract protected function buildDataObject();
		
		/**
		 * 生成数据对象（带锁）
		 * @return	Data_Abstract
		 */
		abstract protected function buildDataObjectWithLock();
	}
}
?>
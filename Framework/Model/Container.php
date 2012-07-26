<?php

namespace Framework\Model
{
	/**
	 * 对象模块仓库
	 * @author	Lucky
	 */
	class Container
	{
		/**
		 * 对象集
		 * @var	Data_Interface[]
		 */
		protected static $objects = array();
		
		/**
		 * 注册需要保存的数据对象
		 * @param	Data_Interface	$dataObject	数据对象
		 */
		public static function register( Save & $dataObject )
		{
			if( !isset( self::$objects[$dataObject->getCacheKey()] ) )
			{
				self::$objects[$dataObject->getCacheKey()] = $dataObject;
			}
		}
		
		/**
		 * 保存所有对象
		 */
		public static function save()
		{
			foreach( self::$objects as $dataObject )
			{
				$dataObject->save();
			}
			
			self::$objects = array();
		}
		
		/**
		 * 获取需要保存的数据对象
		 * @param	string	$key	索引键
		 * @return	Data_Interface
		 */
		public static function get( $key )
		{
			if( isset( self::$objects[$key] ) )
			{
				return self::$objects[$key];
			}
			return null;
		}
	}
}

?>
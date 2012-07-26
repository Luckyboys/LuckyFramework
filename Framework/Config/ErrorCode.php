<?php
namespace Framework\Config
{
	abstract class ErrorCode
	{
		/**
		 * 单例对象
		 * @var	\Framework\Config\ErrorCode
		 */
		private static $_singletonObject = null;
		
		/**
		 * 获取实例
		 * @return	\Framework\Config\ErrorCode
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
		
		public function getMessage( $code )
		{
			switch( $code )
			{
				case 7:
					return '数据库访问出错';
				
				case 50:
					return '错误的控制器名字';
				
				case 51:
					return '错误的动作器名字';
					
				case 52:
					return '方法不存在';
					
				case 53:
					return '配置了错误的数据库引擎';
			}
			
			return '';
		}
	}
}
?>
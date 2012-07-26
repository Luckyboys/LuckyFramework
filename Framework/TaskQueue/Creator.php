<?php

namespace Framework\TaskQueue
{
	use Framework\Core\Common;
	
	class Creator
	{
		/**
		 * 获取邮件发送器
		 * @return	iTaskQueue
		 */
		public static function getTaskQueue( $queueName )
		{
			switch( Common::getConfig()->whereIsHere() )
			{
				case \Framework\Config\ENV_IN_SAE:
					
					return Sae::getInstance( $queueName );
					
				case \Framework\Config\ENV_IN_NORMAL:
					
					return Normal::getInstance( $queueName );
			}
		}
	}
}
?>
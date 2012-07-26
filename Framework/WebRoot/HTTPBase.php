<?php

namespace Framework\WebRoot
{
	use Framework\WebRoot\Base;
	abstract class HTTPBase extends Base
	{
		/**
		 * 获取需要调用的方法名
		 * @return	string
		 */
		protected function getMethodName()
		{
			if( isset( $_GET['method'] ) )
			{
				return $_GET['method'];
			}
		
			
			if( strlen( $this->config->getDefaultControllerName() ) > 0
				&& strlen( $this->config->getDefaultActionerName() ) > 0 )
			{
				return $this->config->getDefaultControllerName() .'.'. $this->config->getDefaultActionerName();
			}
			return '';
		}
	}
}
?>
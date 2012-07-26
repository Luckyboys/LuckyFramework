<?php

namespace Framework\Error
{
	/**
	 * 错误信息
	 * @author Luckyboys
	 */
	class Message
	{
		/**
		 * 错误信息
		 * @var string
		 */
		private $_errorMessage = '';
		
		/**
		 * 实例化错误信息
		 * @param	string	$errorMessage	错误信息
		 */
		public function __construct( $errorMessage )
		{
			$this->_errorMessage = $errorMessage;
		}
		
		/**
		 * 获取错误信息
		 * @return	string
		 */
		public function getErrorMessage()
		{
			return $this->_errorMessage;
		}
	}
}
?>
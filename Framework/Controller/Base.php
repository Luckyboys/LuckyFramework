<?php

namespace Framework\Controller
{
	use Framework\Core\Common;
	abstract class Base
	{
		/**
		 * 输入的数据
		 * @var	array
		 */
		protected $inputData = array();
		
		/**
		 * 控制器助手
		 * @var	Controller_Helper_Abstract
		 */
		protected $helper;
		
		/**
		 * 错误信息集
		 * @var	Error_Message[]
		 */
		private $_errorMessages = array();
		
		/**
		 * 配置文件
		 * @var	\Framework\Config\SystemConfig
		 */
		protected $config = array();
		
		/**
		 * 是否中断执行动作器
		 * @var	boolean
		 */
		private $_isDiscontinueActioner = false;
		
		/**
		 * 实例化
		 */
		public function __construct()
		{
			$this->_initConfig();
		}
		
		/**
		 * 设置输入数据
		 * @param	array	$data	输入数据
		 */
		public function setInputData( $data )
		{
			$this->inputData = $data;
		}
		
		/**
		 * 初始化控制器
		 */
		public function init()
		{
			$this->_initHelper();
		}
		
		/**
		 * 是否中断执行动作器
		 */
		public function isDiscontinueActioner()
		{
			return $this->_isDiscontinueActioner;
		}
		
		/**
		 * 添加错误信息
		 * @param Error_Message $errorMessage	错误信息
		 */
		protected function addError( Error_Message $errorMessage )
		{
			$this->_errorMessages[] = $errorMessage;
		}
		
		/**
		 * 获取所有错误信息
		 * @return	Error_Message[]
		 */
		protected function getErrorMessages()
		{
			return $this->_errorMessages;
		}
		
		/**
		 * 请求中断执行动作器
		 */
		protected function pleaseDiscontinueActioner()
		{
			$this->_isDiscontinueActioner = true;
		}
		
		/**
		 * 获取控制器助手
		 * @return	Controller_Helper_Abstract
		 */
		protected function getHelper()
		{
			return null;
		}
		
		/**
		 * 初始化控制器助手
		 */
		private function _initHelper()
		{
			$this->helper = $this->getHelper();
		}
		
		/**
		 * 初始化系统配置文件
		 */
		private function _initConfig()
		{
			$this->config = Common::getConfig();
		}
	}
}
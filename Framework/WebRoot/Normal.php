<?php

namespace Framework\WebRoot
{
	/** 
	 * 实现普通入口
	 * @author Luckyboys
	 */
	class Normal extends HTTPBase
	{
		/**
		 * 获取输入的数据
		 * @return	array
		 */
		protected function getInputData()
		{
			return array_merge( $_GET , $_POST );
		}
		
		/**
		 * 开启Session功能
		 */
		public function startSession()
		{
			session_start();
		}
	}
}
?>
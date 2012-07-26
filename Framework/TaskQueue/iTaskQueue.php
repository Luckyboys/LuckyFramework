<?php

namespace Framework\TaskQueue
{
	/**
	 * 添加队列接口
	 * @author Lucky
	 */
	interface iTaskQueue
	{
		/**
		 * 添加任务
		 * @param	string	$url	URL地址
		 * @param	array	$postData	提交的数据
		 * @return	iTaskQueue
		 */
		public function addTask( $url , $postData = array() );
		
		/**
		 * 提交任务
		 * @return	boolean
		 */
		public function commit();
		
		/**
		 * 获取错误信息
		 * @return	string
		 */
		public function getErrorMessage();
		
		/**
		 * 获取错误码
		 * @return	int
		 */
		public function getErrorCode();
	}
}
?>
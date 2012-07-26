<?php

namespace Framework\TaskQueue
{
	use Framework\Net;
	
	/**
	 * CURL模拟队列
	 * @author Lucky
	 */
	class Curl extends Base
	{
		/**
		 * 获取实例
		 * @param	string	$queueName	队列名称
		 * @return	Curl
		 */
		public static function getInstance( $queueName )
		{
			return parent::getInstance( $queueName );
		}
		
		/* (non-PHPdoc)
		 * @see TaskQueue_Abstract::commitTask()
		 */
		protected function commitTask( $taskList )
		{
			$client = new CURL();
			foreach( $taskList as $task )
			{
				$client->setURL( $task['url'] );
				$client->call( $task['postData'] );
			}
			return true;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see TaskQueue_Interface::getErrorMessage()
		 */
		public function getErrorMessage()
		{
			return "Unknown Error";
		}
		
		/**
		 * (non-PHPdoc)
		 * @see TaskQueue_Interface::getErrorCode()
		 */
		public function getErrorCode()
		{
			return 0;
		}
	}
}
?>
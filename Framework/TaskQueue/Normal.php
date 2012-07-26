<?php
namespace Framework\TaskQueue
{
	use Framework\Net\SendData;

	class Normal extends Base
	{
		/**
		 * 获取实例
		 * @param	string	$queueName	队列名称
		 * @return	Normal
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
			foreach( $taskList as $task )
			{
				SendData::getInstance( $task['url'] , $task['postData'] )->request();
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
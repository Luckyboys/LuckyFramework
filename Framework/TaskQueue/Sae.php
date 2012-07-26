<?php

namespace Framework\TaskQueue
{
	/**
	 * SAE队列
	 * @author Lucky
	 */
	class Sae extends Base
	{
		/**
		 * 队列
		 * @var	SaeTaskQueue
		 */
		private $_taskQueue = null;
		
		/**
		 * 获取实例
		 * @param	string	$queueName	队列名称
		 * @return	Sae
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
				$this->_taskQueue->addTask( $task['url'] , http_build_query( $task['postData'] ) );
			}
			
			return $this->_taskQueue->push();
		}
		
		/**
		 * (non-PHPdoc)
		 * @see TaskQueue_Interface::getErrorMessage()
		 */
		public function getErrorMessage()
		{
			return $this->_taskQueue->errmsg();
		}
		
		/**
		 * (non-PHPdoc)
		 * @see TaskQueue_Interface::getErrorCode()
		 */
		public function getErrorCode()
		{
			return $this->_taskQueue->errno();
		}
		
		/**
		 * 实例化
		 * @param	string	$queueName	队列名称
		 */
		protected function __construct( $queueName )
		{
			parent::__construct( $queueName );
			$this->_taskQueue = new \SaeTaskQueue( $this->getQueueName() );
		}
	}
}
?>
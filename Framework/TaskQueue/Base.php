<?php

namespace Framework\TaskQueue
{
	/**
	 * 队列抽象类
	 * @author Lucky
	 */
	abstract class Base implements iTaskQueue
	{
		/**
		 * 任务列表
		 * @var	array(
		 * 			array(
		 * 				url:string
		 * 				postData:array
		 * 			)
		 * 		)
		 */
		private $_taskList = array();
		
		/**
		 * 队列名称
		 * @var	string
		 */
		private $_queueName;
		
		/**
		 * 单例对象
		 * @var	TaskQueue_Abstract[]
		 */
		private static $_singletonObjects = array();
		
		/**
		 * (non-PHPdoc)
		 * @see TaskQueue_Interface::addTask()
		 */
		public function addTask( $url , $postData = array() )
		{
			$this->_taskList[] = array(
				'url' => $url ,
				'postData' => $postData ,
			);
			return $this;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see TaskQueue_Interface::commit()
		 */
		public function commit()
		{
			if( !is_array( $this->_taskList ) || count( $this->_taskList ) <= 0 )
			{
				return true;
			}
			
			return $this->commitTask( $this->_taskList );
		}
		
		/**
		 * 获取实例
		 * @param	string	$queueName	队列名称
		 * @return	Base
		 */
		public static function getInstance( $queueName )
		{
			if( !isset( self::$_singletonObjects[$queueName] ) )
			{
				$className = get_called_class();
				self::$_singletonObjects[$queueName] = new $className( $queueName );
			}
			
			return self::$_singletonObjects[$queueName];
		}
		
		/**
		 * 提交任务
		 * @param	array	$taskList	array(
		 * 									array(
		 * 										url:string
		 * 										postData:array
		 * 									)
		 * 								)
		 */
		abstract protected function commitTask( $taskList );
		
		/**
		 * 获取队列名称
		 * @return	string
		 */
		protected function getQueueName()
		{
			return $this->_queueName;
		}
		
		/**
		 * 实例化
		 * @param	string	$queueName	队列名称
		 */
		protected function __construct( $queueName )
		{
			$this->_queueName = $queueName;
		}
	}
}
?>
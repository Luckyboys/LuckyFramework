<?php
namespace Framework\Model
{
	use Framework\Helper\ArrayHelper;
	
	/**
	 * 数据操作
	 * @author Lucky
	 */
	class DataOperation
	{
		/**
		 * 数据操作行为（添加）
		 * @var	int
		 */
		const DATA_ACTION_ADD = 1;
		
		/**
		 * 数据操作行为（更新）
		 * @var	int
		 */
		const DATA_ACTION_UPDATE = 2;
		
		/**
		 * 数据操作行为（删除）
		 * @var	int
		 */
		const DATA_ACTION_DELETE = 3;
	
		/**
		 * 数据操作类型
		 * @var	int
		 */
		private $_action;
		
		/**
		 * 数据
		 * @var	array(
		 * 			{$key:string}:{$value:mixed}
		 * 		)
		 */
		private $_data;
		
		/**
		 * 是否废除这个操作
		 * @var	boolean
		 */
		private $_isDisCard = false;
		
		/**
		 * 实例化
		 * @param	int	$action	数据操作类型
		 * @param	array	$data	数据
		 * 							array(
		 * 								{$key:string}:{$value:mixed}
		 * 							)
		 */
		public function __construct( $action , $data )
		{
			$this->_action = $action;
			$this->_data = $data;
		}
		
		/**
		 * 合并当前数据操作
		 * @param	int	$action	数据操作类型
		 * @param	array	$data	数据
		 * 							array(
		 * 								{$key:string}:{$value:mixed}
		 * 							)
		 */
		public function mergeOperation( $action , $data )
		{
			switch( $this->_action )
			{
				case self::DATA_ACTION_ADD:
						
					$this->_mergeDataActionWhenAdd( $action , $data );
			
					break;
						
				case self::DATA_ACTION_UPDATE:
						
					$this->_mergeDataActionWhenUpdate( $action , $data );
			
					break;
						
				case self::DATA_ACTION_DELETE:
						
					$this->_mergeDataActionWhenDelete( $action , $data );
						
					break;
			}
		}
		
		/**
		 * 是否废除这个操作
		 * @return	boolean
		 */
		public function isDiscard()
		{
			return $this->_isDisCard;
		}
		
		/**
		 * 获取数据操作类型
		 * @return	int
		 */
		public function getAction()
		{
			return $this->_action;
		}
		
		/**
		 * 数据
		 * @return	array(
		 * 				{$key:string}:{$value:mixed}
		 * 			)
		 */
		public function getData()
		{
			return $this->_data;
		}
	
		/**
		 * 当之前操作是删除新记录时，合并当前数据动作
		 * @param	int	$action	数据动作
		 * @param	array	$data	数据
		 * 							array(
		 * 								{$key:string}:{$value:mixed}
		 * 							)
		 */
		private function _mergeDataActionWhenDelete( $action , $data )
		{
			switch( $action )
			{
				case self::DATA_ACTION_DELETE:
				case self::DATA_ACTION_UPDATE:
					break;
				
				case self::DATA_ACTION_ADD:
					$this->_action = DATA_ACTION_UPDATE;
					$this->_data = ArrayHelper::arrayMergeRecursiveDistinct( $this->_data , $data );
					break;
			}
		}
	
		
		/**
		 * 当之前操作是更新记录时，合并当前数据动作
		 * @param	int	$action	数据动作
		 * @param	array	$data	数据
		 * 							array(
		 * 								{$key:string}:{$value:mixed}
		 * 							)
		 */
		private function _mergeDataActionWhenUpdate( $action , $data )
		{
			switch( $action )
			{
				case self::DATA_ACTION_ADD:
				case self::DATA_ACTION_UPDATE:
					$this->_data = ArrayHelper::arrayMergeRecursiveDistinct( $this->_data , $data );
					break;
				
				case self::DATA_ACTION_DELETE:
					$this->_action = DATA_ACTION_DELETE;
					break;
			}
		}
		
		/**
		 * 当之前操作是添加记录时，合并当前数据动作
		 * @param	int	$action	数据动作
		 * @param	array	$data	数据
		 * 							array(
		 * 								{$key:string}:{$value:mixed}
		 * 							)
		 */
		private function _mergeDataActionWhenAdd( $action , $data )
		{
			switch( $action )
			{
				case self::DATA_ACTION_ADD:
				case self::DATA_ACTION_UPDATE:
					$this->_data = ArrayHelper::arrayMergeRecursiveDistinct( $this->_data , $data );
					break;
				
				case self::DATA_ACTION_DELETE:
					$this->_isDisCard = true;
					break;
			}
		}
	}
}
?>
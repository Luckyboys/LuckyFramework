<?php

namespace Framework\Controller\Helper
{
	use Framework\Exception\Base as BaseException;
	use Framework\Error\ErrorList;
	
	/**
	 * 控制器助手抽象类
	 * @author Lucky
	 */
	abstract class Base
	{
		/**
		 * 输入数据
		 * @var	array
		 */
		protected $inputDatas;
		
		/**
		 * 错误列表
		 * @var	ErrorList
		 */
		protected $errorList;
		
		/**
		 * 没有设置错误码
		 * @var	int
		 */
		const NON_SET_ERROR_CODE = 0;
		
		/**
		 * 实例化
		 * @param	array	$inputDatas	数据
		 */
		public function __construct( $inputDatas )
		{
			$this->inputDatas = $inputDatas;
			$this->errorList = new ErrorList();
		}
		
		/**
		 * 获取错误码
		 * @return	ErrorList
		 */
		public function getErrorList()
		{
			return $this->errorList;
		}
		
		/**
		 * 获取值为正整数的输入参数
		 * @param	string	$key	字段名
		 * @param	boolean	$isThrowException	是否抛出异常
		 * @param	int	$errorCode	错误码
		 * @return	int
		 */
		protected function getValueAsPositiveInteger( $key , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
		{
			if( ( $value = (integer)$this->inputDatas[$key] ) <= 0 )
			{
				$this->_addError( $isThrowException , $errorCode );
				
				return 0;
			}
				
			return $value;
		}

		/**
		 * 获取值为前后没有空格的字符串的输入参数
		 * @param	string	$key	字段名
		 * @param	boolean	$isThrowException	是否抛出异常
		 * @param	int	$errorCode	错误码
		 * @return	int
		 */
		protected function getValueAsTrimString( $key , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
		{
			if( strlen( $value = trim( $this->inputDatas[$key] ) ) <= 0 )
			{
				$this->_addError( $isThrowException , $errorCode );
			
				return '';
			}
			
			return $value;
		}
		
		/**
		 * 添加错误
		 * @param	boolean	$isThrowException	是否抛出异常
		 * @param	int	$errorCode	错误码
		 * @throws	BaseException
		 */
		private function _addError( $isThrowException , $errorCode )
		{
			if( $errorCode == self::NON_SET_ERROR_CODE )
			{
				return;
			}
			
			if( $isThrowException )
			{
				throw new BaseException( $errorCode );
			}
			$this->errorList->addError( $errorCode );
		}
	}
}
?>
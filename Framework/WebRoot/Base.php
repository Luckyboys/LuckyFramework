<?php

namespace Framework\WebRoot
{
	use Framework\Core\Common;
	use Framework\Exception\Base as Exception_Base;
	
	/** 
	 * 入口基类
	 * @author Luckyboys
	 */
	abstract class Base
	{
		/**
		 * 脚本开始时间
		 * @var	float
		 */
		protected $startTime = 0;
		
		/**
		 * 输入的数据
		 * @var	array
		 */
		protected $inputData = array();
		
		/**
		 * 控制器
		 * @var	Controller_Abstract
		 */
		protected $controller;
		
		/**
		 * 执行结果
		 * @var	mixed
		 */
		protected $result;
		
		/**
		 * 配置
		 * @var	\Framework\Config\SystemConfig
		 */
		protected $config = array();
		
		/**
		 * XHProf客户端
		 * @var	XHProf_Interface
		 */
		private $_xhProfClient = null;
		
		/**
		 * 控制器类名前缀
		 * @var	string
		 */
		private $_nameSpacePrefix = '';
		
		/**
		 * 执行结果显示形式（不显示）
		 * @var	int
		 */
		const SHOW_RESULT_TYPE_NONE = 0;
		
		/**
		 * 执行结果显示形式（JSON形式显示）
		 * @var	int
		 */
		const SHOW_RESULT_TYPE_JSON = 1;
		
		/**
		 * 执行结果显示形式（文本形式显示）
		 * @var	int
		 */
		const SHOW_RESULT_TYPE_TEXT = 2;
		
		/**
		 * 执行结果显示形式（HTML形式显示）
		 * @var	int
		 */
		const SHOW_RESULT_TYPE_HTML = 3;
		
		/**
		 * 控制器命名空间
		 * @var	
		 */
		private $_controllerNameSpace;
		
		/**
		 * 创建入口
		 * @param	string	$nameSpacePrefix	应用的命名空间
		 * @param	string	$controllerNameSpace	控制器命名空间
		 */
		public function __construct( $nameSpacePrefix , $controllerNameSpace )
		{
			$this->startTime = microtime( true );
			
			if( defined( 'DEBUG' ) && DEBUG )
			{
				error_reporting( E_ALL ^ E_NOTICE );
				ini_set( 'display_errors' , 'On' );
			}
			else 
			{
				error_reporting( 0 );
				ini_set( 'display_errors' , 'Off' );
			}
			
			if( !defined( 'ROOT_DIR' ) )
			{
				define( "ROOT_DIR" , dirname( dirname( dirname( __FILE__ ) ) ) .'/' );
			}
			
			if( defined( 'USE_XHPROF' ) && USE_XHPROF )
			{
				$this->_startXHProf();
			}
			
			$this->inputData = $this->getInputData();
			if( get_magic_quotes_gpc() )
			{
				Common::prepareGPCData( $this->inputData );
			}
			
			$this->config = Common::getConfig();
			
			$this->_nameSpacePrefix = $nameSpacePrefix;
			
			$this->_controllerNameSpace = $controllerNameSpace;
		}
		
		/**
		 * 分配器
		 * @return	mixed
		 */
		public function dispatcher()
		{
			if( strlen( $this->getControllerName() ) <= 0 )
			{
				throw new Exception_Base( Exception_Base::STATUS_CONTROLLER_NAME_ERROR );
			}
			
			if( strlen( $actionerName = $this->getActionerName() ) <= 0 )
			{
				throw new Exception_Base( Exception_Base::STATUS_ACTIONER_NAME_ERROR );
			}
			
			if( file_exists( $this->_getControllerFileName() ) )
			{
				$controllerClassName = $this->_getControllerClassName();
				
				$this->controller = new $controllerClassName;
				if( $this->controller->isDiscontinueActioner() )
				{
					return;
				}
				$this->controller->setInputData( $this->inputData );
				$this->controller->init();
				
				$this->beforeCallActioner();
				if( method_exists( $this->controller , $actionerName ) )
				{
					$this->result = $this->controller->$actionerName();
					return;
				}
			}
			throw new Exception_Base( Exception_Base::STATUS_METHOD_NOT_EXIST );
		}
		
		/**
		 * 获取控制器类名
		 * @return	string
		 */
		private function _getControllerClassName()
		{
			return $this->_controllerNameSpace .'\\'. ucfirst( strtolower( $this->getControllerName() ) );
		}
		
		/**
		 * 获取控制器文件名
		 * @return	string
		 */
		private function _getControllerFileName()
		{
			return ROOT_DIR . str_replace( '\\' , '/' , ltrim( $this->_getControllerClassName() , '\\' ) ) .'.php';
		}
		
		/**
		 * 获取控制器名称
		 * @return	string
		 */
		protected function getControllerName()
		{
			$splitedMethod = $this->_getSplitedMethodName();
			return ( $splitedMethod[0] ? $splitedMethod[0] : '' );
		}
		
		/**
		 * 获取动作器名称
		 * @return	string
		 */
		protected function getActionerName()
		{
			$splitedMethod = $this->_getSplitedMethodName();
			return $splitedMethod[1] ? $splitedMethod[1] : '';
		}
		
		/**
		 * 获取分割后的方法名
		 * @return	array(
		 * 				{controllerName} ,
		 * 				{actionnerName}
		 * 			)
		 */
		private function _getSplitedMethodName()
		{
			$method = $this->getMethodName();
			if( !empty( $method ) )
			{
				return explode( '.' , $method );
			}
			return array(
				'' , '' ,
			);
		}
		
		/**
		 * 执行了分配器之后
		 */
		protected function afterDispatcher()
		{
			;
		}
		
		/**
		 * 执行动作器之前
		 */
		protected function beforeCallActioner()
		{
			;
		}
		
		/**
		 * 显示执行结果
		 * @param	mixed	$result	执行结果
		 * @param	int	$showType	显示形式
		 */
		protected function showResult( $showResultType = self::SHOW_RESULT_TYPE_NONE )
		{
			switch( $showResultType )
			{
				case self::SHOW_RESULT_TYPE_JSON:
					
					echo json_encode( $this->result );
					break;
					
				case self::SHOW_RESULT_TYPE_TEXT:
					
					if( is_array( $this->result ) )
					{
						print_r( $this->result );
					}
					else
					{
						echo $this->result;
					}
					break;
					
				case self::SHOW_RESULT_TYPE_HTML:
					
					if( isset( $this->result['errorCode'] ) && $this->result['errorCode'] > 0 )
					{
						echo "ErrorCode: {$this->result['errorCode']}";
					}
					
					break;
			}
		}
		
		/**
		 * 执行
		 */
		public function run( $showResultType = self::SHOW_RESULT_TYPE_NONE )
		{
			try
			{
				$this->dispatcher();
				$this->afterDispatcher();
				\Framework\Model\Container::save();
			}
			catch( \Exception $ex )
			{
				$this->result = array(
					'errorCode' => $ex->getCode() ,
					'errorMessage' => $ex->getMessage() ,
				);
				
				if( DEBUG )
				{
					$this->result['trace'] = $ex->getTraceAsString();
				}
			}
			$this->showResult( $showResultType );
		}
		
		/**
		 * 开启XHProf
		 */
		private function _startXHProf()
		{
			$xhProfConfig = Common::getConfig()->getXHProf();
			if( is_array( $xhProfConfig )
				&& isset( $xhProfConfig['isOpenAnalysis'] ) && $xhProfConfig['isOpenAnalysis']
				&& strlen( Common::getConfig()->getXHProfClass() ) > 0 )
			{
				$className = Common::getConfig()->getXHProfClass();
				$this->_xhProfClient = new $className;
				$this->_xhProfClient->startXHProf();
			}
		}
		
		/**
		 * 关闭XHProf
		 */
		private function _endXHProf()
		{
			if( isset( $this->_xhProfClient ) )
			{
				$this->_xhProfClient->endXHProf();
			}
		}
		
		/**
		 * 获取需要调用的方法名
		 * @return	string
		 */
		abstract protected function getMethodName();
		
		/**
		 * 获取输入的数据
		 * @return	array
		 */
		abstract protected function getInputData();
	}
}
?>
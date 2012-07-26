<?php

namespace Framework\Controller
{
	/**
	 * 普通控制器
	 * @author Luckyboys
	 */
	abstract class NormalAbstract extends Base
	{
		/**
		 * Session数据
		 * @var	array
		 */
		protected $session = array();
		
		/**
		 * 被注册的变量
		 * @var	array(
		 * 			{key:string}:mixed
		 * 		)
		 */
		private $_assignedVariable = array();
		
		/**
		 * 样式文件地址
		 * @var	string[]
		 */
		protected $styleFiles = array();
		
		/**
		 * 页面标题
		 * @var	string
		 */
		private $_title = "";
		
		/**
		 * 实例化
		 */
		public function __construct()
		{
			parent::__construct();
			$this->session = & $_SESSION;
		}
		
		/**
		 * 判断用户是否已经登录
		 * @return	boolean
		 */
		protected function isLogined()
		{
			return $this->_conditionOfLogined();
		}
		
		/**
		 * 指派变量
		 * @param	string	$key	键
		 * @param	mixed	$variable	变量
		 */
		protected function assignVariable( $key , $variable )
		{
			$this->_assignedVariable[$key] = $variable;
		}
		
		/**
		 * 显示页面
		 * @param	string	$pagePath	页面路径
		 */
		protected function displayPage( $pagePath )
		{
			extract( $this->_assignedVariable );
			include TPL_DIR . $pagePath;
		}
		
		/**
		 * 输出JSON响应
		 * @param	mixed	$data	数据
		 */
		protected function echoJSONResponse( $data )
		{
			echo json_encode( $data );
		}
		
		/**
		 * 输出处理动作的相应
		 * @param	boolean	$status	处理状态
		 * @param	string	$message	提示信息
		 * @param	string	$redirect	[optional]跳转地址
		 */
		protected function echoDoActionResponse( $status , $message = '' , $redirect = '' )
		{
			$result = array(
				'status' => $status ,
				'message' => $message ,
			);
			
			if( $redirect )
			{
				$result['redirect'] = $redirect;
			}
			
			$this->echoJSONResponse( $result );
		}
		
		/**
		 * 判断登录条件
		 * @return	boolean
		 */
		private function _conditionOfLogined()
		{
			return $this->_haveUserId() && $this->_haveUsername();
		}
		
		/**
		 * 判断是否有用户ID
		 * @return	boolean
		 */
		private function _haveUserId()
		{
			return isset( $this->session['userId'] ) && ( $this->session['userId'] > 0 );
		}
	
		/**
		 * 判断是否有用户名
		 * @return	boolean
		 */
		private function _haveUsername()
		{
			return isset( $this->session['username'] ) && strlen( $this->session['username'] ) > 0;
		}
	}
}
?>
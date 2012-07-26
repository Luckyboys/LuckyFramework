<?php

namespace Framework\WebRoot
{
	use Framework\Exception\Base;
	class API extends HTTPBase
	{
		/**
		 * 控制器
		 * @var	Controller_ApiAbstract
		 */
		protected $controller;
		
		/**
		 * 获取输入的数据
		 * @return	array
		 */
		protected function getInputData()
		{
			$datas = array();
			if( isset( $_POST['data'] ) )
			{
				$postData = json_decode( $_POST['data'] , true );
				if( !empty( $postData ) )
				{
					$datas = array_merge( $datas , $postData );
				}
				unset( $_POST['data'] );
			}
			
			if( isset( $_GET['data'] ) )
			{
				$getData = json_decode( $_GET['data'] , true );
				if( !empty( $getData ) )
				{
					$datas = array_merge( $datas , $getData );
				}
				unset( $_GET['data'] );
			}
			return array_merge( $_GET , $_POST , $datas );
		}
	}
}
?>
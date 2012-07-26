<?php

namespace Framework\IO
{
	use Framework\Exception\Base as Exception_Base;
	class IOException extends Exception_Base
	{
		/**
		 * 文件夹路径没有配置
		 * @var	int
		 */
		const STATUS_FOLDER_PATH_NOT_CONFIG = 300;
	}
}
?>
<?php

namespace Framework\XHProf
{
	class Sae implements iXHProf
	{
		/**
		 * (non-PHPdoc)
		 * @see XHProf_Interface::startXHProf()
		 */
		public function startXHProf()
		{
			if( function_exists( 'sae_xhprof_start' ) )
			{
				sae_xhprof_start();
			}
		}
		
		/**
		 * (non-PHPdoc)
		 * @see XHProf_Interface::endXHProf()
		 */
		public function endXHProf()
		{
			if( function_exists( 'sae_xhprof_end' ) )
			{
				sae_xhprof_end();
			}
		}
	}
}
?>
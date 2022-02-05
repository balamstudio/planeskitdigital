<?php

	class DiviLibShortcode {
		
		private static $initiated = false;
		
		/**
		 * Holds an instance of DiviLibShortcode Helper class
		 *
		 * @since 1.0
		 * @var DiviLibShortcode_Helper
		 */
		public static $helper;
		
		
		public static function init() {
			
			if ( ! self::$initiated ) {
				
				self::init_hooks();
			}
		}
		
		
		/**
		 * Initializes WordPress hooks
		 */
		protected static function init_hooks() {
			
			self::$initiated = true;
			
			add_shortcode( 'divilifeshortcode', array( 'DiviLibShortcode', 'showDiviLibShortcode' ) );
		}
		
		
		public static function showDiviLibShortcode( $atts ) {
			
			$atts = shortcode_atts( array( 'id' => ''), $atts );
			
			return do_shortcode('[et_pb_section global_module="' . $atts['id'] . '"][/et_pb_section]');
		}
		
		
		/**
		 * Log debugging infoormation to the error log.
		 *
		 * @param string $e The Exception object
		 */
		protected static function log( $e = FALSE ) {
			
			$data_log = $e;
			
			if ( is_object( $e ) ) {
				
				$data_log = sprintf( "Exception: \n %s \n", $e->getMessage() . "\r\n\r\n" . $e->getFile() . "\r\n" . 'Line:' . $e->getLine() );
			}
			
			if ( apply_filters( 'DiviLibShortcode_log', defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) ) {
				
				error_log( print_r( compact( 'data_log' ), true ) );
			}
		}
	}
	
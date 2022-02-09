<?php

	class DiviLibShortcode_Admin {
		
		private static $_show_errors = FALSE;
		private static $initiated = FALSE;
		
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		public static $options;
		
		public static function init() {
			
			if ( ! self::$initiated ) {
				
				self::init_hooks();
			}
		}
		
		
		private static function init_hooks() {
			
			self::$initiated = true;
			
			// Admin styles/scripts
			add_action( 'admin_init', array( 'DiviLibShortcode_Admin', 'init_requirements' ) );
			add_action( 'admin_init', array( 'DiviLibShortcode_Admin', 'register_assets' ) );
			
			// Register Divi Library Layout columns
			add_filter( 'manage_et_pb_layout_posts_columns', array( 'DiviLibShortcode_Admin', 'add_shortcode_column' ) );
			add_action( 'manage_et_pb_layout_posts_custom_column', array( 'DiviLibShortcode_Admin', 'manage_shortcode_column' ), 10, 2 );
		}
		
		
		public static function register_assets( $hook ) {
			
			wp_register_style( 'divi-library-shortcodes-admin-bootstrap', DIVI_LIBSHORT_PLUGIN_URL . 'assets/css/admin/bootstrap.css', array(), '1.0.0', 'all' );
			wp_register_style( 'divi-library-shortcodes-select2', DIVI_LIBSHORT_PLUGIN_URL . 'assets/css/admin/select2.min.css', array(), '4.0.6', 'all' );
			wp_register_script( 'divi-library-shortcodes-select2', DIVI_LIBSHORT_PLUGIN_URL . 'assets/js/admin/select2.full.min.js', array('jquery'), '4.0.6', true );
			wp_register_style( 'divi-library-shortcodes-select2-bootstrap', DIVI_LIBSHORT_PLUGIN_URL . 'assets/css/admin/select2-bootstrap.min.css', array('divi-library-shortcodes-admin-bootstrap'), '1.0.0', 'all' );
			
			wp_register_style( 'divi-library-shortcodes-admin', DIVI_LIBSHORT_PLUGIN_URL . 'assets/css/admin/admin.css', array(), '1.0.0', 'all' );
			wp_register_script( 'divi-library-shortcodes-admin-functions', DIVI_LIBSHORT_PLUGIN_URL . 'assets/js/admin/admin-functions.js', array( 'jquery' ), '1.0.0', true );
		}
		
		
		public static function include_assets( $hook ) {
			
			wp_enqueue_style( 'divi-library-shortcodes-select2' );
			wp_enqueue_style( 'divi-library-shortcodes-select2-bootstrap' );
			wp_enqueue_script( 'divi-library-shortcodes-select2' );
			wp_enqueue_style( 'divi-library-shortcodes-admin' );
			wp_enqueue_script( 'divi-library-shortcodes-admin-functions' );
		}
		
		
		public static function init_requirements() {
			
			if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
				
				if ( et_pb_is_allowed( 'divi_library' ) ) {
				
					if ( in_array( basename( $_SERVER['PHP_SELF'] ), array('post-new.php', 'page-new.php', 'post.php', 'page.php') ) ) {
						
						// Add WYSIWYG button
						add_filter( 'mce_buttons', array( 'DiviLibShortcode_Admin', 'dls_filter_mce_button' ) );
						add_filter( 'mce_external_plugins', array( 'DiviLibShortcode_Admin', 'dls_filter_mce_plugin' ) );
						add_action( 'edit_form_advanced', array( 'DiviLibShortcode_Admin', 'dls_wysiwyg_button' ) );
						add_action( 'edit_page_form', array( 'DiviLibShortcode_Admin', 'dls_wysiwyg_button' ) );
						
						add_action( 'admin_enqueue_scripts', array( 'DiviLibShortcode_Admin', 'include_assets' ), '999');
					}
					
					// Add Search Filter Post Title and Get Divi Library posts callback
					add_filter( 'posts_where', array( 'DiviLibShortcode_Admin', 'post_title_like_where' ), 10, 2 );
					add_action( 'wp_ajax_nopriv_ajax_dls_listposts', array( 'DiviLibShortcode_Admin', 'get_wp_posts' ) );
					add_action( 'wp_ajax_ajax_dls_listposts', array( 'DiviLibShortcode_Admin', 'get_wp_posts' ) );
				}
			}
		}
		
		
		public static function post_title_like_where( $where, $wp_query ) {
			
			global $wpdb;
			
			if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
				
				$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( trim( $post_title_like ) ) ) . '%\'';
			}
			
			return $where;
		}
		
		
		public static function get_wp_posts() {
			
			if ( isset( $_POST['q'] ) ) {
			
				$q = stripslashes( $_POST['q'] );
			
			} else {
				
				return;
			}
			
			
			if ( isset( $_POST['page'] ) ) {
				
				$page = (int) $_POST['page'];
				
			} else {
				
				$page = 1;
			}
			
			
			if ( isset( $_POST['json'] ) ) {
				
				$json = (int) $_POST['json'];
				
			} else {
				
				$json = 0;
			}
			
			$current_user_id = get_current_user_id();
			
			$data = null;
			
			$posts = array();
			
			$total_count = 0;
			
			$args = array(
				'post_title_like' => $q,
				'post_type' => 'et_pb_layout',
				'post_status' => 'publish',
				'author'     => $current_user_id,
				'meta_query'      => array(
					array(
						'key'     => '_et_pb_predefined_layout',
						'value'   => 'on',
						'compare' => 'NOT EXISTS',
					)
				),
				'cache_results'  => false,
				'posts_per_page' => 7,
				'paged' => $page,
				'orderby' => 'id',
				'order' => 'DESC'
			);
			$query = new WP_Query( $args );
			
			$get_posts = $query->get_posts();
			
			$posts = array_merge( $posts, $get_posts );
			
			$total_count = (int) $query->found_posts;
			
			$posts = self::keysToLower( $posts );
			
			if ( $json ) {
				
				header( 'Content-type: application/json' );
				$data = json_encode(
				
					array(
						'total_count' => $total_count,
						'items' => $posts
					)
				);
				
				die( $data );
			}
			
			return $posts;
		}
		
		
		private static function keysToLower( &$obj )
		{
			$type = (int) is_object($obj) - (int) is_array($obj);
			if ($type === 0) return $obj;
			foreach ($obj as $key => &$val)
			{
				$element = self::keysToLower($val);
				switch ($type)
				{
				case 1:
					if (!is_int($key) && $key !== ($keyLowercase = strtolower($key)))
					{
						unset($obj->{$key});
						$key = $keyLowercase;
					}
					$obj->{$key} = $element;
					break;
				case -1:
					if (!is_int($key) && $key !== ($keyLowercase = strtolower($key)))
					{
						unset($obj[$key]);
						$key = $keyLowercase;
					}
					$obj[$key] = $element;
					break;
				}
			}
			return $obj;
		}
		
		
		function dls_filter_mce_button( $buttons ) {
			
			array_push( $buttons, '|', 'divilifeshortcode' );
			
			return $buttons;
		}
		
		
		function dls_filter_mce_plugin( $plugins ) {
			
			$plugins['divilifeshortcodes'] = DIVI_LIBSHORT_PLUGIN_URL . 'assets/js/admin/tinymce_plugin_dls.js';
			
			return $plugins;
		}
		
		
		public static function dls_wysiwyg_button() {
		?>
			<script type="text/javascript">
			var dls_settings = {},
				dls_strings = {
				divilifeshortcode : "<?php esc_html_e( 'Add Divi Library Shortcodes', DIVI_LIBSHORT_PLUGIN_NAME ); ?>",
			};
			
			dls_settings['divilifeshortcode'] = {
				id: {
					name: '<?php esc_html_e( 'Choose Divi Library Module', DIVI_LIBSHORT_PLUGIN_NAME ); ?>',
					defaultvalue: '',
					description: '',
					type: 'select',
					options: ''
				}
			};
			</script>
		<?php
		}
		
		
		function add_shortcode_column( $columns ) {
			
			$_new_columns = array();
			
			foreach ( $columns as $column_key => $column ) {
				
				$_new_columns[ $column_key ] = $column;

				if ( 'taxonomy-layout_type' === $column_key ) {
					
					$_new_columns['dls_shortcode'] = esc_html__( 'Divi Library Shortcode', DIVI_LIBSHORT_PLUGIN_NAME );
				}
			}
			
			return $_new_columns;
		}
		
		
		function manage_shortcode_column( $column_key, $post_id ) {
			
			switch ( $column_key ) {
				
				case 'dls_shortcode':
						
					$divilifeshortcode = '[divilifeshortcode id=\'' . $post_id . '\']';
						
					echo $divilifeshortcode;
					
					break;
			}
		}
	}
	
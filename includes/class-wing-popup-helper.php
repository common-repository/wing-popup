<?php

/**
 * Helper class for Wing Popup
 *
 * @since 1.0.0
 */
class WDPOP_Helper
{
    // Static variable
    private static $wdpops;
    public $version = '1.0.0';

    // Static initialization method
    private static function get_popups() {
        if (self::$wdpops === null) {
            self::$wdpops = self::get_all_active_popups_privately();
        }
    }
    // Function to simulate fetching a value
    private static function get_all_active_popups_privately() {
        $popups = [];
        $wdpops = get_option( 'wdpops' );
        
        if(empty($wdpops) || empty($wdpops['wdpop_item'])) return $popups;
        
        foreach ($wdpops['wdpop_item'] as $key => $this_popup) {
            if($this_popup['popup_status'] == "0") continue;
            $popups[$key+1] = $this_popup; // Increase the index by 1 to match with the UI ID
        }
        return $popups;
    }
    public static function get_custom_post_types () {
        return  get_post_types(
			[
				'public'   => true,
				'_builtin' => false,
			],
			'objects'
		);
    }
    public static function get_custom_taxonomies () {
        return get_taxonomies(
			[
				'public'   => true,
				'_builtin' => false,
			],
			'objects'
		);
    }
    public static function get_display_options() {
	
		$top_level_items = [];
		$general = array(
			'home_page' => 'Home Page',
			'search_result_page' => 'Search Result Page',
			'404_error_page' => '404 Error Page',
            'archive_page' => 'Archive Page',
            'posts_page' => 'Posts Page',
            'single_page' => 'Single Page(All Post Types)',
		);
		$pages = array(
            'all_pages' => 'All Pages',
            'pages_selected' => 'Pages: Selected',
            'pages_id' => 'Pages: ID',
        );
		$posts = array(
            'all_posts' => 'All Posts',
            'posts_selected' => 'Posts: Selected',
            'posts_id' => 'Posts: ID',
            'posts_with_category' => 'Posts: With Category',
            'posts_with_tag' => 'Posts: With Tag',
            'posts_with_format' => 'Posts: With Format',
        );
		
        $categories = array(
            'all_categories' => 'All Categories',
            'categories_selected' => 'Categories: Selected',
            'categories_id' => 'Categories: ID',
        );
        $tags = array(
            'all_tags' => 'All Tags',
            'tags_selected' => 'Tags: Selected',
            'tags_id' => 'Tags: ID',
        );
        
		$default_items = [
			'General' => $general,
			'Pages' => $pages,
			'Posts' => $posts,
		];
		
		$top_level_items = $default_items;
		
		$post_types = self::get_custom_post_types();
		foreach ($post_types as $name => $post_type ) {
			$post_type_items = [
				'all_' . $post_type->name => 'All ' . $post_type->label,
				$post_type->name . '_selected' => $post_type->label . ': Selected',
				$post_type->name . '_id' => $post_type->label . ': With ID',
			];
			if($post_type->has_archive) {
				$post_type_items[$post_type->name . '_archive'] = $post_type->label . ' Archive';
			}
            
            $cpt_taxonomies = get_object_taxonomies($post_type->name, 'objects');
            foreach ( $cpt_taxonomies as $tax_name => $taxonomy ) {
                $post_type_items[$post_type->name . '_with_'. $taxonomy->name] = $post_type->label . ': With ' . $taxonomy->label;
            }
            
            $top_level_items[$post_type->label] = $post_type_items;
		}
        $top_level_items['Categories'] = $categories;
        $top_level_items['Tags'] = $tags;
        
        
        $taxonomies = self::get_custom_taxonomies();
		foreach ( $taxonomies as $tax_name => $taxonomy ) {
			$tax_items = [
				'all_' . $taxonomy->name => 'All ' . $taxonomy->label,
				$taxonomy->name . '_selected' => $taxonomy->label . ' Selected',
				$taxonomy->name . '_id' => $taxonomy->label . ' With ID',
			];
			$top_level_items[$taxonomy->labels->name] = $tax_items;
		}

        return $top_level_items;
    }
    public static function get_display_options_fields(){
        $display_options = self::get_display_options();
        
        $ajax_select = true;
        
        $fileds = array(
            array(
                'id'          => 'popup_display_options',
                'type'        => 'select',
                'placeholder' => 'Select an Option',
                'chosen'      => true,
                'options'     => $display_options,
                'class'     => 'popup_display_options_root',
            ),
            array(
                'id'          => 'pages_selected',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'ajax'        => $ajax_select,
                'options'     => 'pages',
                'placeholder' => 'Select pages',
                'dependency' => array( 'popup_display_options', '==', 'pages_selected' ),
            ),
            array(
                'id'    => 'pages_id',
                'type'  => 'text',
                'placeholder' => 'Page IDs. Ex: 15, 26, 33',
                'dependency' => array( 'popup_display_options', '==', 'pages_id' ),
            ),
            array(
                'id'          => 'posts_selected',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'ajax'        => $ajax_select,
                'options'     => 'posts',
                'placeholder' => 'Select Posts',
                'dependency' => array( 'popup_display_options', '==', 'posts_selected' ),
            ),
            array(
                'id'    => 'posts_id',
                'type'  => 'text',
                'placeholder' => 'Post IDs. Ex: 15, 26, 33',
                'dependency' => array( 'popup_display_options', '==', 'posts_id' ),
            ),
            array(
                'id'          => 'posts_with_category',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'ajax'        => $ajax_select,
                'options'     => 'categories',
                'placeholder' => 'Select Categories',
                'dependency' => array( 'popup_display_options', '==', 'posts_with_category' ),
            ),
            array(
                'id'          => 'posts_with_tag',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'ajax'        => $ajax_select,
                'options'     => 'tags',
                'placeholder' => 'Select Tags',
                'dependency' => array( 'popup_display_options', '==', 'posts_with_tag' ),
            ),
            array(
                'id'          => 'posts_with_format',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'options'     => 'categories',
                'query_args'  => array(
                    'taxonomy'  => 'post_format',
                ),
                'placeholder' => 'Select Formats',
                'dependency' => array( 'popup_display_options', '==', 'posts_with_format' ),
            ),
        );
        
        $post_types = self::get_custom_post_types();
		foreach ($post_types as $name => $post_type ) {
            
            $this_selected = array(
                'id'          => $post_type->name . '_selected',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'ajax'        => $ajax_select,
                'options'     => 'pages',
                'placeholder' => 'Select '.$post_type->label,
                'options'     => 'posts',
                'query_args'  => array(
                    'post_type' => $post_type->name,
                ),
                'dependency' => array( 'popup_display_options', '==', $post_type->name . '_selected' ),
            );
            $this_ids = array(
                'id'    => $post_type->name . '_id',
                'type'  => 'text',
                'placeholder' => $post_type->label.' IDs. Ex: 15, 26, 33',
                'dependency' => array( 'popup_display_options', '==', $post_type->name . '_id' ),
            );
            
            $cpt_taxonomies = get_object_taxonomies($post_type->name, 'objects');
            foreach ( $cpt_taxonomies as $tax_name => $taxonomy ) {
                $this_ctx_selected = array(
                    'id'          => $post_type->name . '_with_'. $taxonomy->name,
                    'type'        => 'select',
                    'chosen'      => true,
                    'multiple'    => true,
                    'sortable'    => true,
                    'ajax'        => $ajax_select,
                    'placeholder' => 'Select '.$taxonomy->label,
                    'options'     => 'categories',
                    'query_args'  => array(
                        'taxonomy'  => $taxonomy->name,
                    ),
                    'dependency' => array( 'popup_display_options', '==', $post_type->name . '_with_'. $taxonomy->name ),
                );
                $fileds[] = $this_ctx_selected;
            }

            
            
            
            $fileds[] = $this_selected;
            $fileds[] = $this_ids;
            
		}
        
        $categories_selected = array(
            'id'          => 'categories_selected',
            'type'        => 'select',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => $ajax_select,
            'options'     => 'categories',
            'placeholder' => 'Select Categories',
            'dependency' => array( 'popup_display_options', '==', 'categories_selected' ),
        );
        $categories_id = array(
            'id'    => 'categories_id',
            'type'  => 'text',
            'placeholder' => 'Category IDs. Ex: 15, 26, 33',
            'dependency' => array( 'popup_display_options', '==', 'categories_id' ),
        );

        $tags_selected = array(
            'id'          => 'tags_selected',
            'type'        => 'select',
            'chosen'      => true,
            'multiple'    => true,
            'sortable'    => true,
            'ajax'        => $ajax_select,
            'options'     => 'tags',
            'placeholder' => 'Select Tags',
            'dependency' => array( 'popup_display_options', '==', 'tags_selected' ),
        );
        $tags_id = array(
            'id'    => 'tags_id',
            'type'  => 'text',
            'placeholder' => 'Tag IDs. Ex: 15, 26, 33',
            'dependency' => array( 'popup_display_options', '==', 'tags_id' ),
        );
        
        $fileds[] = $categories_selected;
        $fileds[] = $categories_id;
        $fileds[] = $tags_selected;
        $fileds[] = $tags_id;

        $taxonomies = self::get_custom_taxonomies();
		foreach ( $taxonomies as $tax_name => $taxonomy ) {
            
            $this_selected = array(
                'id'          => $taxonomy->name . '_selected',
                'type'        => 'select',
                'chosen'      => true,
                'multiple'    => true,
                'sortable'    => true,
                'ajax'        => $ajax_select,
                'placeholder' => 'Select '.$taxonomy->label,
                'options'     => 'categories',
                'query_args'  => array(
                    'taxonomy'  => $taxonomy->name,
                ),
                'dependency' => array( 'popup_display_options', '==', $taxonomy->name . '_selected' ),
            );
            $this_ids = array(
                'id'    => $taxonomy->name . '_id',
                'type'  => 'text',
                'placeholder' => $taxonomy->label.' IDs. Ex: 15, 26, 33',
                'dependency' => array( 'popup_display_options', '==', $taxonomy->name . '_id' ),
            );
            
            $fileds[] = $this_selected;
            $fileds[] = $this_ids;
            
		}
        
        return $fileds;
        
    }
    public static function get_all_active_popups(){
        self::get_popups();
        return self::$wdpops;
    }
    public static function hide_by_excludes($excludes) {
        if(empty($excludes) || !is_array($excludes)) return false;
    
        foreach ($excludes as $exclude) {
            $key = $exclude["popup_display_options"];
            if(self::check_page_conditions($key, $exclude)) return true;            
            if(self::check_post_conditions($key, $exclude)) return true;
            if(self::check_custom_post_types($key, $exclude)) return true;
            if(self::check_category_and_tag_conditions($key, $exclude)) return true;
            if(self::check_custom_taxonomies($key, $exclude)) return true;
        }
    
        return false;
    }
    public static function hide_by_includes($includes) {
        if(empty($includes) || !is_array($includes)) return false;
        foreach ($includes as $include) {
            $key = $include["popup_display_options"];
            if(self::check_page_conditions($key, $include)) return false;
            if(self::check_post_conditions($key, $include)) return false;
            if(self::check_custom_post_types($key, $include)) return false;
            if(self::check_category_and_tag_conditions($key, $include)) return false;
            if(self::check_custom_taxonomies($key, $include)) return false;
        }
        return true;
    }
    public static function should_show_popup($popup) {
        if($popup['wdpop_item_tab']['popup_display_type'] == "everywhere") return true;
        
        $includes = isset($popup['wdpop_item_tab']['popup_display_accordions']['popup_display_includes']) ? $popup['wdpop_item_tab']['popup_display_accordions']['popup_display_includes'] : null;
        $excludes = $popup['wdpop_item_tab']['popup_display_accordions']['popup_display_excludes'];
        if(empty($includes) && empty($excludes)) return true;
        
        if(self::hide_by_excludes($excludes)) return false;
        
        if(self::hide_by_includes($includes)) return false;
        
        return true;
    }
    private static function check_page_conditions($key, $item) {
        $con = [
            "home_page" => is_front_page(),
            "posts_page" => is_home(),
            "search_result_page" => is_search(),
            "404_error_page" => is_404(),
            "archive_page" => is_archive(),
            "single_page" => is_singular(),
            "all_pages" => is_page(),
            "pages_selected" => isset($item["pages_selected"]) && is_page($item["pages_selected"]),
            "pages_id" => is_page(explode(",", $item["pages_id"])),
        ];
        return isset($con[$key]) && $con[$key];
    }
    private static function check_post_conditions($key, $item) {
        $con = [
            "all_posts" => is_single(),
            "posts_selected" => isset($item["posts_selected"]) && is_single($item["posts_selected"]),
            "posts_id" => is_single(explode(",", $item["posts_id"])),
            "posts_with_category" => isset($item["posts_with_category"]) && is_single(self::get_posts_by_categories($item["posts_with_category"])),
            "posts_with_tag" => isset($item["posts_with_tag"]) && is_single(self::get_posts_by_tags($item["posts_with_tag"])),
            "posts_with_format" => isset($item["posts_with_format"]) && is_single(self::get_posts_by_terms('post', 'post_format', $item["posts_with_format"])),
        ];
        return isset($con[$key]) && $con[$key];
    }
    private static function check_category_and_tag_conditions($key, $item) {
        $con = [
            "all_categories" => is_category(),
            "categories_selected" => isset($item["categories_selected"]) && is_category($item["categories_selected"]),
            "categories_id" => is_category(explode(",", $item["categories_id"])),
            "all_tags" => is_tag(),
            "tags_selected" => isset($item["tags_selected"]) && is_tag($item["tags_selected"]),
            "tags_id" => is_tag(explode(",", $item["tags_id"]))
        ];
        return isset($con[$key]) && $con[$key];
    }
    private static function check_custom_taxonomies($key, $item) {
        $taxonomies = self::get_custom_taxonomies();
    
        foreach ($taxonomies as $tax_name => $taxonomy) {
            if(self::check_single_tax($key, $taxonomy->name, $item)) return true;
        }
    
        return false;
    }
    private static function check_single_tax($key, $name, $item) {
        $con = [
            "all_" . $name => is_tax($name),
            $name . '_selected' => isset($item[$name . '_selected']) && is_tax($name, $item[$name . '_selected']),
            $name . '_id' => is_tax($name, explode(",", $item[$name . '_id'])),
        ];
        return isset($con[$key]) && $con[$key];
    }
    private static function check_custom_post_types($key, $item) {
        $post_types = self::get_custom_post_types();
    
        foreach ($post_types as $name => $post_type) {
            if(self::check_single_cpt($key, $post_type->name, $item)) return true;
        }
    
        return false;
    }
    private static function check_single_cpt($key, $name, $item) {
        $con = [
            "all_" . $name => is_singular($name),
            $name . '_selected' => is_singular($name) && in_array(get_the_ID(), $item[$name . '_selected']),
            $name . '_id' => is_singular($name) && in_array(get_the_ID(), explode(",", $item[$name . '_id'])),
            $name . '_archive' => is_post_type_archive($name),
        ];
        
        if(isset($con[$key]) && $con[$key]) return true;
        
        $cpt_taxonomies = get_object_taxonomies($name, 'objects');
        foreach ($cpt_taxonomies as $tax_name => $taxonomy) {
            if ($key == $name . '_with_' . $taxonomy->name) {
                $cpt_term_ids = $item[$name . '_with_' . $taxonomy->name];
                $cpt_post_ids = self::get_posts_by_terms($name, $taxonomy->name, $cpt_term_ids);
                if (is_singular($name) && in_array(get_the_ID(), $cpt_post_ids)) {
                    return true;
                }
            }
        }
    
        return false;
    }
    private static function get_posts_by_terms($post_type, $taxonomy, $terms) {
        $args = array(
            'posts_per_page' => 100,
            'fields' => 'ids',
            'post_type' => $post_type,
        );
    
        $tax_q_name = 'tax_query';
        $tax_q_val = array(
            'taxonomy' => $taxonomy,
            'field' => 'term_id',
            'terms' => $terms,
            'operator' => 'IN'
        );
        $args[$tax_q_name] = $tax_q_val;
        
        $query = new WP_Query($args);
    
        return $query->posts;
    }
    public static function get_posts_by_tags($tags) {
        $args = array(
            'tag__in' => $tags, // Tags to include
            'posts_per_page' => -1, // Retrieve all posts
            'fields' => 'ids' // Only get post IDs
        );
    
        $query = new WP_Query($args);
    
        return $query->posts;
    }
    public static function get_posts_by_categories($categories) {
        $args = array(
            'category__in' => $categories,
            'posts_per_page' => -1,
            'fields' => 'ids'
        );
    
        $query = new WP_Query($args);
    
        return $query->posts;
    }
    public static function get_all_popups() {
        $popups = [];
        $wdpops = get_option( 'wdpops' );
        foreach ($wdpops['wdpop_item'] as $key => $this_popup) {
            $popups[$key+1] = $this_popup;
        }
        return $popups;
    }
    public static function if_popups_active() {
        $wdpops = WDPOP_Helper::get_all_active_popups();
        
        foreach ($wdpops as $key => $this_popup) {
            if(WDPOP_Helper::should_show_popup($this_popup)) return true;
        }
        return false;
    }
	/**
	 * Returns a file's last modification time formatted with a version prefix.
	 * The version is included as a prefix in the format: "version.ymdGis".
	 *
	 * @param string $dir The directory path of the file.
	 * @return string A string representing the last modification time of the file
	 *                formatted according to the version and date format "ymdGis".
	 * @since 1.0.0
	 */
	public static function wing_filemtime( $dir ){
		return gmdate(WDPOP_VER .".ymdGis", filemtime( $dir ));
	}
	
	/**
	 * Enqueues a stylesheet with a versioned URL to avoid caching problems.
	 * The version is dynamically generated based on the file's last modification time.
	 *
	 * @param string $handle The handle name for the stylesheet.
	 * @param string $src    The source URL of the stylesheet relative to WDPOP_URL.
	 * @since 1.0.0
	 */
	public static function wing_add_dynamic_style( $handle = '', $src ='' ){
		wp_enqueue_style( $handle, WDPOP_URL.$src, array(), self::wing_filemtime(WDPOP_DIR.$src), 'all' );
	}
	/**
	 * Enqueues a script with a versioned URL to avoid caching problems.
	 * The version is dynamically generated based on the file's last modification time.
	 *
	 * @param string $handle  The handle name for the script.
	 * @param string $src     The source URL of the script relative to WDPOP_URL.
	 * @param array  $dep     (Optional) An array of registered script handles this script depends on. Default is array('jquery').
	 * @param bool   $footer  (Optional) Whether to enqueue the script before </body> instead of in the <head>. Default is true.
	 * @since 1.0.0
	 */
	public static function wing_add_dynamic_script( $handle = '', $src ='', $dep = array('jquery'), $footer = true ){
        $args = array( 
            'in_footer' => $footer,
            'strategy'  => 'defer',
        );
		wp_enqueue_script( $handle, WDPOP_URL.$src, $dep, self::wing_filemtime(WDPOP_DIR.$src), $args );	
	}


}



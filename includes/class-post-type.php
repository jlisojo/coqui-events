<?php
/**
 * Event post type and taxonomies.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Simple_Events_Post_Type {

    public function __construct() {
        add_action('init', array($this, 'register_post_type'), 0);
        add_action('init', array($this, 'register_taxonomies'), 0);
        add_action('pre_get_posts', array($this, 'archive_query'));
    }

    /**
     * Register the events CPT.
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Events', 'Post Type General Name', 'coqui-events'),
            'singular_name'         => _x('Event', 'Post Type Singular Name', 'coqui-events'),
            'menu_name'             => __('Events', 'coqui-events'),
            'name_admin_bar'        => __('Event', 'coqui-events'),
            'archives'              => __('Event Archives', 'coqui-events'),
            'attributes'            => __('Event Attributes', 'coqui-events'),
            'parent_item_colon'     => __('Parent Event:', 'coqui-events'),
            'all_items'             => __('All Events', 'coqui-events'),
            'add_new_item'          => __('Add New Event', 'coqui-events'),
            'add_new'               => __('Add New', 'coqui-events'),
            'new_item'              => __('New Event', 'coqui-events'),
            'edit_item'             => __('Edit Event', 'coqui-events'),
            'update_item'           => __('Update Event', 'coqui-events'),
            'view_item'             => __('View Event', 'coqui-events'),
            'view_items'            => __('View Events', 'coqui-events'),
            'search_items'          => __('Search Events', 'coqui-events'),
            'not_found'             => __('No events found', 'coqui-events'),
            'not_found_in_trash'    => __('No events found in Trash', 'coqui-events'),
            'featured_image'        => __('Event Image', 'coqui-events'),
            'set_featured_image'    => __('Set event image', 'coqui-events'),
            'remove_featured_image' => __('Remove event image', 'coqui-events'),
            'use_featured_image'    => __('Use as event image', 'coqui-events'),
            'insert_into_item'      => __('Insert into event', 'coqui-events'),
            'uploaded_to_this_item' => __('Uploaded to this event', 'coqui-events'),
            'items_list'            => __('Events list', 'coqui-events'),
            'items_list_navigation' => __('Events list navigation', 'coqui-events'),
            'filter_items_list'     => __('Filter events list', 'coqui-events'),
        );

        $args = array(
            'label'               => __('Event', 'coqui-events'),
            'description'         => __('Events and activities', 'coqui-events'),
            'labels'              => $labels,
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'taxonomies'          => array(Simple_Events_Helpers::TAX_CATEGORY, Simple_Events_Helpers::TAX_TAG),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-calendar-alt',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => Simple_Events_Helpers::rewrite_slug(),
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rewrite'             => array(
                'slug'       => Simple_Events_Helpers::rewrite_slug(),
                'with_front' => false,
            ),
            'query_var'           => true,
        );

        register_post_type(Simple_Events_Helpers::POST_TYPE, $args);
    }

    /**
     * Register category and tag taxonomies.
     */
    public function register_taxonomies() {
        register_taxonomy(Simple_Events_Helpers::TAX_CATEGORY, array(Simple_Events_Helpers::POST_TYPE), array(
            'labels' => array(
                'name'              => _x('Event Categories', 'taxonomy general name', 'coqui-events'),
                'singular_name'     => _x('Event Category', 'taxonomy singular name', 'coqui-events'),
                'search_items'      => __('Search Categories', 'coqui-events'),
                'all_items'         => __('All Categories', 'coqui-events'),
                'parent_item'       => __('Parent Category', 'coqui-events'),
                'parent_item_colon' => __('Parent Category:', 'coqui-events'),
                'edit_item'         => __('Edit Category', 'coqui-events'),
                'update_item'       => __('Update Category', 'coqui-events'),
                'add_new_item'      => __('Add New Category', 'coqui-events'),
                'new_item_name'     => __('New Category Name', 'coqui-events'),
                'menu_name'         => __('Categories', 'coqui-events'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
            'show_in_rest'      => true,
        ));

        register_taxonomy(Simple_Events_Helpers::TAX_TAG, array(Simple_Events_Helpers::POST_TYPE), array(
            'labels' => array(
                'name'          => _x('Event Tags', 'taxonomy general name', 'coqui-events'),
                'singular_name' => _x('Event Tag', 'taxonomy singular name', 'coqui-events'),
                'search_items'  => __('Search Tags', 'coqui-events'),
                'all_items'     => __('All Tags', 'coqui-events'),
                'edit_item'     => __('Edit Tag', 'coqui-events'),
                'update_item'   => __('Update Tag', 'coqui-events'),
                'add_new_item'  => __('Add New Tag', 'coqui-events'),
                'new_item_name' => __('New Tag Name', 'coqui-events'),
                'menu_name'     => __('Tags', 'coqui-events'),
            ),
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-tag'),
            'show_in_rest'      => true,
        ));
    }

    /**
     * Order the public archive by event date.
     *
     * @param WP_Query $query Query.
     */
    public function archive_query($query) {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }

        if (!$query->is_post_type_archive(Simple_Events_Helpers::POST_TYPE) && !$query->is_tax(array(Simple_Events_Helpers::TAX_CATEGORY, Simple_Events_Helpers::TAX_TAG))) {
            return;
        }

        $query->set('meta_key', '_se_event_date');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', (int) Simple_Events_Settings::get('per_page', 12));
        $query->set('meta_query', array(
            array(
                'key'     => '_se_event_date',
                'value'   => current_time('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ));
    }
}

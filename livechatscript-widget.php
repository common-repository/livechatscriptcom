<?php
/*
Plugin Name:    Insert Live Chat Script
Plugin URI:     http://www.livechatscript.com/plugin.aspx
Description:    Adds Livechat script to your page
Version:        4.7
Author:         admin@livechatscript.com
Author URI: 	http://www.livechatscript.com
License:        GPLv2
*/


class LivechatscriptWidget extends WP_Widget {

    /**
     * Widget construction
     */
    function __construct() {
        $widget_ops = array('classname' => 'widget_text enhanced-text-widget', 'description' => __('Insert LiveChat Script', 'livechatscript'));
        $control_ops = array('width' => 400, 'height' => 250);
        parent::__construct('LivechatscriptWidget', __('LiveChat Script', 'livechatscript'), $widget_ops, $control_ops);
        load_plugin_textdomain('livechatscript', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /**
     * Setup the widget output
     */
    function widget( $args, $instance ) {

        if (!isset($args['widget_id'])) {
          $args['widget_id'] = null;
        }

        extract($args);

        $text = apply_filters('widget_enhanced_text', $instance['text'], $instance);
        $hideTitle = !empty($instance['hideTitle']) ? true : false;
        $newWindow = !empty($instance['newWindow']) ? true : false;
        $filterText = !empty($instance['filter']) ? true : false;
        $bare = !empty($instance['bare']) ? true : false;

        if ( $cssClass ) {
            if( strpos($before_widget, 'class') === false ) {
                $before_widget = str_replace('>', 'class="'. $cssClass . '"', $before_widget);
            } else {
                $before_widget = str_replace('class="', 'class="'. $cssClass . ' ', $before_widget);
            }
        }

        echo $bare ? '' : $before_widget;

        if ($newWindow) $newWindow = "target='_blank'";


        echo $bare ? '' : '<div class="textwidget widget-text">';

        // Parse the text through PHP
        ob_start();
        eval('?>' . $text);
        $text = ob_get_contents();
        ob_end_clean();

        // Run text through do_shortcode
        $text = do_shortcode($text);

        // Echo the content
        echo $filterText ? wpautop($text) : $text;

        echo $bare ? '' : '</div>' . $after_widget;

    }

    /**
     * Run on widget update
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['text'] =  $new_instance['text'];
        $instance['newWindow'] = isset($new_instance['newWindow']);
        $instance['filter'] = isset($new_instance['filter']);
        $instance['bare'] = isset($new_instance['bare']);

        return $instance;
    }

    /**
     * Setup the widget admin form
     */
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'text' => ''
        ));

        $text = format_to_edit($instance['text']);
?>

        <style>
            .monospace { font-family: Consolas, Lucida Console, monospace; }
        </style>



        <p>
            <div>You need to paste the Livechat script code below. If you dont have it yet, get it from <a href="http://www.livechatscript.com">http://www.livechatscript.com</a></div>
            <br><br>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Code', 'livechatscript'); ?>:</label>
            <textarea class="widefat monospace" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        </p>


<?php
    }
}

/**
 * Register the widget
 */
function enhanced_text_widget_init() {
    register_widget('LivechatscriptWidget');
}

add_action('widgets_init', 'enhanced_text_widget_init');

<?php
/*
 * Plugin Name: WP Go Maps Block
 * Plugin URI: https://wordpress.org/plugins/wp-go-maps-block
 * Description: The easiest-to-use Google Maps plugin is now available as a standalone block! Create custom Google or OpenLayers maps with high-quality markers, and easily add your map to WordPress posts or pages.
 * Version: 1.0.0
 * Author: WP Go Maps (formerly WP Google Maps)
 * Author URI: https://www.wpgmaps.com
 * Text Domain: wp-go-maps-block
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * 1.0.0 - TBD
 * Launch!
 */

namespace WPGMZA\Standalone\Gutenberg;

if(!defined('ABSPATH')){
    exit;
}

class MapBlock {
    const SHORTCODE_PROXY_SLUG = 'wpgmza';

    private $cachedVersion = null;

    /**
     * Constructor
     */
    public function __construct(){
        $this->hooks();
    }

    /**
     * All hooks and shortcode registers happen here
     * 
     * @return void
     */
    public function hooks(){
        add_action('enqueue_block_assets', array($this, 'onEnqueueBlockAssets'));
        add_action('init', array($this, 'onInit'));

        add_filter('wpgmza_internal_engine_build_version_plugins', array($this, 'getBuildVersion'));
    }

    /**
     * On init delegate
     * 
     * @return void
     */
    public function onInit(){
        if($this->checkRequirements()){
            $this->registerBlock();
        }
    }

    /**
     * On block assets enqueue delegate
     * 
     * @return void
     */
    public function onEnqueueBlockAssets(){
        if(!is_admin() || !$this->checkRequirements()){
            return;
        }

        $versionString = $this->getVersion();

        $blockAssets = array(
            "wp-blocks", 
            "wp-i18n"
        );

        if(!wp_script_is('wp-edit-widgets') && !wp_script_is('wp-customize-widgets')){
            $blockAssets[] = "wp-editor";
        }

        $path = plugin_dir_url(__FILE__);
        wp_register_script(
            "wpgmza-standalone-map-block", 
            $path . "js/block.js", 
            $blockAssets,
            $versionString
        );

        wp_localize_script("wpgmza-standalone-map-block", 'wpgmzaStandaloneBlockLocalized', 
            array(
                'defaultIcon' => $path . 'images/icon.png', 
                'blinkyIcon' => $path . 'images/blinky.png', 
                'hasFullPlugin' => $this->hasFullPlugin() ? 'true' : 'false',
                'fullPluginLink' =>  admin_url('plugin-install.php?tab=plugin-information&plugin=wp-google-maps'),
                'fullPluginNotices' => array(
                    (object) array( "title" => __('Want to add unlimited markers to your maps?', 'wp-go-maps-block') ),
                    (object) array( "title" => __('Want to create polygons and polylines?', 'wp-go-maps-block') ),
                    (object) array( "title" => __('Need to add shapes to your maps?', 'wp-go-maps-block') ),
                    (object) array( "title" => __('Did you know we also have a Store locator?', 'wp-go-maps-block') ),
                ),
                'fullPluginFeatures' => array(
                    __('Unlimited Markers', 'wp-go-maps-block'),
                    __('Store Locator', 'wp-go-maps-block'),
                    __('Map Themes', 'wp-go-maps-block'),
                    __('Polygons & Polylines', 'wp-go-maps-block'),
                    __('Much more!', 'wp-go-maps-block')
                ),
                'mapListPageLink' => admin_url('admin.php?page=wp-google-maps-menu')
            )
        );

        wp_register_style(
            "wpgmza-standalone-map-block", 
            $path . "css/block.css", 
            array(),
            $versionString
        );

        /* For the previews, also enqueue OL - We will only do OL for now */
        wp_register_style('wpgmza_block_ol_api_call', $path . 'lib/ol.css', array(), $versionString);
        wp_register_script('wpgmza_block_ol_api_call', $path . 'lib/ol.js', array(), $versionString);
    }

    /**
     * Register the map block
     * 
     * @return void
     */
    public function registerBlock(){
        $path = plugin_dir_path(__FILE__);
        
        register_block_type_from_metadata(
            $path, 
            array(
                'render_callback' => array($this, 'onRender')
            )
        );
    }

    /**
     * Render the map block, without the use of a shortcode specifically
     * 
     * @return void
     */
    public function onRender($attributes){
        $html = "";

        /* Prepare the attributes */
        $defaults = array(
            "id" => "1",
            "zoom" => false,
            "width" => false,
            "height" => false,
            "map_engine" => "open-layers",
            "google_maps_api_key" => "",
            "lat" => "",
            "lng" => "",
            "address" => "",
            "alignment" => "center",
            "infowindow_mode" => "click"
        );

        /* Leverage shortcode_atts to combine defaults with passed attributes */
        $attributes = shortcode_atts($defaults, $attributes);

        /* Convert attributes over into an object */
        $attributes = (object) $attributes;

        /* Attribute cleanup */
        $attributes->id = !empty($attributes->id) && !empty(intval($attributes->id)) ? intval($attributes->id) : 1;
        $attributes->map_engine = !empty($attributes->map_engine) ? $attributes->map_engine : 'open-layers';

        /* Escape the attributes */
        foreach($attributes as $key => $value){
            $attributes->{$key} = esc_attr($value);
        }

        if(!$this->hasFullPlugin()){
            /* Only our block plugin is in use */
            $styles = array(
                'display' => 'block',
                'width' => !empty($attributes->width) ? esc_attr($attributes->width) : '100%',
                'height' => !empty($attributes->height) ? esc_attr($attributes->height) : '400px',
                'overflow' => 'hidden',
                'position' => 'relative'
            );

            $classes = array('wpgmza_map', 'wpgmza_standalone_map_block');
            if(!empty($attributes->classname)){
                $classes[] = trim($attributes->classname);
            }

            switch($attributes->alignment){
                case 'left':
                    $classes[] = "wpgmza-auto-left"; 
                    break;
                case 'center': 
                    $classes[] = "wpgmza-auto-center";    	
                    break;
                case 'right':
                    $classes[] = "wpgmza-auto-right";    	
                    break;
            }

            $elemAttributes = array(
                "id" => "wpgmza_map_block_{$attributes->id}",
                "class" => implode(" ", $classes),
                "style" => $this->prepareInlineAttributes($styles, ";", ":", ""),
                "data-maps-engine" => $attributes->map_engine,
                "data-build-engine" => 'standalone-block',
                "data-icon" => plugin_dir_url(__FILE__) . 'images/icon.png',
                "data-lat" => $attributes->lat,
                "data-lng" => $attributes->lng,
                "data-zoom" => $attributes->zoom,
                "data-address" => $attributes->address,
                "data-infowindow-mode" => $attributes->infowindow_mode
            );


            $elemAttributes = $this->prepareInlineAttributes($elemAttributes);
            $html = "<div class='wpgmza_map_wrapper'><div {$elemAttributes}></div></div>";
        } else {
            /* Full plugin is in use, we need to proxy it  */
            $styles = array(
                'width' => !empty($attributes->width) ? esc_attr($attributes->width) : '100%',
                'height' => !empty($attributes->height) ? esc_attr($attributes->height) : '400px',
            );

            $classes = array();
            if(!empty($attributes->classname)){
                $classes[] = trim($attributes->classname);
            }

            switch($attributes->alignment){
                case 'left':
                    $classes[] = "wpgmza-proxied-left"; 
                    break;
                case 'center': 
                    $classes[] = "wpgmza-proxied-center";    	
                    break;
                case 'right':
                    $classes[] = "wpgmza-proxied-right";    	
                    break;
            }

            $elemAttributes = array(
                "class" => implode(" ", $classes),
                "style" => $this->prepareInlineAttributes($styles, ";", ":", ""),
                "data-lat" => $attributes->lat,
                "data-lng" => $attributes->lng,
                "data-zoom" => $attributes->zoom,
                "data-address" => $attributes->address,
                "data-infowindow-mode" => $attributes->infowindow_mode
            );

            $elemAttributes = $this->prepareInlineAttributes($elemAttributes);

            /* Prepare the proxied shortcode */
            $proxy = (object) array(
                "shortcode" => self::SHORTCODE_PROXY_SLUG,
                'attributes' => array(
                    'id' => $attributes->id
                )
            );

            $proxy->attributes = $this->prepareInlineAttributes($proxy->attributes);
            $proxyShortcode = do_shortcode("[{$proxy->shortcode} {$proxy->attributes}]");


            $html = "<div class='wpgmza_map_wrapper proxied'><div {$elemAttributes}>{$proxyShortcode}</div></div>";
        }

        /* Asset Handling */
        $this->onEnqueueShortcodeAssets($attributes);
        
        return $html;
    }

    /**
     * For WP Go Maps Support team
     * 
     * We add a build version to the system so that it can be identified when debugging a site
     * 
     * @param array $versions The current versions
     * 
     * @return array
     */
    public function getBuildVersion($versions){
        if(is_array($versions)){
            $versions['standalone'] = $this->getVersion();
        }
        return $versions;
    }

    /**
     * Load all the frontend assets for the shortcode (driven by block) to render
     * 
     * This includes the API, based on attribute engine selection and the main client JS which will do the actual work
     * 
     * @param object $attributes
     * 
     * @return void
     */
    private function onEnqueueShortcodeAssets($attributes){
        if($this->hasFullPlugin()){
            /* User has switched to using the full plugin, we will keep the blocks working by polyfilling their logic */
            $this->onEnqueuePolyfillAssets();
            return;
        }

        $versionString = $this->getVersion();
        $path = plugin_dir_url(__FILE__);
        
        wp_enqueue_style("wpgmza_block_client", $path . "css/client.css", array(), $versionString);
        wp_enqueue_script('wpgmza_block_client', $path . 'js/client.js', array('jquery'), $versionString);

        $engine = !empty($attributes->map_engine) ? $attributes->map_engine : 'open-layers';
        
        switch($engine){
            case 'google-maps':
                $libraries = array('geometry', 'places', 'visualization', 'marker');

                $params = (object) array(
                    'v' => 'quarterly',
                    'language' => get_locale(),
                    'key' => !empty($attributes->google_maps_api_key) ? trim($attributes->google_maps_api_key) : '',
                    'libraries' => implode(',', $libraries),
                );

                switch($params->language){
                    case 'he_IL': 
                        $params->language = 'iw';
                        break;
                }

                $params->language = substr($params->language, 0, 2);
                $url = '//maps.google.com/maps/api/js?' . http_build_query($params);

                wp_enqueue_script('wpgmza_block_google_api_call', $url, array(), $versionString);
                break;
            case 'open-layers':
            default: 
                wp_enqueue_style('wpgmza_block_ol_api_call', $path . 'lib/ol.css', array(), $versionString);
                wp_enqueue_script('wpgmza_block_ol_api_call', $path . 'lib/ol.js', array(), $versionString);
                break;
        }
    }

    /**
     * Load polyfill assets to allow the blocks created with the block plugin to
     * work as expected, even though the full plugin has been installed as well
     * 
     * This means leveraging the full plugin core API instead of hand-rolling each feature for the block
     * 
     * @return void
     */
    private function onEnqueuePolyfillAssets(){
        $versionString = $this->getVersion();
        $path = plugin_dir_url(__FILE__);
        
        wp_enqueue_style("wpgmza_block_polyfill", $path . "css/block-polyfill.css", array(), $versionString);
        wp_enqueue_script('wpgmza_block_polyfill', $path . 'js/block-polyfill.js', array('jquery', 'wpgmza'), $versionString);
    }

    /**
     * Check if the block can be registered
     * 
     * This might become disabled if WP Go Maps plugin is installed, or if the Gutenberg core is not accessible
     * 
     * @return bool
    */
    private function checkRequirements(){
        if(function_exists('register_block_type')){
            return true;
        }
        return false;
    }

    /**
     * Check if the user has also installed our full plugin, if so, that takes preference
     * 
     * We would ideally still load this block, but change it to be managed by the core plugin
     * 
     * Perhaps load additional client assets to help with that management
     * 
     * @return bool
     */
    private function hasFullPlugin(){
        if(class_exists('WPGMZA\Plugin')){
            return true;
        }
        return false;
    }

    /**
	 * Combines inline attributes 
	 * 
	 * @param array $data The data to be combined
	 * @param string $separator Symbol to use for separate 'rows'
	 * @param string $assigner Symbol to use for key/value assignments
	 * @param string $capsule What to wrap values in, if anything
	 * 
	 * @return string 
	*/
	private function prepareInlineAttributes($data, $separator = " ", $assigner = "=", $capsule = "\""){
		if(is_array($data)){
			$output = "";
			foreach($data as $key => $value){
				if(is_array($value)){
					$value = $this->prepareInlineAttributes($value, $separator, $assigner, $capsule);
				}

				$value = esc_attr($value);

				$output .= "{$key}{$assigner}{$capsule}{$value}{$capsule}{$separator}";
			}

			return $output;
		}
		return $data;
	}

    /**
     * Get a version string for scripts
     * 
     * @return string
    */
    protected function getVersion(){
        if($this->cachedVersion != null)
			return $this->cachedVersion;
		
		$subject = file_get_contents(plugin_dir_path(__FILE__) . 'wp-go-maps-block.php');
		if(preg_match('/Version:\s*(.+)/', $subject, $m))
			$this->cachedVersion = trim($m[1]);
		
		return $this->cachedVersion;
    }
}


add_action('plugins_loaded', function() {
	$mapBlockInstance = new MapBlock();
});

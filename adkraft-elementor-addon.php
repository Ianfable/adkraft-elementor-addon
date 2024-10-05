<?php
/**
 * Plugin Name: AdKraft Elementor Addon
 * Description: Elementor Addon.
 * Author: Marko Jankovic
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Ako je ova datoteka pozvana izravno, prekini.

final class My_Elementor_Addon {

    const VERSION = '1.0.0';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function init_widgets() {
        require_once( 'widgets/menu-dropdown.php' );
    }

    public function enqueue_scripts() {
        // Dodajte CSS datoteku
        wp_enqueue_style( 'elementor-menu-addon-css', plugins_url( 'css/elementor-menu-addon.css', __FILE__ ), [], self::VERSION );

        // Dodajte JavaScript datoteku
        wp_enqueue_script( 'elementor-menu-addon-js', plugins_url( 'js/elementor-menu-addon.js', __FILE__ ), [], self::VERSION, true );
    }

}

My_Elementor_Addon::instance();

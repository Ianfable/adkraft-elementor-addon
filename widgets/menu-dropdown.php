<?php


class Menu_Mobile_Widget extends \Elementor\Widget_Base {

public function get_name() {
    return 'menu-dropdown';
}

public function get_title() {
    return __( 'Menu Dropdown', 'adkraft-elementor-addon' );
}

public function get_icon() {
    return 'eicon-menu-bar';
}

public function get_categories() {
    return [ 'general' ];
}

protected function _register_controls() {
    $menus = wp_get_nav_menus();
    $menu_options = array();

    foreach ( $menus as $menu ) {
        $menu_options[ $menu->term_id ] = $menu->name;
    }

    $this->start_controls_section(
        'section_menu',
        [
            'label' => __( 'Odabir izbornika', 'adkraft-elementor-addon' ),
        ]
    );

    $this->add_control(
        'selected_menu',
        [
            'label' => __( 'Izaberi izbornik', 'adkraft-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $menu_options,
            'default' => key( $menu_options ),
        ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
        'section_style',
        [
            'label' => __( 'Stilovi', 'adkraft-elementor-addon' ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_control(
        'font_size',
        [
            'label' => __( 'Veličina fonta', 'adkraft-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 50,
                    'max' => 200,
                ],
            ],
           'selectors' => [
                '{{WRAPPER}} .loki-menu > ul > li > a' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $this->add_control(
        'dropdown_font_size',
        [
            'label' => __( 'Veličina fonta za dropdown stavke', 'adkraft-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 5,
                    'step' => 0.1,
                ],
                '%' => [
                    'min' => 50,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .loki-menu ul li ul li a' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    $this->add_control(
        'font_color',
        [
            'label' => __( 'Boja fonta', 'adkraft-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#000000',
            'selectors' => [
                '{{WRAPPER}} .loki-menu > ul > li > a' => 'color: {{VALUE}};',
            ],
        ]
    );

    $this->add_control(
        'dropdown_text_color',
        [
            'label' => __( 'Boja teksta u padajućem izborniku', 'adkraft-elementor-addon' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .loki-menu ul li ul li a' => 'color: {{VALUE}};',
            ],
        ]
    );


    // Dodajte ostala polja za stilove ovdje prema potrebi

    $this->end_controls_section();
}
protected function render() {
    $settings = $this->get_settings_for_display();
    $menu_id = $settings['selected_menu'];
    $menu = wp_get_nav_menu_object($menu_id);

    if (!$menu) {
        _e('Izbornik nije pronađen.', 'adkraft-elementor-addon');
        return;
    }

    $menu_items = wp_get_nav_menu_items($menu->term_id);
    $dropdown_id = 1;

    echo '<div class="loki-wrapper">
            <div class="loki-header">
                <div class="loki-container">
                 <nav class="loki-menu loki-submenu-scale" role="navigation">
                    <ul style="display:block">';

    foreach ($menu_items as $menu_item) {
        if ($menu_item->menu_item_parent == 0) {
            $uniqueId = "loki-" . time() . $dropdown_id;

            echo '<li class="loki-menu-current">
                    <input type="checkbox" id="' . $uniqueId . '">
                    <a href="' . $menu_item->url . '">' . $menu_item->title;
            
            if ($this->hasChildItems($menu_item->ID, $menu_items)) {
                echo ' <label for="' . $uniqueId . '"></label>';
            }

            echo '</a>
                  <ul>';

            foreach ($menu_items as $submenu) {
                if ($submenu->menu_item_parent == $menu_item->ID) {
                    $subUniqueId = "loki-" . time() . $submenu->ID;

                    echo '<li class="ad-kraft-menu-first-level">
                            <input type="checkbox" id="' . $subUniqueId . '">
                            <a href="' . $submenu->url . '">' . $submenu->title;

                    if ($this->hasChildItems($submenu->ID, $menu_items)) {
                        echo ' <label for="' . $subUniqueId . '"></label>';
                    }

                    echo '</a>
                          <ul>';

                    foreach ($menu_items as $second_level_submenu) {
                        if ($second_level_submenu->menu_item_parent == $submenu->ID) {
                            $secondLevelUniqueId = "loki-" . time() . $second_level_submenu->ID;

                            echo '<li class="ad-kraft-menu-second-level">
                                    <input type="checkbox" id="' . $secondLevelUniqueId . '">
                                    <a href="' . $second_level_submenu->url . '">' . $second_level_submenu->title;

                            if ($this->hasChildItems($second_level_submenu->ID, $menu_items)) {
                                echo ' <label for="' . $secondLevelUniqueId . '"></label>';
                            }

                            echo '</a>
                                  <ul>';

                            foreach ($menu_items as $third_level_submenu) {
                                if ($third_level_submenu->menu_item_parent == $second_level_submenu->ID) {
                                    echo '<li class="ad-kraft-menu-third-level">
                                            <a href="' . $third_level_submenu->url . '">' . $third_level_submenu->title . '</a>
                                          </li>';
                                }
                            }

                            echo '</ul>
                                </li>';
                        }
                    }

                    echo '</ul>
                        </li>';
                }
            }

            echo '</ul>
                </li>';

            $dropdown_id++;
        }
    }

    echo '</ul></nav></div></div></div>';
}

// Helper function to determine if a menu item has child items
private function hasChildItems($itemId, $allItems) {
    foreach ($allItems as $item) {
        if ($item->menu_item_parent == $itemId) {
            return true;
        }
    }
    return false;
}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Menu_Mobile_Widget() );
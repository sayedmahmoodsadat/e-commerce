<?php

namespace Elementor;

if (!defined('ABSPATH'))
    exit;      // Exit if accessed directly

class Tour_Card_Elementor_Widget extends Widget_Base {

    //Function for get the slug of the element name.
    public function get_name() {
        return 'tour-card-elementor-widget';
    }

    //Function for get the name of the element.
    public function get_title() {
        return __('Tour Card', 'card-elements-for-elementor');
    }

    //Function for get the icon of the element.
    public function get_icon() {
        return 'fas fa-bus-alt';
    }

    //Function for include element into the category.
    public function get_categories() {
        return ['card-elements'];
    }

    /*
     * Adding the controls fields for the tour card
     */
    protected function register_controls() {

        /*
         * Start tour card controls fields
         */
        $this->start_controls_section(
            'section_items_data', array(
                'label' => esc_html__('Tour Card Items', 'card-elements-for-elementor'),
            )
        );


        $this->add_control(
            'tour_card_style', [
                'label' => __('Tour Card Style', 'card-elements-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'tour-card-style-1' => esc_html__('Card Style 1', 'card-elements-for-elementor'),
                    'tour-card-style-2' => esc_html__('Card Style 2 (PRO)', 'card-elements-for-elementor'),
                    'tour-card-style-3' => esc_html__('Card Style 3 (PRO)', 'card-elements-for-elementor'),
                    'tour-card-style-4' => esc_html__('Card Style 4 (PRO)', 'card-elements-for-elementor'),
                    'tour-card-style-5' => esc_html__('Card Style 5 (PRO)', 'card-elements-for-elementor'),
                ],
                'default' => 'tour-card-style-1',
            ]
        );

        $this->add_control(
            'place_name', [
                'label' => __('Place Name', 'card-elements-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Name', 'card-elements-for-elementor'),
                'placeholder' => __('Enter place name', 'card-elements-for-elementor'),
            ]
        );

        $this->add_control(
            'place_image', [
                'label' => __('Image', 'card-elements-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'image_background_overlay', [
                'label' => __('Background Overlay', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .tour-img-overlay,
                    {{WRAPPER}} .tour-card-style-2 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-3 .tour-img-overlay,
                    {{WRAPPER}} .tour-card-style-4 .tour-img-overlay,
                    {{WRAPPER}} .tour-card-style-5 .tour-img-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tour_price', array(
                'label' => esc_html__('Cost', 'card-elements-for-elementor'),
                'type' => Controls_Manager::TEXT,
            )
        );

        $this->add_control(
            'tour_sale', array(
                'label' => esc_html__('Add Sale Tag', 'card-elements-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Show', 'card-elements-for-elementor'),
                'label_off' => __('Hide', 'card-elements-for-elementor'),
            )
        );

        $this->add_control(
            'tour_sale_text', array(
                'label' => esc_html__('Add Sale Text', 'card-elements-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'tour_sale' => 'yes',
                ],
            )
        );

        $this->add_control(
            'tour_sale_background', array(
                'label' => esc_html__('Sale Background', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tour_sale' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-2 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-sale-wrapper' => 'background-color: {{VALUE}};',
                ],
            )
        );


        $this->add_responsive_control(
            'tour_sale_alignment', [
                'label' => __('Alignment', 'card-elements-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'condition' => [
                    'tour_sale' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-sale-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tour_sale_color', array(
                'label' => esc_html__('Sale Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tour_sale' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-2 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-sale-wrapper .tour-sale-text' => 'color: {{VALUE}};',
                ],
            )
        );


        $this->add_control(
            'tour_sale_border_radius', [
                'label' => __('Border Radius', 'card-elements-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'condition' => [
                    'tour_sale' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-2 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-sale-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_sale',
                'condition' => [
                    'tour_sale' => 'yes',
                ],
                'selector' => '{{WRAPPER}} .elementor-tour-sale-wrapper .tour-sale-text',
            ]
        );

        $this->add_control(
            'tour_sale_icon', [
                'label' => 'Sale Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-tags',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'tour_sale' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_sale_color', [
                'label' => __('Icon Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-sale-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tour-sale-icon svg *' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sale_icon_size', [
                'label' => __('Sale Icon Size', 'card-elements-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'tour_sale' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-sale-icon i' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .tour-sale-icon svg' => 'width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'tour_days_icon', [
                'label' => 'Days Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-sun',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'tour_days', array(
                'label' => esc_html__('Days', 'card-elements-for-elementor'),
                'type' => Controls_Manager::NUMBER,
            )
        );

        $this->add_control(
            'tour_person_icon', [
                'label' => 'Person Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-users',
                    'library' => 'fa-solid',
                ],
            ]
        );


        $this->add_control(
            'tour_person', array(
                'label' => esc_html__('Persons', 'card-elements-for-elementor'),
                'type' => Controls_Manager::NUMBER,
            )
        );

        $this->add_control(
            'tour_guide_icon', [
                'label' => 'Guide Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-flag',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'tour_guides', array(
                'label' => esc_html__('Guides', 'card-elements-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'tour_card_style' => ['tour-card-style-1', 'tour-card-style-2', 'tour-card-style-3', 'tour-card-style-4', 'tour-card-style-5'],
                ],
            )
        );

        $this->add_control(
            'display_whatsapp_share', [
                'label' => __('Whatsapp Share', 'card-elements-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'card-elements-for-elementor'),
                'label_off' => __('Hide', 'card-elements-for-elementor'),
            ]
        );

        $this->add_control(
            'display_tour_description', [
                'label' => __('Display Description', 'card-elements-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'card-elements-for-elementor'),
                'label_off' => __('Hide', 'card-elements-for-elementor'),
            ]
        );


        $this->add_control(
            'tour_description', array(
                'label' => esc_html__('Description', 'card-elements-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'display_tour_description' => 'yes',
                ],
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipisci ng elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'card-elements-for-elementor'),
            )
        );

        $this->add_control(
            'button_text', [
                'label' => __('Button Text', 'card-elements-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Book now', 'card-elements-for-elementor'),
                'placeholder' => __('Button text', 'card-elements-for-elementor'),
            ]
        );

        $this->add_control(
            'tour_btn_link', [
                'label' => esc_html__('URL (Link)', 'card-elements-for-elementor'),
                'type' => \Elementor\Controls_Manager::URL,
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->end_controls_section();
        /*
         * End tour card controls fields
         */


        $this->start_controls_section(
            'section_tour_icon', [
                'label' => __('Icon', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'icon_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Size', 'card-elements-for-elementor' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tour-detail-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tour-detail-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


        $this->add_control(
            'tour_detail_icon_color', [
                'label' => __('Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-detail-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tour-detail-icon svg *' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*
         * Start control style tab for tour card
         * Start name control style
         */
        $this->start_controls_section(
            'section_tour_name', [
                'label' => __('Name', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'name_text_align', [
                'label' => __('Alignment', 'card-elements-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-name-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_color', [
                'label' => __('Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-name-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_name',
                'selector' => '{{WRAPPER}} .elementor-tour-name-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .tour-card-style-3 .elementor-tour-name-wrapper,
                {{WRAPPER}} .tour-card-style-4 .elementor-tour-name-wrapper,
                {{WRAPPER}} .tour-card-style-5 .elementor-tour-name-wrapper,
                {{WRAPPER}} .tour-card-style-1 .elementor-tour-name-wrapper',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tour_button_border_radius', [
                'label' => __('Border Radius', 'card-elements-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .elementor-tour-name-wrapper,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-name-wrapper,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-name-wrapper,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-name-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
            ]
        );

        $this->end_controls_section();

        /*
         * Start position control style
         */
        $this->start_controls_section(
            'section_tour_cost', [
                'label' => __('Cost', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'type_text_align', [
                'label' => __('Alignment', 'card-elements-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'card-elements-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'card-elements-for-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'card-elements-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'card-elements-for-elementor'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-price-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'position_color', [
                'label' => __('Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-price-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_position',
                'selector' => '{{WRAPPER}} .elementor-tour-price-wrapper',
            ]
        );

        $this->add_control(
            'separator_color', [
                'label' => __('Separator Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tour_card_style' => ['tour-card-style-2'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-content' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*
         * Start details control style
         */
        $this->start_controls_section(
            'section_tour_details', [
                'label' => __('Details', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'details_text_color', [
                'label' => __('Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-detail-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_detais_text',
                'selector' => '{{WRAPPER}} .tour-detail-text',
            ]
        );

        $this->add_control(
            'details_background_color', [
                'label' => __('Background Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tour_card_style' => ['tour-card-style-1'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-detail-ul' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*
         * Start desription control style
         */
        $this->start_controls_section(
            'section_tour_description', [
                'label' => __('Description', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'description_text_align', [
                'label' => __('Alignment', 'card-elements-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-description-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'descriptsion_color', [
                'label' => __('Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tour-description-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography_description',
                'selector' => '{{WRAPPER}} .elementor-tour-description-wrapper',
            ]
        );

        $this->end_controls_section();

        /*
         * Start button control style
         */
        $this->start_controls_section(
            'section_style', [
                'label' => __('Button', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_text_align', [
                'label' => __('Alignment', 'card-elements-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'card-elements-for-elementor'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'condition' => [
                    'tour_card_style' => ['tour-card-style-2', 'tour-card-style-3', 'tour-card-style-4'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-button' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .tour-button a',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tour_btn_border_radius', [
                'label' => __('Border Radius', 'card-elements-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tour-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .tour-button a',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal', [
                'label' => __('Normal', 'card-elements-for-elementor'),
            ]
        );

        $this->add_control(
            'tour_button_text_color', [
                'label' => __('Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tour-button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color', [
                'label' => __('Background Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-button a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover', [
                'label' => __('Hover', 'card-elements-for-elementor'),
            ]
        );

        $this->add_control(
            'tour_text_hover_color', [
                'label' => __('Text Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-button a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tour_button_background_hover_color', [
                'label' => __('Background Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-button a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tour_button_hover_border_color', [
                'label' => __('Border Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-button a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .tour-button a',
            ]
        );

        $this->add_responsive_control(
            'text_padding', [
                'label' => __('Padding', 'card-elements-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .tour-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        /*
         * Start box control style
         */
        $this->start_controls_section(
            'section_tour_contentbox', [
                'label' => __('Box', 'card-elements-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_padding', [
                'label' => __('Padding', 'card-elements-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .tour-container,
                    {{WRAPPER}} .tour-card-style-2 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-3 .tour-img,
                    {{WRAPPER}} .tour-card-style-4 .tour-card-left,
                    {{WRAPPER}} .tour-card-style-4 .tour-card-right,
                    {{WRAPPER}} .tour-card-style-5 .tour-main-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_box_background_color', [
                'label' => __('Background Color', 'card-elements-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-2 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-4 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-5 .tour-main-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .tour-card-style-1,
                                {{WRAPPER}} .tour-card-style-2,
                                {{WRAPPER}} .tour-card-style-3,
                                {{WRAPPER}} .tour-card-style-4,
                                {{WRAPPER}} .tour-card-style-5',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'border_radius', [
                'label' => __('Border Radius', 'card-elements-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .tour-card-style-1,
                    {{WRAPPER}} .tour-card-style-2,
                    {{WRAPPER}} .tour-card-style-3,
                    {{WRAPPER}} .tour-card-style-4,
                    {{WRAPPER}} .tour-card-style-5' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .tour-card-style-1, {{WRAPPER}} .tour-card-style-2',
            ]
        );
        $this->end_controls_section();

        /*
         * End control style tab for tour card
         */
    }

    /**
     * Render tour card widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $tour_btn_link = $settings['tour_btn_link'];
        $target = $settings['tour_btn_link']['is_external'] ? ' target="_blank"' : '';
        $rel = $settings['tour_btn_link']['nofollow'] ? ' rel="nofollow"' : '';

        switch ($settings['tour_card_style']) {
            case 'tour-card-style-1':
                include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-1.php';  // Card Style 1
                break;
            case 'tour-card-style-2':
                include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 2
                break;
            case 'tour-card-style-3':
                include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 3
                break;
            case 'tour-card-style-4':
                include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 4
                break;
            case 'tour-card-style-5':
                include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 5

                break;
            default:
                include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Default Card Style 1
                break;
        }
    }

}

Plugin::instance()->widgets_manager->register_widget_type(new Tour_Card_Elementor_Widget());

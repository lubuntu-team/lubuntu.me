<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Creative_Button extends Widget_Base {
	

	public function get_name() {
		return 'eael-creative-button';
	}

	public function get_title() {
		return esc_html__( 'EA Creative Button', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}


	protected function _register_controls() {

		// Content Controls
  		$this->start_controls_section(
  			'eael_section_creative_button_content',
  			[
  				'label' => esc_html__( 'Button Content', 'essential-addons-elementor' )
  			]
  		);


		$this->add_control(
			'creative_button_text',
			[
				'label' => __( 'Button Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Click Me!',
				'placeholder' => __( 'Enter button text', 'essential-addons-elementor' ),
				'title' => __( 'Enter button text here', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
			'creative_button_secondary_text',
			[
				'label' => __( 'Button Secondary Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Go!',
				'placeholder' => __( 'Enter button secondary text', 'essential-addons-elementor' ),
				'title' => __( 'Enter button secondary text here', 'essential-addons-elementor' ),
			]
		);


		$this->add_control(
			'creative_button_link_url',
			[
				'label' => __( 'Link URL', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '#',
				'placeholder' => __( 'Enter link URL for the button', 'essential-addons-elementor' ),
				'title' => __( 'Enter heading for the button', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
			'creative_button_link_target',
			[
				'label' => esc_html__( 'Open in new window?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( '_blank', 'essential-addons-elementor' ),
				'label_off' => __( '_self', 'essential-addons-elementor' ),
				'default' => '_self',
			]
		);

		$this->add_responsive_control(
			'eael_creative_button_alignment',
			[
				'label' => esc_html__( 'Button Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'prefix_class' => 'eael-creative-button-align-',
			]
		);
		
		$this->add_control(
			'eael_creative_button_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
			]
		);

		$this->add_control(
			'eael_creative_button_icon_alignment',
			[
				'label' => esc_html__( 'Icon Position', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'essential-addons-elementor' ),
					'right' => esc_html__( 'After', 'essential-addons-elementor' ),
				],
				'condition' => [
					'eael_creative_button_icon!' => '',
				],
			]
		);
		

		$this->add_control(
			'eael_creative_button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 60,
					],
				],
				'condition' => [
					'eael_creative_button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button-icon-right' => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} .eael-creative-button-icon-left' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .eael-creative-button--shikoba i' => 'left: -{{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
		
        $this->start_controls_section(
			'eael_section_pro',
			[
				'label' => __( 'Go Premium for More Features', 'essential-addons-elementor' )
			]
		);

        $this->add_control(
            'eael_control_get_pro',
            [
                'label' => __( 'Unlock more possibilities', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( '', 'essential-addons-elementor' ),
						'icon' => 'fa fa-unlock-alt',
					],
				],
				'default' => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="https://essential-addons.com/elementor/buy.php" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
            ]
        );

        $this->end_controls_section();

  		// Style Controls
		$this->start_controls_section(
			'eael_section_creative_button_settings',
			[
				'label' => esc_html__( 'Button Effects &amp; Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'creative_button_effect',
			[
				'label' => esc_html__( 'Set Button Effect', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-creative-button--default',
				'options' => [
					'eael-creative-button--default' 	=> esc_html__( 'Default', 		'essential-addons-elementor' ),
					'eael-creative-button--winona' 		=> esc_html__( 'Winona', 		'essential-addons-elementor' ),
					'eael-creative-button--ujarak' 		=> esc_html__( 'Ujarak', 		'essential-addons-elementor' ),
					'eael-creative-button--wayra' 		=> esc_html__( 'Wayra', 		'essential-addons-elementor' ),
					'eael-creative-button--tamaya' 		=> esc_html__( 'Tamaya', 		'essential-addons-elementor' ),
					'eael-creative-button--rayen' 		=> esc_html__( 'Rayen', 		'essential-addons-elementor' ),
					'eael-creative-button--pro-1' 	=> esc_html__( 'Pipaluk (Pro)', 'essential-addons-elementor' ),
					'eael-creative-button--pro-2' 	=> esc_html__( 'Moema (Pro)', 	'essential-addons-elementor' ),
					'eael-creative-button--pro-3' 	=> esc_html__( 'Wave (Pro)', 	'essential-addons-elementor' ),
					'eael-creative-button--pro-4' 	=> esc_html__( 'Aylen (Pro)', 	'essential-addons-elementor' ),
					'eael-creative-button--pro-5' 	=> esc_html__( 'Saqui (Pro)', 	'essential-addons-elementor' ),
					'eael-creative-button--pro-6' 	=> esc_html__( 'Wapasha (Pro)', 'essential-addons-elementor' ),
					'eael-creative-button--pro-7' 	=> esc_html__( 'Nuka (Pro)', 	'essential-addons-elementor' ),
					'eael-creative-button--pro-8' 	=> esc_html__( 'Antiman (Pro)', 'essential-addons-elementor' ),
					'eael-creative-button--pro-9' 	=> esc_html__( 'Quidel (Pro)', 	'essential-addons-elementor' ),
					'eael-creative-button--pro-10' 	=> esc_html__( 'Shikoba (Pro)', 'essential-addons-elementor' ),
				],
				'description' => '10 more effects on <a href="https://essential-addons.com/elementor/buy.php">Pro version</a>'
			]
		);



		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_creative_button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-creative-button',
			]
		);
		
		$this->add_responsive_control(
			'eael_creative_button_padding',
			[
				'label' => esc_html__( 'Button Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
			]
		);
		
		
		
		$this->start_controls_tabs( 'eael_creative_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_creative_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);
		

		
		$this->add_control(
			'eael_creative_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_creative_button_border',
				'selector' => '{{WRAPPER}} .eael-creative-button',
			]
		);
		
		$this->add_control(
			'eael_creative_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-creative-button::before' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-creative-button::after' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
		

		
		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_creative_button_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_creative_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);

		$this->add_control(
			'eael_creative_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f54',
			]
		);

		$this->add_control(
			'eael_creative_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wapasha::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::before'  => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .eael-creative-button',
			]
		);
		
		
		$this->end_controls_section();
		
		
		$this->end_controls_section();	
		
		
	}


	protected function render( ) {
		
		
      $settings = $this->get_settings();
      $creative_button_image = $this->get_settings( 'creative_button_image' );
	  $button_padding = $this->get_settings( 'eael_creative_button_padding' ); 

	?>


<a id="eael-creative-button-<?php echo esc_attr($this->get_id()); ?>" class="eael-creative-button <?php echo esc_attr($settings['creative_button_effect'] ); ?>"
    href="<?php echo esc_attr($settings['creative_button_link_url'] ); ?>" target="<?php echo esc_attr($settings['creative_button_link_target'] ); ?>" data-text="<?php echo esc_attr($settings['creative_button_secondary_text'] ); ?>">
	<span>
		<?php if ( ! empty( $settings['eael_creative_button_icon'] ) && $settings['eael_creative_button_icon_alignment'] == 'left' ) : ?>
			<i class="<?php echo esc_attr($settings['eael_creative_button_icon'] ); ?> eael-creative-button-icon-left" aria-hidden="true"></i> 
		<?php endif; ?>

		<?php echo  $settings['creative_button_text'];?>

		<?php if ( ! empty( $settings['eael_creative_button_icon'] ) && $settings['eael_creative_button_icon_alignment'] == 'right' ) : ?>
			<i class="<?php echo esc_attr($settings['eael_creative_button_icon'] ); ?> eael-creative-button-icon-right" aria-hidden="true"></i> 
		<?php endif; ?>
	</span>
</a>

<style type="text/css">

	a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?> {
	    color: <?php echo esc_attr($settings['eael_creative_button_text_color'] ); ?>;
	    padding: <?php echo $button_padding['top'] . $button_padding['unit'] .' '.  $button_padding['right'] . $button_padding['unit'] .' '.  $button_padding['bottom'] . $button_padding['unit'] .' '.  $button_padding['left'] . $button_padding['unit'] ?>;
	    background-color: <?php echo esc_attr($settings['eael_creative_button_background_color'] ); ?>;
	}

	a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>:hover {
	    color: <?php echo esc_attr($settings['eael_creative_button_hover_text_color'] ); ?>;
	    background-color: <?php echo esc_attr($settings['eael_creative_button_hover_background_color'] ); ?>;
	}


<?php if ( $settings['creative_button_effect'] == 'eael-creative-button--winona' ): ?>

    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--winona::after, 
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--winona > span {
        padding: <?php echo $button_padding['top'] . $button_padding['unit'] .' '.  $button_padding['right'] . $button_padding['unit'] .' '.  $button_padding['bottom'] . $button_padding['unit'] .' '.  $button_padding['left'] . $button_padding['unit'] ?>;
    }
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--winona::after {
        color: <?php echo esc_attr($settings['eael_creative_button_hover_text_color'] ); ?>;
    }    

<?php elseif ( $settings['creative_button_effect'] == 'eael-creative-button--ujarak' ): ?>


    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--ujarak:hover {
        background-color: <?php echo esc_attr($settings['eael_creative_button_background_color'] ); ?>;
    } 
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--ujarak::before {
        background-color: <?php echo esc_attr($settings['eael_creative_button_hover_background_color'] ); ?>;
    }   

<?php elseif ( $settings['creative_button_effect'] == 'eael-creative-button--wayra' ): ?>

    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--wayra:hover {
        background-color: <?php echo esc_attr($settings['eael_creative_button_background_color'] ); ?>;
    }  
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--wayra:hover::before {
        background-color: <?php echo esc_attr($settings['eael_creative_button_hover_background_color'] ); ?>;
    }     

<?php elseif ( $settings['creative_button_effect'] == 'eael-creative-button--tamaya' ): ?>

    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--tamaya::before, 
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--tamaya::after {
        background-color: <?php echo esc_attr($settings['eael_creative_button_background_color'] ); ?>;
        color: <?php echo esc_attr($settings['eael_creative_button_text_color'] ); ?>;
    }

    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--tamaya:hover {
        background-color: <?php echo esc_attr($settings['eael_creative_button_hover_background_color'] ); ?>;
    }

    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--tamaya::before {
        padding: <?php echo $button_padding['top'] . $button_padding['unit'] .' '.  $button_padding['right'] . $button_padding['unit'] .' '.  $button_padding['bottom'] . $button_padding['unit'] .' '.  $button_padding['left'] . $button_padding['unit'] ?>;
    }

<?php elseif ( $settings['creative_button_effect'] == 'eael-creative-button--rayen' ): ?>

    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--rayen:hover {
        background-color: <?php echo esc_attr($settings['eael_creative_button_background_color'] ); ?>;
    }
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--rayen::before {
        background-color: <?php echo esc_attr($settings['eael_creative_button_hover_background_color'] ); ?>;
    }
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--rayen::before, 
    a#eael-creative-button-<?php echo esc_attr($this->get_id()); ?>.eael-creative-button--rayen > span {
        padding: <?php echo $button_padding['top'] . $button_padding['unit'] .' '.  $button_padding['right'] . $button_padding['unit'] .' '.  $button_padding['bottom'] . $button_padding['unit'] .' '.  $button_padding['left'] . $button_padding['unit'] ?>;
    }

<?php else: ?>


<?php endif; ?>

</style>


	<?php
	
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Creative_Button() );
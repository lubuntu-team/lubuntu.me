<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Fancy_Text extends Widget_Base {
	

	public function get_name() {
		return 'eael-fancy-text';
	}

	public function get_title() {
		return esc_html__( 'EA Fancy Text', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}


	protected function _register_controls() {

		// Content Controls
  		$this->start_controls_section(
  			'eael_fancy_text_content',
  			[
  				'label' => esc_html__( 'Fancy Text', 'essential-addons-elementor' )
  			]
  		);

		
		$this->add_control(
			'eael_fancy_text_prefix',
			[	
				'label' => esc_html__( 'Prefix Text', 'essential-addons-elementor' ),
				'placeholder' => esc_html__( 'Place your prefix text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This is the ', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
			'eael_fancy_text_strings',
			[
				'label' => esc_html__( 'Fancy Text Strings', 'essential-addons-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'eael_fancy_text_strings_text_field' => esc_html__( 'first string', 'essential-addons-elementor' ),
					],
					[
						'eael_fancy_text_strings_text_field' => esc_html__( 'second string', 'essential-addons-elementor' ),
					],
					[
						'eael_fancy_text_strings_text_field' => esc_html__( 'third string', 'essential-addons-elementor' ),
					],
				],
				'fields' => [
					[
						'name' => 'eael_fancy_text_strings_text_field',
						'label' => esc_html__( 'Fancy String', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
				],
				'title_field' => '{{{ eael_fancy_text_strings_text_field }}}',
			]
		);


		$this->add_control(
			'eael_fancy_text_suffix',
			[
				'label' => esc_html__( 'Suffix Text', 'essential-addons-elementor' ),
				'placeholder' => esc_html__( 'Place your suffix text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( ' of the sentence.', 'essential-addons-elementor' ),
			]
		);
		
		

		$this->end_controls_section();
		
		// Settings Control
  		$this->start_controls_section(
  			'eael_fancy_text_settings',
  			[
  				'label' => esc_html__( 'Fancy Text Settings', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
			'eael_fancy_text_style',
			[
				'label' => esc_html__( 'Style Type', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1' => esc_html__( 'Style 1', 'essential-addons-elementor' ),
					'style-2' => esc_html__( 'Style 2', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'eael_fancy_text_style_pro_alert',
			[
				'label' => esc_html__( 'Only available in pro version!', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'eael_fancy_text_style' => ['style-2'],
				]
			]
		);

		$this->add_responsive_control(
			'eael_fancy_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
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
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-container' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'eael_fancy_text_transition_type',
			[
				'label' => esc_html__( 'Animation Type', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'typing',
				'options' => [
					'typing' => esc_html__( 'Typing', 'essential-addons-elementor' ),
					'fadeIn' => esc_html__( 'Fade', 'essential-addons-elementor' ),
					'fadeInUp' => esc_html__( 'Fade Up', 'essential-addons-elementor' ),
					'fadeInDown' => esc_html__( 'Fade Down', 'essential-addons-elementor' ),
					'fadeInLeft' => esc_html__( 'Fade Left', 'essential-addons-elementor' ),
					'fadeInRight' => esc_html__( 'Fade Right', 'essential-addons-elementor' ),
					'zoomIn' => esc_html__( 'Zoom', 'essential-addons-elementor' ),
					'bounceIn' => esc_html__( 'Bounce', 'essential-addons-elementor' ),
					'swing' => esc_html__( 'Swing', 'essential-addons-elementor' ),
				],
			]
		);
		

		$this->add_control(
			'eael_fancy_text_speed',
			[
				'label' => esc_html__( 'Typing Speed', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '50',
				'condition' => [
					'eael_fancy_text_transition_type' => 'typing',
				],
			]
		);
		
		$this->add_control(
			'eael_fancy_text_delay',
			[
				'label' => esc_html__( 'Delay on Change', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '2500'
			]
		);
		
		$this->add_control(
			'eael_fancy_text_loop',
			[
				'label' => esc_html__( 'Loop the Typing', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'eael_fancy_text_transition_type' => 'typing',
				],
			]
		);
		
		$this->add_control(
			'eael_fancy_text_cursor',
			[
				'label' => esc_html__( 'Display Type Cursor', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'eael_fancy_text_transition_type' => 'typing',
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
		
		$this->start_controls_section(
			'eael_fancy_text_prefix_styles',
			[
				'label' => esc_html__( 'Prefix Text Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_fancy_text_prefix_color',
			[
				'label' => esc_html__( 'Prefix Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-prefix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-fancy-text-prefix',
			]
		);
		
		
		$this->end_controls_section();
		
		
		
		$this->start_controls_section(
			'eael_fancy_text_strings_styles',
			[
				'label' => esc_html__( 'Fancy Text Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_fancy_text_strings_color',
			[
				'label' => esc_html__( 'Fancy Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_fancy_text_strings_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-fancy-text-strings, {{WRAPPER}} .typed-cursor',
			]
		);
		
		$this->add_control(
			'eael_fancy_text_strings_background_color',
			[
				'label' => esc_html__( 'Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'eael_fancy_text_cursor_color',
			[
				'label' => esc_html__( 'Typing Cursor Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_fancy_text_cursor' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_fancy_text_strings_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_fancy_text_strings_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_fancy_text_strings_border',
				'selector' => '{{WRAPPER}} .eael-fancy-text-strings',
			]
		);
		
		
		$this->add_control(
			'eael_fancy_text_strings_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		
		
		$this->start_controls_section(
			'eael_fancy_text_suffix_styles',
			[
				'label' => esc_html__( 'Suffix Text Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_fancy_text_suffix_color',
			[
				'label' => esc_html__( 'Suffix Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-suffix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'ending_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-fancy-text-suffix',
			]
		);
		
		
		$this->end_controls_section();
		
	}


	protected function render( ) {
		
		
      $settings = $this->get_settings();
		
      if( 'style-1' === $settings['eael_fancy_text_style'] || 'style-2' === $settings['eael_fancy_text_style'] ) {
      	$eael_fancy_text_style = 'style-1';
      }
	?>

	<div class="eael-fancy-text-container <?php echo esc_attr( $eael_fancy_text_style ); ?>">			
			<?php if ( ! empty( $settings['eael_fancy_text_prefix'] ) ) : ?><span class="eael-fancy-text-prefix"><?php echo wp_kses(($settings['eael_fancy_text_prefix'] ), true ); ?> </span><?php endif; ?>
			
			<?php if ( $settings['eael_fancy_text_transition_type']  == 'fancy' ) : ?>
			<span id="eael-fancy-text-<?php echo esc_attr($this->get_id()); ?>" class="eael-fancy-text-strings"></span>
			<?php endif; ?>
			
			<?php if ( $settings['eael_fancy_text_transition_type']  != 'fancy' ) : ?>
			<span id="eael-fancy-text-<?php echo esc_attr($this->get_id()); ?>" class="eael-fancy-text-strings"><?php 
				$eael_fancy_text_strings_list = "";
				foreach ( $settings['eael_fancy_text_strings'] as $item ) {
				           $eael_fancy_text_strings_list .=  $item['eael_fancy_text_strings_text_field'] . ', '; 
				}
				echo rtrim($eael_fancy_text_strings_list, ", "); ?></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $settings['eael_fancy_text_suffix'] ) ) : ?><span class="eael-fancy-text-suffix"> <?php echo wp_kses(($settings['eael_fancy_text_suffix'] ), true ); ?> </span><?php endif; ?>
	</div><!-- close .eael-fancy-text-container -->
	
	<div class="clearfix"></div>
	
	<?php if ( $settings['eael_fancy_text_transition_type']  == 'typing' ) : ?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		'use strict';
		$("#eael-fancy-text-<?php echo esc_attr($this->get_id()); ?>").typed({
		strings: [<?php foreach ( $settings['eael_fancy_text_strings'] as $item ) : ?><?php if ( ! empty( $item['eael_fancy_text_strings_text_field'] ) ) : ?>"<?php echo esc_attr($item['eael_fancy_text_strings_text_field'] ); ?>",<?php endif; ?><?php endforeach; ?>],
			typeSpeed: <?php echo esc_attr($settings['eael_fancy_text_speed'] ); ?>,
			backSpeed: 0,
			startDelay: 300,
			backDelay: <?php echo esc_attr($settings['eael_fancy_text_delay'] ); ?>,
			showCursor: <?php if ( ! empty( $settings['eael_fancy_text_cursor'] ) ) : ?>true<?php else: ?>false<?php endif; ?>,
			loop: <?php if ( ! empty( $settings['eael_fancy_text_loop'] ) ) : ?>true<?php else: ?>false<?php endif; ?>,
		});
	});
	</script>
	<?php endif; ?>
	
	<?php if ( $settings['eael_fancy_text_transition_type']  != 'typing' ) : ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			'use strict';
			$("#eael-fancy-text-<?php echo esc_attr($this->get_id()); ?>").Morphext({
				animation: "<?php echo esc_attr($settings['eael_fancy_text_transition_type'] ); ?>",
				separator: ",",
				speed: <?php echo esc_attr($settings['eael_fancy_text_delay'] ); ?>,
				complete: function () {
				        // Overrides default empty function
				    }
			});
		});
		</script>
	<?php endif; ?>
	
	<?php
	
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Fancy_Text() );
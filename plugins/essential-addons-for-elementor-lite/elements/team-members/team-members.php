<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Team_Member extends Widget_Base {

	public function get_name() {
		return 'eael-team-member';
	}

	public function get_title() {
		return esc_html__( 'EA Team Member', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	
	protected function _register_controls() {

		
  		$this->start_controls_section(
  			'eael_section_team_member_image',
  			[
  				'label' => esc_html__( 'Team Member Image', 'essential-addons-elementor' )
  			]
  		);
		

		$this->add_control(
			'eael_team_member_image',
			[
				'label' => __( 'Team Member Avatar', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'condition' => [
					'eael_team_member_image[url]!' => '',
				],
			]
		);


		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_team_member_content',
  			[
  				'label' => esc_html__( 'Team Member Content', 'essential-addons-elementor' )
  			]
  		);


		$this->add_control(
			'eael_team_member_name',
			[
				'label' => esc_html__( 'Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'essential-addons-elementor' ),
			]
		);
		
		$this->add_control(
			'eael_team_member_job_title',
			[
				'label' => esc_html__( 'Job Position', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Software Engineer', 'essential-addons-elementor' ),
			]
		);
		
		$this->add_control(
			'eael_team_member_description',
			[
				'label' => esc_html__( 'Description', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'essential-addons-elementor' ),
			]
		);
		

		$this->end_controls_section();


  		$this->start_controls_section(
  			'eael_section_team_member_social_profiles',
  			[
  				'label' => esc_html__( 'Social Profiles', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_team_member_enable_social_profiles',
			[
				'label' => esc_html__( 'Display Social Profiles?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		
		
		$this->add_control(
			'eael_team_member_social_profile_links',
			[
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'eael_team_member_enable_social_profiles!' => '',
				],
				'default' => [
					[
						'social' => 'fa fa-facebook',
					],
					[
						'social' => 'fa fa-twitter',
					],
					[
						'social' => 'fa fa-google-plus',
					],
					[
						'social' => 'fa fa-linkedin',
					],
				],
				'fields' => [
					[
						'name' => 'social',
						'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::ICON,
						'label_block' => true,
						'default' => 'fa fa-wordpress',
						'include' => [
							'fa fa-apple',
							'fa fa-behance',
							'fa fa-bitbucket',
							'fa fa-codepen',
							'fa fa-delicious',
							'fa fa-digg',
							'fa fa-dribbble',
							'fa fa-envelope',
							'fa fa-facebook',
							'fa fa-flickr',
							'fa fa-foursquare',
							'fa fa-github',
							'fa fa-google-plus',
							'fa fa-houzz',
							'fa fa-instagram',
							'fa fa-jsfiddle',
							'fa fa-linkedin',
							'fa fa-medium',
							'fa fa-pinterest',
							'fa fa-product-hunt',
							'fa fa-reddit',
							'fa fa-shopping-cart',
							'fa fa-slideshare',
							'fa fa-snapchat',
							'fa fa-soundcloud',
							'fa fa-spotify',
							'fa fa-stack-overflow',
							'fa fa-tripadvisor',
							'fa fa-tumblr',
							'fa fa-twitch',
							'fa fa-twitter',
							'fa fa-vimeo',
							'fa fa-vk',
							'fa fa-whatsapp',
							'fa fa-wordpress',
							'fa fa-xing',
							'fa fa-yelp',
							'fa fa-youtube',
						],
					],
					[
						'name' => 'link',
						'label' => esc_html__( 'Link', 'essential-addons-elementor' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'default' => [
							'url' => '',
							'is_external' => 'true',
						],
						'placeholder' => esc_html__( 'Place URL here', 'essential-addons-elementor' ),
					],
				],
				'title_field' => '<i class="{{ social }}"></i> {{{ social.replace( \'fa fa-\', \'\' ).replace( \'-\', \' \' ).replace( /\b\w/g, function( letter ){ return letter.toUpperCase() } ) }}}',
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
			'eael_section_team_members_styles_general',
			[
				'label' => esc_html__( 'Team Member Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_team_members_preset',
			[
				'label' => esc_html__( 'Style Preset', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-team-members-simple',
				'options' => [
					'eael-team-members-simple' 		=> esc_html__( 'Simple Style', 		'essential-addons-elementor' ),
					'eael-team-members-overlay' 	=> esc_html__( 'Overlay Style', 	'essential-addons-elementor' ),
					'eael-team-members-pro-style-3' 	=> esc_html__( 'Centered Style', 	'essential-addons-elementor' ),
					'eael-team-members-pro-style-4' 		=> esc_html__( 'Circle Style', 	'essential-addons-elementor' ),
					'eael-team-members-pro-style-5' => esc_html__( 'Social on Bottom', 	'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'eael_team_members_preset_pro_alert',
			[
				'label' => esc_html__( 'Only available in pro version!', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'eael_team_members_preset' => ['eael-team-members-pro-style-3', 'eael-team-members-pro-style-4', 'eael-team-members-pro-style-5'],
				]
			]
		);

		$this->add_control(
			'eael_team_members_overlay_background',
			[
				'label' => esc_html__( 'Overlay Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .eael-team-members-overlay .eael-team-content' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_team_members_preset' => 'eael-team-members-overlay',
				],
			]
		);

		$this->add_control(
			'eael_team_members_background',
			[
				'label' => esc_html__( 'Content Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_alignment',
			[
				'label' => esc_html__( 'Set Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __( 'Default', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'centered' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'eael-team-align-default',
				'prefix_class' => 'eael-team-align-',
			]
		);

		$this->add_responsive_control(
			'eael_team_members_padding',
			[
				'label' => esc_html__( 'Content Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_team_members_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-team-item',
			]
		);

		$this->add_control(
			'eael_team_members_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-team-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_team_members_image_styles',
			[
				'label' => esc_html__( 'Team Member Image Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);		

		$this->add_responsive_control(
			'eael_team_members_image_width',
			[
				'label' => esc_html__( 'Image Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'eael_team_members_image_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_team_members_image_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_team_members_image_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-team-item figure img',
			]
		);

		$this->add_control(
			'eael_team_members_image_rounded',
			[
				'label' => esc_html__( 'Rounded Avatar?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'team-avatar-rounded',
				'default' => '',
			]
		);


		$this->add_control(
			'eael_team_members_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
				'condition' => [
					'eael_team_members_image_rounded!' => 'team-avatar-rounded',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_team_members_typography',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_team_members_name_heading',
			[
				'label' => __( 'Member Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_team_members_name_color',
			[
				'label' => esc_html__( 'Member Name Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-member-name' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_name_typography',
				'selector' => '{{WRAPPER}} .eael-team-item .eael-team-member-name',
			]
		);

		$this->add_control(
			'eael_team_members_position_heading',
			[
				'label' => __( 'Member Job Position', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_team_members_position_color',
			[
				'label' => esc_html__( 'Job Position Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-member-position' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_position_typography',
				'selector' => '{{WRAPPER}} .eael-team-item .eael-team-member-position',
			]
		);

		$this->add_control(
			'eael_team_members_description_heading',
			[
				'label' => __( 'Member Description', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_team_members_description_color',
			[
				'label' => esc_html__( 'Description Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content .eael-team-text' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_description_typography',
				'selector' => '{{WRAPPER}} .eael-team-item .eael-team-content .eael-team-text',
			]
		);


		$this->end_controls_section();

		
		$this->start_controls_section(
			'eael_section_team_members_social_profiles_styles',
			[
				'label' => esc_html__( 'Social Profiles Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);		


		$this->add_control(
			'eael_team_members_social_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_team_members_social_profiles_padding',
			[
				'label' => esc_html__( 'Social Profiles Spacing', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-content > .eael-team-member-social-profiles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs( 'eael_team_members_social_icons_style_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_team_members_social_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f1ba63',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'eael_team_members_social_icon_background',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_team_members_social_icon_border',
				'selector' => '{{WRAPPER}} .eael-team-member-social-link > a',
			]
		);
		
		$this->add_control(
			'eael_team_members_social_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_social_icon_typography',
				'selector' => '{{WRAPPER}} .eael-team-member-social-link > a',
			]
		);

		
		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_team_members_social_icon_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_team_members_social_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ad8647',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_social_icon_hover_background',
			[
				'label' => esc_html__( 'Hover Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_social_icon_hover_border_color',
			[
				'label' => esc_html__( 'Hover Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();


		$this->end_controls_section();


	}


	protected function render( ) {
		
      $settings = $this->get_settings();
      $team_member_image = $this->get_settings( 'eael_team_member_image' );
	  $team_member_image_url = Group_Control_Image_Size::get_attachment_image_src( $team_member_image['id'], 'thumbnail', $settings );	
	  if( empty( $team_member_image_url ) ) : $team_member_image_url = $team_member_image['url']; else: $team_member_image_url = $team_member_image_url; endif;
	  $team_member_classes = $this->get_settings('eael_team_members_preset') . " " . $this->get_settings('eael_team_members_image_rounded');
	
	?>


	<div id="eael-team-member-<?php echo esc_attr($this->get_id()); ?>" class="eael-team-item <?php echo $team_member_classes; ?>">
		<div class="eael-team-item-inner">
			<div class="eael-team-image">
				<figure>
					<img src="<?php echo esc_url($team_member_image_url);?>" alt="<?php echo $settings['eael_team_member_name'];?>">
				</figure>
			</div>

			<div class="eael-team-content">
				<h3 class="eael-team-member-name"><?php echo $settings['eael_team_member_name']; ?></h3>
				<h4 class="eael-team-member-position"><?php echo $settings['eael_team_member_job_title']; ?></h4>

				<?php if ( ! empty( $settings['eael_team_member_enable_social_profiles'] ) ): ?>
				<ul class="eael-team-member-social-profiles">
					<?php foreach ( $settings['eael_team_member_social_profile_links'] as $item ) : ?>
						<?php if ( ! empty( $item['social'] ) ) : ?>
							<?php $target = $item['link']['is_external'] ? ' target="_blank"' : ''; ?>
							<li class="eael-team-member-social-link">
								<a href="<?php echo esc_attr( $item['link']['url'] ); ?>"<?php echo $target; ?>><i class="<?php echo esc_attr($item['social'] ); ?>"></i></a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<p class="eael-team-text"><?php echo $settings['eael_team_member_description']; ?></p>
			</div>
		</div>
	</div>

	
	<?php
	
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Team_Member() );
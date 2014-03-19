<?php
/*
Section: SelectMe
Author: TourKick (Clifford P)
Author URI: http://tourkick.com/?utm_source=pagelines&utm_medium=section&utm_content=authoruri&utm_campaign=mini_select_section
Description: A select box for PageLines DMS. I like to use it for displaying the short link to the post, but maybe you'll prefer displaying the full link. Options include showing shortlink, showing permalink, and customizing the leading text. Global and local DMS options. Includes animation and other customizations.
Demo: http://www.pagelinestheme.com/selectme-section?utm_source=pagelines&utm_medium=section&utm_content=demolink&utm_campaign=mini_select_section
Version: 1.1
Class Name: DMSSelectMe
Filter: component
Cloning: true
v3: true
Loading: active
*/

class DMSSelectMe extends PageLinesSection {

	function section_persistent() {
		add_filter('pl_settings_array', array(&$this, 'options'));
		//add_filter( 'pless_vars', array($this,'selectme_less_vars'));
	}

/*
	function selectme_less_vars($less){

		if( function_exists('pl_has_editor') && pl_has_editor() ){
			global $post;
			$postid = $post->ID;
		} else { return; };

		$selectmeborder = $this->opt('selectme_color_border') ? pl_hashify($this->opt('selectme_content')) : '@pl-link';
		$selectmeshadow = $this->opt('selectme_color_border') ? pl_hashify($this->opt('selectme_content')) : '@pl-link';


		$less['selectmeborder'] = $selectmeborder;
		$less['selectmeshadow'] = $selectmeshadow;

		return $less;
	}
*/


    function options( $settings ){

        $settings[ $this->id ] = array(
                'name'  => $this->name,
                'icon'  => 'icon-terminal', // or icon-ellipsis-horizontal
                'pos'   => 3,
                'opts'  => $this->global_opts()
        );

        return $settings;
    }

    function global_opts(){

        $global_opts = array(
			array(
				'type'		=> 'multi',
				'title'		=> 'SelectMe Leading Text Config',
				'key'		=> 'selectme_leading_config',
				//'col'		=> 1,
				'opts'		=> array(
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_leading_text',
						'help' 	=> __( 'Default: "Shortlink:" or "Permalink:", depending on the content option you pick below.<br/> You may prefer "Link:" or "Like it? Share it:", etc.', $this->id ),
						'label' 		=> __( 'SelectMe Leading Text', $this->id ),
					),
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_leading_class',
						'help' 	=> __( 'Insert a CSS Class (separate multiple with a space) just to the SelectMe Leading Text area. "selectmeleading" will always be included.', $this->id ),
						'label' 		=> __( 'SelectMe Leading Text Class (Optional)', $this->id ),
					),
				)
			),
			array(
				'type'		=> 'multi',
				'title'		=> 'SelectMe Content Config',
				'key'		=> 'selectme_content_config',
				//'col'		=> 1,
				'opts'		=> array(
					array(
						'type' 			=> 'select',
						'key'			=> 'selectme_content',
						'label' 		=> 'SelectMe Content',
						//'default'		=> 'shortlink',
						'help' 	=> __( 'If you pick the shortlink option, make sure to have shortlinks already created. Examples include: yoursite.com?p=1234 (every site already has this), wp.me (Jetpack), bit.ly, etc. Shortlink is default, but Permalink is used if shortlink does not exist, which it always should.', $this->id ),
						'opts'			=> array(
							'shortlink'	=> array('name' => 'Shortlink (Default)'),
							'permalink'	=> array('name' => 'Permalink'),
						)
					),
/*
					array(
						'key'		=> 'selectme_color_border',
						'type'		=> 'color',
						'default'	=> '',
						'label' 	=> __('Select Box Border Color', $this->id)
					),
					array(
						'key'		=> 'selectme_color_shadow',
						'type'		=> 'color',
						'default'	=> '',
						'label' 	=> __('Select Box Shadow Color', $this->id)
					),
*/
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_content_class',
						'help' 	=> __( 'Insert a CSS Class (separate multiple with a space) just to the SelectMe content area. "selectmecontent" will always be included.', $this->id ),
						'label' 		=> __( 'SelectMe Content Class (Optional)', $this->id ),
					),
				)
			),
			array(
				'type'		=> 'multi',
				'title'		=> 'SelectMe Config',
				'key'		=> 'selectme_config',
				//'col'		=> 1,
				'opts'		=> array(
					array(
						'type' 			=> 'select',
						'key'			=> 'selectme_align',
						'label' 		=> 'Alignment / Float',
						//'default'		=> 'left',
						'opts'			=> array(
							// https://github.com/pagelines/DMS/blob/1.1/less/pl-objects.less#L449
							'left'			=> array('name' => 'Float Left'),
							'right'			=> array('name' => 'Float Right'),
							'center'		=> array('name' => 'Center'),
							'textjustify'	=> array('name' => 'Justify'),
						)
					),
					array(
						'type' 			=> 'select_animation',
						'key'			=> 'selectme_animation',
						'label' 		=> __( 'Viewport Animation', $this->id ),
						//'default'		=> 'no-anim',
						'help' 			=> __( 'Optionally animate the appearance of this section on view.', $this->id ),
					),
					array(
						'key'			=> 'selectme_pad',
						'type' 			=> 'text',
						'label' 	=> __( 'Padding (use CSS Shorthand)', $this->id ),
						'help'		=> __( 'This option uses CSS padding shorthand. For example, use "15px 30px" for 15px padding top/bottom, and 30 left/right.', $this->id ),
					),
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_global_class',
						'help' 	=> __( 'Insert a CSS Class (separate multiple with a space) for the entire title and subtitle div. A global alternative to using the Standard Options -> Styling Classes to add custom classes to every SelectMe section area individually, although that still works too and is applied at a higher-level (i.e. before this div class).', $this->id ),
						'label' 		=> __( 'SelectMe Global Class (Optional)', $this->id ),
					),
				)
			),
        );

        return array_merge($global_opts);
    }


	function section_opts(){
		$opts = array(
			array(
				'type'		=> 'multi',
				'title'		=> 'SelectMe Leading Text Config',
				'key'		=> 'selectme_leading_config',
				//'col'		=> 1,
				'opts'		=> array(
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_leading_text',
						'help' 	=> __( 'Default: "Shortlink:" or "Permalink:", depending on the content option you pick below.<br/> You may prefer "Link:" or "Like it? Share it:", etc.', $this->id ),
						'label' 		=> __( 'SelectMe Leading Text', $this->id ),
					),
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_leading_class',
						'help' 	=> __( 'Insert a CSS Class (separate multiple with a space) just to the SelectMe Leading Text area. "selectmeleading" will always be included.', $this->id ),
						'label' 		=> __( 'SelectMe Leading Text Class (Optional)', $this->id ),
					),
				)
			),
			array(
				'type'		=> 'multi',
				'title'		=> 'SelectMe Content Config',
				'key'		=> 'selectme_content_config',
				//'col'		=> 1,
				'opts'		=> array(
					array(
						'type' 			=> 'select',
						'key'			=> 'selectme_content',
						'label' 		=> 'SelectMe Content',
						//'default'		=> 'shortlink',
						'help' 	=> __( 'If you pick the shortlink option, make sure to have shortlinks already created. Examples include: yoursite.com?p=1234 (every site already has this), wp.me (Jetpack), bit.ly, etc. Shortlink is default, but Permalink is used if shortlink does not exist, which it always should.', $this->id ),
						'opts'			=> array(
							'shortlink'	=> array('name' => 'Shortlink'),
							'permalink'	=> array('name' => 'Permalink'),
						)
					),
					array(
						'type' 			=> 'text',
						'key'			=> 'selectme_content_class',
						'help' 	=> __( 'Insert a CSS Class (separate multiple with a space) just to the SelectMe content area. "selectmecontent" will always be included.', $this->id ),
						'label' 		=> __( 'SelectMe Content Class (Optional)', $this->id ),
					),
				)
			),
			array(
				'type'		=> 'multi',
				'title'		=> 'SelectMe Config',
				'key'		=> 'selectme_config',
				//'col'		=> 1,
				'opts'		=> array(
					array(
						'type' 			=> 'select',
						'key'			=> 'selectme_align',
						'label' 		=> 'Alignment / Float',
						//'default'		=> 'left',
						'opts'			=> array(
							'left'			=> array('name' => 'Float Left'),
							'right'			=> array('name' => 'Float Right'),
							'center'		=> array('name' => 'Center'),
							'textjustify'	=> array('name' => 'Justify'),
						)
					),
					array(
						'type' 			=> 'select_animation',
						'key'			=> 'selectme_animation',
						'label' 		=> __( 'Viewport Animation', $this->id ),
						//'default'		=> 'no-anim',
						'help' 			=> __( 'Optionally animate the appearance of this section on view.', $this->id ),
					),
					array(
						'key'			=> 'selectme_pad',
						'type' 			=> 'text',
						'label' 	=> __( 'Padding (use CSS Shorthand)', $this->id ),
						'help'		=> __( 'This option uses CSS padding shorthand. For example, use "15px 30px" for 15px padding top/bottom, and 30 left/right.', $this->id ),
					),
				)
			),
        );

		return $opts;

	}

	function section_template() {

		// Check for DMS
		if( function_exists('pl_has_editor') && pl_has_editor() ){
			global $post;
			$postid = $post->ID;
		} else { return; };

		$selectme = pl_setting('selectme_content') ? pl_setting('selectme_content') : 'shortlink';
			$selectme = $this->opt('selectme_content') ? $this->opt('selectme_content') : $selectme;
		$shortlink = wp_get_shortlink($postid);
		$permalink = get_permalink($postid);

		if(
			$selectme == 'shortlink'
			&& !function_exists('wp_get_shortlink')
		) {
			$selectme = 'permalink';
		} elseif (
			$selectme == 'shortlink'
			&& !empty($shortlink)
		) {
			$leadingtext = pl_setting('selectme_leading_text') ? pl_setting('selectme_leading_text') : 'Shortlink:';
				$leadingtext = $this->opt('selectme_leading_text') ? $this->opt('selectme_leading_text') : $leadingtext;
			$content = $shortlink;
		} elseif (
			$selectme == 'permalink'
			&& function_exists('get_permalink')
			&& !empty($permalink)
		) {
			$leadingtext = pl_setting('selectme_leading_text') ? pl_setting('selectme_leading_text') : 'Permalink:';
				$leadingtext = $this->opt('selectme_leading_text') ? $this->opt('selectme_leading_text') : $leadingtext;
			$content = $permalink;
		} else { return; };


		$selectme_leading_class = pl_setting('selectme_leading_class') ? pl_setting('selectme_leading_class') : '';
			$selectme_leading_class = $this->opt('selectme_leading_class') ? $this->opt('selectme_leading_class') : $selectme_leading_class;
		$selectme_content_class = pl_setting('selectme_content_class') ? pl_setting('selectme_content_class') : '';
			$selectme_content_class = $this->opt('selectme_content_class') ? $this->opt('selectme_content_class') : $selectme_content_class;

		// Global div class
		$globalclass = pl_setting('selectme_global_class') ? pl_setting('selectme_global_class') : '';
			//there is no local option

		// Section Style Options
		$align = pl_setting('selectme_align') ? pl_setting('selectme_align') : '';
			$align = $this->opt('selectme_align') ? $this->opt('selectme_align') : $align;

		$animationclass = pl_setting('selectme_animation') ? pl_setting('selectme_animation') : '';
			$animationclass = $this->opt('selectme_animation') ? $this->opt('selectme_animation') : $animationclass;

		$pad = pl_setting('selectme_pad') ? sprintf('padding: %s;', pl_setting('selectme_pad')) : '';
			$pad = $this->opt('selectme_pad') ? sprintf('padding: %s;', $this->opt('selectme_pad')) : $pad;

		// SECTION OUTPUT
		printf('<div class="fix selectme-wrap pl-animation %s %s %s" style="%s">', $globalclass, $align, $animationclass, $pad); //"fix" is from DMS Core -- https://github.com/pagelines/DMS/blob/Dev/includes/class.posts.php#L293

		printf('<span class="selectmeleading %s">%s</span> <span class="selectmecontent %s"><input type="text" value="%s" onclick="this.focus(); this.select();" /></span>', $selectme_leading_class, $leadingtext, $selectme_content_class, $content);

		echo '</div>';

	}
}
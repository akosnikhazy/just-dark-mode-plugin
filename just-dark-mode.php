<?php

/*
Just Dark Mode: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Just Dark Mode is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Just Dark Mode.
*/

/*
Plugin Name: Just Dark Mode
Description: Just a very simple dark mode switch, that repsects privacy and doesn't care to sell a service for you.
Version: 1.0
Text Domain: just-dark-mode
Author: Ákos Nikházy
License: GPL2
*/

if (!defined('ABSPATH')) exit;


add_action('wp_footer',  'dark_mode_switch');
add_action('admin_menu', 'just_dark_mode_menu');
add_action('admin_enqueue_scripts', 'just_dark_mode_admin_css_js');

function dark_mode_switch()
{
	$where 		= get_option('just_dark_mode_where','bottom-right');
	$distancetb = get_option('just_dark_mode_distancetb','10');
	$distancelr = get_option('just_dark_mode_distancelr','10');
	$size 		= get_option('just_dark_mode_size','50');
	$type 		= get_option('just_dark_mode_type','1');
	$domain 	= preg_replace('#^https?://#i', '',home_url());
	
	switch($where)
	{
		case 'top-left':
			$tb = 'top';
			$lr = 'left';
			break;
		case 'top-right':
			$tb = 'top';
			$lr = 'right';
			break;
		case 'bottom-left':
			$tb = 'bottom';
			$lr = 'left';
			break;
		case 'bottom-right':
			$tb = 'bottom';
			$lr = 'right';
			break;
		default:
			$tb = 'bottom';
			$lr = 'right';
	}
	
	?>
	<style>
		#JDMSwitch,#JDMElegantSwitch{
			display: flex;                
			justify-content: center;
			align-items: center;
			width: <?php echo esc_html($size); ?>px;
			height: <?php echo esc_html($size); ?>px;
			font-size: <?php echo esc_html($size); ?>px;
			text-align: center;
			position: fixed;
			<?php echo esc_html($lr); ?>: <?php echo esc_html($distancelr); ?>px;
			<?php echo  esc_html($tb); ?>: <?php echo esc_html($distancetb); ?>px;
			cursor:pointer;
			
		}
		
		#JDMSwitch:active{
			filter: brightness(0.8);
		}
		
        .JDMSwitchdark::before {
            content: "🌙";
        }
		.JDMSwitchlight::before {
            content: "🌞";
        }
	
		

		.JDMElegantSwitch.JDMSwitchdark{
			transform: scaleX(-1);
		}

		html.JDMdark{
			filter:invert(1);
		}
		
		html.JDMdark img, 
		html.JDMdark video, 
		html.JDMdark iframe,
		html.JDMdark #JDMSwitch{
			filter:invert(1);
		}
    </style>
	<?php
	
	switch($type){
		case 1:
		    ?><div id="JDMSwitch" class="JDMSwitchlight" title="dark and light mode switch"></div><?php 
			break;
		case 2:
		    ?><svg id="JDMSwitch" class="JDMElegantSwitch" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  title="dark and light mode switch"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect><circle cx="8" cy="12" r="4"></circle></svg><?php 
			break;
		
	}
    ?>
    <script>
		function getOneYearFromNow() 
		{
			const futureDate = new Date();
			futureDate.setFullYear(futureDate.getFullYear() + 1);
			futureDate.setHours(23, 59, 59, 0);
			return futureDate.toUTCString();
		}
		
		function cookieExists(cookieName) 
		{
			const cookies = document.cookie;
			const regex = new RegExp('(^|; )' + encodeURIComponent(cookieName) + '=([^;]*)');
			return regex.test(cookies);
		}
		
		function getCookie(cookieName) 
		{
			const cookies = document.cookie;
			const regex = new RegExp('(^|; )' + encodeURIComponent(cookieName) + '=([^;]*)');
			const match = cookies.match(regex);

			return match ? decodeURIComponent(match[2]) : null;
		}

        document.addEventListener('DOMContentLoaded', function() {
			let JDMnow = 'JDMlight';
			let JDMSwitch = document.getElementById('JDMSwitch');
			let HTMLtag = document.getElementsByTagName('html');
			
			const expirationDate = getOneYearFromNow();
			
			if(!cookieExists('JDMSwitch'))
			{
            	document.cookie = `JDMSwitch=JDMlight; expires=${expirationDate}; path=/; domain=<?php echo esc_html($domain); ?>; secure`;
			} 
			else 
			{
				JDMnow = getCookie('JDMSwitch');
				if(JDMnow == 'JDMlight')
				{
					JDMSwitch.classList.add('JDMSwitchlight');
				}
				else
				{
					JDMSwitch.classList.add('JDMSwitchdark');
	 				JDMSwitch.classList.remove('JDMSwitchlight');
					JDMSwitch.setAttribute('stroke', '#eee');
					HTMLtag[0].className = 'JDMdark';
				}
			}
			JDMSwitch.addEventListener('click', function()
			{
				JDMnow = getCookie('JDMSwitch');
				if(JDMnow == 'JDMlight')
				{
					document.cookie = `JDMSwitch=JDMdark; expires=${expirationDate}; path=/; domain=<?php echo esc_html($domain); ?>; secure`;
					JDMSwitch.classList.add('JDMSwitchdark');
					JDMSwitch.classList.remove('JDMSwitchlight');
					JDMSwitch.setAttribute('stroke', '#eee');
					HTMLtag[0].classList.add('JDMdark');
				} 
				else 
				{
					document.cookie = `JDMSwitch=JDMlight; expires=${expirationDate}; path=/; domain=<?php echo esc_html($domain); ?>; secure`;
					JDMSwitch.classList.remove('JDMSwitchdark');
					JDMSwitch.classList.add('JDMSwitchlight');
					JDMSwitch.setAttribute('stroke', '#333');
					HTMLtag[0].classList.remove('JDMdark');
				}
			});

        });
    </script>
	
	<?php
}

function just_dark_mode_menu() {
	add_options_page(
        'Just Dark Mode', 
        'Just Dark Mode Settings', 
        'manage_options', 
        'just-dark-mode-settings',
        'just_dark_mode_settings_page'
    );
}

function just_dark_mode_settings_page() {
  
    if (!current_user_can('manage_options'))return;
    
    if (isset($_POST['submit']))
	{ // save changes
	
		// Verify the nonce
		if (!isset($_POST['post_grid_nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['post_grid_nonce'])), 'post_grid_settings_action')) {
			// Nonce verification failed
			wp_die('Nonce verification failed. Please try again.');
		}
		
		// prevent false data
		$legitPosition = array('top-left','top-right','bottom-left','bottom-right');
		$legitType = array('1','2');
		
		$where = 'bottom-right';
		$distancetb = '10';
		$distancelr = '10';
		$size 		= '50';
		$type 		= '1';
		
		if(isset($_POST['just_dark_mode_type']))
			$type		= sanitize_text_field(wp_unslash($_POST['just_dark_mode_type']));
		
		if(isset($_POST['just_dark_mode_where']))
			$where		= sanitize_text_field(wp_unslash($_POST['just_dark_mode_where']));
		
		if(isset($_POST['just_dark_mode_tb']))
			$distancetb = sanitize_text_field(wp_unslash($_POST['just_dark_mode_tb']));
		
		if(isset($_POST['just_dark_mode_lr']))
			$distancelr = sanitize_text_field(wp_unslash($_POST['just_dark_mode_lr']));
		
		if(isset($_POST['just_dark_mode_size']))
			$size	 	= sanitize_text_field(wp_unslash($_POST['just_dark_mode_size']));
		
		if(in_array($type,$legitType))
		{
			
			update_option('just_dark_mode_type',$type);
			
		}
		
		if(in_array($where,$legitPosition))
		{
			 update_option('just_dark_mode_where', $where);
		}
		
		if(is_numeric($distancetb))
		{
			update_option('just_dark_mode_distancetb', $distancetb);
		}
		
		if(is_numeric($distancelr))
		{
			update_option('just_dark_mode_distancelr', $distancelr);
		}
		
		if(is_numeric($size))
		{
			update_option('just_dark_mode_size', $size);
		}
		
		
	}
	
	$where 		= get_option('just_dark_mode_where','bottom-right');
	$distancetb = get_option('just_dark_mode_distancetb','10');
	$distancelr = get_option('just_dark_mode_distancelr','10');
	$size 		= get_option('just_dark_mode_size','50');
	$type 		= get_option('just_dark_mode_type','1');
	
	/* Hidden nonce field HTML */
	$html_escaped = '<div class="wrap JDMwrap">
				<h1>Just Dark Mode Settings</h1>
				
				<form method="post" action="">
					'. wp_nonce_field('post_grid_settings_action', 'post_grid_nonce');
	
	/* Button type settings HTML */
	$html_escaped .= '<p>Select the type of button you want on your page.</p>
	<label>🌞↔🌙 <input type="radio" name="just_dark_mode_type" value="1" ' . checked('1',$type,false,false) . '></label><br><br>
	<label><svg width="25px" height="20px" viewBox="0 0 25 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  title="dark and light mode switch"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect><circle cx="8" cy="12" r="4"></circle></svg> <input type="radio" name="just_dark_mode_type" value="2" ' . checked('2',$type,false,false) . '></label>
	<hr>';
	
	/* Button corner position settings HTML */
	$html_escaped .= '<p>Where should the button show up?</p>
					   <label for="tl" class="JDMAdminLabel">Top-Left</label><input id="tl" type="radio" name="just_dark_mode_where" value="top-left" ' . checked('top-left',$where,false,false) . '>
					   <label for="tr" class="JDMAdminLabel">Top-Right</label><input  id="tr" type="radio" name="just_dark_mode_where" value="top-right" ' . checked('top-right',$where,false,false) . '><br>
					   <label for="bl" class="JDMAdminLabel">Bottom-Left</label><input  id="bl" type="radio" name="just_dark_mode_where" value="bottom-left" ' . checked('bottom-left', $where,false,false) . '>
					   <label for="br" class="JDMAdminLabel">Bottom-Right</label><input  id="br" type="radio" name="just_dark_mode_where" value="bottom-right" ' . checked('bottom-right',$where,false,false) . '>';
	
	/* Button distance from wall settings HTML */
	$html_escaped .= '<hr><p>These are the distances the button will show up from the sides. For example if you choose bottom right position the first input tells how far it is from the bottom and the second tells from the right side of the screen.</p>
					   <label for="distancetb" class="JDMAdtblr" id="tblabel">Distance from top or bottom</label> <input type="text" id="distancetb" name="just_dark_mode_tb" value="' . esc_attr($distancetb) . '">px<br><br>
					   <label for="distancelr" class="JDMAdtblr" id="lrlabel">Distance from left or right</label> <input type="text" id="distancelr" name="just_dark_mode_lr" value="' . esc_attr($distancelr) . '">px';
	
	/* Button size settings HTML */
	$html_escaped .= '<hr>
					   <p>The size of the button.</p>
					   <label>Size: <input type="text" id="size" name="just_dark_mode_size" value="' . esc_attr($size) . '">px</label>';
   
	/* Submit button HTML */
	$html_escaped .= '<br><br>
				<input type="submit" name="submit" class="button button-primary" value="Save Changes"></form>';
	
	 /* Plus info HTML */
	$html_escaped .= '<div id="JDM-plugin-info">
				
				<h2>Why?</h2>
				<p>I just wanted a dark mode plugin without bloat. This is the closest I got with it.</p>
				
				<h3>Legal</h3>
				<p>This plugin created by Ákos Nikházy. It is <a href="https://github.com/akosnikhazy/just-dark-mode-plugin" target="_blank">free and open source</a>. Do whatever.</p>
			</div>
			</div>';
	
	
	
	$allowed_html = array(
					'div' => array(
						'class' => array(), 
						'id' => array(),
					),
					'svg' => array(
						'width' => array(),
						'height' => array(),
						'viewBox' => array(),
						'xmlns' => array(),
						'fill' => array(),
						'stroke' => array(),
						'stroke-width' => array(),
						'stroke-linecap' => array(),
						'stroke-linejoin' => array(),
					),
					'rect' => array(
						'x' => array(),
						'y' => array(),
						'width' => array(),
						'height' => array(),
						'rx' => array(),
						'ry' => array(),
					),
					'circle' => array(
						'cx' => array(),
						'cy' => array(),
						'r' => array(),
					),
					'form' => array(
						'method' => array(),
						'action' => array()
					),
					'h1' => array(),
					'h2' => array(),
					'h3' => array(),
					'p' => array(), 
					'label' => array(
						'for' => array(),
						'class'=> array(),
						'id' => array(),
					), 
					'input' => array( 
						'type' => array(),
						'name' => array(),
						'value' => array(),
						'id' => array(),
						'class' =>array(),
						'checked' => array(),
					),
					'hr' => array(),
					'br' => array(), 
					'a' => array( 
						'href' => array(),
						'target' => array(),
					)
				);
	echo wp_kses($html_escaped,$allowed_html);
					
}

function just_dark_mode_admin_css_js($hook) 
{

	if ($hook != 'settings_page_just-dark-mode-settings') return;
	
	wp_enqueue_style('just-dark-mode-style', plugin_dir_url(__FILE__) . 'css/just-dark-mode-admin.css', array(), time());
	wp_enqueue_script('just-dark-mode-script', plugin_dir_url(__FILE__) . 'js/just-dark-mode-admin.js', array(), time(), true);
}
?>

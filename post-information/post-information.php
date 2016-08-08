<?php
/*
Plugin Name: Post Information
Plugin URI: http://www.siolon.com/2007/wordpress-post-information-plugin/
Description: A nice way to show/hide post information in an organized manner.
Version: 1.6.1
Author: Chris Poteet
Author URI: http://www.siolon.com/
*/

add_action('init', 'add_jquery');

function add_jquery() {
  wp_enqueue_script('jquery'); 
}

add_action('wp_head', 'postinfo_head');

function postinfo_head() {
 	echo "<link href=\"". plugins_url('post-information.css', __FILE__). "\" type=\"text/css\" rel=\"stylesheet\" media=\"screen\" />";
?>

<script type="text/javascript">
jQuery(document).ready(function($){
	$('.post-info').hide();
	$('.open-post-info').click(function() {
		var id = $(this).attr('id');
        $('.post-info-' + id).slideToggle("medium", function() {
            $(this).prev().toggleClass("toggled");
        }); 
		return false;
	});
}); 
</script>

<?php }
 
function postinfo() {
	global $post;
	$date = get_option('date_format');
	
	echo '<p class="open-post-info" id="'. $post->post_name .'">Click for Post Details</p>';
	echo '<div class="post-info post-info-'. $post->post_name .'">';
	echo '<ul>';
	echo '<li class="date">';
	echo the_time($date);
	echo '</li><li class="categories">';
	echo the_category(', ');
	echo '</li><li class="tags">';
	echo the_tags('',', ','');
	if (is_single()) {
		echo '</li><li class="comments"><a href="#comments">Comments Below</a>';
	}
	else {
		echo '</li><li class="comments">';
		echo comments_popup_link('No Comments', 'Comments (1)', 'Comments (%)');	
	}
	echo '</li><li class="author">';
	echo the_author_posts_link();
	echo '</li><li class="words">';
	echo wordcount();
	echo ' Words</li>';
	echo '</ul>';
	echo '</div>';
}

function wordcount() {
  $words = get_content_for_count('', 0, '');
    if ($words) {
      $post = strip_tags($words);
      $post = explode(' ', $post);
      $totalcount = count($post);
    } else {
      $totalcount = 0;
    } 
  echo $totalcount;
}

function get_content_for_count($more_link_text = null, $stripteaser = 0, $more_file = '') {
	global $id, $post, $more, $page, $pages, $multipage, $preview, $pagenow;

	$output = '';
	$hasTeaser = false;
	$file = $more_file;

	if ( $page > count($pages) ) // if the requested page doesn't exist
		$page = count($pages); // give them the highest numbered page that DOES exist

	$content = $pages[$page-1];
	if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
		$content = array($content);
	} else {
		$content = array($content);
	}
	if ( (false !== strpos($post->post_content, '<!--noteaser-->') && ((!$multipage) || ($page==1))) )
		$stripteaser = 1;
	$teaser = $content[0];
	if ( ($more) && ($stripteaser) && ($hasTeaser) )
		$teaser = '';
	$output .= $teaser;

	return $output;
}

?>
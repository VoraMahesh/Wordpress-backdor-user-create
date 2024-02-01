<?php

/**
 * shortcode for listing blog posts
 * Author: 
 */

add_shortcode('ajaxloadmoreblogdemo','ajaxloadmoreblogdemo');
function ajaxloadmoreblogdemo($atts, $content = null){
	ob_start();
	$atts = shortcode_atts(
        array(
			'post_type' => 'post',
			'initial_posts' => '3',
			'loadmore_posts' => '3',
			'order' => 'ASC',
		), $atts, $tag
    );
	$additonalArr = array();
	$additonalArr['appendBtn'] = true;
	$additonalArr["offset"] = 0; ?>
	<div class="dcsAllPostsWrapper"> 
		<input type="hidden" name="dcsPostType" value="<?=$atts['post_type']?>">
    	<input type="hidden" name="offset" value="0">
    	<input type="hidden" name="dcsloadMorePosts" value="<?=$atts['loadmore_posts']?>">
    	<div class="dcsDemoWrapper">
			<?php dcsGetPostsFtn($atts, $additonalArr); ?>
		</div>
		<div class="response-data" style="display: none;"></div>
	</div>
	<?php
    return ob_get_clean();
}



function dcsGetPostsFtn($atts, $additonalArr=array()){ 

   	$args = array(
	    'post_type' => $atts['post_type'],
	    'posts_per_page' => $atts['initial_posts'],
	    'offset' => $additonalArr["offset"],
	    'order' => 'ASC',
	);
	$the_query = new WP_Query( $args );
	$havePosts = true;
	if ( $the_query->have_posts() ) {
	    while ( $the_query->have_posts() ) {
	        $the_query->the_post(); ?>
	       		<div class="loadMoreRepeat">
	       			 
		  						<div class="article"> 
                      <!-- Article Image --> 
                       <a class="article_featured-image" href="<?php echo get_permalink(); ?>"><img class="blur-up lazyload" data-src="<?=get_field("single_blog_image")?>" src="<?=get_field("single_blog_image")?>" alt="<?=get_the_title()?>"></a> 
                      <h2 class="h3"><a href="<?php echo get_permalink(); ?>"><?=get_the_title()?></a></h2>
                      <ul class="publish-detail">                      
                          <li><i class="anm anm-user-al" aria-hidden="true"></i> <?=get_author_name()?></li>
                          <li><i class="icon anm anm-clock-r"></i> <time datetime="<?=get_the_date( 'F j, Y' );?>"><?=get_the_date( 'F j, Y' );?></time></li>
                          <li>
                              <ul class="inline-list">   
                                  <li><i class="icon anm anm-comments-l"></i> <a href="<?php echo get_permalink(); ?>"> <?=get_comments_number()?> comments</a></li>
                              </ul>
                          </li>
                      </ul>
                      <div class="rte"><?php the_excerpt(); ?></div>
                      <p><a href="<?php echo get_permalink(); ?>" class="btn btn-secondary btn--small">Read more <i class="fa fa-caret-right" aria-hidden="true"></i></a></p>
                  </div>
                    
                </div>
	        <?php
	    }
	} else {
	   $havePosts = false; 
	}
	wp_reset_postdata();
   	if($havePosts && $additonalArr['appendBtn'] ){ ?>
	   	<div class="btnLoadmoreWrapper loadmore-post">
	   		<a href="javascript:void(0);" class="btn btn-primary dcsLoadMorePostsbtn">Load More</a>
	  	</div>
	  	
	  	<!-- loader for ajax -->
	  	<div class="dcsLoaderImg" style="display: none;">
		    <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" style="
		    color: #ff7361;">
			    <path fill="#ff7361" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
			      <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
			  </path>
			</svg>
		</div>
		<div class="loadmore-post">
	  		<p class="noMorePostsFound btn" style="display: none;">No more Blog Post</p>
	  	</div>

    <?php
	}
}


//Ajax_callback
add_action("wp_ajax_dcsAjaxLoadMorePostsAjaxReq","dcsAjaxLoadMorePostsAjaxReq");
add_action("wp_ajax_nopriv_dcsAjaxLoadMorePostsAjaxReq","dcsAjaxLoadMorePostsAjaxReq");
function dcsAjaxLoadMorePostsAjaxReq(){
	extract($_POST);
	$additonalArr = array();
	$additonalArr['appendBtn'] = false;
	$additonalArr["offset"] = $offset;
	$atts["initial_posts"] = $dcsloadMorePosts;
	$atts["post_type"] = $postType;
	dcsGetPostsFtn($atts, $additonalArr);
	die();
}


// -------------- End Blog ajax listing -----------------
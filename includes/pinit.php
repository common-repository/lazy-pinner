<?php
include('auth.php');


// POST IMAGE
function get_default_post_image($size = 'thumbnail', $post_id = false){
    global $post, $id;
    $post_id = (int)$post_id;
    if (!$post_id) $post_id = $post->ID;
    $image = '';
    if(has_post_thumbnail($post_id)) {
        $image = get_the_post_thumbnail_src(get_the_post_thumbnail($post_id, $size));
    } else {
        $args = array(
            'post_parent' => $post_id
            , 'post_type' => 'attachment'
            , 'post_mime_type' => 'image'
            , 'post_status' => 'any'
            , 'numberposts' => 1
            , 'order' => 'ASC'
 
        );
        $attachments = get_children($args);
        foreach($attachments as $attachment) {
            //$attachment = array_shift($attachments);
            $image = wp_get_attachment_image_src($attachment->ID, $size);
		$image = $image[0];
				
        }
    }
    return $image;
}

function get_the_post_thumbnail_src($img)
{
  return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}


function post_to_pinterest($ch, $token, $cookiefile, $URL, $token) {
	global $wpdb;
	$desc = get_the_title();
	$link = get_permalink();
	$imgurl =  get_default_post_image($image);
	//$imgurl = get_the_post_thumbnail_src(get_the_post_thumbnail());
        $baseurl = get_site_url();
	$table = $wpdb->prefix . 'lazy_pinner_user';
	$result = $wpdb->get_row("SELECT board_id FROM $table", ARRAY_N);
	$board_id = $result[0];
        // Use the new URL
        $URL3 = "http://pinterest.com/resource/PinResource/create/";
        // Set up the json object, then urlencode it
	$ndata= '{"options":{"board_id":"'.$board_id.'","description":"'.$desc.'","link":"'.$link.'","image_url":"'.$imgurl.'","method":"scraped"},"context":{"app_version":"2132"}}';
        $ndata=urlencode($ndata);
        $nsourceurl='/pin/find/';
        $nsourceurl=urlencode($nsourceurl);
	$nmodpath='App()>ImagesFeedPage(resource=FindPinImagesResource(url='.$baseurl.'))>Grid()>Pinnable()>ShowModalButton(color=primary, submodule=[object Object], text=Pin it, tagName=button, primary_on_hover=true, class_name=repinSmall, has_icon=true, show_text=false, ga_category=pin_create)#Modal(module=PinCreate())';
        $nmodpath=urlencode($nmodpath);
        // Build up what we're about to post
        $posting = "data=".$ndata."&source_url=".$nsourceurl."&module_path=".$nmodpath;
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_URL, $URL3);
        curl_setopt($ch, CURLOPT_REFERER, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $posting);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        // Pass on the right CSRF Token
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-CSRFToken: $token"));
        $curlresponse = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpCode == 404){
		$post = get_the_ID();
                $log =  'Curl error : ' . $httpCode . 'Please check settings' . curl_error($ch); 
        	$table = $wpdb->prefix . 'lazy_pinner_logs';
		$wpdb->query("INSERT INTO $table (pinner_postid, pinner_comment) VALUES($post, '$log')"); 
		curl_close($ch);
        } else {
		$post = get_the_ID();
                $log = "Successful Pin";//echo "post message Sent Succesfully" ;
		$wpdb->show_errors();
        	$table = $wpdb->prefix . 'lazy_pinner_logs';
		$wpdb->query("INSERT INTO $table (pinner_postid, pinner_comment) VALUES($post, '$log')"); 
                curl_close($ch); // close cURL handler
        }
}


function post_pin() {
login();
}
add_action('publish_post','post_pin');


?>

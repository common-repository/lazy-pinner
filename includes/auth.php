<?php
/*
Copyright 2013  Lee Thompson (email : sr.mysql.dba@gmail.com) 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


//This is how we authenicate to Pinterest

function login() {
        // Create Cookie File
        $cookiefile = './pinitcookie.txt';
        if (file_exists($cookiefile)) { unlink ($cookiefile); }
        //User and pass to Pinterest
	global $wpdb;
	$wpdb->show_errors();
        $table = $wpdb->prefix . 'lazy_pinner_user';
        $result = $wpdb->get_row("SELECT * FROM $table", ARRAY_N);
        $lzemail = $result[0];
        $lzpass = $result[1];
        $lzpasskey = $result[2];
        $lzpassword = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $lzpasskey, base64_decode($lzpass), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));

        // initial login page which redirects to correct sign in page, sets some cookies

        $URL =  'https://pinterest.com/login';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.10 (maverick) Firefox/3.6.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);       
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        //Create the page for login
        $page = curl_exec($ch);
        preg_match('/^Set-Cookie:\s*([^;]*)/mi', $page, $m);
        //Get Pinterest CSRF token
        parse_str($m[1], $cookies);
        $token=$cookies['csrftoken'];
 
        // try to find the actual login form
        if (!preg_match('/<form id="AuthForm".*?<\/form>/is', $page, $form)) {
            die('Failed to find log in form!');
        }
 
        $form = $form[0];
 
        // find the action of the login form
        if (!preg_match('/action="([^"]+)"/i', $form, $action)) {
            die('Failed to find login form url');
        }
 
        $URL2 = "https://pinterest.com".$action[1]; // this is our new post url
 
        // find all hidden fields which we need to send with our login, this includes security tokens
        $count = preg_match_all('/<input type="hidden"\s*name="([^"]*)"\s*value="([^"]*)"/i', $form, $hiddenFields);
 
        $postFields = array();
 
        // turn the hidden fields into an array
        for ($i = 0; $i < $count; ++$i) {
            $postFields[$hiddenFields[1][$i]] = $hiddenFields[2][$i];
        }
 
        // add our login values
        $postFields['email']    = $lzemail;
        $postFields['csrfmiddlewaretoken']   = $token;
        $postFields['password'] = $lzpassword;
 
        $post = '';
 
        // convert to string, this won't work as an array, form will not accept multipart/form-data, only application/x-www-form-urlencoded
        foreach($postFields as $key => $value) {
            $post .= $key . '=' . urlencode($value) . '&';
        }
 
        $post = substr($post, 0, -1);
 
        curl_setopt($ch, CURLOPT_URL, $URL2);
        curl_setopt($ch, CURLOPT_REFERER, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $page = curl_exec($ch); // make request
 
        if ($page === FALSE) {
        var_dump(curl_getinfo($ch));
 
        }
        post_to_pinterest($ch, $token, $cookiefile, $URL, $token);
}
?>

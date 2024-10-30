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

function draw_form(){
	        if(function_exists( 'mcrypt_module_open' ) && !ini_get('safe_mode') && !ini_get('open_basedir') ){
		global $wpdb;
        	$wpdb->show_errors();
        	$table = $wpdb->prefix . 'lazy_pinner_user';	
		$result = $wpdb->get_row("SELECT * FROM $table", ARRAY_N);
		$lzemail = $result[0];
		$lzpass = $result[1];
		$lzpasskey = $result[2];
		//$lzpassvar = base64_decode($lzpass);
		$lzpassvar = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $lzpasskey, base64_decode($lzpass), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		$lzboard = $result[3];
		$lzboarduser = $result[4];
	}
	else {
                echo "<div class=\"error\">Your server is not configured to use this plugin. </div>";
		Main_error();
	}
?>
<style>
body{
font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
font-size:12px;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}
/* ----------- My Form ----------- */
.myform{
margin-top:5px;
width:400px;
padding:14px;
}
/* ----------- lzstyle ----------- */
#lzstyle{
border:solid 2px #b7ddf2;
background:#ebf4fb;
}
#lzstyle h1 {
font-size:14px;
font-weight:bold;
margin-bottom:8px;
}
#lzstyle p{
font-size:11px;
color:#666666;
margin-bottom:20px;
border-bottom:solid 1px #b7ddf2;
padding-bottom:10px;
}
#lzstyle label{
display:block;
font-weight:bold;
text-align:right;
width:140px;
float:left;
}
#lzstyle .small{
color:#666666;
display:block;
font-size:11px;
font-weight:normal;
text-align:right;
width:140px;
}
#lzstyle input{
float:left;
font-size:12px;
padding:4px 2px;
border:solid 1px #aacfe4;
width:200px;
margin:2px 0 20px 10px;
}
#lzstyle button{
clear:both;
margin-left:150px;
width:125px;
height:31px;
background:#666666 url(img/button.png) no-repeat;
text-align:center;
line-height:31px;
color:#FFFFFF;
font-size:11px;
font-weight:bold;
}

#lzstyle .help{
display:block;
margin-top:22px
}

#images img {
width: 500px;
display:block;
float:right;
margin-top:5px;
margin-right:100px;
height: auto;
display: none;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>
    function showme(ID){
	$("div#images").children("img#" + ID).stop(true, true).show();
    }
    function hideme(ID) {
        $("div#images").children("img#" + ID).hide();
    }
</script>
<div id="images">
    <img src="<?php echo plugins_url('lazy-pinner/images/email.png')?>" id="email">
    <img src="<?php echo plugins_url('lazy-pinner/images/pass.png')?>" id="pass">
    <img src="<?php echo plugins_url('lazy-pinner/images/key.png')?>" id="key">
    <img src="<?php echo plugins_url('lazy-pinner/images/url.png')?>" id="boards">
</div>

<div id="lzstyle" class="myform">
<form method="post" action="<?php echo $PHP_SELF; ?>">
<h1>Lazy Pinterest Options</h1>
<p>Connection Settings to Pinterest</p>

<label>Pinterest Email
<span class="small">Your pinterest email</span>
</label>
<input type="text" name="lzemail" id="lzemail" value = "<?php echo $lzemail; ?>" />
<span class="help"><img src ="http://www.biofects.com/wp-content/plugins/lazy-pinner/images/questionMarkIcon_SMALL.jpg" onmouseover="showme('email')"  onmouseout="hideme('email')"></a><span>

<label>Pinterest Password
<span class="small">Your pinterest password</span>
</label>
<input type="password" name="lzpass" id="lzpass" onblur="javascript:if(this.type='text'){this.type='password'} else {this.type='password'}" onfocus="javascript:if(this.type='password') {this.type='text'}" value = "<?php echo $lzpassvar; ?>" />
<span class="help"><img src ="http://www.biofects.com/wp-content/plugins/lazy-pinner/images/questionMarkIcon_SMALL.jpg" onmouseover="showme('pass')"  onmouseout="hideme('pass')"><span>

<label>Password Hash Key
<span class="small">Any Random Text</span>
</label>
<input type="text" name="lzpasskey" id="lzpasskey" value = "<?php echo $lzpasskey; ?>" />
<span class="help"><img src ="http://www.biofects.com/wp-content/plugins/lazy-pinner/images/questionMarkIcon_SMALL.jpg" onmouseover="showme('key')"  onmouseout="hideme('key')"><span>

<label>Pinterest Board URL
<span class="small">Copy and paste your Board URL</span>
</label>
<input type="text" name="directurl" id="directurl" value = "https://pinterest.com/<?php echo $lzboarduser.'/'.$lzboard; ?>" />
<span class="help"><img src ="http://www.biofects.com/wp-content/plugins/lazy-pinner/images/questionMarkIcon_SMALL.jpg" onmouseover="showme('boards')"  onmouseout="hideme('boards')"><span>


<button type="submit">Save settings</button>
<div class="spacer"></div>

</form>
</div>

<br><br>
<div class="wrap">
Donations are accepted for continued development of Lazy Pinner. Thank you.<br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YYSLHKPG557XS">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br> Please rate our plugin <a href ="http://wordpress.org/support/view/plugin-reviews/lazy-pinner?filter=5">here</a>
</div><br><br>
<?php
}
if(isset($_POST['lzemail']))
{
        $lzemailvar=$_POST["lzemail"];
	$lzpass=$_POST["lzpass"];
//	$lzboardvar=str_replace(' ', '-', $_POST["lzboard"]);	
	$lzpasskeyvar=$_POST["lzpasskey"];	
        $lzpassvar=trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $lzpasskeyvar, $lzpass, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
//	$lzboarduservar=$_POST["lzboarduser"];
	$directurl=$_POST['directurl'];
	$directurl = str_replace('http://', 'https://', $directurl );
	$directurl = explode('/', $directurl);;
	$lzboarduservar=$directurl[3];
	$lzboardvar = $directurl[4];
	$lzboarduservar=preg_replace('/[^\da-z]/i', '', $lzboarduservar);
 	$userurl = "https://pinterest.com/".$lzboarduservar."/".$lzboardvar;
 	$cookiefile = './pinitcookie.txt';
        if (file_exists($cookiefile)) { unlink ($cookiefile); }
        //User and pass to Pinterest
        $email    = $lzemailvar;
        $password = $lzpassvar;
        // initial login page which redirects to correct sign in page, sets some cookies
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.10 (maverick) Firefox/3.6.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-CSRFToken: $token"));
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_URL, $userurl);
        $page = curl_exec($ch);
        $res2 = preg_match('/.*var board =\ (\d+)/', $page, $names);
        $lzboardidvar = $names[1];
	if (!$lzboardidvar) {
        	echo "<div class=\"error\">Could Not Connect to Pinterest. Please check your information</div>";
		draw_form();
	}else{
		$table = $wpdb->prefix . 'lazy_pinner_user';
		$sql = $wpdb->query("UPDATE $table set email = '$lzemailvar', password = '$lzpassvar', passkey = '$lzpasskeyvar', board = '$lzboardvar', board_user = '$lzboarduservar', board_id = $lzboardidvar");
	        echo "<div class=\"updated\">Successful... Connected to your Pinterest Account and saved settings are saved.</div>";
		draw_form();
	}
}else{
	draw_form();
}

function Main_error()
{
echo "<br>This plugin needs the following. Please check your server settings or contact your host."
?>
<ul>
<?php
if(!function_exists( 'mcrypt_module_open' )) {
?>
<li>Mcrypt needs to be installed</li>
<?php } 
if(ini_get('safe_mode')) {
?>
<li>Safe Mode neeeds to be disabled</li>
<?php }
if(ini_get('open_basedir')){
?>
<li>Open_basedir cannot be set</li>
<?php  } ?>
</ul>
<?
exit;
}

?>


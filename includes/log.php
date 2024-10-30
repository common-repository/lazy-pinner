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
?>
<h1>Lazy Pinner Log</h1><br>
<div class="wrap" style="overflow:auto; height:200px; width:700px;">
<table border=1 width="600px">
	<tr>
	<td>Date of Post</td><td>Post Id</td><td>Comment</td>
	</tr>
<?php
global $wpdb;
$table = $wpdb->prefix . 'lazy_pinner_logs';
$log_results=$wpdb->get_results("SELECT * FROM $table", ARRAY_N);
foreach($log_results as $lresults) {
echo '<tr><td>' . $lresults[0] . '</td><td>'. $lresults[1] . '</td><td>'. $lresults[2] . '</td></tr>';
} 

?>
</table>
</div><br>
<div class="wrap">
Donations are accepted for continued development of Lazy Pinner. Thank you.<br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YYSLHKPG557XS">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br> Please rate our plugin <a href ="http://wordpress.org/support/view/plugin-reviews/lazy-pinner?filter=5">here</a>
</div>

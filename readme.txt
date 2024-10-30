=== Plugin Name ===
Contributors: Lee Thompson and Nick Westerlund
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=25LDNVSUTHKAJ
Tags: Pinterest, automatic, Pin,
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This Plugin will automatically post to pinterest when you publish your post.

== Description ==

This Plugin will automatically post to pinterest when you publish your post. Pinterst does not have an API so we have made this plugin to post to pinterest automatacially. 

== Installation ==

1. Install Plugin from wordpress search or upload plugin to wordpress plugins directory then activate plugin.

2. Go to Lay Pinner section fill out form save settings.
3. Pinterest Email... 		This is yor email you login to pinterest with.
4. Pinterest Password... 	This is your password you use to connect to pinterest.
5. Password Hash Key...		This is the key we use to encrypt your pinterest password in your wordpress database.
6. Pinterest URL...		This is the URL to your boards (https://pinterest.com/biofects/biofects).

== Frequently Asked Questions ==

= Does this require anything =

1. This script requires your host to have mcrypt, this is to encrypt passwords saved in the database.

2. Issues of safe_mode or open_basedir defined (I will fix this in next release).

= Why two developers =

Lee Thompson is the primary developer and Nick Westerlund helped with the authenication to pinterest. 

== Screenshots ==

There are no screenshots, but you can see it at [biofects](http://www.biofects.com) and [pinterest](http://www.pinterest.com/biofects)

== Changelog ==

= 1.0 =
* Initial Release

= 1.1 =
* Clean up Admin section

= 1.2 =
Fixed pinterest board name that is put in with spaces, pinterest converts to lower and a dash in the board name.

= 2.0 =
This major release has a lot of changes. Dont worry it will not impact any setting you have done already. I have made entering your pinterest info easier. I have created a page for logs.

= 2.1 =
I have made the pinner find the first image attached if no featured image is set, this removes the need to posts to have featured image.

= 2.1.1 =
Added uninstall function to remove tables and options this plugin creates

= 2.1.2 =
Added the new file I forgot

= 2.1.3 =
Fixed plugin delete

= 2.1.4 = 
Forgot to save new file

= 2.2 =
Added some error checking

= 2.3 =
Added curl check for common errors

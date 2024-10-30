=== Better Email Validation ===
Contributors: sharmavijay79
Tags: email validation, is_email, antispam 
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JKWDG57E8SCMA
Requires at least: 3.3
Tested up to: 3.8.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides better email validations to protect your blog from spam comments and bots creating accounts.

== Description ==
= Better Email Validation =
Provides better email validations to protect your blog from spam comments and bots creating accounts. It does deep validation to make sure the email account exists on the server. It requires fsockopen to be available and ability to connect to port 25. 

**More information**
- Other [WordPress plugins](http://www.techeach.com/wordpress-plugins/?utm_source=wp-plugin-repo&utm_medium=link&utm_campaign=more-info-link) by [Vijay Sharma](http://www.techeach.com?utm_source=wp-plugin-repo&utm_medium=link&utm_campaign=more-info-link)
- Contact Vijay on Twitter: [@sharmavijay79](http://twitter.com/sharmavijay79)

== Installation ==
1. Upload Better Email Validation plugin to your blog.
2. Activate it.
3. Ta da! You are done.

== Frequently Asked Questions ==
= Why did you create this plugin? =
I hate comment spam because it bloats my database. I found that nearly 50% of these email addresses were invalid. Thus this plugin was born. 

= I activated the plugin but I still get spam =
This plugin is not 100% protection around the spam. It just protects your blogs from bots entering spam email addresses to create accounts and comments.

= I installed the plugin but it is not working =
This plugin required access to fsockopen and your hosting provider should have port 25 access open to do deep server email checks.

= I get a *401 Unauthorized* error when I verify the API key but I\'m very sure that it is correct =
You could be using the normal API key, for this plugin you need to enter the **Public API Key** this is slightly 
longer than the normal API key and is found just below it.

== Screenshots ==
1. Install the plugin and activate it. You are all set.

== Changelog ==
= 1.0 =
* Initial release.

== Upgrade Notice ==
= 0.1 =
* Initial release.
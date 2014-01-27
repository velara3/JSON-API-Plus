JSON-API-Plus
=============

JSON-API Additions

This project contains a controller class for User authentication for the WordPress JSON-API plug-in (http://wordpress.org/plugins/json-api/). It adds login, registration, logout, lost password, reset password, get logged in user and is user logged in methods. 

You will want to go through the documentation (http://wordpress.org/plugins/json-api/other_notes/) on the main plugin page (http://wordpress.org/plugins/json-api/) to get a feel for how it works. Once you do that the following will make more sense. If you need more help post questions to the forum here (http://wordpress.org/support/plugin/json-api).

The main thing is to know what information to pass to the API. It will either be all in a GET request (in the URL query) or if more info is needed, in the POST data.

When making a call append the query to the URL. So, if your site is at http://www.mysite.com/blog/ then the query would be appended as, "http://www.mysite.com/blog/?json=user/login".

LOGIN
Query:
?json=user/login

The POST information should include:
log = string
pwd = string
rememberme = true or false

REGISTER
Query:
?json=user/register

POST data:
username = string
email = string

LOST PASSWORD
Query:
?json=user/lost_password

POST data:
username = string

RESET PASSWORD / CONFIRM REGISTRATION
Query:
?json=user/reset_password&action=resetpass&key=" + key + "&login=" + username

POST data:
pass1 = string
pass2 = string

The user would have received the key in an email when they lost their password or registered for the first time. You must then include their new password in the post.

LOGOUT
Query:
?json=user/logout"

IS USER LOGGED IN
Query:
?json=user/is_user_logged_in"

GET LOGGED IN USER
Query:
?json=user/get_logged_in_user"

CHANGING THE EMAIL ADDRESS SENT TO THE USER
When you reset your password or register you or your user receive an email from "wordpress@mysite.com". You can change by uncommenting the lines at the top of the User controller and filling them in with the email and name you want to use. You may have to actually create that mailbox on your server depending on your host.

/** changing default wordpress email settings. uncomment to set your own email */
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from($old) {
   return 'contact@yoursite.com';
}
function new_mail_from_name($old) {
   return 'Your Site';
}

It would be nice to have this on the settings page but I ran out of time and you would have to modify the main class which I want to avoid since it is out of my control.

NOTE: Of course, if your site uses HTTPS rather than HTTP then make sure your calls are using that. 

Also, when logging in, WordPress creates a set of authentication cookies so you need to enable that for whatever mechanism you are using. 

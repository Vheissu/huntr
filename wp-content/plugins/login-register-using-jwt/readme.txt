=== WP Login and Register using JWT ===
Contributors: cyberlord92
Tags: jwt, json web token, single sign-on, api, wordpress sso, wp login, jwt authentication, jwt login, register, rest api, jwt sso, login
Requires at least: 3.0.1
Tested up to: 6.0
Stable tag: 2.3.0
Requires PHP: 5.6
License: MIT/Expat
License URI: https://docs.miniorange.com/mit-license

WordPress login (WordPress Single Sign-On) using JWT(JSON Web Token) from other WordPress sites or any other application. [24/7 SUPPORT]

== Description ==

The [WordPress Login and Register using JWT plugin](https://plugins.miniorange.com/wordpress-login-using-jwt-single-sign-on-sso) allows you to log in (WordPress Single Sign-On) into the WordPress application using the JWT token(JSON Web token) from any other WordPress site or other applications/platforms including mobile applications.

**WORDPRESS SINGLE SIGN-ON / SSO ( LOGIN INTO WORDPRESS )**
WordPress Single Sign-On also simply called WordPress SSO allows you to login into WordPress using the credentials of other platforms.

**WORDPRESS SINGLE SIGN-ON / SSO using JWT(JSON Web Token)**
WordPress Single Sign-On with JWT allows you to log into the WordPress site using the user based JWT token obtained externally.
The JWT token authentication is the most popular way of authentication nowadays as it is a secure and lightweight protocol. The JWT token can be obtained either when a user logs into other platforms via [OAuth](https://oauth.net/)/[OpenID](https://openid.net/connect/) protocol or can be created explicitly using the user information and secure algorithms. 
With this plugin, you can easily use the user based JWT token to log a user in rather than asking them to authenticate again.

*Let's take an example* - If you have a WordPress site and mobile app, now if you are logged into the mobile app, now if you try to access the WordPress site, then to access the particular content, the WordPress site will ask for login again and which is not feasible, so with the JWT SSO (JWT Single Sign-On), you can create the JWT token for the user who is already logged into the mobile app and then on accessing the WordPress site, you can pass that JWT token, using which the same user can authenticate and autologin to the WordPress site and hence won't need to enter the credentials again.

This plugin provides a REST API endpoint(/wp-json/api/v1/mo-jwt) by which requesting with the valid username and password in the body returns the JWT token and that token can be used to autologin into the WordPress site.

The plugin also provides the feature to register(create) users in WordPress securely using the register API endpoint provided by the plugin and on successful user registration, the API will return the JWT token for the registered user in the response which can be used further to login user in WordPress or other platforms. 

The feature to delete the user from WordPress via API endpoint is also available using, just passing the user based JWT token of that user to be deleted in the request body results in user deletion.

We support possibly all kinds of JWT tokens (access-token/id-token) obtained from OAuth/OpenID providers like Microsoft Azure AD, Azure B2C, Okta, Keycloak, ADFS, AWS Cognito, Google, Facebook, Apple, Discord and popular applications like Firebase.

WordPress login using the JWT also called JWT SSO (Single Sign-On) can be done from other platforms and applications including mobile apps (android or IOS), an app built with other programming languages like .NET, JAVA, PHP, JS etc. 

**WORDPRESS SINGLE SIGN-ON / SSO using OAuth/OpenID Connect provider**
If you are looking for WordPress Single Sign-On in WordPress with external OAuth/OpenID credentials providers like Microsoft Azure AD, Azure B2C, Office 365, AWS Cognito, Okta, Keycloak, Discord, ADFS, WS02, Strava, Slack, Google, Facebook, Apple, LinkedIn etc such that your users will just need to enter their OAuth/OpenID provider's app credentials, and they will get logged in to the WordPress, then we already have our other awesome and widely popular plugin - [WordPress OAuth Single Sign-On: SSO (OAuth Client)](https://wordpress.org/plugins/miniorange-login-with-eve-online-google-facebook). 

**WordPress REST API endpoints Security**
By default, the WordPress REST API endpoints are public and can be accessed by anyone and lead to data leakage while some REST APIs are protected by default but do not let you access the data unless you are authorized to do so. To help you out with this, we already have a plugin - [WordPress REST API Authentication](https://wordpress.org/plugins/wp-rest-api-authentication/) which you can use to authenticate and authorize your WordPress REST API endpoints hence only authenticated and authorized users are allowed to access the requested data.


== USE CASES == 

* Single Sign-On Users using the JWT token provided by OAuth/OpenID providers
This WordPress login and register using the JWT plugin supports the WordPress Single Sign On (WordPress SSO) or WordPress login using the user based JWT token (id-token/access-token) provided by the OAuth/OpenID Connect providers (like Microsoft Azure AD, Azure B2C, AWS Cognito, Keycloak, Okta, ADFS, Google, Facebook, Apple, Discord and many more..) on login in some other sites/applications using their credentials.
So, the user just needs to log in once on any other sites/platforms and a JWT token will be provided by these providers for those users will then be used further with security to autologin in other platforms.

* WordPress login and site access from mobile app webview
Suppose you have a mobile application and wants to allow users to access their WordPress site content in the mobile app webview which requires a login so asking the users to enter the credentials again won’t be a good user experience. So, our JWT login plugin provides a solution to you in which the user session from the mobile app can be synchronized with the WordPress site and the user can seamlessly access the WordPress site using the user based JWT token without the need for a WordPress login again.

* Login into other apps and platforms using WordPress credentials
Suppose you have a WordPress site in which all the user identities are stored and you have other applications as well, so for a good user experience, you want them to use the same set of credentials (one which they used to register on the WordPress site) to login to these other applications as well. Now, on your application login form, when they enter their WordPress login credentials, then it will require authentication with the WordPress directly. So, our plugin can help you with this. 
It provides the following HTTP POST API endpoint:
`
/wp-json/api/v1/mo-jwt
`  
in which you need to pass the user's WordPress credentials in the request body and on a successful response, you will get the user based JWT token which you can use further to authenticate or login users in other applications and will return an error response for unauthenticated users.


* Register into WordPress using user registration API from any external platforms:
If you want to register a user on your WordPress site from another application or external platform then our plugin provides a following HTTP POST API endpoint 
`
wp-json/api/v1/mo-jwt-register
` 
and you can pass the new user details like username, email, name and password(optional), role etc. in the request body and on successful response, your user will get created and the corresponding user based JWT will be received and the appropriate error response will be returned on the failure.

* Delete/Remove users from WordPress using the user based JWT token (JSON Web Token)
If you want to delete the user from WordPress using the API request from other platforms securely, then WordPress Login with JWT plugin provides a following HTTP POST API endpoint 
`
wp-json/api/v1/mo-jwt-delete 
` 
and you can pass the user based JWT of the user which you want to delete and on the successful response, the user will get deleted else an appropriate error response will be returned.

* Sync user login sessions between multiple platforms (Session sharing)
If you have a WordPress site and other applications sharing the same subdomain and you want the feature in which if a user logged into one site (WordPress or another) and on accessing the other site in the same browser, then that user should get logged in automatically (user session to be synchronized). So, this feature is possible to have with our plugin's JWT cookie-based session sharing feature.

== Features == 

FREE PLAN

*Create JWT feature*

 - Supports token generation using HS256 signing algorithm.
 - JWT token signing with randomly generated secret signing key.
 - Default token expiration is 60 minutes.

*User Registration feature*

 - Provide an API endpoint for user registration with the default subscriber role.
 - Provide user based JWT token in the success response.
 - No Extra Security key for user registration API.

*User Deletion feature*

 - Provide an API endpoint for user deletion with JWT token validation using the HS256 signing algorithm.
 - No Extra Security key for user deletion API.

*User login feature*

 - Allows wordpress login using a user based JWT with HS256 signing created using plugins create JWT feature.
 - Retrieve the JWT token from the URL parameter to allow auto-login.
 - Auto redirection on login to the homepage or on the same page/URL from where the autologin is initiated.
 - Default Subscriber role on login using JWT.


PREMIUM PLAN

*Create JWT feature* 

 - Supports token generation using HS256 and a more securer RS256 signing algorithm.
 - JWT token signing with custom secret signing key or certificate.
 - Custom token expiration to expire the token as per your requirement to improvise security.
 - Custom JWT token decryption key.

*User Registration feature*

 - Provide an API endpoint for user registration with a custom role.
 - Provide user based JWT token in the success response.
 - Extra Security key for user registration API.

*User Deletion feature*

 - Provide an API endpoint for user deletion with JWT token validation using the HS256 signing algorithm.
 - Extra Security key for user deletion API.

*User login feature*

 - Allows wordpress login using a user based JWT with HS256 signing created either using plugins create JWT feature or JWT token obtained from an external source.
 - Allows wordpress login using a user based JWT with RS256 signing validation.
 - Allows wordpress login using a user based JWT with JWKS validation support.
 - Allows wordpress login using a user based JWT obtained from OAuth/OpenID Connect provider.
 - Retrieve the JWT token from the URL parameter and cookie to allow auto-login between platforms.
 - Auto redirection on login to the homepage or on the same page/URL from where the autologin is initiated.
 - Auto redirection on login to any custom URL.
 - User Attribute/Profile mapping on SSO login.
 - Option to assign any WordPress role rather than default subscriber on SSO login.


 == Installation ==

This section describes how to install the WP JWT Login and Register plugin and get it working.

= From your WordPress dashboard =

1. Visit `Plugins > Add New`
2. Search for `JWT Login`. Find and Install the `WP JWT Login and Register ` plugin by miniOrange
3. Activate the plugin

= From WordPress.org =

1. Download WP JWT Login and Register.
2. Unzip and upload the `wp-jwt-login` directory to your `/wp-content/plugins/` directory.
3. Activate WP JWT Login and Register from your Plugins page.


== Privacy ==

This plugin does not store any user data. This plugin uses login.xecurify.com for registration as miniOrange uses login.xecurify.com if the user chooses to register and upgrade to premium. If the user does not want to register then he can continue using the free plugin. (Link to the privacy policy -  https://www.miniorange.com/privacy-policy.pdf )

== Frequently Asked Questions ==

= What is log in using JWT or JWT login? = 
JWT(JSON Web token) login allows you to login into any platform like WordPress using the user based JWT token rather than passing the actual login credentials. Also, it is a highly secure way to log in as the JWT which consists of user information is signed using highly secure HSA and RSA algorithms.

= What is JWT SSO (JWT Single Sign-On)? = 
JWT SSO(Single Sign-On) or SSO using JWT token allows the user to log in to any platform using one set of credentials and then JWT formed from the logged-in user details can be used to login automatically to other platforms and does not require to enter the credentials again.

= Does this plugin allows auto login users in WordPress from mobile applications = 
Yes, this plugin provides the feature to auto-login users in WordPress sites from mobile applications and also other applications built on Java, React, Node JS, C#, PHP etc frameworks. using the JWT token. Moreover, this plugin provides other features to redirect the user to some other URLs on login as well.

= Does this plugin allows WordPress user registration and deletion of the REST API endpoint? = 
Yes, the plugin provides both the user registration endpoint (wp-json/api/v1/mo-jwt-register) as well as deletion API (wp-json/api/v1/delete). 

= Can sessions across multiple applications be synchronized using this plugin? =
This plugin provides the feature in which if multiple applications share the same subdomain with WordPress and if you are logged into one platform then accessing any of the other platforms will log in the user automatically without the need to authenticate again. 

= Does this plugin provides session sharing for WordPress site opened in the webview of a mobile application? = 
Yes, that would be possible to achieve with the plugin, so if a user logged into the mobile app and then clicks on the WordPress site URL link, that WordPress page will be opened in the webview and the plugin will help in establishing the session sharing in the webview such that user won't be required to log in again and can access the WordPress page seamlessly.


== Screenshots ==

1. List of JWT configuration methods.
2. JWT Login settings
3. Create JWT settings
4. Register for JWT settings
5. Delete users with JWT settings

== Changelog ==

= 2.3.0 =
* Compatibility with WordPress 6.0
* Minor Bug Fixes

= 2.2.0 = 
* Major UI Updates
* Added the functionalities for user registration, deletion.
* Bug Fixes and usability improvements
* Compatilbity with WordPress 5.9.* and PHP 8+ 

= 2.1.2 = 
* Security Fixes
* Compatiblity with WordPress 5.8.1
* Readme Updates

= 2.1.1 = 
* Minor bug fixes
* Readme Changes

= 1.0.0 =  
* First release of the plugin
* Compatibility with WordPress 5.8

== Upgrade Notice ==

= 1.0.0 =  
* First release of the plugin
* Compatibility with WordPress 5.8


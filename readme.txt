=== Restricted Blocks - Conditional Visibility Settings for the Block Editor ===
Contributors: DAEXT
Tags: conditional blocks, conditional content, hide content, dynamic content, visibility
Donate link: https://daext.com
Requires at least: 5.0
Tested up to: 6.6.2
Requires PHP: 5.6
Stable tag: 1.12
License: GPLv3

Restricted Blocks is a WordPress plugin that allows you to restrict access to specific Gutenberg blocks based on a great variety of conditions.

== Description ==
Restricted Blocks is a WordPress plugin that allows you to restrict access to specific Gutenberg blocks based on a great variety of conditions.

You can, for example, restrict content based on the user role, conditionally display content based on the user device, make parts of the post unavailable to search engines, and more.

### Pro Version

For professional users, we distribute a [Pro Version](https://daext.com/restricted-blocks/) of this plugin which includes additional types of restrictions.

## Types of restrictions

This section will provide you details on the types of restrictions and the related usage examples.

### Fixed

This restriction, when activated, removes a block from the front end.

#### Usage Examples

* Temporarily hide existing or new sections of an article until they are ready to be published.

### Password

The restricted content is displayed only after a successful password submission.

#### Usage Examples

* Display private content only to members of a club, community, or to your friends.
* Display downloadable material only to students of a class.

### Device

This restriction allows you to display blocks based on the device of the user.

#### Usage Examples

* Display complex layout elements only with desktop devices
* Display different download links based on the device of the user

### Time Range

With this restriction, you can display content at a specified time interval.

#### Usage Examples

* Sell tickets in predetermined time intervals
* Create a flash sale

### Capability

This restriction uses the [capabilities of the user](https://wordpress.org/support/article/roles-and-capabilities/) to determine whether to display or not a specific block.

#### Usage Examples

* Allow access to premium material only to specific user roles.

### IP Address

This restriction allows you to display or hide blocks based on the IP address of the visitor.

#### Usage Examples

* Prevent spam on contact forms or comments area
* Prevent hacking attempts

### Cookie

Use this restriction to display content based on the presence of specifics cookies.

#### Usage Examples

* Hide or display interface elements based on the information available about the user
* Display content only to logged user

### HTTP Headers

With this restriction, you can display or hide content based on the information available in the HTTP headers.

#### Usage Examples

* Hide content to specific bot or search engines
* Conditionally display content based on the device of the user

## Customizable Style

You can customize the colors and the typography of the elements displayed in the front-end with the 18 style options available in the back-end.

## Technical Information

### Plugin manual

Please see the [plugin manual](https://daext.com/doc/restricted-blocks-lite/) for more information on the plugin installation and usage.

### Device detection

The device of the user is detected with the [Mobile Detect](https://github.com/serbanghita/Mobile-Detect) PHP class.

## Credits

This plugin makes use of the following resources:

* [Select2](https://github.com/select2/select2) licensed under the [MIT License](http://www.opensource.org/licenses/mit-license.php)
* [Mobile Detect](https://github.com/serbanghita/Mobile-Detect) licensed under the [MIT License](http://www.opensource.org/licenses/mit-license.php)

== Installation ==
= Installation (Single Site) =

With this procedure you will be able to install the Restricted Blocks plugin on your WordPress website:

1. Visit the **Plugins -> Add New** menu
2. Click on the **Upload Plugin** button and select the zip file you just downloaded
3. Click on **Install Now**
4. Click on **Activate Plugin**

= Installation (Multisite) =

This plugin supports both a **Network Activation** (the plugin will be activated on all the sites of your WordPress Network) and a **Single Site Activation** in a **WordPress Network** environment (your plugin will be activated on a single site of the network).

With this procedure you will be able to perform a **Network Activation**:

1. Visit the **Plugins -> Add New** menu
2. Click on the **Upload Plugin** button and select the zip file you just downloaded
3. Click on **Install Now**
4. Click on **Network Activate**

With this procedure you will be able to perform a **Single Site Activation** in a **WordPress Network** environment:

1. Visit the specific site of the **WordPress Network** where you want to install the plugin
2. Visit the **Plugins** menu
3. Click on the **Activate** button (just below the name of the plugin)

== Changelog ==

= 1.12 =

*April 8, 2024*

* Fixed a bug (started with WordPress version 6.5) that prevented the creation of the plugin database tables and the initialization of the plugin options during the plugin activation.

= 1.11 =

*November 15, 2022*

* Minor backend improvements.
* Changelog added.

= 1.10 =

*September 1, 2021*

* Initial release.

== Screenshots ==
1. Restrictions menu.
2. Options menu in the "Style" tab
3. Options menu in the "Advanced" tab
4. Selector in the Block inspector
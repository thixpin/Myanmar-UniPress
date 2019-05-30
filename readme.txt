=== Myanmar UniPress ===
Contributors: thixpin
Email: thixpin@gmail.com
Tags: Myanmar Font, Myanmar3, Unicode, Zawgyi, Converter, Font Converter
Requires at least: 3.0.1
Tested up to: 5.2.1 
Requires PHP: 5.2.4
Stable tag: 1.2.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html


Myanmar UniPress will check myanmar content and convert to unicode before saving.

== Description ==

Myanmar UniPress will check myanmar content and convert to browser encoding if the content font is not equal to brower font and Myanmar UniPress will conver the zawgyi contents (posts, comments, pages) as unicode before save. 


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `myanmar-unipress` folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Myanmar UniPress screen to configure the MyanmarUnipress



== Frequently Asked Questions ==

Nothing at the moment




== Screenshots ==

1. Preview of ClassicText Editor with MyanmarUnipress
![Screenshot1](screenshot1.png "assets/screenshot-1.png")


== Changelog ==


= 1.2.1 =
* 30/05/2019
* Add nonce check to admin panel for security


= 1.2 =
* 29/05/2019
* Edit to follow the wp-plugin policy


= 1.1 =
* New option `Disable BunnyJs` for font embed.
* Split by paragraph before detect and convert for WP new editor.

= 1.0 =
* Detecting browser font and auto converting to display
* Detecting content type is Unicode or Zawgyi
* Save all update/insert contents as Unicode
* Converter buttons in text editor (Need to used classic editor).

== Upgrade Notice ==

Nothing at the moment

== Todo ==

1. Add converter buttons on default block of new wp editor 


== Contribution ==

Contributions are warmly welcome. It is only the collection source code of othere developer (I am only copy paste developer :trollface:) . If you have better idea, please do contribution. 


== Support ==

You can connect [me](http:fb.me/thixpin) to get support.


== Credits ==

- [Rabbit Converter](https://github.com/Rabbit-Converter/) was used for Unicode<==>Zawgyi converting.

- Myanmar font detecting and converting functions are come from [MUA-Web-Unicode-Converter](https://github.com/thixpin/MUA-Web-Unicode-Converter) 

- Browser font detecting idea from `Ko Ei maung`

- Plugin template is based on [Zawgyi Embed](https://wordpress.org/plugins/zawgyi-embed/)


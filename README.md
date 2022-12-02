[![Build Status](https://travis-ci.org/Automattic/_s.svg?branch=master)](https://travis-ci.org/Automattic/_s)

_s
===

Hi. I'm a starter theme called `_s`, or `underscores`, if you like. I'm a theme meant for hacking so don't use me as a Parent Theme. Instead try turning me into the next, most awesome, WordPress theme out there. That's what I'm here for.

My ultra-minimal CSS might make me look like theme tartare but that means less stuff to get in your way when you're designing your awesome theme. Here are some of the other more interesting things you'll find here:

* A modern workflow with a pre-made command-line interface to turn your project into a more pleasant experience.
* A just right amount of lean, well-commented, modern, HTML5 templates.
* A custom header implementation in `inc/custom-header.php`. Just add the code snippet found in the comments of `inc/custom-header.php` to your `header.php` template.
* Custom template tags in `inc/template-tags.php` that keep your templates clean and neat and prevent code duplication.
* Some small tweaks in `inc/template-functions.php` that can improve your theming experience.
* A script at `js/navigation.js` that makes your menu a toggled dropdown on small screens (like your phone), ready for CSS artistry. It's enqueued in `functions.php`.
* 2 sample layouts in `sass/layouts/` made using CSS Grid for a sidebar on either side of your content. Just uncomment the layout of your choice in `sass/style.scss`.
Note: `.no-sidebar` styles are automatically loaded.
* Smartly organized starter CSS in `style.css` that will help you to quickly get your design off the ground.
* Full support for `WooCommerce plugin` integration with hooks in `inc/woocommerce.php`, styling override woocommerce.css with product gallery features (zoom, swipe, lightbox) enabled.
* Licensed under GPLv2 or later. :) Use it to make something cool.

Installation
---------------

### Requirements

`_s` requires the following dependencies:

- [Node.js](https://nodejs.org/)
- [Composer](https://getcomposer.org/)

### Quick Start

Clone or download this repository, change its name to something else (like, say, `megatherium-is-awesome`), and then you'll need to do a six-step find and replace on the name in all the templates.

1. Search for `'_s'` (inside single quotations) to capture the text domain and replace with: `'megatherium-is-awesome'`.
2. Search for `_s_` to capture all the functions names and replace with: `megatherium_is_awesome_`.
3. Search for `Text Domain: _s` in `style.css` and replace with: `Text Domain: megatherium-is-awesome`.
4. Search for <code>&nbsp;_s</code> (with a space before it) to capture DocBlocks and replace with: <code>&nbsp;Megatherium_is_Awesome</code>.
5. Search for `_s-` to capture prefixed handles and replace with: `megatherium-is-awesome-`.
6. Search for `_S_` (in uppercase) to capture constants and replace with: `MEGATHERIUM_IS_AWESOME_`.

Then, update the stylesheet header in `style.css`, the links in `footer.php` with your own information and rename `_s.pot` from `languages` folder to use the theme's slug. Next, update or delete this readme.

### Setup

To start using all the tools that come with `_s`  you need to install the necessary Node.js and Composer dependencies :

```sh
$ composer install
$ npm install
```
Notes:
If cross evn throw error : 'cross-env' is not recognized as an internal or external command.  you will need to install it globally with: npm install -g cross-env 

### Plugin Settings for css set up

W3 Total Cache: Go to "General Setting" than the "Minify" section and set "Minify mode:" to "Manual".

Autoptimize: Go to Autoptimize setting section.  Go to "CSS Options" under "JS, CSS, & HTML" tab and turn on "Eliminate render-blocking CSS?".  Leave the inputbox empty since we are adding the style.min.css file to the header in fucntions.php file. We are using a hook from this plugin to add it in.

Formidable: Go to "Global Settings" and make sure in "STLING & SCRIPTS" "Load form styling" is set to "only on applicable pages"

Table of Contents: In this plugin settings make sure "Prevent the loading the core CSS styles. When selected, the appearance options from above willb e ignored" is turn on.


### Build Tool Set Up

Using: Laravel Mix

Add ons: Purgecss, cleancss, browserSync, and others

Name of file with settings: webpack.mix.js

We are using purgecss to go through our files to remove any unneeded styles. If you are creating something and you dont see the style you may need to add a class to the protected list or make sure your php/js file is being read by the webpack.mix.js file.  I have it set to run in production when watching so that the file will be minify.

Note: I have set up users for each of us developer so you can set up the browserSync setting to your liking.  Also if you want to add anythign special on you can update that part of the webpack.mix.js file.

### Available CLI commands

`_s` comes packed with CLI commands tailored for WordPress theme development :

- `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
- `composer lint:php` : checks all PHP files for syntax errors.
- `composer make-pot` : generates a .pot file in the `languages/` directory.
- `npm run compile:css` : compiles SASS files to css.
- `npm run watch` : watches all SASS files and recompiles them to css when they change. This doesn't have a browserSync on it. 
- `npm run watch:brad` : watches all SASS files and recompiles them to css when they change. This will run browserSync with brad setting.
- `npm run watch:edward` : watches all SASS files and recompiles them to css when they change. This will run browserSync with edward setting.
- `npm run watch:jessi` : watches all SASS files and recompiles them to css when they change. This will run browserSync with jessi setting.
- `npm run watch:ryan` : watches all SASS files and recompiles them to css when they change. This will run browserSync with ryan setting.
- `npm run watch:syed` : watches all SASS files and recompiles them to css when they change. This will run browserSync with syed setting.


Now you're ready to go! The next step is easy to say, but harder to do: make an awesome WordPress theme. :)

Good luck!

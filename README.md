Config File Reader Magento 2 Module
===================================

[![Build Status](https://travis-ci.org/webgriffe/module-config-file-reader.svg?branch=master)](https://travis-ci.org/webgriffe/module-config-file-reader)

A Magento 2 module that reads from file additional configuration, inspired by this Magento 1.x extension: [https://github.com/webgriffe/config-extension](https://github.com/webgriffe/config-extension).

Installation
------------

Please, use [Magento Composer Installer](https://github.com/magento-hackathon/magento-composer-installer) and add `webgriffe/module-config-file-reader` to your dependencies. Also add this repository to your `composer.json`.

	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/webgriffe/module-config-file-reader.git"
        }
    ]
    
Config override
---------------

Magento configuration is driven by database. This, sometimes, is overkill and forces us to maintain upgrade script to keep Magento envorinment aligned with features development.
So, this extension enables additional config file that overrides database configuration. The file must be at path `app/etc/default.yml`. For example:

	design:
	  head:
	    default_title: My Store Title
	    title_suffix: - My Store Title

Only `default` configuration scope is overridden.

Overridden config values are shown in backend
---------------------------------------------

Overridden config values are shown in Magento's backend. Every config setting it's shown on its section. For example, if you have the following `default.yml` file:

	design:
	  head:
	    default_title: My Store Title
	    title_suffix: - My Store Title

When you'll go to `Stores -> Configuration -> General -> Design` you'll find the overridden config value shown and not editable.

![Admin Screenshop](admin_screenshot.png)

This feature improves a lot the usability of this extension.


To Do
-----

* Improve system config admin interface to support complex fields
* Environment specific overrides

Credits
-------

* Developed by [Webgriffe®](http://webgriffe.com)






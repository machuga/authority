# Install and Setup

[Composer](http://www.getcomposer.org) is required to install Authority into your project. 

**Quick Install Composer**

```$ curl -s https://getcomposer.org/installer | php```

### Install Authority
Open or create a ``composer.json`` file in your project directory and add an entry for authority to the required section.

```php
File: /myproject/composer.json
{
    require : {
        'machuga/authority' : 'dev-develop'
    }
}
```

If this if the first time you're running composer:

```$ composer install```

Otherwise:

```$ composer update```

### Include Composer

If you're using a modern framework, composer is likely being included for you already.  If not, you can add the autoloader yourself by finding a bootstrap script for your framework, and requiring the the vendor/autoload.php file as demonstrated below:

```require 'path/to/vendor/autoloader.php';```


Aaaaand that's it! You should be ready to use authority and any other packages you've included in composer.</pre>

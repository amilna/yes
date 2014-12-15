YES
===
Yii Ecommerce Support

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

add following config into file composer.json in webroot aplication:
"repositories":[ { "type": "git", "url": "https://github.com/amilna/yes"" }]

then run

```
php composer.phar require amilna/yii2-yes "dev-master"
```

or 

download the extentsion package and extract it into vendor, then add:

```
"amilna/yii2-yes": "*"
```

to the require section of your `composer.json` file.

then add:
"yes" => [
            "class" => "amilna\yes\Module",
        ],

inside modules section of `config\main.php` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \amilna\yes\AutoloadExample::widget(); ?>```
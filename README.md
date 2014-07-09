smart-assets
============

Smart Asset Management for laravel

This package add automatic coffee, less...etc convert to js and css.
It work when you specify path to file like /pipeline/\*/\*.coffee

##Installation

Begin by installing this package through Composer. Edit your project's composer.json file to require kodeks/smart-assets.

It might look something like:

```php
  "require": {
    "laravel/framework": "4.1.*",
    "kodeks/smart-assets": "dev-master"
  }
```

Next, update Composer from the Terminal:

```php
    composer update
```

Once this operation completes, add the service provider. Open `app/config/app.php`, add the following items to the providers array.

```php
    'Kodeks\SmartAssets\SmartAssetsServiceProvider',
```

Next optionally, ensure your environment is setup correctly because by default the asset pipeline will cache and and minify assets on a production environment.

Inside `bootstrap/start.php`

```php
  $env = $app->detectEnvironment(array(
    'local' => array('your-machine-name'),
  ));
```

## Usage

Place these lines into your Laravel view/layout

```php
    <script type="text/javascript" src="/pipeline/folder/file.coffe"></script>
    <link rel="stylesheet" type="text/css" href="/pipeline/folder/file.less" />
```
or if you want concatenate files (work when environment is production)

```php
	<!-- build:css /lib/css/vendor.min.css -->
	<link rel="stylesheet" type="text/css" href="/dist/assets/less/main.less" />
	<link rel="stylesheet" type="text/css" href="/lib/css/bootstrap.css" />
	<!-- endbuild -->
	 
	<!-- build:js /lib/js/vendor.min.js -->
	<script type="text/javascript" src="/lib/js/ng/angular.js"></script>
	<script type="text/javascript" src="/lib/js/ng/angular-animate.js"></script>
	<!-- endbuild -->
	 
```
they save code to vendor.min.css file and replace this strings


## Configuration

To create a custom package config for smart-assets run

```php
  php artisan config:publish kodeks/smart-assets
```

### routing array

```php
  'routing' => array(
    'prefix' => '/pipeline'
  ),
```
This prefix uses when you need convert files

### paths

```php
  'paths' => array(
    'public'
```

### modules

I you use package [https://github.com/creolab/laravel-modules], this function may be usefull

```php
  'modules' => true
```

if true, when smart-assets find files in "path" and not find, it goes to the public/packages/module/ or app/modules/ when production

These are the directories we search for files in. You can think of this like PATH environment variable on your OS. We search for files in the path order listed below.

### mimes

```php
  'mimes' => array(
      'javascripts' => array('.js', '.js.coffee', '.coffee', '.html', '.min.js'),
      'stylesheets' => array('.css', '.css.less', '.css.scss', '.less', '.scss', '.min.css'),
  ),
```

In order to know which mime type to send back to the server we need to know if it is a javascript or stylesheet type. If the extension is not found below then we just return a regular download. You should include all extensions in your `filters` here or you will likely experience unexpected behavior. This should allow developers to mix javascript and css files in the same directory.


## commands

if you need flush cashe, use command
```php
  php artisan assets:clean
```
They flush /storage/cache and /storage/views/

#P.S.

The idea, code and documentation belongs to the [ttps://github.com/CodeSleeve/asset-pipeline]
Thanks guys. Your code is awesome. But i need this functions. Maybe someone else needs this kind of functionality so I'm posting this package

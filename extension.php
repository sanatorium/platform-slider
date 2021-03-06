<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Slider',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/slider',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Slider plugin',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '3.0.4',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Slider\Providers\SliderServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::group([
				'prefix'    => admin_uri().'/slider/sliders',
				'namespace' => 'Sanatorium\Slider\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.slider.sliders.all', 'uses' => 'SlidersController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.slider.sliders.all', 'uses' => 'SlidersController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.slider.sliders.grid', 'uses' => 'SlidersController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.slider.sliders.create', 'uses' => 'SlidersController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.slider.sliders.create', 'uses' => 'SlidersController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.slider.sliders.edit'  , 'uses' => 'SlidersController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.slider.sliders.edit'  , 'uses' => 'SlidersController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.slider.sliders.delete', 'uses' => 'SlidersController@delete']);
			});

		Route::group([
			'prefix'    => 'slider/sliders',
			'namespace' => 'Sanatorium\Slider\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.slider.sliders.index', 'uses' => 'SlidersController@index']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('slider', function($g)
		{
			$g->name = 'Sliders';

			$g->permission('slider.index', function($p)
			{
				$p->label = trans('sanatorium/slider::sliders/permissions.index');

				$p->controller('Sanatorium\Slider\Controllers\Admin\SlidersController', 'index, grid');
			});

			$g->permission('slider.create', function($p)
			{
				$p->label = trans('sanatorium/slider::sliders/permissions.create');

				$p->controller('Sanatorium\Slider\Controllers\Admin\SlidersController', 'create, store');
			});

			$g->permission('slider.edit', function($p)
			{
				$p->label = trans('sanatorium/slider::sliders/permissions.edit');

				$p->controller('Sanatorium\Slider\Controllers\Admin\SlidersController', 'edit, update');
			});

			$g->permission('slider.delete', function($p)
			{
				$p->label = trans('sanatorium/slider::sliders/permissions.delete');

				$p->controller('Sanatorium\Slider\Controllers\Admin\SlidersController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{
		$settings->find('platform')->section('slider', function ($s) {
			$s->name = trans('sanatorium/slider::settings.title');

            $s->fieldset('slider', function ($f) {
                $f->name = trans('sanatorium/slider::settings.title');

                $f->field('style', function ($f) {
                    $f->name   = trans('sanatorium/slider::settings.style.label');
                    $f->info   = trans('sanatorium/slider::settings.style.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-slider.style';

                    $f->option('1', function ($o) {
                        $o->value = 1;
                        $o->label = '1 - standard';
                    });

                    $f->option('2', function ($o) {
                        $o->value = 2;
                        $o->label = '2 - custom pager';
                    });

                    $f->option('3', function ($o) {
                        $o->value = 3;
                        $o->label = '3 - fullscreen';
                    });
                });
            });
        });
	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-sanatorium-slider',
				'name' => 'Slider',
				'class' => 'fa fa-circle-o',
				'uri' => 'slider',
				'regex' => '/:admin\/slider/i',
				'children' => [
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Sliders',
						'uri' => 'slider/sliders',
						'regex' => '/:admin\/slider\/slider/i',
						'slug' => 'admin-sanatorium-slider-slider',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];

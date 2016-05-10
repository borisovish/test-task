# Srizon Laravel (5.2) KickStarter

## About Laravel
Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## About this modified version
This version adds necessary codes, views and database tables for supporting User/Role/Permission management. It also uses the AdminLTE theme.

## Pre-Requisite
You must be familiar with laravel. This distribution is intended for developers only

## Installation
Pull it using git clone. 

Copy and rename the `.env.example` to `.env` then edit the `.env` file to point it to your database. 

Also set `MAIL_DRIVER=log` in the `.env` file

Run `composer install` and `composer update` (if required).

Then run `php artisan migrate` and `php artisan key:generate` command in order to migrate the database structures.

You should be able to see a welcome page when you point your web browser to the public directory

The person who tries to login for the first time will be prompted with the default email/password below the login screen.

Login using that info and go to users->click on user name->click on edit and set your Name, Email and Password

That users will be treated as `super user` and be able to perform super admin tasks

## How to use the *Permission*

You can assign roles to different permission and users to different permissions.
As a result the user will be entitled to some of the permissions via roles.
Finally, You'll have to use `permission aliases` in your code like below:

Inside Blade:

```php
@can('permission_alias')
<h2>This part is visible only to the users having permission_alias permission
@endcan

another way

@if(\Gate::allows('super_admin_task'))
<h2>Super admin task</h2>
@endif
```

Inside Controller

```php
if(\Gate::denies('super_admin_task')) // here super_admin_task is a permission alias
{
	return Redirect::to('/');
}
```

## Official Laravel Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
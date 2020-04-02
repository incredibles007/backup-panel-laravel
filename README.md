# Laravel Backup Panel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://packagist.org/packages/pavel-mironchik/laravel-backup-panel)
[![Build Status](https://img.shields.io/travis/pavel-mironchik/laravel-backup-panel/master.svg?style=flat-square)](https://travis-ci.org/pavel-mironchik/laravel-backup-panel)
[![Quality Score](https://img.shields.io/scrutinizer/g/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://scrutinizer-ci.com/g/pavel-mironchik/laravel-backup-panel)
[![StyleCI](https://github.styleci.io/repos/231844000/shield?branch=master)](https://github.styleci.io/repos/231844000)
[![Total Downloads](https://img.shields.io/packagist/dt/pavel-mironchik/laravel-backup-panel.svg?style=flat-square)](https://packagist.org/packages/pavel-mironchik/laravel-backup-panel)

Laravel Backup Panel provides a dashboard for [spatie/laravel-backup](https://github.com/spatie/laravel-backup) package.
It lets you:
- create a backup (full | only database | only files)
- check the health of your backups
- list all backups
- download a backup
- delete a backup
- monitor used disk storage

![Screenshot](https://i.imgur.com/jrqTPuJ.png)

It resembles the look and functionality of another Spatie package: [spatie/nova-backup-tool](https://github.com/spatie/nova-backup-tool).
This was done on purpose, so users can easily migrate from one to another.
Only it doesn't use polling.
_A "real-time" updates of a backups list isn't such a necessarily thing and an intensive polling can cause unexpected charges if you use services that require to pay per API requests, such as Google Cloud Storage.
Also, some users reported about hitting a rate limit of Dropbox API._

## Requirements

Make sure you meet [the requirements for installing spatie/laravel-backup](https://docs.spatie.be/laravel-backup/v6/requirements).

## Installation

First you must install [spatie/laravel-backup](https://docs.spatie.be/laravel-backup) into your Laravel app. 
The installation instructions are [here](https://docs.spatie.be/laravel-backup/v6/installation-and-setup). 
When successful, running `php artisan backup:run` on the terminal should create a backup and `php artisan backup:list` should return a list with an overview of all backup disks.

You may use composer to install Laravel Backup Panel into your project:

```bash
$ composer require pavel-mironchik/laravel-backup-panel
```

After installing, publish it resources using provided Artisan command:

```bash
$ php artisan laravel-backup-panel:install
```

This will place assets into `public/laravel_backup_panel` directory, add config file `config/laravel_backup_panel.php`, and register service provider `app/Providers/LaravelBackupPanelServiceProvider.php`.

### Upgrading

When upgrading the package, do not forget to re-publish assets:

```bash
$ php artisan vendor:publish --tag=laravel-backup-panel-assets --force
```

## Configuration

Laravel Backup Panel exposes a dashboard at `/backup`. Change it in `config/laravel_backup_panel.php` file:

```php
'path' => 'backup',
```

Sometimes you don't want to run backup jobs on the same queue as user actions and things that is more time critical. 
Specify your desired queue name in `config/laravel_backup_panel.php` file:

```php
'queue' => 'dedicated_low_priority_queue',
```

By default, you will only be able to access the dashboard in the `local` environment. 
To change that, modify authorization gate in the `app/Providers/LaravelBackupPanelServiceProvider.php`:

```php
/**
 * Register the Laravel Backup Panel gate.
 *
 * This gate determines who can access Laravel Backup Panel in non-local environments.
 *
 * @return void
 */
protected function gate()
{
    Gate::define('viewLaravelBackupPanel', function ($user) {
        return in_array($user->email, [
            'admin@your-site.com',
        ]);
    });
}
```

## Usage

Open `http://your-site/backup`. You'll see a dashboard and controls to use.

### Testing

```bash
$ composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Development

Make sure you've prepared a dummy Laravel application to test the package in, because assets will be copied there by this line in `webpack.mix.js`:

```js
.copy('public/vendor/laravel_backup_panel', '../laravel-backup-panel-test/public/vendor/laravel_backup_panel');
```

### Security

If you discover any security related issues, please email mironchikpavel@gmail.com instead of using the issue tracker.

## Support

If you like this package, consider supporting it. You can use this in such ways:
1. If you don't have a Digital Ocean account yet - use this link https://m.do.co/c/d9cd33e44510 to register one. You will get $100 in credit over 60 days, and once you spent $25 - I will get $25 too. This will cover the cost of hosting my nonprofit open-source projects there.
1. If you have some Laravel/Vue project/work to be done, then contact me - mironchikpavel@gmail.com. I work as a freelancer (mostly at UpWork), and such a project can pay my bills.

And any other help will be appreciated.

## Credits

- [Pavel Mironchik](https://github.com/pavel-mironchik)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

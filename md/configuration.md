# Configuration

The package ships with a small config file at `config/extended-commands.php`.

## Publishing the config

```bash
php artisan vendor:publish --tag="laravel-extended-commands-config"
```

## Contents

The config is currently empty — there is nothing to configure yet:

```php
<?php

declare(strict_types=1);

// config for MrPunyapal/LaravelExtendedCommands
return [

];
```

Once the file is published to `config/extended-commands.php`, you can adjust it just like any other Laravel config file. If you do not publish it, the package falls back to its own defaults, so nothing breaks either way.

# Simple notification system for Laravel 4

---

* Extends Laravel MessageBag class.
* Custom template support (comes with Bootstrap3, Bootstrap2 and Foundation5 template).
* Blade string like template.
* Custom groups.
* Formats and sorting for groups and messages.
* Instant and flashable notifications...

---

## Installation

Application specific modules in Laravel 4 can be enabled by adding the following to your **"composer.json"** file:

    "spajz/msg": "dev-master"

Then composer update.

Add a new provider to your providers list in **"app/config/app.php"**:

    'Spajz\Msg\MsgServiceProvider',

Also add a new alias to aliases list in **"app/config/app.php"**:

    'Msg' => 'Spajz\Msg\Facades\Msg',

## Configuration

If you want to edit default config file, just publish it to your app folder.

    php artisan config:publish spajz/msg

## Usage

### Add default messages

```php
// Default groups
Msg::success('Success message');
Msg::error('Error message');
Msg::danger('Danger message');
Msg::info('Info message');
Msg::warning('Warning message');

// Multiple messages
Msg::info(array('First message', 'Second'));
```

### Custom groups

You can add custom groups to config, and then use them like default groups.

```php
// Custom groups
Msg::exit('Exit message');
Msg::break('Break message');

// Custom key
Msg::custom('bar', $message, 'Bar group message format :message');
Msg::custom('foo', array($foo, $bar, 'Some text'), 'Format :message');
```

### Show messages

```php
// Show all messages from the previous request
Msg::show();

// Only info messages
Msg::showInfo();

// With message format
Msg::showInfo($foo, ':key message: :message!');

// Show all messages in same request
Msg::instant();

// Only info with format
Msg::showInfoInstant($foo, ':key message: :message!');

// Custom group with format
Msg::showExitInstant($bar, ':key message: :message!');
```

### Templates

```php
// Add default template for all groups
Msg::setTemplate('myTemplate');

// Assign template to the specific group
Msg::setTemplates('info', 'myInfoTemplate');

// Assign Laravel Blade string like template
$blade = '<div class="box-{{ $key }}">';
$blade .= '@foreach ($messages as $message)';
$blade .= '<b>{{ $message }}</b>';
$blade .= '@endforeach';
$blade .= '</div>';

Msg::setTemplates('info', $blade, true);

// Delete all templates
Msg::deleteTemplates();

// Delete specific group template
Msg::deleteTemplates('danger');

```

### Examples

```php
// Add and show message instantly
echo Msg::danger('Something is wrong')->showDangerInstant();

// Set group format
Msg::setGroupFormat('info', '<b>:message</b>');

// Get raw messages data
Msg::getMesasges();

// Set display mode single or group
Msg::setDisplayMode('single');
```


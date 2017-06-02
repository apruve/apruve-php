# apruve/apruve-php

Innovation in the world of B2B Payments. apruve-php is a library to help integrate apruve.js into your website.

The easiest way to learn how to use this libarary is to grab a clone of our [PHP Demo Store](https://github.com/apruve/apruve-php-demo) project. You can have a demo store up and running in a few minutes.

For an overview of the Apruve integration APIs, see our [Developer Docs](https://docs.apruve.com)

## Issues

Please use [Github issues](https://github.com/apruve/apruve-php/issues) to request features or report bugs.

## Installation

### Composer

Add this require to your `composer.json`:

    "require": {
      "apruve/apruve-php": "~1.2"
    }

**NOTE**: Be sure to update the version as you update the version of apruve-php

Then run `composer install`:

    $ composer install

## Testing

    $ vendor/bin/phpunit tests/

If you'd like to contribute, use the watchr ruby gem to assist.

    $ gem install watchr
    $ watchr ./autotest_watchr.rb


## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Write your code **and tests**
4. Ensure all [tests](#testing) still pass
5. Commit your changes (`git commit -am 'Add some feature'`)
6. Push to the branch (`git push origin my-new-feature`)
7. Create new pull request

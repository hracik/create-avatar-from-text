# Create avatar from text

Create custom avatar from text

## Getting Started

These instructions will get you a copy of the project up and running

### Installing

Install with Composer

```
composer require hracik/php-create-avatar-from-text
```
### Usage

If you want to display text (*text-display* is true by default), but you do not provide both *text-color*, *background-color*, then you should specify option *color-scheme* otherwise text and background will have same color, so text will be invisible.

**Options**:
* size - default *100*
* background-color
* color-scheme - possible values *light*, *dark*
* text-display - default *true*
* text-length - default *2*
* text-case - possible values *upper*, *lower*, *upper-first*, *lower-first*
* text-modification - possible values *initials*, *pseudo*
* text-color
* font-size
* font-weight

```PHP
use Hracik\CreateAvatarFromText;

$options = [
    'size' => 64, 
    'text-case' => 'upper', 
    'text-modification' => 'initials', 
    'font-weight' => 'bold', 
    'color-scheme' => 'light',
];
$svg = CreateAvatarFromText::($string, $options);
```
### Examples

![](examples/1.svg)
![](examples/2.svg)
![](examples/3.svg)

See [examples](https://github.com/hracik/php-create-avatar-from-text/examples).

## Running the tests

Run
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```   
For Windows platforms
```
./vendor/bin/phpunit.bat --bootstrap vendor/autoload.php tests
```

## Built With

* [PHPUnit](https://phpunit.de/) - The PHP Testing Framework
* [Symfony The OptionsResolver Component](https://symfony.com/doc/current/components/options_resolver.html)

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/hracik/php-create-avatar-from-text/tags). 

## Authors

* **Andrej Lahucky** - *Initial work* - [Hracik](https://github.com/hracik)

See also the list of [contributors](https://github.com/hracik/php-create-avatar-from-text/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Acknowledgments

* PurpleBooth


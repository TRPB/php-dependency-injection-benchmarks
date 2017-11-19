# DI Container Benchmarks

Containers currently tested are:
- [Aura.Di](ttps://github.com/auraphp/Aura.Di)
- [Auryn](https://github.com/rdlowrey/auryn)
- [Chernozem](https://github.com/pyrsmk/Chernozem)
- [Di52](https://github.com/lucatume/di52)
- [Dice](http://r.je/dice.html)
- [DiMaria](https://github.com/dangerousdan/dimaria)
- [Joomla DI](https://github.com/joomla-framework/di)
- [Laravel](https://github.com/illuminate/container)
- [Leage](https://github.com/thephpleague/container)
- [Nette](https://github.com/nette/di)
- [Njasm](https://github.com/njasm/container)
- [Phalcon](https://docs.phalconphp.com/en/3.2/di)
- [PHP-DI](http://php-di.org/)
- [Pimple](http://pimple.sensiolabs.org)
- [Slince DI](https://github.com/slince/di)
- [Symfony DI](https://github.com/symfony/dependency-injection)
- [Unbox](https://github.com/mindplay-dk/unbox)
- [Yii2-di](http://www.yiiframework.com/doc-2.0/guide-concept-di-container.html)
- [Zend\Di](https://github.com/zendframework/zend-di)
- [Zend\Servicemanager](https://github.com/zendframework/zend-servicemanager)


**Ray.Di does not seem to work without annotations. Requiring the classes to be coupled to the container goes against the spirit of DI and as such is not tested here** 

**To use phalcon you must have the phalcon.so installed. You may need to edit php-phalcon.ini and adjust the extension dir/path to phalcon.so**

# Usage

1a) Clone this repo and Run `composer install`
or
1b) Install via composer

2) Run `php test1-5_runner.php` and `php test6_runner.php` (These will take some time!)

3) Open the files `test1-5_results.html` and `test6_results.html`

# Results

Test results are viewable here:

[Tests 1 - 5](https://rawgit.com/TomBZombie/php-dependency-injection-benchmarks/master/test1-5_results.html)

[Test 6](https://rawgit.com/TomBZombie/php-dependency-injection-benchmarks/master/test6_results.html)

Please note: These results are representitive and the numbers will change depending on the processing power of the computer they're run on. However, the % differences between the containers should remain roughly the same.

## Tests

### Test 1

Test 1 tests the total time for the containers to construct a single object repeatedly, including the time it takes for the containers to autoload their files.

### Test 2

Test 2 is the same as test one, only autoload time is not included. If there is a big difference between the results for test 1 and test 2 for a container, the container is loading a lot of files

### Test 3

Creation of a deep object graph. This test measures the time it takes the container to construct a 10 level deep object graph repeatedly. This is the longest test and the truest indicator of raw performance although this does not include the DI container set up time. See test 6 for a real-world statistic.

### Test 4

This is a test of how quickly the same object (service) can be repeatedly requested from the container

### Test 5

Test 5 repeatedly has the container construct an object and inject a service

### Test 6

*Not all containers are currently set up for this test.*

This test is the most useful for the faster containers, it measures scalability by measuring the entire script execution time for the PHP process to launch, construct/configure the container and then have the container construct a specified number of objects. Fast containers with a slow startup time will score worse with fewer objects but improve in the rankings as the number of objects is increased. Slower containers with fast startup times will rank highly with fewer objects but will lose out to faster containers once the number of objects gets high enough.


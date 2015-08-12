# DI Container Benchmarks

Containers currently tested are:
- Aura
- Auryn
- Dice
- Laravel
- Leage
- Njasm
- Phalcon
- PHP-DI
- Pimple
- Symfony DI
- Zend\Di
- Zend\Servicemanager


**Nette is currently disabled due to not working as advertised. It fails to load Nette\DI\ContainerLoader which prevents any tests being done**

**Ray.Di does not seem to work without annotations. Requiring the classes to be coupled to the container goes against the spirit of DI and as such is not tested here** 

**To use phalcon you must have the phalcon.so installed. You may need to edit php-phalcon.ini and adjust the extension dir/path to phalcon.so**

# Usage

1a) Clone this repo and Run `composer install`
or
1b) Install via composer

3) Run `php test1-5_runner.php` and `php test6_runner.php` (These will take some time!)
4) Open the files `test1-5_results.html` and `test6_results.html`


# Tests

## Test 1

Test 1 tests the total time for the containers to construct a single object repeatedly, including the time it takes for the containers to autoload their files.

## Test 2

Test 2 is the same as test one, only autoload time is not included. If there is a big difference between the results for test 1 and test 2 for a container, the container is loading a lot of files

## Test 3

Creation of a deep object graph. This test measures the time it takes the container to construct a 10 level deep object graph repeatedly. This is the longest test and the truest indicator of raw performance.

## Test 4

This is a test of how quickly the same object (service) can be repeatedly requested from the container

## Test 5

Test 5 repeatedly has the container construct and object and inject a service

## Test 6

*Not all containers are currently set up for this test.*

This test is the most useful for the faster containers, it measures scalability by measuring the entire script execution time for the PHP process to launch, construct/configure the container and thenhave the container construct a specified number of objects. Fast containers with a slow startup time will score worse with fewer objects but improve in the rankings as the number of objects is increased. Slower containers with fast startup times will rank highly with fewer objects but will lose out to faster containers once the number of objects gets high enough.



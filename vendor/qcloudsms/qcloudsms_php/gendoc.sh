#!/bin/bash

composer install
php ./vendor/sami/sami/sami.php update ./sami_config.php -v

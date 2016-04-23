#!/bin/bash

set -ex

# Run the unit tests
vendor/bin/phpunit

# BEHAT_TAGS=$(php ci/behat-tags.php)

# Run the functional tests
vendor/bin/behat --format progress

# Run CodeSniffer
# ./codesniffer/scripts/phpcs --standard=./ci/ php/
#!/usr/bin/env bash

set -ex

PACKAGE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/../ && pwd )"
WP_CLI_BIN_DIR="/tmp/wp-cli-phar"
WP_CLI_CONFIG_PATH="/tmp/wp-cli-phar/config.yml"

install_wp_cli() {

	# the Behat test suite will pick up the executable found in $WP_CLI_BIN_DIR
	mkdir -p $WP_CLI_BIN_DIR
	wget https://github.com/wp-cli/builds/raw/gh-pages/phar/wp-cli-nightly.phar
	mv wp-cli-nightly.phar $WP_CLI_BIN_DIR/wp
	chmod +x $WP_CLI_BIN_DIR/wp

}

set_package_context() {

	touch $WP_CLI_CONFIG_PATH
	printf 'require:' > $WP_CLI_CONFIG_PATH
	requires=$(php $PACKAGE_DIR/utils/get-package-require-from-composer.php composer.json)
	for require in "${requires[@]}"
	do
		printf "\n%2s-%1s$PACKAGE_DIR/$require" >> $WP_CLI_CONFIG_PATH
	done
	printf "\n" >> $WP_CLI_CONFIG_PATH

}

download_behat() {

	cd $PACKAGE_DIR
	curl -s https://getcomposer.org/installer | php
	php composer.phar require --dev behat/behat='~2.5'

}

install_db() {
	mysql -e 'CREATE DATABASE IF NOT EXISTS wp_cli_test;' -uroot
	mysql -e 'GRANT ALL PRIVILEGES ON wp_cli_test.* TO "wp_cli_test"@"localhost" IDENTIFIED BY "password1"' -uroot
}

install_wp_cli
# set_package_context
download_behat
install_db
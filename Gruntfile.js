module.exports = function( grunt ) {

	'use strict';
	var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		addtextdomain: {
			options: {
				textdomain: 'rest-api-toolbox',
			},
			target: {
				files: {
					src: [ '*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**' ]
				}
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		clean:  {
			wp: [ "release" ]
		},

		phplint: {
			options: {
				limit: 10,
				stdout: true,
				stderr: true
			},
			files: [
				'admin/**/*.php',
				'includes/*.php',
				'*.php'
			]
		},

		phpunit: {
			'default': {
				cmd: 'phpunit',
				args: ['-c', 'phpunit.xml.dist']
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					mainFile: 'rest-api-toolbox.php',
					potFilename: 'rest-api-toolbox.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},

		copy:   {
			// create release for WordPress repository
			wp: {
				files: [

					// directories
					{ expand: true, src: ['admin/**'], dest: 'release/rest-api-toolbox/' },
					{ expand: true, src: ['includes/**'], dest: 'release/rest-api-toolbox/' },
					{ expand: true, src: ['languages/**'], dest: 'release/rest-api-toolbox/' },

					// root dir files
					{
						expand: true,
						src: [
							'*.php',
							'readme.txt',
							],
						dest: 'release/rest-api-toolbox/'
					}

				]
			} // wp

		}

	} );

	require('phplint').gruntPlugin(grunt);

	var tasks = [
		'grunt-contrib-clean',
		'grunt-contrib-copy',
		'grunt-wp-i18n',
		'grunt-wp-readme-to-markdown'
		];

	for	( var i = 0; i < tasks.length; i++ ) {
		grunt.loadNpmTasks( tasks[ i ] );
	};


	grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

	grunt.registerTask( 'test', [ 'phplint', 'phpunit' ] );

	// create release for WordPress repository
	grunt.registerTask( 'wp', [ 'clean', 'copy' ] );

	grunt.registerMultiTask('phpunit', 'Runs PHPUnit tests', function() {
		grunt.util.spawn({
			cmd: this.data.cmd,
			args: this.data.args,
			opts: {stdio: 'inherit'}
		}, this.async());
	});

	grunt.util.linefeed = '\n';

};

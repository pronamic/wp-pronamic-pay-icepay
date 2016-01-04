module.exports = function( grunt ) {
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig( {
		// Package
		pkg: grunt.file.readJSON( 'package.json' ),

		// JSHint
		jshint: {
			all: [ 'Gruntfile.js', 'composer.json', 'package.json' ]
		},

		// PHP Code Sniffer
		phpcs: {
			application: {
				src: [
					'src/**/*.php',
					'tests/**/*.php'
				],
			},
			options: {
				bin: 'vendor/bin/phpcs',
				standard: 'phpcs.ruleset.xml',
				showSniffCodes: true
			}
		},

		// PHPLint
		phplint: {
			options: {
				phpArgs: {
					'-lf': null
				}
			},
			all: [
				'src/**/*.php',
				'tests/**/*.php'
			]
		},

		// PHP Mess Detector
		phpmd: {
			application: {
				dir: 'src'
			},
			options: {
				reportFormat: 'xml',
				rulesets: 'phpmd.ruleset.xml'
			}
		},
	} );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpmd', 'phpcs' ] );
};

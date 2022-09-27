/**
 * Eckode Utils
 *
 * @package Eckode
 */

/**
 * Modules
 */
// Node Modules.
const {
	open,
	unlink,
	writeFile,
	existsSync,
} = require( 'fs' );
const chalk = require( 'chalk' );
const {log} = console;
// Internal.
const themeConfig = require( '../../theme.config' );
// Node Processes
const {
	env: {
		npm_lifecycle_event, // eslint-disable-line camelcase
	}
} = process;

const utils = {
	/**
	 * Helper to grab config from theme.config.js
	 */
	themeConfig( key = null ) {
		if ( null === key ) {
			return themeConfig;
		}
		return themeConfig[key];
	},

	/**
	 *
	 */
	devConfig() {
		return new Promise( ( resolve, reject ) => {

			log( chalk.underline.cyanBright( `Eckode Theme Configuration: ` ) );

			/**
			 * When user kills process (CTRL c) remove .wds file
			 */
			for ( const sig of ['SIGINT', 'SIGTERM', 'exit'] ) {
				process.on( sig, () => {
					if ( process ) {
						if ( existsSync( './.wds' ) ) {
							unlink( './.wds', () => {
								// Quietly delete .wds file
							} );
						}
					} else {
						log( 'No such process found!' );
					}
				} );
			}

			/** Add .wds (Webpack Dev Server) file when webpack-dev-server starts */
			open( './.wds', 'r', ( err ) => {
				if ( err ) {
					log( chalk.cyanBright( '+' ), chalk.grey.bold( '｢theme｣' ), 'Writing .wds file. (async)' );
					writeFile( './.wds', '', ( err ) => {
						if ( err ) {
							reject( err );
						}
						log( chalk.cyanBright( '+' ), chalk.grey.bold( '｢theme｣' ), 'Resolved. ".wds" file created. \n' );
						resolve();
					} );
				}
			} );
		} );
	},

	/**
	 * Is current process SSL
	 */
	isSsl() {
		// eslint-disable-next-line camelcase
		return !!~npm_lifecycle_event.indexOf( ':s' );
	},
};

module.exports = utils;

#!/usr/bin/env node

/**
 * Node dependencies
 */
const { resolve, dirname } = require( 'path' );

/**
 * External dependencies
 */
const archiver = require( 'archiver' );
const fse = require( 'fs-extra' );
const sanitizeFilename = require( 'sanitize-filename' );

/**
 * Retrieves the plugin version from the plugin's file header.
 *
 * @return {string} Plugin version.
 */
function getPluginVersion() {
	const path = resolve( __dirname, '../plugin.php' );
	const file = fse.readFileSync( path, { encoding: 'utf-8' } );
	return file.match( /Version:\s+([0-9\.\w-]+)/ )[ 1 ];
}

/**
 * Generates the filename to use for the resulting release zip file.
 *
 * @return {string} Sanitized release zip file name.
 */
function generateFilename() {
	const version = getPluginVersion();

	return sanitizeFilename( `wp-reverse-proxy.v${ version }.zip` );
}

/**
 * Makes a new release.
 *
 * @return {Promise} A promise instance.
 */
async function makeRelease() {
	const root = dirname( __dirname );
	const releaseDir = resolve( root, 'release' );
	const pluginDir = resolve( releaseDir, 'wp-reverse-proxy' );
	const filename = resolve( root, generateFilename() );

	console.log( `Creating ${ filename }` ); // eslint-disable-line no-console

	const cp = path => fse.copy( resolve( root, path ), resolve( pluginDir, path ) );

	// Remove release directory and release archive if it exists.
	await Promise.all( [ fse.remove( releaseDir ), fse.remove( filename ) ] );

	// Create directory for release.
	await fse.ensureDir( pluginDir );

	// Copy files to the release directory.
	const fileCopyPromises = [
		cp( 'readme.md' ),
		cp( 'readme.txt' ),
		cp( 'plugin.php' ),
		cp( 'composer.json' ),
		cp( 'assets/dist' ),
		cp( 'assets/src' ),
		cp( 'inc' ),
		cp( 'vendor' ),
	];
	await Promise.all( fileCopyPromises );

	// Archive the release directory.
	const archive = archiver( 'zip', { zlib: { level: 9 } } );
	archive.pipe( fse.createWriteStream( filename ) );
	archive.directory( pluginDir, 'wp-reverse-proxy', {} );
	await archive.finalize();

	// Remove release folder at the end.
	await fse.remove( releaseDir );
}

makeRelease().catch( error => {
	console.error( error ); // eslint-disable-line no-console
	process.exit( 1 );
} );

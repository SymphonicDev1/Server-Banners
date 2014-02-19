<?php
//------------------------------------------------------------------------------------------------------------+
//
// Name: TMW3.php
//
// Description: Code to parse TeknoMW3 servers
// Initial author: momo5502 <MauriceHeumann@googlemail.com>
//
//------------------------------------------------------------------------------------------------------------+

if ( !defined( "BANNER_CALL" ) ) {
	exit( "DIRECT ACCESS NOT ALLOWED" );
}

//------------------------------------------------------------------------------------------------------------+
//Query COD server - main function!

function query( $ip, $port )
{
	$cmd = "\x4C\x4F\x4F\x50\x02\x67\x65\x74\x73\x74\x61\x74\x75\x73\x00";
	
	return parseQueryData( getQueryData( $ip, $port, $cmd ), $ip, $port, $cmd );
}

//------------------------------------------------------------------------------------------------------------+
//Open socket connection, send and receive information, return it & close socket again

function getQueryData( $ip, $port, $send )
{
	$server  = "udp://" . $ip;
	$connect = @fsockopen( $server, $port, $errno, $errstr, 1 );

	if ( !$connect )
		return "-1";
	
	
	else {
		fwrite( $connect, $send );
		stream_set_timeout( $connect, 2 );
		$output = fread( $connect, 8192 );
		$info   = stream_get_meta_data( $connect );
		fclose( $connect );
		
		if ( !$output || !isset( $output ) || $output == "" || $info[ 'timed_out' ] )
				return "-1";
		else
			return $output;
	}
}

//------------------------------------------------------------------------------------------------------------+
//Parse the query data and return it as array

function parseQueryData( $input, $ip, $port, $cmd )
{
	if ( $input == "-1" )
		return getErr( $ip, $port );
	
	if ( !strpos( $input, "hostname" ) )
		$hostname = "Unknown Hostname";
	
	$data  = explode( "\\", $input );
	$_data = array( );
	
	for ( $i = 0; $i < count( $data ); $i++ ) {
		if ( $i % 2 == 0 ) {
			$_data[ $data[ $i - 1 ] ] = $data[ $i ];
		}
	}
	
	// Too lazy to figure out what modulo to use...
	if( !isSet($_data[ "sv_maxclients" ]) )
	{
		$_data = array( );
	
		for ( $i = 0; $i < count( $data ); $i++ ) {
			if ( $i % 2 == 1 ) {
				$_data[ $data[ $i - 1 ] ] = $data[ $i ];
			}
		}
	}
	
	$gametype   = $_data[ "g_gametype" ];
	$maxplayers = $_data[ "sv_maxclients" ];
	$mapname    = $_data[ "mapname" ];
	$protocol   = "TMW3";
	$hostname   = $_data[ "sv_hostname" ];
	
	$unclean = $hostname;
	
	StripColors( $hostname );
	StripColors( $gametype );
	
	$temp = explode( "\n", $input );
	$players = count($temp) - 2;
	
	//Put information into an array
	$data = array(
		 "value" => 1,
		"hostname" => $hostname,
		"gametype" => $gametype,
		"protocol" => $protocol,
		"clients" => $players,
		"maxclients" => $maxplayers,
		"mapname" => $mapname,
		"server" => $ip . ":" . $port,
		"unclean" => $unclean,
		"response" => $input,
		"isW2" => false
	);
	
	
	return $data;
}

//------------------------------------------------------------------------------------------------------------+
//Remove color tags

function StripColors( &$var )
{
	for ( $i = 0; $i < 10; $i++ )
		$var = str_replace( "^{$i}", "", $var );
	
	$var = str_replace( "^:", "", $var );
	$var = str_replace( "^;", "", $var );
}

//------------------------------------------------------------------------------------------------------------+
?>
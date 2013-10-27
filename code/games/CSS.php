<?php
//------------------------------------------------------------------------------------------------------------+
//
// Name: CSS.php
//
// Description: Code to query Counter Strike: Source servers
// Initial author: momo5502 <MauriceHeumann@googlemail.com>
// Note: Query algorithm by HSFighter!
//
//------------------------------------------------------------------------------------------------------------+

if ( !defined( "BANNER_CALL" ) ) {
	exit( "DIRECT ACCESS NOT ALLOWED" );
}

//------------------------------------------------------------------------------------------------------------+
//Query CSS server - main function!

function query( $ip, $port )
{
	include("dependencies/HL.serverstatus.class.php");
	
	
	$query = new HLServerAbfrage;
	$query -> hlserver($ip.':'.$port);
	$infos = $query->infos();
	
	if( isSet($infos["name"]) && $infos["name"] != "" )
	{
		$data = $infos;
		$data[ "protocol" ]   = "CSS";
		$data[ "value" ]      = 1;
		$data[ "server" ]     = $ip . ":" . $port;
		$data[ "response" ]   = "-";
		$data[ "hostname" ]   = $infos["name"];
		$data[ "clients" ]    = $infos["players"];
		$data[ "maxclients" ] = $infos["maxplayers"];
		$data[ "gametype" ]   = "CSS";
		$data[ "mapname" ]    = $infos["map"];
		$data[ "unclean" ]    = $data[ "hostname" ];
	}
	else
		$data = getErr( $ip, $port );
		
	return $data;
}

//------------------------------------------------------------------------------------------------------------+
?>
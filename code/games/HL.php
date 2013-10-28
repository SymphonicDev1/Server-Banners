<?php
//------------------------------------------------------------------------------------------------------------+
//
// Name: HL.php
//
// Description: Code to query Half Life servers
// Initial author: momo5502 <MauriceHeumann@googlemail.com>
// Note: Query algorithm by HSFighter!
//
//------------------------------------------------------------------------------------------------------------+

if ( !defined( "BANNER_CALL" ) ) {
	exit( "DIRECT ACCESS NOT ALLOWED" );
}

//------------------------------------------------------------------------------------------------------------+
//Query HL server - main function!

function query( $ip, $port )
{
	include("dependencies/HL.serverstatus.class.php");
	
	
	$query = new HLServerAbfrage;
	$query -> hlserver($ip.':'.$port);
	$infos = $query->infos();
	
	if( isSet($infos["name"]) && $infos["name"] != "" )
	{
		$data = $infos;
		$game = getHLGame( $infos["directory"] );
		$data[ "protocol" ]   = $game;
		$data[ "value" ]      = 1;
		$data[ "server" ]     = $ip . ":" . $port;
		$data[ "response" ]   = "-";
		$data[ "hostname" ]   = $infos["name"];
		$data[ "clients" ]    = $infos["players"];
		$data[ "maxclients" ] = $infos["maxplayers"];
		$data[ "gametype" ]   = $game;
		$data[ "mapname" ]    = $infos["map"];
		$data[ "unclean" ]    = $data[ "hostname" ];
	}
	else
		$data = getErr( $ip, $port );
		
	return $data;
}

//------------------------------------------------------------------------------------------------------------+
// Get game based on derectory query
// TODO: Do this with DB query

function getHLGame( $dir )
{
	switch( $dir )
	{
		case "cstrike":
			return "CSS";
			break;
			
		case "left4dead":
			return "L4D";
			break;
	}
	
	return false;
}

//------------------------------------------------------------------------------------------------------------+
?>
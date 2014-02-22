#!/bin/bash

#$1 == SERVER_ID
#	if empty fresh install
#$2 == BARTLBY USER
#$3 == BARTLBY PW

BARTLBY_USER=$2;
BARTLBY_PW=$3;
SERVER_ID=$1;
THIS_ARCH=$(uname -m);

#check if system is able to handle agent :)
# check if dialog is installed, check if curl is installed

function bartlby_agent_install {
	#add user bartlby
	#add inetd services /if not already
	#create folders
	#download agent_binarys
	#download agent_config
	#restart inetd
	echo "Fresh install";
}

function bartlby_agent_sync {
	#sha1 local plugins
	#get remote sha1 list
	#download diff

	echo "Sync it! (server_id ${SERVER_ID})";
}


type -a dialog 2>&1>>/dev/null
EX=$?;
if [ $EX != 0 ];
then
	echo "dialog is not installed ready to install it? (y/n)"
	read r;
	if [ "$r" = "y" ];
	then
		apt-get install dialog;
	else
		echo "OK fix it and re-run this script";
	fi;
fi;


type -a curl 2>&1>>/dev/null
EX=$?;
if [ $EX != 0 ];
then
	dialog --yesno "curl is not installed - install it right now?" 0 0
	reply=$?;
	if [ $reply = 0 ];
	then
		clear
		apt-get install curl;
	else
		dialog --msgbox "You skipped installation of curl - hard exit!" 5 50
		clear
	fi;
fi;



#ok so check if agent is already installed
if [ ! -f /opt/bartlby-agent/bartlby_agent ];
then
	bartlby_agent_install;
fi;




if [ "x$SERVER_ID"  = "x" ];
then
	#no server_id specified!
	if [ -f /opt/bartlby-agent/deploy_server_id ];
	then
		SERVER_ID=$(cat /opt/bartlby-agent/deploy_server_id);
	else 
		SERVER_ID="";
	fi;

fi;

if [ "x$SERVER_ID"  = "x" ];
then
	sid=`dialog --inputbox "Enter Server ID" 0 0 "" 3>&1 1>&2 2>&3`
	echo $sid > /opt/bartlby-agent/deploy_server_id;
	SERVER_ID=$(cat /opt/bartlby-agent/deploy_server_id);
	clear;
fi;


if [ "x$BARTLBY_USER"  = "x" ];
then
	#no server_id specified!
	if [ -f /opt/bartlby-agent/deploy_bartlby_user ];
	then
		BARTLBY_USER=$(cat /opt/bartlby-agent/deploy_bartlby_user);
	else 
		BARTLBY_USER="";
	fi;

fi;
if [ "x$BARTLBY_PW"  = "x" ];
then
	#no server_id specified!
	if [ -f /opt/bartlby-agent/deploy_bartlby_pw ];
	then
		BARTLBY_PW=$(cat /opt/bartlby-agent/deploy_bartlby_pw);
	else 
		BARTLBY_PW="";
	fi;

fi;


if [ "x$BARTLBY_USER"  = "x" ];
then
	sid=`dialog --inputbox "Enter Bartlby Username" 0 0 "" 3>&1 1>&2 2>&3`
	echo $sid > /opt/bartlby-agent/deploy_bartlby_user;
	BARTLBY_USER=$(cat /opt/bartlby-agent/deploy_bartlby_user);
	clear;
fi;

if [ "x$BARTLBY_PW"  = "x" ];
then
	sid=`dialog --inputbox "Enter Bartlby Password" 0 0 "" 3>&1 1>&2 2>&3`
	echo $sid > /opt/bartlby-agent/deploy_bartlby_pw;
	BARTLBY_PW=$(cat /opt/bartlby-agent/deploy_bartlby_pw);
	clear;
fi;





bartlby_agent_sync
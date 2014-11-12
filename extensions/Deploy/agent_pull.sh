#!/bin/bash

#$1 == SERVER_ID
#	if empty fresh install
#$2 == BARTLBY USER
#$3 == BARTLBY PW
#$4 == remote bartlby url

BARTLBY_USER=$2;
BARTLBY_PW=$3;
SERVER_ID=$1;
BARTLBY_SESSION="";
THIS_ARCH=$(uname -m);

function bartlby_deploy_settings {

	if [ ! -d /opt/bartlby-agent/ ];
	then
		mkdir -p /opt/bartlby-agent/
	fi;
	if [ ! -d /opt/bartlby-agent/deploy_settings ];
	then
		mkdir /opt/bartlby-agent/deploy_settings
	fi;
	

	if [ "x$SERVER_ID"  = "x" ];
	then
		#no server_id specified!
		if [ -f /opt/bartlby-agent/deploy_settings/server_id ];
		then
			SERVER_ID=$(cat /opt/bartlby-agent/deploy_settings/server_id);
		else 
			SERVER_ID="";
		fi;

	fi;

	if [ "x$SERVER_ID"  = "x" ];
	then
		sid=`dialog --inputbox "Enter Server ID" 0 0 "" 3>&1 1>&2 2>&3`
		echo $sid > /opt/bartlby-agent/deploy_settings/server_id;
		SERVER_ID=$(cat /opt/bartlby-agent/deploy_settings/server_id);
		clear;
	fi;


	if [ "x$BARTLBY_USER"  = "x" ];
	then
		#no server_id specified!
		if [ -f /opt/bartlby-agent/deploy_settings/remote_user ];
		then
			BARTLBY_USER=$(cat /opt/bartlby-agent/deploy_settings/remote_user);
		else 
			BARTLBY_USER="";
		fi;

	fi;
	if [ "x$BARTLBY_PW"  = "x" ];
	then
		#no server_id specified!
		if [ -f /opt/bartlby-agent/deploy_settings/remote_pw ];
		then
			BARTLBY_PW=$(cat /opt/bartlby-agent/deploy_settings/remote_pw);
		else 
			BARTLBY_PW="";
		fi;

	fi;
	if [ "x$BARTLBY_URL"  = "x" ];
	then
		#no server_id specified!
		if [ -f /opt/bartlby-agent/deploy_settings/remote_url ];
		then
			BARTLBY_URL=$(cat /opt/bartlby-agent/deploy_settings/remote_url);
		else 
			BARTLBY_URL="";
		fi;

	fi;

	if [ "x$BARTLBY_URL"  = "x" ];
	then
		sid=`dialog --inputbox "Enter Bartlby URL" 0 0 "http://" 3>&1 1>&2 2>&3`
		echo $sid > /opt/bartlby-agent/deploy_settings/remote_url;
		BARTLBY_URL=$(cat /opt/bartlby-agent/deploy_settings/remote_url);
		clear;
	fi;


	if [ "x$BARTLBY_USER"  = "x" ];
	then
		sid=`dialog --inputbox "Enter Bartlby Username" 0 0 "" 3>&1 1>&2 2>&3`
		echo $sid > /opt/bartlby-agent/deploy_settings/remote_user;
		BARTLBY_USER=$(cat /opt/bartlby-agent/deploy_settings/remote_user);
		clear;
	fi;

	if [ "x$BARTLBY_PW"  = "x" ];
	then
		sid=`dialog --inputbox "Enter Bartlby Password" 0 0 "" 3>&1 1>&2 2>&3`
		sid=$(echo -n $sid|sha1sum|awk '{print $1}');
		echo $sid > /opt/bartlby-agent/deploy_settings/remote_pw;
		BARTLBY_PW=$(cat /opt/bartlby-agent/deploy_settings/remote_pw);
		clear;
	fi;
}
function bartlby_download_agent_conf {
	curl -s --cookie $BARTLBY_SESSION "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php?mode=agentcfg-list&arch=all"|while read fname sha arch; do 
		local_sha=$( sha1sum  /opt/bartlby-agent/${fname}|awk '{print $1}');
		if [ "x$local_sha" != "x${sha}" ];
		then
			curl -s --cookie $BARTLBY_SESSION -o /opt/bartlby-agent/$fname "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php?mode=get-agent-cfg&arch=all&fn=${fname}";
			
		fi;

	done;
}
function bartlby_download_agent_bins {

	curl -s --cookie $BARTLBY_SESSION "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php?mode=agent-list&arch=${THIS_ARCH}"|while read fname sha arch; do 
		local_sha=$( sha1sum  /opt/bartlby-agent/${fname}|awk '{print $1}');
		if [ "x$local_sha" != "x${sha}" ];
		then
			curl -s --cookie $BARTLBY_SESSION -o /opt/bartlby-agent/$fname "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php?mode=get-agent-bin&arch=${THIS_ARCH}&fn=${fname}";
			chmod a+rwx /opt/bartlby-agent/$fname
		fi;

	done;
}
function bartlby_update_sync_time {
	curl -s --cookie $BARTLBY_SESSION "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php&arch=${THIS_ARCH}&mode=update-sync-time&server_id=${SERVER_ID}" 
		

}
function bartlby_download_agent_pull {
	curl -s --cookie $BARTLBY_SESSION "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php&mode=get-agent-pull-list"|while read script_name sha arch; do 
		local_sha=$( sha1sum  /opt/bartlby-agent/${script_name}|awk '{print $1}');
		if [ "x$local_sha" != "x${sha}" ];
		then
			
			curl -s --cookie $BARTLBY_SESSION -o /opt/bartlby-agent/$script_name "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php?mode=get-agent-pull&script_name=${plg_name}";
			chmod a+rwx /opt/bartlby-agent/$script_name

		fi;

	done;
}

function bartlby_download_plugins {
	curl -s --cookie $BARTLBY_SESSION "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php&arch=${THIS_ARCH}&mode=plugin-list&server_id=${SERVER_ID}"|while read plg_name sha arch; do 
		local_sha=$( sha1sum  /opt/bartlby-agent/plugins/${plg_name}|awk '{print $1}');
		if [ "x$local_sha" != "x${sha}" ];
		then
			
			curl -s --cookie $BARTLBY_SESSION -o /opt/bartlby-agent/plugins/$plg_name "${BARTLBY_URL}/extensions_wrap.php?script=Deploy/pull.php?mode=get-plugin&arch=${arch}&plugin=${plg_name}";
			chmod a+rwx /opt/bartlby-agent/plugins/$plg_name

		fi;

	done;
}

#check if system is able to handle agent :)
# check if dialog is installed, check if curl is installed

function bartlby_agent_install {
	#add user bartlby
	useradd bartlby
	update-inetd         --add         'bartlbya\t\tstream\ttcp\tnowait.500\tbartlby\t/opt/bartlby-agent/bartlby_agent\t/opt/bartlby-agent/bartlby.cfg'	
	update-inetd         --add         'bartlbyv\t\tstream\ttcp\tnowait.500\tbartlby\t/opt/bartlby-agent/bartlby_agent_v2\t/opt/bartlby-agent/bartlby.cfg'	

	#check etc services
	grep bartlbya /etc/services 2>&1>>/dev/null
	EX=$?;
	if [ $EX != 0 ];
	then
		echo -e "bartlbya\t 9030/tcp\t #bartlby-agent v1" >> /etc/services
	fi;
	grep bartlbyv /etc/services 2>&1>>/dev/null
	EX=$?;
	if [ $EX != 0 ];
	then
		echo -e "bartlbyv\t 9032/tcp\t #bartlby-agent v2" >> /etc/services
	fi;
	#download agent_binarys
	bartlby_download_agent_bins
	bartlby_download_agent_conf
	#restart inetd
	/etc/init.d/openbsd-inetd restart
	
}
function bartlby_get_ui_session {
	BARTLBY_SESSION=$(curl --silent -i  "${BARTLBY_URL}/login.php" -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' --data "login_username=${BARTLBY_USER}&password=${BARTLBY_PW}&btl_instance_id=0" |grep Cookie|tail -1|awk '{ print $2 }'|sed 's/;//');
	EX=$?;
	if [ $EX != 0 ];
	then
		echo "AUTH FAILED";
		exit;
	fi
}
function bartlby_agent_sync {
	#check agent itself
	#check agent cfg
	bartlby_get_ui_session
	bartlby_download_agent_bins
	bartlby_download_agent_conf
	#get plugin list
	bartlby_download_plugins
	#update pull script
	bartlby_download_agent_pull
	#done update sync time
	bartlby_update_sync_time
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


bartlby_deploy_settings; #fetch configs
#ok so check if agent is already installed
grep bartlby_agent /etc/inetd.conf |grep /opt/bartlby-agent 2>&1>>/dev/null
EX=$?;
if [ $EX !=  0 ];
then
	bartlby_agent_install;
fi;


bartlby_agent_sync
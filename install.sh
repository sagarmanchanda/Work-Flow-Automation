#! /bin/bash

# Shell colors used here.
green='tput setaf 2'
blackbg='tput setab 0'
yellow='tput setaf 3'
red='tput setaf 1'
cyan='tput setaf 6'
nc='tput sgr0'

clear

# Composer related installation.
echo -n "$($red)Enter http proxy (skip if it's already set in the environment):$($nc)"
read proxy

if [ "$proxy" != "" ]
	then export HTTP_PROXY=$proxy
fi
#sudo apt-get update
sudo apt-get install curl
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer install

echo "$($yellow)$($blackbg)[OK]$($nc)"

# DB related installation.

echo -e "\n$($green)WFA creates a unique database for each generated instance.\nTo accomplish this, WFA needs GRANT-level privileges. It is recommended that you supply the root/administrator credentials for this task.\n\nIf you wish to create a new user for WFA to use please assign it appropriate privileges eg:\n\nMySQL:$($cyan)$($blackbg)GRANT ALL ON *.* to '#user'@'localhost' IDENTIFIED BY '#pass' WITH GRANT OPTION$($nc)"

printf '%*s\n' "${COLUMNS:-$(tput cols)}" '' | tr ' ' -


while true
do
	echo -e "\n"
	echo "[$($yellow)Database host$($nc)]"
	echo -e "$($yellow)Description$($nc): Enter the hostname for your MySQL database. For example \"localhost\""
	read -p "> " dbhost
	
	echo -e "\n"
	echo "[$($yellow)Database user$($nc)]"
	echo -e "$($yellow)Description$($nc): Enter details of user. Please not that the user should have grant privileges set. For example \"root\""
	read -p "> " dbuser
	
	echo -e "\n"
	echo "[$($yellow)User password$($nc)]"
	echo -e "$($yellow)Description$($nc): Enter the password for user $dbuser"
	read -p "> " dbpass
	
	# Start with install.php stuff now.
	phpout=$(php install.php $dbhost $dbuser $dbpass)

	if [ $phpout = 0 ]
	then
		echo "$($green)Database successfully created and settings saved!$($nc)"
		break
	elif [ $phpout = 1 ]
	then
		echo "$($red)ERROR: Invalid credentials or mysql not running. Please fix.$($nc)"
	elif [ $phpout = 2 ]
	then
		echo "$($red)ERROR: Unable to write wfa.config. Please check your read write permissions on the WFA module folder.$($nc) (Example: Use sudo chmod or sudo chown)."
	fi
done

echo "$($yellow)$($blackbg)[OK]$($nc)"

# Now go for the login database

printf '%*s\n' "${COLUMNS:-$(tput cols)}" '' | tr ' ' -

read -p "Do you want to use LDAP to configure your login database? [y/n]" confirmldap

if [ "$confirmldap" = "y" ]
then
	echo -e "\n"
	echo "[$($yellow)Database domain$($nc)]"
	echo -e "$($yellow)Description$($nc): Enter the domain for your LDAP configuration. For example \"test.com\""
	read -p "> " ldapdomain

	echo -e "\n"
	echo "[$($yellow)Database dc details$($nc)]"
	echo -e "$($yellow)Description$($nc): Enter dc details for your LDAP configuration. For example if your domain was \"test.com\", your dc details will most probably be \"dc=test,dc=com\""
	read -p "> " ldapdc

	echo -e "$ldapdomain\n$ldapdc" > src/wfa.ldap
fi
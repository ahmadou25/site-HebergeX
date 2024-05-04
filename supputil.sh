#!/bin/bash

username=$1
userdel $username
rm -fr /home/$username

rm /etc/apache2/sites-available/$username.conf
a2dissite $username
perl -ni.orig -e "print unless(/$username IN/)" /etc/bind/db.heberge1.lan
service bind9 reload

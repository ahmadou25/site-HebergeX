#!/bin/bash

# % = username			%2 = password


useradd $1 -md /home/$1
echo $1:$2 | chpasswd
mkdir /home/$1/public_html
touch /home/$1/public_html/index.html
cp /etc/apache2/sites-available/xxxx.conf /etc/apache2/sites-available/$1.conf
sed -i -e "s/xxxx/$1/g" /etc/apache2/sites-available/$1.conf

a2ensite $1.conf
systemctl restart apache2
echo "$1 IN CNAME SrvWeb" >> /etc/bind/db.heberge1.lan

systemctl reload bind9

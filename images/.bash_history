sudo apt update
apt install apache2
systemctl status apache2
mkdir /var/www/app.global-health-diagnostics.com
mkdir /var/www/app.globalhealth-diagnostics.com
vim /var/www/app.globalhealth-diagnostics.com/index.html
chown -R www-data:www-data /var/www/app.globalhealth-diagnostics.com/
apt-get install mariadb-server default-libmysqlclient-dev  screen
sudo mysql_secure_installation
mysql -u root -p
nano /etc/ssh/sshd_config
systemctl restart ssh
apache2ctl configtest
a2ensite app.globalhealth-diagnostics.com
a2enmod rewrite
systemctl restart apache2
sudo apt update
sudo apt install snapd
sudo snap install core
sudo snap refresh core
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot
sudo nano /etc/apache2/sites-available/app.globalhealth-diagnostics.com.conf
sudo apache2ctl configtest
sudo systemctl reload apache2
sudo ufw status
sudo ufw app list
apt install ufw
ufw app list
ufw allow OpenSSH
ufw enable
ufw status
sudo ufw status
sudo ufw allow 'WWW'
sudo ufw status
sudo systemctl status apache2
hostname -I
sudo apt install curl
sudo a2dissite 000-default.conf
sudo apache2ctl configtest
sudo systemctl restart apache2
sudo certbot --apache -d app.globalhealth-diagnostics.com -d www.app.globalhealth-diagnostics.com
sudo certbot --apache -d app.globalhealth-diagnostics.com -d www.app.globalhealth-diagnostics.com
sudo systemctl restart apache2
sudo certbot renew --dry-run
sudo systemctl restart apache2
sudo systemctl restart apache2
sudo systemctl restart apache2
sudo systemctl restart apache2
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo systemctl restart apache2
node -v
uname
ls
du
clear
du
df -h
hostname
lsblk
.
test
node -v
brew
node -v
ls
clear
dir
cd snap
ls
ls
cd cert bot
cd .
cd /
ls
cd var/www/app.globalhealth-diagnostics.com/
ls
lscd .
cd ..
ls
cd /
cd ..
cd /
:
/
cd ..
ls
cd /
clear
exit
ls
cd /
exit
sudo systemctl restart apache2
sudo apache2ctl configtest
sudo apache2ctl configtest
systemctl status apache2.service
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo apache2ctl configtest
sudo systemctl restart apache2
certbot --apache --agree-tos --redirect --hsts --uir --staple-ocsp --email admin@globalhealth-diagnostics.com -d app.globalhealth-diagnostics.com,www.app.globalhealth-diagnostics.com
sudo systemctl restart apache2
chown -R www-data:www-data /var/www/app.globalhealth-diagnostics.com/html/
apache2ctl configtest
a2ensite app.globalhealth-diagnostics.com
a2enmod rewrite
systemctl restart apache2
systemctl restart apache2
systemctl restart apache2
sudo ufw allow in "Apache Full"
sudo ufw status
sudo ufw allow 'Apache Full'
sudo ufw reload
sudo ufw status
sudo ufw allow 443/tcp
sudo ufw reload
sudo apt update
sudo apt install php-mbstring php-zip php-gd
sudo apt-get install wget
lsb_release -a
apt -y install phpmyadmin
sudo mkdir -p /var/lib/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/lib/phpmyadmin
sudo cp /usr/share/phpmyadmin/config.sample.inc.php /usr/share/phpmyadmin/config.inc.php
sudo nano /usr/share/phpmyadmin/config.inc.php
sudo nano /usr/share/phpmyadmin/config.inc.php
sudo mariadb
sudo mariadb < /usr/share/phpmyadmin/sql/create_tables.sql
sudo mariadb
sudo apt install curl
sudo apt install php libapache2-mod-php php-mysql
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo a2enconf phpmyadmin.conf
sudo systemctl reload apache2
sudo nano /etc/apache2/mods-enabled/dir.conf
sudo systemctl reload apache2
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo systemctl restart apache2
sudo nano /usr/share/phpmyadmin/.htaccess
sudo a2enconf phpmyadmin.conf
sudo systemctl reload apache2
sudo systemctl reload apache2
sudo systemctl reload apache2
sudo systemctl reload apache2
sudo systemctl restart apache2
sudo systemctl restart apache2
https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-all-languages.tar.gz
https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-all-languages.tar.gz
wget https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-all-languages.tar.gz
tar xvf phpMyAdmin-5.2.1-all-languages.tar.gz
sudo mv phpMyAdmin-5.2.1-all-languages/ /usr/share/phpmyadmin
sudo mkdir -p /var/lib/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/lib/phpmyadmin
sudo cp /usr/share/phpmyadmin/config.sample.inc.php /usr/share/phpmyadmin/config.inc.php
sudo nano /usr/share/phpmyadmin/config.inc.php
sudo mariadb < /usr/share/phpmyadmin/sql/create_tables.sql
sudo mariadb
sudo systemctl restart apache2
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo a2enconf phpmyadmin.conf
sudo systemctl reload apache2
sudo nano /etc/apache2/mods-enabled/dir.conf
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo nano /usr/share/phpmyadmin/.htaccess
n
sudo apt install php libapache2-mod-php php-mysql
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo nano /usr/share/phpmyadmin/.htaccess
sudo apt-get remove --purge phpmyadmin
sudo apt-get autoremove
sudo apt-get autoclean
sudo rm /etc/apache2/conf-available/phpmyadmin.conf
sudo rm /etc/apache2/conf-enabled/phpmyadmin.conf
sudo rm -rf /etc/phpmyadmin
sudo systemctl reload apache2
systemctl status apache2.service
systemctl status apache2.service
sudo systemctl reload apache2
wget https://files.phpmyadmin.net/phpMyAdmin/5.2.1/phpMyAdmin-5.2.1-all-languages.tar.gz
tar xvf phpMyAdmin-5.2.1-all-languages.tar.gz
sudo mv phpMyAdmin-5.2.1-all-languages/ /usr/share/phpmyadmin
rm -r /usr/share/phpmyadmin
sudo mv phpMyAdmin-5.2.1-all-languages/ /usr/share/phpmyadmin
sudo mkdir -p /var/lib/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/lib/phpmyadmin
sudo cp /usr/share/phpmyadmin/config.sample.inc.php /usr/share/phpmyadmin/config.inc.php
sudo nano /usr/share/phpmyadmin/config.inc.php
sudo nano /usr/share/phpmyadmin/config.inc.php
sudo mariadb < /usr/share/phpmyadmin/sql/create_tables.sql
sudo mariadb
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo a2enconf phpmyadmin.conf
sudo systemctl reload apache2
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo nano /usr/share/phpmyadmin/.htaccess
sudo systemctl status apache2
sudo systemctl status apache2
sudo systemctl status apache2
sudo systemctl reload apache2
sudo systemctl reload apache2
nano /etc/apache2/conf-available/phpmyadmin.conf
sudo systemctl reload apache2
sudo htpasswd -c /usr/share/phpmyadmin/.htpasswd admghd
sudo nano /etc/apache2/conf-available/phpmyadmin.conf
sudo systemctl reload apache2
apt install nodejs
node --version
sudo apt-get install build-essential curl git m4 ruby texinfo libbz2-dev libcurl4-openssl-dev libexpat-dev libncurses-dev zlib1g-dev
brew update
npm run dev
cd /var/www/app.globalhealth-diagnostics.com/html/
npm run dev
nex dev
npm run dev
ls
cd ..
ls
/var/www/app.globalhealth-diagnostics.com
ls
cd /var/www/app.globalhealth-diagnostics.com
ls
cd html/
ls
node -vb
node -v
npx create-next-app globalhealth-record-system
npx create-next-app globalhealth-record-system
ls
npx
npm
-npm
node -v
npm -v
curl
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
cd /
sudo apt-get install build-essential curl git m4 ruby texinfo libbz2-dev libcurl4-openssl-dev libexpat-dev libncurses-dev zlib1g-dev
ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/linuxbrew/go/install)"
$ curl -sL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
$ sudo apt install nodejs -y
clear
$ curl -sL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
clear
$ sudo apt install nodejs -y
clear
$ sudo apt install nodejs -y
sudo apt install nodejs -y
npm -v
node -v
npx http-server
sudo apt-get update
sudo apt-get install nodejs
sudo apt-get install npm
npm -v
npx create-next-app globalhealth-record-system
cd /var/www/app.globalhealth-diagnostics.com/html
ls
npx create-next-app globalhealth-record-system
ls
ls
rm
rmdir globalhealth-record-system/
rmdir globalhealth-record-system/
cd globalhealth-record-system/
npm run dev
npm run build
npm run start
ls
cd ..
ls
npm run dev
git
clear
git
git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git clone git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git clone git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git remote set-url git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git config --global --add safe.directory /var/www/app.globalhealth-diagnostics.com/html
git remote set-url git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
ls
git remote set-url origin  git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git remote set-url origin git@github.com:dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git clone https://github.com/dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
ls
clear
clear
ls
cd app
ls
dir
ls
git
git pull origin main
git clone https://github.com/dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
ls
cd clear
clear
ls
git remote set-url origin git@github.com:dhanmarlanortiz/global-health-diagnostics-ellsectronic-health-record-systems.git
cd ..
git clone https://github.com/dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git clone https://github.com/dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
c;ear
git init
git remote add origin https://github.com/dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
git fetch origin
git checkout -b master --track origin/master
git checkout -b master --track origin/master
git checkout origin/master -ft
git checkout -b master --track main
git checkout -b master --track main
ls
cd app
clear
ls
git checkout master
ls
cd global-health-diagnostics-electronic-health-record-systems/
ls
ls
clear
cd ..
ls
dir
cd ..
ls
rm -rf public
ls
rm -rf node_modules
ls
ls
dir
git status
ls
ls
rm -rf *
ls
ls
ls
git init --bare
ls
cd hooks
ls
ls
cd ..
ls
rm -rf *
ls
git clone https://github.com/dhanmarlanortiz/global-health-diagnostics-electronic-health-record-systems.git
ls
ls
clear
ls
mv /global-health-diagnostics-electronic-health-record-systems/* /
ls
cd global-health-diagnostics-electronic-health-record-systems/
ks
ls
npm run dev
clear
ls
npm run dev
npm -v
npm run build
 cd ..
ls
cd ..
ls
cd html
ls
rm -rf *
ls
npx create-next-app dev
cd dev
npm run dev
npm run build
npm run build
ls
ls
npm run build
ls
dir
clear
dir
ls
npm run build
npm run start
npm i eslint
ls
npm run start
npm run build
npm i lint

npm audit fix --force
npm run build
ls
rm -rf *
lsd
ls
cd ..
rm -rf *
ls
rm -rf .git
ls
ls
npm run dev
npm run build
ls
ls
mysql -u pma -p
ls
npm run build
ls
rm -rf *
cd ..
ls
npx create-next-app html
cd html
rm -rf -- ..?* .[!.]* *
ls
cd ..
npx create-next-app html
npm run dev
cd html
clear
npm run dev
npm run build
php -v
rm -rf -- ..?* .[!.]* *
ls
mysql -u root -p
apt-get install nano -y
nano /etc/mysql/my.cnf
service mysql restart
service mysqld restart
service mariadb restart
ls
cd ..
ls
cd var
ls
cd www
ls
cd app.globalhealth-diagnostics.com/
ls
cd html
ls
find /var/www/app.globalhealth-diagnostics.com/html -type d -exec chmod 755 {} \;
find /var/www/app.globalhealth-diagnostics.com/html -type f -exec chmod 644 {} \;
chown -R www-data:www-data /var/www/app.globalhealth-diagnostics.com/html/*
htop
apt-get install htop
htop

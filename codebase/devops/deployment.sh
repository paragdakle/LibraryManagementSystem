# Deployment script for Database project

#######
## Steps to follow in the deployment
## 1. Install the necessary packages.
## 2. Setup Laravel
## 3. Copy the code base.
## 4. Setup the database.
## 5. Configure logging.
## 6. Configure the server.
#######

#!/bin/bash

SERVER_BASE_PATH="/var/www"
SERVER_PATH="$SERVER_BASE_PATH/LibraryManagementSystem"
SERVER_CONFIG_PATH="$SERVER_PATH/config"
SERVER_LOG_PATH="$SERVER_PATH/storage"
SERVER_DATABASE_CONFIG_PATH="$SERVER_CONFIG_PATH/database.php"
SERVER_APP_CONFIG_PATH="$SERVER_CONFIG_PATH/app.php"
BASEDIR=$(dirname $0)
NGINX_CONFIG_FILENAME="dbproject"
APACHE2_SERVICE_NAME="apache2"
NGINX_DEFAULT_CONFIG_FILE_PATH="/etc/nginx/sites-enabled/default"
NGINX_INIT_FILE="/etc/init.d/nginx"
CONFIG_FILE_DIR="/etc/dbproject"
LOAD_CONFIG_FILENAME="load_configuration.sh"

# update package list to get information of newest versions of packages
function update-package-list {
    if sudo apt-get update; then
        echo "Package list updated successfully!"
    else
        echo "Error while updating package list"
        exit 1
    fi
}

#install basic dependencies
function install-basic-deps {
    if sudo apt-get install -y curl jq; then
        echo "Basic dependencies installed successfully!"
    else
        echo "Error while installing basic dependencies"
        exit 1
    fi
}

#install php5-fpm, php5-cli and required extensions
function install-php {
    if sudo apt-get install -y php5-fpm php5-cli php5-mcrypt php5-mysql php5-curl; then
        echo "Php installed successfully!"
    else
        echo "Error while installing Php"
        exit 1
    fi
}

#install mysql
function install-mysql {
    if sudo apt-get install -y mysql-server; then
        echo "Mysql installed successfully!"
    else
        echo "Error while installing Mysql"
        exit 1
    fi
}

#install nginx
function install-nginx {
    #check if apache2 is already present and started. If yes then first stop apache2.
    if ps ax | grep -v grep | grep $APACHE2_SERVICE_NAME > /dev/null; then
        echo "$SERVICE service running. Stopping apache2..."
        sudo service apache2 stop
    fi
    if sudo apt-get install -y nginx; then
        echo "Nginx installed successfully!"
    else
        echo "Error while installing Nginx"
        exit 1
    fi
}

#install composer
function install-composer {
	if composer --version; then
		echo "Composer is already installed"
	else
		if curl -sS https://getcomposer.org/installer | sudo php5 -- --install-dir=/usr/local/bin --filename=composer; then
			echo "Composer installed successfully!"
		else
			echo "Error while installing composer"
			exit 1
		fi
	fi
}

#install all the framework dependencies
function install-laravel-dependencies {
	if sudo composer install; then
		echo "Framework dependencies installed successfully!"
	else
		echo "Error while installing framework dependencies"
		exit 1
	fi
}

function install-deps {
    echo "Installing dependencies...";
    
    update-package-list
    install-basic-deps
    install-php
    install-mysql
    install-nginx
    install-composer
}

function stop-server {
    echo "Stopping Servers...";
    sudo service nginx stop;
    sudo service php5-fpm stop;
}

#this function will start Subscription Server
function start-server {
    echo "Starting Server..."
    stop-server
    sudo service php5-fpm start
    sudo service nginx start
}

function copy-repo {
    create-config-dir
    if [ ! -d $SERVER_BASE_PATH ]; then
        sudo mkdir $SERVER_BASE_PATH
    fi
    if [ -d $SERVER_PATH ]; then
        sudo rm -fr $SERVER_PATH
    fi
    sudo mkdir $SERVER_PATH
    sudo cp -R $BASEDIR/../* $SERVER_PATH
    sudo cp -R $BASEDIR/../.g* $SERVER_PATH
    sudo cp -R $BASEDIR/../.e* $SERVER_PATH
    sudo chown -R www-data:www-data $SERVER_PATH
    sudo chmod -R 775 $SERVER_LOG_PATH
}

function initialize-database {
    if [ -f $BASEDIR/database_initialization.sql ]; then
        echo "Enter your mysql password on prompt"
        mysql -u root -p < $BASEDIR/database_initialization.sql
    else
        echo -n "Enter complete path to devops directory including devops: "
        read path
        if [ -f $path/database_initialization.sql ]; then
            echo "Enter your mysql password on prompt"
            mysql -u root -p < $path/database_initialization.sql
        else
            echo "Terminating deployment script as invalid path entered for database_initialization.sql file!"
        fi
    fi
}

function configure-repo {
    
    initialize-database;

    stty -echo echonl
    echo -n "Enter your mysql password again: "
    read password
    stty echo
    load-config
    sed -i -e "s/bermudas/$password/" $CONFIG_FILE_DIR/config.json
    load-config
    cd $SERVER_PATH
    install-laravel-dependencies;
    php artisan migrate
    php artisan db:seed
}

function create-config-dir {
    if [ ! -d $CONFIG_FILE_DIR ]; then
        sudo mkdir $CONFIG_FILE_DIR
        sudo cp $BASEDIR/config.json $CONFIG_FILE_DIR
        sudo cp $BASEDIR/load_configuration.sh $CONFIG_FILE_DIR
    fi
}

function configure-server {
    sudo sed -i -e "s/'cipher' => 'AES-256-CBC',/'cipher' => 'AES-128-CBC',/" $SERVER_APP_CONFIG_PATH
    sudo sed -i -e 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=1/' /etc/php5/fpm/php.ini

    sudo php5enmod mcrypt

    cd /etc/nginx/sites-available
    cat << EOF > $NGINX_CONFIG_FILENAME
server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    root $SERVER_PATH/public;
    index index.php index.html index.htm;

    server_name dbproject;
    client_max_body_size 8m;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        try_files \$uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF

    if [ -f $NGINX_DEFAULT_CONFIG_FILE_PATH ]; then
        sudo rm $NGINX_DEFAULT_CONFIG_FILE_PATH
    fi
    if [ ! -f /etc/nginx/sites-enabled/$NGINX_CONFIG_FILENAME ]; then
        sudo ln -s /etc/nginx/sites-available/$NGINX_CONFIG_FILENAME /etc/nginx/sites-enabled/$NGINX_CONFIG_FILENAME
    fi
}

#this function will install all the dependencies as well as start the Subscription Server
function install {
    echo "Building Server...";
    install-deps;
    copy-repo;
    configure-repo;
    configure-server;
    start-server;
}

function remove-deps {
    echo "Removing installed dependencies...";
    stop-server;
    
    if sudo apt-get remove --purge -y nginx php5-curl php5-mcrypt curl jq mysql-server php5-mysql php5-cli php5-fpm; then
        if sudo apt-get -y autoremove; then
            if sudo apt-get -y autoclean; then
                echo "Successfully removed dependencies";
            else
                echo "Autoclean failed!"
            fi
        else
            echo "Autoremove failed!"
        fi
    else
        echo "Failed to remove dependencies";
        exit 1
    fi
}

function clean-up-code {
    echo "Cleaning up code base...";
    sudo rm -fr $SERVER_BASE_PATH
}

function remove-config {
    echo "Removing config dir..."
    if [ -d $CONFIG_FILE_DIR ]; then
        sudo rm -fr $CONFIG_FILE_DIR
    fi
}

function clean-up {
    echo "Cleaning up the installed Server...."
    remove-deps;
    clean-up-code;
    remove-config;
}

function load-config {
    echo "Loading configurations from config.json"
    if [ ! -d $CONFIG_FILE_DIR ]; then
        create-config-dir
    fi
    cd $CONFIG_FILE_DIR
    ./$LOAD_CONFIG_FILENAME
}

function show-help-menu {
    echo "=======================================================================";
    echo "Usage: ./deployment.sh <OPTION>";
    echo "=======================================================================";
    echo "<OPTION>";
    echo "install        : Install all the dependencies and start the Server with provided configuration.";
    echo "install-deps   : Install the dependencies needed to start Server.";
    echo "start-server   : Start the Server with provided configurations."; 
    echo "clean-up       : Remove the dependencies and server installed."
    echo "load-config    : Loads the server configurations from config.json file."
    echo "------------------------------------------------------------------------";
}

if [ "$1" == "install" ]; then
    install
elif [ "$1" == "install-deps" ]; then
    install-deps
elif [ "$1" == "start-server" ]; then
    start-server
elif [ "$1" == "clean-up" ]; then
    clean-up
elif [ "$1" == "load-config" ]; then
    load-config
elif [ "$1" == "help" ]; then
    show-help-menu
else
    show-help-menu
fi
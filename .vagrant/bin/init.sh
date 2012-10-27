#!/bin/bash
# init Project

### Custom Settings #################################

    version="1.7.0.2"
	magentoVersion="magento-${version}"
	magentoUrl="http://www.magentocommerce.com/getmagento/${version}/${magentoVersion}.tar.gz"
	magentoTarball="/usr/local/src/vagrant/files/${magentoVersion}.tar.gz"
	
	magentoSampledataVersion="1.6.1.0"
	magentoSampledata="magento-sample-data-${magentoSampledataVersion}"
	magentoSampledataUrl="http://www.magentocommerce.com/getmagento/${magentoSampledataVersion}/${magentoSampledata}.tar.gz"
	magentoSampledataTarball="/usr/local/src/vagrant/files/${magentoSampledata}.tar.gz"
	
### Settings #################################
	mysql=`which mysql`
	dbrootuser="root"
	dbrootpass="vagrant"
	dbhost="localhost"
	dbname=$(echo ${magentoVersion} | tr "." "_" | tr "-" "_")
	dbuser=""
	dbpass="vagrant1"
	serverurl="http://localhost:8080"
	url=$serverurl"/${magentoVersion}"

echo [+] init ${magentoVersion}

### FUNCTIONS #############################

	download () {
		wget --quiet --output-document $1 "$2"
		ret=$?
		if [ $# -eq 3 ]; then
			if [ $ret -ne 0 ]; then
				exit 255
			fi
		else
			case $ret in "0") echo "Download of ${2} successful."; esac;
			case $ret in "1") echo "Error while downloading ${2}: Generic error code."; exit 255; esac;
			case $ret in "2") echo "Error while downloading ${2}: Parse error"; exit 255; esac;
			case $ret in "3") echo "Error while downloading ${2}: File I/O error."; exit 255; esac;
			case $ret in "4") echo "Error while downloading ${2}: Network failure."; exit 255; esac;
			case $ret in "5") echo "Error while downloading ${2}: SSL verification failure."; exit 255; esac;
			case $ret in "6") echo "Error while downloading ${2}: Username/password authentication failure."; exit 255; esac;
			case $ret in "7") echo "Error while downloading ${2}: Protocol errors."; exit 255; esac;
			case $ret in "8") echo "Error while downloading ${2}: Server issued an error response."; exit 255; esac;
		fi
	}

### Check preconditions #################################
	if [ $(/usr/bin/id -u) -ne 0 ]; then
		echo "This script can only be run with superuser privileges"
		exit 255
	fi

	## all software installed?
	if [ "$(dpkg -l apache2.2-bin 2>/dev/null | grep ii | wc -l)" -lt 1 ]; then
		echo "This script relies on package \"apache2\" to be installed. Precondition unmet! Please run apt-get install apache2 prior to $0"
		exit 255
	fi

	if [ "$(dpkg -l mysql-client 2>/dev/null | grep ii | wc -l)" -lt 1 ]; then
		if [ "$(dpkg -l mysql-server 2>/dev/null | grep ii | wc -l)" -lt 1 ]; then
			echo "This script relies on package \"mysql-client\" (AT LEAST the client!) to be installed. Precondition unmet! Please run apt-get install mysql-client (or mysql-server) prior to $0"
			exit 255
		fi
	fi

	if [ "$(dpkg -l php5-cgi 2>/dev/null | grep ii | wc -l)" -lt 1 ]; then
		echo "This script relies on package \"php5-cgi\" to be installed. Precondition unmet! Please run apt-get install php5-cgi prior to $0"
		exit 255
	fi

	if [ "$(dpkg -l php5-cli 2>/dev/null | grep ii | wc -l)" -lt 1 ]; then
		echo "This script relies on package \"php5-cli\" to be installed. Precondition unmet! Please run apt-get install php5-cli prior to $0"
		exit 255
	fi

	if [ "$(dpkg -l php5-mysql 2>/dev/null | grep ii | wc -l)" -lt 1 ]; then
		echo "This script relies on package \"php5-mysql\" to be installed. Precondition unmet! Please run apt-get install php5-mysql prior to $0"
		exit 255
	fi

	## user vagrant exists?
	vagrantUserId="$(/usr/bin/id -u vagrant 2>/dev/null)"
	if [ "$?" -ne 0 ]; then
		echo "This script relies on an existing user \"vagrant\" - User not found on this system!"
		exit 255
	fi

	# user vagrant is group member of www-data
	if [ "$(cat /etc/group | grep vagrant | grep www-data | wc -l)" -lt 1 ]; then
		echo "This script relies on user \"vagrant\" being a member of group \"www-data\". Precondition unmet! try adduser vagrant www-data"
		exit 255
	fi

	# can we connect to mysql?
 	$mysql -h ${dbhost} -u${dbrootuser} -p${dbrootpass} -e "SELECT 1;" 2>&1 1>/dev/null
	if [ "$?" -ne 0 ]; then
		echo "Cannot connect to mysql server with credentials provided. Please fix this issue first."
		exit 255;
	fi
	
### Start up #################################
	# build database user
	if [ "${magentoVersion}" != "" ]; then
		arr=($(echo ${magentoVersion} | tr "-" " "));
		dbuser=$(echo ${arr[@]:0:$((${#arr[@]}-1))} | tr " " "-")
		dbuser=${dbuser:0:16} # shorten string to please mysql
	fi
	
#########Install ####################################################

	wwwroot="/var/www/${magentoVersion}"

	if ( [ -f ${magentoTarball} ] && [ ! -d ${wwwroot} ] ); then
		
		if ( [ ! -f ${magentoTarball} ] ); then
			echo "[+] magento hates directdownloads :-( put ${magentoVersion}.tar.gz in the .vagrant/files/ folder"
			#download ${magentoTarball} ${magentoUrl}
			exit;
		fi
		
		if ( [ ! -f ${magentoSampledataTarball} ] ); then
			echo "[+] magento hates directdownloads :-( put ${magentoSampledata}.tar.gz in the .vagrant/files/ folder"
			#download ${magentoTarball} ${magentoUrl}
			exit;
		fi	
		
		echo [+] install: ${wwwroot}
		echo [+] magentoTarball: ${magentoTarball}
		
### Installer: Database ########################
		echo "[+] prepare Database"
	
		$mysql -h ${dbhost} -u${dbrootuser} -p${dbrootpass} -e "DROP DATABASE IF EXISTS \`${dbname}\`;" 2>/dev/null; if [ "$?" -ne 0 ]; then echo "FATAL ERROR: We got a problem running a privileged command in mysql. Please take care that user \"${dbrootuser}\" may issue the DROP DATABASE command @${dbhost}"; exit 255; fi
		echo "[+] drop Database if exists ${dbname}"
		
		$mysql -h ${dbhost} -u${dbrootuser} -p${dbrootpass} -e "CREATE DATABASE \`${dbname}\`;" 2>/dev/null; if [ "$?" -ne 0 ]; then echo "FATAL ERROR: We got a problem running a privileged command in mysql. Please take care that user \"${dbrootuser}\" may issue the CREATE DATABASE command @${dbhost}."; exit 255; fi
		echo "[+] create Database ${dbname}"
		
		$mysql -h ${dbhost} -u${dbrootuser} -p${dbrootpass} -e "GRANT ALL ON *.* TO '${dbuser}'@'localhost' IDENTIFIED BY '${dbpass}';" 2>&1>/dev/null; if [ "$?" -ne 0 ]; then echo "FATAL ERROR: We got a problem running a privileged command in mysql. Please take care that user \"${dbrootuser}\" may issue the GRANT command @${dbhost}."; exit 255; fi
		echo "[+] grant all on ${dbuser}"
		
		$mysql -h ${dbhost} -u${dbrootuser} -p${dbrootpass} -e "FLUSH PRIVILEGES;" 2>/dev/null; if [ "$?" -ne 0 ]; then echo "FATAL ERROR: We got a problem running a privileged command in mysql. Please take care that user \"${dbrootuser}\" may issue the FLUSH PRIVILEGES command @${dbhost}."; exit 255; fi
		echo "[+] flush privileges"

### Installer: Magento ########################

		mkdir ${wwwroot}
		echo [+] unpack live shop into ${wwwroot}
		tar --strip-components=1 -xf ${magentoTarball} -C ${wwwroot}
		echo [+] preparing rights
		sudo chmod o+w ${wwwroot}/var ${wwwroot}/var/.htaccess ${wwwroot}/app/etc
		sudo chmod -R 775 ${wwwroot}/media ${wwwroot}/var     
		sudo chown vagrant:www-data ${wwwroot}/* -R	
		
		echo "[+] Installing Magento"
		
		### Installer: Sampledata ########################
	
		if ( [ -f ${magentoSampledataTarball} ] ); then
			tar --strip-components=1 -xf ${magentoSampledataTarball} -C ${wwwroot}
			mv ${wwwroot}/magento_sample_data_for_${magentoSampledataVersion}.sql ${wwwroot}/data.sql
			
			if ( [ -f ${wwwroot}/data.sql ] ); then
				$mysql -h ${dbhost} -u${dbrootuser} -p${dbrootpass} ${dbname} < ${wwwroot}/data.sql 2>/dev/null; if [ "$?" -ne 0 ]; then echo "WARNING: We got a problem running a command in mysql. Please take care that user \"${dbuser}\" may issue the INSERT command on database ${dbname}@${dbhost}."; fi
				rm ${wwwroot}/data.sql
				echo "[+] installed sample data"
			else
				echo "[-] sample data ${wwwroot}/data.sql not found"
			fi
		fi
	
		cd ${wwwroot}/
		echo "[+] calling magento installer"
	
		php install.php -- \
		       --license_agreement_accepted "yes" \
		       --locale "de_DE" \
		       --timezone "Europe/Berlin" \
		       --default_currency "USD" \
		       --db_host "${dbhost}" \
		       --db_name "${dbname}" \
		       --db_user "${dbuser}" \
		       --db_pass "${dbpass}" \
		       --url "${url}/" \
		       --use_rewrites "yes" \
		       --use_secure "no" \
		       --secure_base_url "" \
		       --use_secure_admin "no" \
		       --admin_firstname "flagbit" \
		       --admin_lastname "flagbit" \
		       --admin_email "info@flagbit.de" \
		       --admin_username "admin" \
		       --admin_password "vagrant1"
	
	fi
	
	echo "[+] Have fun with vagrant and Magento at ${url}"
	
 


	
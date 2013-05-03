#!/bin/sh

# TODO detect BASE_PATH
BASE_PATH=/var/www/magento
. ${BASE_PATH}/setup/config.sh

Mage_Extract() {
    current_path=`pwd`

    # Create source directory if needed
    mkdir -p ${BASE_PATH}/src
    cd ${BASE_PATH}/src

    # Download source
    if [ ! -f magento-${MAGE_VERSION}.tar.gz ]; then
        wget http://www.magentocommerce.com/downloads/assets/${MAGE_VERSION}/magento-${MAGE_VERSION}.tar.gz
    fi

    # Extract magento
    if [ ! -d ${BASE_PATH}/magento-${MAGE_VERSION} ]; then
        tar xzf magento-${MAGE_VERSION}.tar.gz
        mv magento ${BASE_PATH}/magento-${MAGE_VERSION}
    fi

    cd ${BASE_PATH}

    # Create symbolic link
    if [ ! -L magento ]; then
        ln -s magento-${MAGE_VERSION} magento
    fi

    # Innodb detection patch for MySQL 5.6.1+ @see http://www.magentocommerce.com/boards/viewthread/33904/P15/
    # TODO Use a patch rather than copying the file
    cp ${BASE_PATH}/patches/Mysql4.php ${BASE_PATH}/magento/app/code/core/Mage/Install/Model/Installer/Db/

    # Enable error reporting
    if [ ${ERROR_REPORTING} = Y ]; then
        if [ ! -f ${BASE_PATH}/magento/errors/local.xml ]; then
            cp ${BASE_PATH}/magento/errors/local.xml.sample ${BASE_PATH}/magento/errors/local.xml
        fi
    fi

    if [ ${MAGE_SAMPLE_DATA} = Y ]; then
        cd ${BASE_PATH}/src
        # Download source
        if [ ! -f magento-sample-data-${MAGE_DATA_VERSION}.tar.gz ]; then
            wget http://www.magentocommerce.com/downloads/assets/${MAGE_DATA_VERSION}/magento-sample-data-${MAGE_DATA_VERSION}.tar.gz
        fi
        # Extract sample data if needed
        if [ ! -d magento-sample-data-${MAGE_DATA_VERSION} ]; then
            tar xzf magento-sample-data-${MAGE_DATA_VERSION}.tar.gz
        fi
        # Copy sample data to magento media folder
        cp -a magento-sample-data-${MAGE_DATA_VERSION}/media/* ${BASE_PATH}/magento/media/
    fi

    # Set permissions
    chmod -R a+rwX ${BASE_PATH}/magento/media
    chmod a+rwX ${BASE_PATH}/magento/var ${BASE_PATH}/magento/var/.htaccess ${BASE_PATH}/magento/app/etc

    cd ${current_path}
}

Mage_DB_Setup() {
    ${MYSQL} -e"CREATE DATABASE IF NOT EXISTS ${MYSQL_DB} DEFAULT CHARACTER SET utf8;"
    # Bring in sample data
    if [ ${MAGE_SAMPLE_DATA} = Y ]; then
        ${MYSQL} ${MYSQL_DB} < ${BASE_PATH}/src/magento-sample-data-${MAGE_DATA_VERSION}/magento_sample_data_for_${MAGE_DATA_VERSION}.sql
    fi
}

Mage_DB_User_Setup() {
    # Check for existing user
    if [ `${MYSQL} -e"SELECT user FROM mysql.user WHERE user='${MYSQL_USER}' AND host='${HOST}'" --xml | grep -c '<row>'` = 1 ]; then
        ${MYSQL} -e"DROP USER '${MYSQL_USER}'@'${HOST}';"
    fi
    ${MYSQL} -e"CREATE USER '${MYSQL_USER}'@'${HOST}' IDENTIFIED BY '${MYSQL_PASS}';"
    ${MYSQL} -e"GRANT ALL PRIVILEGES ON ${MYSQL_DB}.* TO '${MYSQL_USER}'@'${HOST}'; FLUSH PRIVILEGES;"
}

Mage_Install()
{
    # Note: don't indent below. My Mac wasn't able to parse the options when indented
    ${PHP} -f ${BASE_PATH}/magento/install.php -- --license_agreement_accepted "yes" \
--locale ${LOCALE} --timezone ${TIMEZONE} --default_currency ${CURRENCY} \
--db_host ${MYSQL_HOST} --db_name ${MYSQL_DB} --db_user ${MYSQL_USER} --db_pass ${MYSQL_PASS} \
--url "http://${HOST_ALIAS}/" --skip_url_validation --use_rewrites "${USE_REWRITES}" \
--use_secure "${USE_SECURE}" --secure_base_url "https://${HOST_ALIAS}/" --use_secure_admin ${USE_SECURE_ADMIN} \
--admin_lastname "${ADMIN_LASTNAME}" --admin_firstname "${ADMIN_FIRSTNAME}" --admin_email "${ADMIN_EMAIL}" \
--admin_username "${ADMIN_USER}" --admin_password "${ADMIN_PASS}" \
--encryption_key "${ENCRYPTION_KEY}" \
--session_save "${SESSION_SAVE}" \
--enable_charts \
--admin_frontname "${ADMIN_FRONTNAME}"
}

Mage_Permissions() {
    chmod o-w ${BASE_PATH}/magento
    # Set permissions
    chmod -R a+rwX ${BASE_PATH}/magento/media
    chmod a+rwX ${BASE_PATH}/magento/var ${BASE_PATH}/magento/var/.htaccess ${BASE_PATH}/magento/app/etc
}

Mage_Clean() {
    # Check for existing database
    if [ `${MYSQL} -e"SHOW DATABASES" --xml | grep -c ">${MYSQL_DB}<"` = 1 ]; then
        ${MYSQL} -e"DROP DATABASE ${MYSQL_DB};"
    fi
    # Check for existing user
    if [ `${MYSQL} -e"SELECT user FROM mysql.user WHERE user='${MYSQL_USER}' AND host='${HOST}'" --xml | grep -c '<row>'` = 1 ]; then
        ${MYSQL} -e"DROP USER '${MYSQL_USER}'@'${HOST}';"
    fi

    # Remove files
    if [ -d ${BASE_PATH}/magento-${MAGE_VERSION} ]; then
        rm -rf ${BASE_PATH}/magento-${MAGE_VERSION}
    fi

    # Remove symbolic link
    if [ -L ${BASE_PATH}/magento ]; then
        rm -f ${BASE_PATH}/magento
    fi
}

Mage_Link_Modules() {
    current_path=`pwd`

    # Local packages
    mkdir -p ${BASE_PATH}/magento/app/code/local
    cd ${BASE_PATH}/magento/app/code/local
    for package in `ls -d ../../../../modules/local/*`; do
        ln -s $package .
    done

    # Community packages

    # Configs to enable modules
    cd ${BASE_PATH}/magento/app/etc/modules
    for config in `ls ../../../../modules/*.xml`; do
        ln -s $config .
    done

    cd $current_path
}

case "$1" in
        doit)
            Mage_Extract
            Mage_DB_Setup
            Mage_DB_User_Setup
            Mage_Install
            ;;
        extract)
            Mage_Extract
            ;;
        dbsetup)
            Mage_DB_Setup
            Mage_DB_User_Setup
            ;;
        modules)
            Mage_Link_Modules
            ;;
        install)
            Mage_Install
            ;;
        permissions)
            Mage_Permissions
            ;;
        clean)
            Mage_Clean
            ;;
        *)
            echo $"Usage: $0 <doit|extract|dbsetup|modules|install|permissions|clean>"
            echo "  doit = Download, extract, patch, dbsetup and install"
            echo "  extract = Download and extract Magento and sample data"
            echo "  dbsetup = Setup database and database user"
            echo "  modules = Link modules"
            echo "  install = Install using defaults"
            echo "  permissions = Set safe permissions"
            echo "  clean = Clean out database and app/etc/local.xml"
esac

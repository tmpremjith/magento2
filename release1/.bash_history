pwd
ls 
 php8.3 bin/magento setup:upgrade 
bin/magento sampledata:deploy
cd premjith.mgtcommerce.com/
bin/magento sampledata:deploy
pwd
cd 
pwd
php8.3 bin/magento setup:upgrade 
php8.3 bin/magento setup:di:compile
 php8.3 bin/magento setup:static-content:deploy en_US 
 php8.3 bin/magento cache:clean
 rm -rf var/cache/*
rm -rf var/page_cache/* 
varnishstat
ls
cd app/
ls
cd etc/
ls
cp env.php env.php_back_mar28
nano env.php
cat env.php_back_mar28 > env.php
nano env.php
php8.1 bin/magento deploy:mode:set production
cd 
php8.1 bin/magento deploy:mode:set production
php8.3  bin/magento deploy:mode:set production
php bin/magento cron:install
bin/magento module:enable Mgt_Varnish
php bin/magento setup:upgrade
bin/magento module:enable Mgt_Varnish
php bin/magento module:enable Mgt_Varnish
bin/magento module:enable Mgt_Varnish
php bin/magento setup:upgrade
bin/magento setup:static-content:deploy
pwd
cd 
ls
pwd
php bin/magento indexer:reindex
php bin/magento indexer:status
php bin/magento cache:clean 
cd 
pwd
ls
php  bin/magento module:enable Mgt_Waf
bin/magento setup:upgrade
bin/magento setup:di:compile
php bin/magento setup:static-content:deploy

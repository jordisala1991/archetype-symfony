alias console="php /vagrant/bin/console"
alias phpunit="php /home/{{ ansible_user }}/vendor/bin/phpunit"
alias phpunit-coverage="php -dzend_extension=xdebug.so /home/{{ ansible_user }}/vendor/bin/phpunit --coverage-html /vagrant/coverage"
alias php-cs-fixer="/home/{{ ansible_user }}/vendor/bin/php-cs-fixer fix"
alias phpmd="/home/{{ ansible_user }}/vendor/bin/phpmd /vagrant/src text phpmd.xml"
alias dep="/home/{{ ansible_user }}/vendor/bin/dep"

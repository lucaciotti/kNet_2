# INSTALL COMPOSER
cd
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# COMPOSER SELF-UPDATE
sudo composer self-update
# COMPOSER DOWNGRADE
sudo composer self-update --2.2

# SOLUZIONE 1 - PHP 7.4
sudo apt install -y php7.4 php7.4-mbstring php7.4-xml php7.4-curl php7.4-zip php7.4-gd
sudo update-alternatives --config php (alias->chagePhp)

# SOLUZIONE 2 - PHP 8
sudo apt install -y php php-mbstring php-xml php-curl php-zip php-gd

# VPN con 213.152.198.83 Fondamentale!!

# DATABASE Dump!
-> creare ssh tunnel port forwarding 3336:3306
ssh -L 3336:127.0.0.1:3306 ced@213.152.198.83


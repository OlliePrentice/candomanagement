# Install composer

class composer::install {

  if ! defined(Package['curl']) {
    package { 'curl':
      ensure => installed,
    }
  }

  exec { 'install composer':
    environment => ["COMPOSER_HOME=/usr/local/bin"],
    command     => 'curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer',
    require     => Package['curl'],
  }

}
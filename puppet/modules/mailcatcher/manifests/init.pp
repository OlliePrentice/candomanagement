# Install and setup MailCatcher
#-------------------------
#
#   Interface:  http://www.vagrantpress.dev:1080/
#   SMTP:       smtp://www.vagrantpress.dev:1025
#
#-------------------------

class mailcatcher::install {

  # Install dependancies
	package { [
	    'build-essential',
	    'software-properties-common',
	    'libsqlite3-dev',
	    'ruby1.9.1-dev'
	]:
	    ensure => present,
	}

  package { 'mailcatcher-ruby19':
      ensure   => 'installed',
      provider => 'gem',
      require => Package['build-essential','software-properties-common','libsqlite3-dev','ruby1.9.1-dev']
  }

  # Setup MailCatcher to start on boot.
  file { 'mailcatcher.conf':
      path => '/etc/init/mailcatcher.conf',
      owner => 'root',
      group => 'root',
      mode => '644',
      source => 'puppet:///modules/mailcatcher/mailcatcher.conf',
      require => Package['mailcatcher-ruby19']
  }

  # Ensure MailCatcher is running!
  service { 'mailcatcher':
      name => 'mailcatcher',
      ensure  => running,
      path => '/etc/init/',
      subscribe => File['mailcatcher.conf']
  }

  # Setup Mailcatcher as default mail app
  file { 'mailcatcher.ini':
      path => '/etc/php5/mods-available/mailcatcher.ini',
      owner => 'root',
      group => 'root',
      mode => '644',
      source => 'puppet:///modules/mailcatcher/mailcatcher.ini',
      require => File['mailcatcher.conf']
  }

  # Restart Apache if using mod_php or Restart PHP-FPM if using FPM
  exec { 'restart-dependencies':
     command => 'sudo php5enmod mailcatcher;sudo service apache2 restart;sudo service php5-fpm restart',
     require => File['mailcatcher.ini']
  }

}
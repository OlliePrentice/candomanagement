# Lo+Behold Barebones VagrantPress Project Puppet Module

class newproject::install {

  $wpRoot       = '/vagrant/wordpress/'
  $composerHome = '/usr/local/bin'

  $composerName = 'lo-and-behold/crimeline'
  $composerDesc = 'Crimeline WordPress project'

  # Remove/add new plugins as and when you need to
  # Only plugins available in the public WP plugin repo can be obtained using this method
  $plugins = [
    # https://wordpress.org/plugins/force-regenerate-thumbnails/
    'wpackagist-plugin/force-regenerate-thumbnails=*',

    # https://wordpress.org/plugins/google-analytics-for-wordpress/
    'wpackagist-plugin/google-analytics-for-wordpress=*',

    # https://wordpress.org/plugins/iwp-client/
    'wpackagist-plugin/iwp-client=*',

    # https://wordpress.org/plugins/wordpress-seo/
    'wpackagist-plugin/wordpress-seo=*',

    # https://wordpress.org/plugins/email-templates/
    #'wpackagist-plugin/email-templates=*',

    # https://wordpress.org/plugins/imsanity/
    #'wpackagist-plugin/imsanity=*',

    # https://wordpress.org/plugins/safe-redirect-manager/
    #'wpackagist-plugin/safe-redirect-manager=*',

    # https://wordpress.org/plugins/simple-page-ordering/
    'wpackagist-plugin/simple-page-ordering=*',

    # https://wordpress.org/plugins/siteorigin-panels/
    #'wpackagist-plugin/siteorigin-panels=*',
  ]

  $pluginsList = join($plugins, ' ')

  # Ensure cURL is available
  if ! defined(Package['curl']) {
    package { 'curl':
      ensure => installed,
    }
  }

  # Install WP-CLI
  exec { 'evo-install-wp-cli':
    command => 'curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && sudo mv wp-cli.phar /usr/local/bin/wp',
    require => Package['curl']
  }

  # Implement .gitignore files
  # @todo Could probably do with some cleaning up
  exec { 'evo-gitignore-1':
    cwd     => "${wpRoot}",
    unless  => "cat ${wpRoot}.gitignore",
    command => 'touch .gitignore && cat >> .gitignore << EOF
!/wp-content/
',
    require => Exec['untar-wordpress']
  }

  exec { 'evo-gitignore-2':
    cwd     => "${wpRoot}wp-content/",
    unless  => "cat ${wpRoot}wp-content/.gitignore",
    command => 'touch .gitignore && cat >> .gitignore << EOF
/*
!.gitignore
!/plugins/
!/themes/'
,
    require => Exec['untar-wordpress']
  }

  exec { 'evo-gitignore-plugins':
    cwd     => "${wpRoot}wp-content/plugins/",
    unless  => "cat ${wpRoot}wp-content/plugins/.gitignore",
    command => 'touch .gitignore && cat >> .gitignore << EOF
/*
!.gitignore

# Add a "!/plugin-dir/", where [plugin-dir] is the plugin\'s directory, for
# every plugin you want tracked by the repo
# !/my-plugin/
',
    require => Exec['untar-wordpress']
  }

  exec { 'evo-gitignore-themes':
    cwd     => "${wpRoot}wp-content/themes/",
    unless  => "cat ${wpRoot}wp-content/themes/.gitignore",
    command => 'touch .gitignore && cat >> .gitignore << EOF
/*
!.gitignore

# Add a "!/theme-dir/", where [theme-dir] is the theme\'s directory, for
# every theme you want tracked by the repo
# !/my-theme/
',
    require => Exec['untar-wordpress']
  }

  # Remove default WordPress plugins and themes
  exec { 'evo-remove-default-assets':
    cwd     => "${wpRoot}wp-content/",
    command => "rm -rf themes/twentythirteen themes/twentyfourteen themes/twentyfifteen themes/twentysixteen plugins/akismet plugins/hello.php",
    require => Exec['untar-wordpress']
  }

  # Initialise Composer for the project
  exec { 'evo-init-composer':
    environment => ["COMPOSER_HOME=$composerHome"],
    command     => "composer init -n --name=\"${composerName}\" --description=\"${composerDesc}\" -d=${wpRoot}",
    require     => Exec['install composer', 'evo-remove-default-assets']
  }

  # Inform Composer of the WPackagist repository
  exec { 'evo-composer-repos':
    cwd         => $wpRoot,
    command     => 'composer config repositories.0 composer http://wpackagist.org',
    environment => ["COMPOSER_HOME=$composerHome"],
    require     => Exec['evo-init-composer']
  }

  # Turn off HTTPS requirement in Composer
  exec { 'evo-composer-secure-http':
    cwd         => $wpRoot,
    command     => 'composer config secure-http false',
    environment => ["COMPOSER_HOME=$composerHome"],
    require     => Exec['evo-init-composer']
  }

  # Install the plugins listed in this class
  exec { 'evo-install-plugins':
    cwd         => $wpRoot,
    command     => "composer require ${pluginsList}",
    environment => ["COMPOSER_HOME=$composerHome"],
    require     => Exec['evo-composer-secure-http']
  }


  # Install Vagrant Trigger scripts
  file { 'db_backup':
      path => '/usr/local/bin/db_backup',
      owner => 'root',
      group => 'root',
      mode => '777',
      source => 'puppet:///modules/newproject/db_backup'
  }

  file { 'vagrant_halt':
      path => '/usr/local/bin/vagrant_halt',
      owner => 'root',
      group => 'root',
      mode => '777',
      source => 'puppet:///modules/newproject/vagrant_halt',
      require => File['db_backup']
  }

  file { 'vagrant_destroy':
      path => '/usr/local/bin/vagrant_destroy',
      owner => 'root',
      group => 'root',
      mode => '777',
      source => 'puppet:///modules/newproject/vagrant_destroy',
      require => File['db_backup']
  }

  file { 'vagrant_suspend':
      path => '/usr/local/bin/vagrant_suspend',
      owner => 'root',
      group => 'root',
      mode => '777',
      source => 'puppet:///modules/newproject/vagrant_suspend',
      require => File['db_backup']
  }

  file { 'xdebug_off':
      path => '/usr/local/bin/xdebug_off',
      owner => 'root',
      group => 'root',
      mode => '777',
      source => 'puppet:///modules/newproject/xdebug_off',
      require => File['db_backup']
  }

  file { 'xdebug_on':
      path => '/usr/local/bin/xdebug_on',
      owner => 'root',
      group => 'root',
      mode => '777',
      source => 'puppet:///modules/newproject/xdebug_on',
      require => File['db_backup']
  }

}
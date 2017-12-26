# Install Sphinx

class sphinx::install {

  package { [
    'sphinxsearch'
  ]:
  ensure => present,
  }


  # Setup Sphinx config file.
  file { 'sphinx.conf':
      path => '/etc/sphinxsearch/sphinx.conf',
      owner => 'root',
      group => 'root',
      mode => '644',
      source => 'puppet:///modules/sphinx/sphinx.conf',
      require => Package['sphinxsearch']
  }
}

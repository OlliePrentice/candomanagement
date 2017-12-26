
MailCatcher is a mail server for the development environment, that allows you to view the emails sent.


To view the emails you have generated, visit:
  http://www.vagrantpress.dev:1080/


The Module sets MailCatcher as the default mail server but if you need to set it manually, use:
  smtp://www.vagrantpress.dev:1025


To stop MailCatcher, SSH into the virtual machine and use:
  sudo service mailcatcher stop


To start MailCatcher, SSH into the virtual machine and use:
  sudo service mailcatcher start


To view the status of MailCatcher, SSH into the virtual machine and use:
  sudo service mailcatcher status


Some helpful URLs related to setting up and using MailCatcher:
 - https://www.vultr.com/docs/install-mailcatcher-on-ubuntu-14
 - https://serversforhackers.com/setting-up-mailcatcher

Fix for issue with Ruby2 dependency:
 - https://github.com/sj26/mailcatcher/issues/277#issuecomment-262371971

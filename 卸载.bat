@ECHO OFF
echo ====Uninstall Services====

net stop apache2

D:\YSQServer\php\apache2.2\bin\httpd.exe -k uninstall -n "apache2"
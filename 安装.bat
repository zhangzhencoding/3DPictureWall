@ECHO OFF
echo ====Install Services====
D:\YSQServer\php\apache2.2\bin\httpd.exe -k install -n "apache2"

net start apache2

D:\YSQServer\web\index.html
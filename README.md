mumble_stat
===========

Statistik für registrierte Mumble User

Mit diesem Script kann man die Mumble-Log-Datei auslesen.

Zunächst ändert man in der mumble-server.ini:

logfile = /var/www/_stat/mumble-server.log
logdays = 0

Das Verzeichnis /var/www/_stat sollte man mit einer .htaccess-Datei vor neugierigen Blicken schützen. 
In der php-Datei trägt man dann den User, das Passwort und den Pfad zur Log-Datei ein. 
Fertig. 







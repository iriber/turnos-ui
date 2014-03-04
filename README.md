turnos-ui
=========

Sistema de turnos. UI en phprasty

========
no-ip
========
sudo apt-get install wget
wget -c http://www.no-ip.com/client/linux/noip-duc-linux.tar.gz
tar xvzf noip-duc-linux.tar.gz
cd noip-2.1.9-1
make
make install


- como servicio

creamos un pequeño script (/etc/init.d/noip2) para llamar al servicio en el arranque, con el siguiente contenido

#! /bin/sh
sudo /usr/local/bin/noip2

Quinto: le asignamos permisos de ejecución al script

sudo chmod +x /etc/init.d/noip2

Sexto: creamos un enlace al script para adecuarlo a System V

sudo update-rc.d noip2 defaults

==========
phpunit
==========

$ sudo apt-get install phpunit
$ sudo apt-get install pear
$ sudo pear upgrade pear
$ sudo pear channel-discover pear.phpunit.de
$ sudo pear channel-discover components.ez.no
$ sudo pear channel-discover pear.symfony-project.com
$ sudo pear install –alldeps phpunit/PHPUnit

Para comprobar que funciona ejecuta lo siguiente:

    $ phpunit –version


=============
ip estática
=============

modificar el siguiente archivo: /etc/network/interfaces

auto lo
iface lo inet loopback
 
auto eth0
iface eth0 inet static
address 192.168.1.44
netmask 255.255.255.0
gateway 192.168.1.1

sudo service networking restart

Si hubiera algún problema, la solución típica es tirar y levantar el interfaz de red. Esto se hace de la siguiente manera:
view sourceprint?
1.sudo ifdown eth0
2.sudo ifup eth0

Configurar los DNS en  /etc/resolv.conf con el formato:

nameserver aqui.dns.mi.proveedor
nameserver aqui.dns2.mi.proveedor


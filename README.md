ttc-test-tabtapi
================

The Frenoy webservices allow you to show table tennis competition results directly on your website. 
This project is a simple web form where you can enter parameters, call functions and see the results.

More information about the Frenoy webservices can be found at: [http://tabt.frenoy.net](http://tabt.frenoy.net)

[TabT-API Source](https://github.com/gfrenoy/TabT-API)


[Deployed version at TTC Aalst](https://ttc-aalst.be/tabt-api.html)


The wsdl endpoints
------------------

VTTL: https://api.vttl.be/?wsdl  
Sporta: https://ttonline.sporta.be/api/?wsdl  
KAVVV: https://tafeltennis.kavvv.be/api/?wsdl  



Docker
------

Adjust `settings.ini.php` if you want to provide
a default club or login credentials.


```sh
docker-compose up -d
```

Surf to [http://localhost:7209](http://localhost:7209)

### Local Development

Does not copy the files into the container
but serves the files from the local file
system, for easier development!

```sh
cd dev
docker-compose up -d
```

Surf to [http://localhost:1709](http://localhost:1709)



Local Install
-------------

### Install

Install PHP and uncomment in `php.ini`  
(Open `phpinfo.php` if unsure about ini location)  

```ini
extension=soap
extension=openssl
```

### Run

Start the website:  

```sh
php -S localhost:8001
```

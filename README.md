ttc-test-tabtapi
================

The Frenoy webservices allow you to show table tennis competition results directly on your website. 
This project is a simple web form where you can enter parameters, call functions and see the results.

Try it live at: http://ttc-erembodegem.be/tabtapi-test/

More information about the Frenoy webservices can be found at: http://tabt.frenoy.net/index.php

[TabT-API Source](https://github.com/gfrenoy/TabT-API)

The wsdl endpoints
------------------
VTTL: https://api.vttl.be/?wsdl  
Sporta: https://ttonline.sporta.be/api/?wsdl  
KAVVV: https://tafeltennis.kavvv.be/api/?wsdl  

Dev
---

Follow https://babelut.be/@tabt

### Install

Uncomment in `php.ini`  
(Open `phpinfo.php` if unsure about ini location)  
```
extension=soap
extension=openssl
```

### Run

Start the website:  
```
php -S localhost:8001
```

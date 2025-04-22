ttc-test-tabtapi
================

The Frenoy webservices allow you to show table tennis competition results directly on your website. 
This project is a simple web form where you can enter parameters, call functions and see the results.

More information about the Frenoy webservices can be found at: [http://tabt.frenoy.net](http://tabt.frenoy.net)

[TabT-API Source](https://github.com/gfrenoy/TabT-API)



The wsdl endpoints
------------------

VTTL: https://api.vttl.be/?wsdl  
Sporta: https://ttonline.sporta.be/api/?wsdl  
KAVVV: https://tafeltennis.kavvv.be/api/?wsdl  

Dev
---

### Install

Uncomment in `php.ini`  
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

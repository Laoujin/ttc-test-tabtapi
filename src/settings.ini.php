;<?php http_response_code(403); exit; ?>
; The line above keeps this file out of the web: parse_ini_file() reads it as INI
; (a ';' line is a comment), but a direct HTTP request executes the <?php and 403s
; before any value below is echoed. It MUST stay the first line.
; Credentials
Account =
Password =

; WSDL Urls
VTTL = https://api.vttl.be/?wsdl
Sporta = https://ttonline.sporta.be/api/?wsdl
KAVVV = https://tafeltennis.kavvv.be/api/?wsdl

; Defaults
ClubName = Aalst
ClubVTTL = OVL134
ClubSporta = 4055

;<?php exit; ?>
; Guard above: parse_ini_file() treats a ';' line as a comment, but a direct web
; request runs the PHP and exits before any setting below is served. Keep it first.
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

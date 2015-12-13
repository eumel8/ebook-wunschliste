eBook Wunschliste
=================

Dieser Webservice benutzt die Amazon Product Advertising API auf der
Suche nach aehnlichen Produkten, speziell Buecher und Schriftsteller.
Oftmals liest man ein Buch von einem Schriftsteller und moechte 
wissen, welche Buecher der Schriftsteller noch geschrieben hat oder
welche aehnlichen Buecher es von anderen Schriftstellern gibt.
Die Liste von Schriftstellern wird online in einer gemeinsamen
Datenbank gespeichert. Die vergangenen Suchen werden in einem
individuellen Cookie gespeichert.

Eine weitere Funktion der Webseite ist das Buecherbord. Aehnlich
einem Kanban-Board kann man Buecher in eine Liste von zu lesenden
Buechern eintragen, diese in die Spalte Lesen verschieben, wenn man 
das Buch gerade liest und nach Gelesen, wenn man damit fertig ist 
(oder den Eintrag danach loeschen). Der Autor kann wieder in der
eBook Wunschliste rechrschiert werden. 
Die Daten zum Buecherbord werden vom Browser lokal auf dem Rechner
des Benutzers abgelegt.


Vorbedingungen
--------------
- Apache/PHP5
- Schreibrechte in DocumentRoot fuer author.db
- AWS Account (siehe unten)
- HTML5 faehiger Browser
- Zugriff auf gehostete jQuery, jQuery-ui und Bootstrap.js

Konfiguration
-------------

$AWSAccessKeyId  = <Amazon WebService Access Key>
$AWSSecretKey    = <Amazon WebService Secret Key>
$AssociateTag    = <Amazon WebService AssociateTag>

(siehe: http://docs.aws.amazon.com/AWSECommerceService/latest/DG/Welcome.html)

Hinweis
-------

Benutzerfuehrung und API des benutzten Amazon-Marktplatzes ist deutsch,
es ist aber leicht, den Dienst in anderen Sprachen zu portieren



Contributing
------------

1. Fork it.
2. Create a branch (`git checkout -b my_markup`)
3. Commit your changes (`git commit -am "Added Snarkdown"`)
4. Push to the branch (`git push origin my_markup`)
5. Open a [Pull Request][1]
6. Enjoy a refreshing Diet Coke and wait


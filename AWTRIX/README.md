# AWTRIX

Integriert eine Smart Pixel Clock mit [AWTRIX 3 Firmware](https://blueforcer.github.io/awtrix3/#/README) z. B. eine Ulanzi TC001 per HTTP API in [IP-Symcon](https://www.symcon.de/de/).

Zur Verwendung dieses Moduls als Privatperson, Einrichter oder Integrator wenden Sie sich bitte zunächst an den Autor.

Für dieses Modul besteht kein Anspruch auf Fehlerfreiheit, Weiterentwicklung, sonstige Unterstützung oder Support.
Bevor das Modul installiert wird, sollte unbedingt ein Backup von IP-Symcon durchgeführt werden.
Der Entwickler haftet nicht für eventuell auftretende Datenverluste oder sonstige Schäden.
Der Nutzer stimmt den o.a. Bedingungen, sowie den Lizenzbedingungen ausdrücklich zu.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)
8. [Icons](#8-icons)

### 1. Funktionsumfang

* Power Ein/Aus
* Helligkeitsregulierung
* Batteriestatus

* Hinzufügen, Verwalten und Löschen von Custom Apps
* Benachrichtigungen auslösen

### 2. Voraussetzungen

- IP-Symcon ab Version 7.1

### 3. Software-Installation

* Bei kommerzieller Nutzung (z.B. als Einrichter oder Integrator) wenden Sie sich bitte zunächst an den Autor.
* Über das Module Control folgende URL hinzufügen: https://github.com/ubittner/SymconAWTRIX

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'SymconAWTRIX'-Modul mithilfe des Schnellfilters gefunden werden.  
 Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

| Name          | Beschreibung                                                           |
|---------------|------------------------------------------------------------------------|
| ...           |                                                                        |
| Built-In Apps | Uhrzeit, Datum, Temperatur, Feuchtigkeit, Batterie                     |
| Custom Apps   | Eigene Apps, welche in der Anzeigenschleife angezeigt werden           |
| Notifications | Benachrichtigung, welche sofort, einmalig für die Dauer angezeigt wird |
| ...           |                                                                        |

Wird zu einem späteren Zeitpunkt nachgereicht.

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

| Name | Typ | Beschreibung |
|------|-----|--------------|
|      |     |              |

Wird zu einem späteren Zeitpunkt nachgereicht.

#### Profile

| Name | Typ |
|------|-----|
|      |     |

Wird zu einem späteren Zeitpunkt nachgereicht.

### 6. WebFront

Die Funktionalität, die das Modul im WebFront bietet.

* Power Ein/Aus
* Helligkeitsregulierung
* Batteriestatus

### 7. PHP-Befehlsreferenz

Wird zu einem späteren Zeitpunkt nachgereicht.

### 8. Icons

Sie können LaMetric-Icons in der [LaMetric Icon-Galerie](https://developer.lametric.com/icons) entdecken und finden oder in der AWTRIX 3 App.

Beispiele:

| Nummer | Bezeichnung             | Quelle                | Beschreibung                   |
|--------|-------------------------|-----------------------|--------------------------------|
| 112    | 112                     | AWTRIX 3 App          | Alarmleuchte in rot und blau   |
| 21256  | Electric power Animated | LaMetric Icon-Galarie | Stromstecker                   |
| 61606  | RED CIRCLE              | LaMetric Icon-Galarie | Quadrat in rot                 |
| 61607  | GREEN CIRCLE            | LaMetric Icon-Galarie | Quadrat in grün                |
| 61608  | BLUE CIRCLE             | LaMetric Icon-Galarie | Quadrat in blau                |
| 61609  | YELLOW CIRCLE           | LaMetric Icon-Galarie | Quadrat in gelb                |
| 61610  | ORANGE CIRCLE           | LaMetric Icon-Galarie | Quadrat in orange              |
| 259    | red                     | LaMetric Icon-Galarie | Ausgefülltes Quadrat in rot    |
| 8289   | green                   | LaMetric Icon-Galarie | Ausgefülltes Quadrat in grün   |
| 1779   | blue                    | LaMetric Icon-Galarie | Ausgefülltes Quadrat in blau   |
| 1536   | yellow                  | LaMetric Icon-Galarie | Ausgefülltes Quadrat in gelb   |
| 842    | orange                  | LaMetric Icon-Galarie | Ausgefülltes Quadrat in orange |
| 902    | red circle              | LaMetric Icon-Galarie | Roter Punkt                    |
| 900    | blue circle             | LaMetric Icon-Galarie | Blauer Punkt                   |
| 19450  | Green Light             | LaMetric Icon-Galarie | Grüner Punkt                   |
| 904    | yellow circle           | LaMetric Icon-Galarie | Gelber Punkt                   |
| 32862  | orange circle           | LaMetric Icon-Galarie | Oranger Punkt                  |



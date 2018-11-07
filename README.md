# Tvorba uživatelských rozhraní - Projekt
## TODO list pro Buddy členy organizace ESN


### Autor
- Dominik Harmim <harmim6@gmail.com>


### Dokumentace
- Technická zpráva projektu: `./doc/xharmi00_tz.pdf`.
- Technická zpráva projektu (1. část): `./doc/xharmi00_tz_01.pdf`.
- Technická zpráva projektu (2. část): `./doc/xharmi00_tz_02.pdf`.


### Struktura projektu
- `./doc/` Dokumentace.
- `./node_modules/` JavaScript knihovny nainstalované přes NPM. Slouží k nastavení a stahování JavaScript a CSS
  závislostí systému.
- `./www/` Kořenový adresář přístupný z webu.
  * `./www/css/` CSS soubory.
  * `./www/html/` HTML šablony.
  * `./www/img/` Obrázky.
  * `./www/js/` JavaScript skripty.
- `./.gitignore` Ignorované soubory verzovacím systémem Git.
- `./docker-compose.override.sample.yml` Vzorový konfigurační soubor pro lokální modifikaci konfigurace Docker.
- `./docker-compose.yml` Konfigurační soubor pro Docker.
- `./Gruntfile.js` JavaScript skript pro správu a stahování JavaScript a CSS závislostí systému.
  (Konfigurační soubor nástroje Grunt.)
- `./LICENSE` Licence.
- `./Makefile` Soubor pro správu systému programem make.
- `./package.json` Definice používaných JavaScript knihoven pro stahování JavaScript a CSS závislostí systému
  nástrojem NPM.
- `./package-lock.json` Pomocný soubor pro nástroj NPM.
- `./README.md` README se základními informacemi o systému.


### Požadavky (obecné)
- [Git](https://git-scm.com/downloads). (Pro stažení repositáře.)


### Požadavky
- [Docker](https://www.docker.com/products/docker-engine#/download) nebo přes
  [Docker Desktop](https://www.docker.com/products/docker-desktop).
- [Docker Compose](https://docs.docker.com/compose/install/#install-compose).


### Nastavení (obecné)
#### Stažení repositáře
Přes SSH
```
$ git clone git@github.com:harmim/vut-itu-project.git ~/cesta/k/repositari
```
nebo přes HTTPS.
```
$ git clone https://github.com/harmim/vut-itu-project.git ~/cesta/k/repositari
```



### Instalace
1.
```
$ cp docker-compose.override.sample.yml docker-compose.override.yml
```

2. Změna konfigurace v souboru `docker-compose.override.yml`, např. nastavení `XDEBUG_CONFIG` na
`docker.for.win.localhost` pro Windows.

3.
```
$ docker-compose up -d
```
(pro zrušení Docker kontejneru `$ docker-compose down`)

4.
```
$ make
```


### Make příkazy
U kažédho příkazu je možné uvést `PRODUCTION=1/0` pro nastavení knihoven pro produkční/vývojový server,
např. `$ make install PRODUCTION=1`. Výchozí hodnota je `0`.

- `install` `composer` `assets`. Výchozí cíl.
- `assets` `npm` + `grunt`.
- `npm` Instalace JavaScript knihoven přes NPM pro nastavování a stahování JavaScript a CSS knihoven systému.
- `grunt` Kopírování a nastavování (minifikace, aj.) JavaScript a CSS knihoven systému.
- `clean` Odstranení všech dočasných souborů.
- `html` Otevře HTML šablony.

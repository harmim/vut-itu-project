# Tvorba uživatelských rozhraní - Projekt
## TODO list pro Buddy členy organizace ESN


### Autor
- Dominik Harmim <xharmi00@stud.fit.vutbr.cz>


### Dokumentace
- Technická zpráva projektu: `./doc/xharmi00_41_100_tz.pdf`.
- Technická zpráva projektu (1. část): `./doc/xharmi00_tz_01.pdf`.
- Technická zpráva projektu (2. část): `./doc/xharmi00_tz_02.pdf`.


### Databáze
SQL skript pro inicializaci schématu databáze se nachází v [`./sql/init.sql`](./sql/init.sql).


### Struktura projektu
- `./app/` Jádro systému. PHP skripty a třídy, konfigurační NEON soubory. Latte šablony.
  * `./app/config/` Konfigurační NEON soubory systému.
  * `./app/*Module/` Jednotlivé moduly systému. Tento adresář obsahuje konfigurační NEON soubor pro daný modul,
    adresář `Controls` pro komponenty, `Model` pro modelové třídy, `Presenters` pro presentery (kontrolery) a
    `templates` pro Latte šablony.
  * `./app/bootstrap.php` výchozí skript s nastavením konfigrace celého systému.
- `./doc/` Dokumentace.
- `./docker/` Nastavení Docker.
- `./libs/` PHP třídy, které modifikují nebo rozšiřují chování Nette framework.
- `./log/` Chybové záznamy systému ("logy").
- `./node_modules/` JavaScript knihovny nainstalované přes NPM. Slouží k nastavení a stahování JavaScript a CSS
  závislostí systému.
- `./sql/` SQL skripty.
  * `./sql/init.sql` Skript pro inicializaci databáze.
- `./temp/` Dočasné soubory systému.
- `./vednor/` PHP knihovny nainstalované přes Composer.
- `./www/` Kořenový adresář přístupný z webu.
  * `./www/css/` CSS soubory.
  * `./www/html/` HTML šablony.
  * `./www/img/` Obrázky.
  * `./www/js/` JavaScript skripty.
  * `./www/index.php` Výchozí PHP skript spuštěný při spuštění systému.
- `./.gitignore` Ignorované soubory verzovacím systémem Git.
- `./.htaccess` Výchozí konfigurace webového serveru Apache pro celý systém.
- `./coding-standard.yml` Nastavení nástroje `nette/coding-standard` pro kontrolu stylu PHP kódu.
- `./composer.json` Definice používaných PHP knihoven, verze PHP a způsobu automatického načítání PHP souborů
  nástrojem Composer.
- `./composer.lock` Pomocný soubor pro nástroj Composer.
- `./docker-compose.override.sample.yml` Vzorový konfigurační soubor pro lokální modifikaci konfigurace Docker.
- `./docker-compose.yml` Konfigurační soubor pro Docker.
- `./Gruntfile.js` JavaScript skript pro správu a stahování JavaScript a CSS závislostí systému.
  (Konfigurační soubor nástroje Grunt.)
- `./LICENSE` Licence.
- `./Makefile` Soubor pro správu systému programem make.
- `./package.json` Definice používaných JavaScript knihoven pro stahování JavaScript a CSS závislostí systému
  nástrojem NPM.
- `./package-lock.json` Pomocný soubor pro nástroj NPM.
- `./phpstan.neon` Nastavení nástroje `phpstan` pro statickou analýzu PHP kódu.
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

#### Nastavení DNS
- Doména `vut-itu-project.localhost.com` musí směrovat na localhost (127.0.0.1).
Lze to udělat např. editací souboru `/etc/hosts`, respektive (`C:\Windows\System32\Drivers\etc\hosts`).
Nebo je možné použít program [`dnsmasq`](http://www.thekelleys.org.uk/dnsmasq/doc.html) kde je možné
nastavit, aby celé skupiny domén směrovaly na localhost, např. všechny domény, které mají ve svém
názvu řetězec `localhost`. [Návod pro Mac](https://getgrav.org/blog/macos-mojave-apache-mysql-vhost-apc).
Nebo je také možné nainstalovat si `dnsmasq` přes Docker.


### Nastavení

#### Nginx-proxy
Nastavení Nginx proxy serveru, přes který se budeme připojovat k systému a který bude proxy na Apach server.

1. Vytvoření souboru `~/docker-compose.yml` např. v domovském adresáři:
```yml
version: '3'

services:
    nginx-proxy:
        image: jwilder/nginx-proxy
        ports:
            - 80:80
        volumes:
            - /var/run/docker.sock:/tmp/docker.sock:ro
        restart: always

networks:
    default:
      external:
        name: nginx-proxy
```

2.
```
$ docker network create nginx-proxy
```

3.
```
$ (cd ~ && docker-compose up -d)
```
Tato Nginx proxy se teď bude spouštět vždy po restartu Docker automaticky.


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

- `install` `composer` + `assets`. Výchozí cíl.
- `composer` Instalace PHP knihoven a vygenerování souboru pro automatické načítání PHP souborů.
- `assets` `npm` + `grunt`.
- `npm` Instalace JavaScript knihoven přes NPM pro nastavování a stahování JavaScript a CSS knihoven systému.
- `grunt` Kopírování a nastavování (minifikace, aj.) JavaScript a CSS knihoven systému.
- `code-checker` Spuštění kontroly validity PHP kódu.
- `coding-standard` Spuštění kontroly stylu PHP kódu.
- `phpstan` Spuštění statické analýzy PHP kódu.
- `clean` Odstranení všech dočasných souborů.
- `clean-cache` Ostranění dočasných souborů Nette framework.
- `pack` Zabalení projektu pro odevzdání.

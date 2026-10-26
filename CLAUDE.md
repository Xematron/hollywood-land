# HOLLYWOOD LAND — TFG DAW M12

_(Nom anterior del projecte: CINECITA, canviat el 2026-10-12 per encaixar millor amb la temàtica cinema clàssic + diner anys 50.)_

Projecte final cicle CFGS DAW, mòdul M12 (Projecte). Enunciat font: `DAW_M12_Enunciat_2627.odt` (no tornar a llegir-lo, aquest fitxer ja en té el resum complet).

## Directrius de col·laboració

- **Mode tutor per defecte**: explicar els passos a seguir (comandes, on clicar, què escriure) perquè l'estudiant els executi ell mateix. No actuar de manera autònoma tret que ell ho demani explícitament per a una tasca concreta.
  - Motiu: és un TFG individual avaluat amb entrevista síncrona davant tribunal — l'objectiu és que l'estudiant domini tot el que ha fet, no que l'app estigui "feta".
- Projecte treballat des de **dos ordinadors diferents**: fer `git pull` abans de començar sessió i `git push` en acabar, perquè aquest fitxer i el codi estiguin sincronitzats a totes dues màquines.
- **Política de commits**: fer `git commit` després de tancar cada punt/tasca significativa dins la sessió (no un sol commit gegant al final), per tenir un historial real que reflecteixi el procés (criteri C). El `git push` es fa **només un cop, al final de la sessió** (o abans de canviar d'ordinador) — no cal pujar cada commit individualment.
- Aquest `CLAUDE.md` és la **font única de veritat** del context del projecte (no memòria externa de l'assistent). Actualitzar-lo a mesura que es prenen decisions o canvia l'estat, perquè qualsevol sessió/ordinador tingui el mateix context en llegir-lo.
- Ús d'IA (Claude Code) com a eina de suport: transparent, sense amagar-ho. Aquest fitxer forma part del repositori amb normalitat. Si el tribunal pregunta directament per l'ús d'eines, resposta honesta.

## Dates clau

- Lliurament: **20/11/2026 23:55h** (documentació + codi font + vídeo, fitxer únic, nom `DAW_M12_Cognom1_InicialCognom2.odt/pdf`).
- Entrevista síncrona davant tribunal (~10 min): aprox. 1 setmana després del lliurament.
- Mòdul: 99h de dedicació previstes.
- Treball desatès: sense tutoria/seguiment continuat del professorat.

## Idea de negoci

HOLLYWOOD LAND: cinema multi-sala fictici especialitzat en reposicions temàtiques en VOSE (ciència-ficció, fantasia, Oscars, retrospectives d'autor), amb cine-fòrums/debats post-projecció i una cafeteria/diner temàtic anys 50 (sala sense menjar/beure). Públic objectiu: cinèfils 25-55 anys, estudiants de cinema/humanitats, grups/col·lectius. Competidors de referència: Cinesa, Verdi.

## Criteris d'avaluació i pes

- A. Definició del projecte — 1 punt (nom, empresa/públic, competència, viabilitat econòmica, protecció de dades, innovació)
- B. Planificació del projecte — 2 punts (Agile vs Traditional **en anglès**, eines gestió àgil **en anglès**, backlog en eina tipus Taiga, hores per funcionalitat, requisits tècnics, eines)
- C. Desenvolupament i documentació tècnica — 5 punts (repo Git amb historial complet, desplegament, esquema BD, documentació tècnica, manual d'usuari)
- D. Presentació — 2 punts (vídeo 10-15 min, entrevista síncrona)

Criteris generals: originalitat/integritat/coherència, correctesa funcional, pulcritud i rigor documental.

## Requisits tècnics obligatoris (l'app ha de cobrir tots)

- **BD relacional** (MySQL/MariaDB/PostgreSQL) amb model conceptual i relacional.
- **Frontend** responsive, accessible (WCAG), HTML5/CSS3/JS + framework modern (Bootstrap/Tailwind/React/Angular...).
- **Backend** amb lògica de negoci (Java EE/Spring Boot, PHP, Node.js...) i accés a dades segur.
- **Serveis web** (REST o SOAP).
- **Gestió d'usuaris i seguretat**: autenticació, control d'accés, rols (admin, taquiller/personal, client).
- **Control de versions**: repo Git accessible amb historial de commits real reflectint el procés.
- **Desplegament** funcional en servidor d'aplicacions o hosting/cloud accessible pel tribunal.

**Stack triat**: PHP natiu (OOP) + PDO per backend, MySQL per BD, HTML5/CSS3/JS + Bootstrap per frontend, serveis REST en PHP retornant JSON per accions dinàmiques.

## Funcionalitats mínimes de l'app (backlog base)

- Homepage amb cartellera (pel·lícula, horari, sala, preu)
- Login i gestió d'usuaris (perfils: admin, taquiller/personal, client)
- Gestió d'informes/estadístiques (ex: pel·lícula més taquillera per període)
- Venda d'entrades: gestió de butaques per sala/horari/dia, preu variable per dia
- (Ampliable amb funcionalitats pròpies del model: cicles temàtics, inscripció a debats, recomanador dins d'un cicle, gestió VOSE/idiomes — valorat com a element innovador a l'enunciat)

Distribució d'hores orientativa per funcionalitat: ~25% anàlisi i disseny, ~60% implementació (inclou proves), ~15% documentació.

## Lliurables obligatoris

1. Document de definició + planificació (apartats A i B de l'enunciat)
2. Backlog configurat en eina àgil (Taiga o similar) + captura de pantalla
3. Repositori Git amb historial complet (URL)
4. Entorn de desplegament accessible + credencials de prova (admin/client) si és possible
5. Captura de l'esquema de BD
6. Documentació tècnica + manual d'usuari
7. Vídeo de presentació 10-15 min (estructura: planificació/arquitectura 2-3min, codi destacat 4-5min, demo funcional 5-6min, conclusions 1min), publicat a YouTube ocult/Vimeo/Drive
8. Entrevista síncrona final

Nota idioma: apartats 7 i 8 de l'enunciat (Agile vs Traditional, Agile Management Tools) **s'han de respondre en anglès**.

## Decisions preses

- 2026-10-12: Nom del projecte canviat de "Cinecita" a **Hollywood Land** (veure justificació al punt 1 de l'ODT i a `presentacio.md`).
- 2026-10-12: Eina de gestió àgil triada: **Taiga** (open-source, gratuïta, pensada per Scrum/Kanban, adequada per treball individual).
- 2026-10-12: Backlog a Taiga — "Cicles temàtics i recomanador" fusionat dins la funcionalitat Homepage/cartellera (no és un mòdul propi). Taula d'hores final (99h totals): Homepage/cartellera 24h, Login/usuaris 18h, Informes/estadístiques 12h, Venda d'entrades 25h, Inscripció a debats i VOSE/idiomes 10h, Configuració d'entorn i desplegament 10h.
- 2026-10-12: Stack tècnic triat: **PHP natiu (OOP) + PDO**, **MySQL**, **HTML5/CSS3/JS + Bootstrap**, serveis REST en PHP/JSON. Descartat framework PHP (Laravel/Symfony) i Alpine+Tailwind (que l'estudiant domina professionalment amb Hyvä/Magento2) per mantenir-ho el més simple possible dins les 99h disponibles.
- 2026-10-17: Taula d'eines (punt 11) tancada. Totes open-source, prioritzant mínima fricció i consistència entre els 2 ordinadors de treball:
  - **IDE**: VS Code.
  - **Entorn local**: Docker Engine + docker-compose (nadiu a Linux, OSS pur, `docker-compose.yml` versionat al repo per tenir entorn idèntic a les 2 màquines). Contenidors: PHP+Apache, MySQL, phpMyAdmin.
  - **Gestor BD**: phpMyAdmin (inclòs al docker-compose, útil per la captura de l'esquema de BD del lliurable).
  - **Eina de proves API**: Bruno (Postman descartat per no ser open-source; Bruno és 100% FOSS i guarda col·leccions com a fitxers de text versionables a Git).
  - **Disseny gràfic**: Penpot (alternativa OSS a Figma, per mockups/wireframes).
  - **Maquinari**: ordinador personal portàtil, 24 GB RAM.

_(seguir afegint aquí a mesura que es triïn stack tecnològic, etc. — amb data i motiu breu)_

## Estat actual

_(actualitzar cada sessió amb en quin punt de l'enunciat s'està treballant)_
- 2026-10-12: Briefing llegit i resumit en aquest CLAUDE.md. Encara no s'ha triat stack ni eina de gestió àgil. Pendent: apartat A (definició del projecte) — ja hi ha contingut redactat dins de l'ODT original per als punts 1-6, cal revisar si es manté o es reescriu.
- 2026-10-12: Punt 1 (nom del projecte) revisat i tancat. Nom canviat de "Cinecita" a **Hollywood Land**, amb justificació redactada i afegida a l'ODT.
- 2026-10-12: Punts 1-10 de l'ODT revisats/redactats i enganxats pel propi estudiant (punts 7 i 8 redactats per l'assistent, a petició explícita). Backlog creat a Taiga amb hores estimades.
- 2026-10-17: **Punt 11 tancat** (taula d'eines completa, veure "Decisions preses").
- 2026-10-17: ODT actualitzat per l'estudiant amb contingut ja decidit prèviament: nom "Hollywood Land" propagat a tot el document (ja no queda "Cinecita" enlloc), **punt 7** (Agile vs Traditional, en anglès) redactat, **punt 8** (Agile management tools, en anglès, comparativa Jira/Trello/Taiga/Asana-ClickUp/Azure DevOps/GitHub Projects + justificació Taiga) redactat, **punt 9** (taula d'hores per funcionalitat, 99h total) redactat, i paràgraf d'stack tècnic (PHP natiu+PDO, MySQL, Bootstrap, REST/JSON) redactat. Propera sessió: revisar si queda algun altre apartat de l'ODT pendent (12+) o passar a configurar l'entorn Docker i començar implementació.
- 2026-10-17: Repositori Git pujat a GitHub (públic): https://github.com/Xematron/hollywood-land — branca `main`, remot `origin` configurat amb autenticació via Personal Access Token. **Punt 12 (Repositori del codi font) tancat**, URL enganxada a l'ODT.
- 2026-10-17: Secció B (Planificació) **completa**. Secció C (Desenvolupament i documentació tècnica) en curs: punt 12 fet; punts 13 (entorn desplegament), 14 (esquema BD), 15 (documentació tècnica) i 16 (manual d'usuari) **requereixen tenir l'aplicació construïda** — no es poden tancar només amb documentació.
- 2026-10-17: **Punt 13 (entorn de desplegament) posposat explícitament** — es decidirà on es desplega més endavant, un cop hi hagi aplicació funcional. Opcions valorades: hosting gratuït PHP+MySQL, VPS propi, servidor del CFGS, o túnel local (ngrok) només pel dia de l'entrevista. Cap decisió presa encara.
- 2026-10-17: **Model de BD tancat** (9 taules: Usuari, Pel·lícula, CicleTematic, Sala, Butaca, Sessio, Entrada, Debat, InscripcioDebat). Preu variable va directe al camp `preu` de `Sessio` (sense taula `Tarifa` separada, per simplicitat). `Entrada` té `UNIQUE(sessio_id, butaca_id)` per evitar vendre la mateixa butaca dues vegades.
- 2026-10-17: **Entorn Docker creat i funcionant** (`docker-compose.yml` a l'arrel del repo): contenidor `web` (PHP 8.2+Apache amb pdo_mysql, build des de `docker/php/Dockerfile`), `db` (MySQL 8.0), `phpmyadmin`. Credencials a `.env` (gitignored, plantilla a `.env.example`). Esquema SQL a `sql/init/01_schema.sql` (s'autocarrega al primer arrencada del contenidor `db`). Accés: app http://localhost:8080, phpMyAdmin http://localhost:8081, MySQL extern (DBeaver) a `localhost:3307` (port 3306 del host ja ocupat per un MySQL natiu del sistema, remapejat). Verificat: PHP connecta per PDO i les 9 taules es creen correctament.
- 2026-10-17: Claude Code (l'assistent) ja pot accedir directament a la BD via `docker exec hollywood_db mysql ...` per crear/verificar/corregir l'esquema; phpMyAdmin/DBeaver queden per inspecció visual de l'estudiant.
- 2026-10-17: **Punt 14 (captura de l'esquema de BD) tancat** — captura feta des del Dissenyador de phpMyAdmin i enganxada a l'ODT.
- 2026-10-17: Alias de shell (`~/.zshrc`, per màquina, no versionat) per gestionar l'entorn Docker: `hl-up`, `hl-down`, `hl-build`, `hl-logs`, `hl-ps` (totes fan `cd` a la ruta del repo primer). Cal recrear-los a cada ordinador amb la ruta local correcta.
- 2026-10-17: **Incidència resolta — compte GitHub incorrecte en aquest ordinador**. Aquest PC té per defecte el compte GitHub `xtrech` (sense accés d'escriptura al repo), mentre el repo `hollywood-land` pertany al compte `Xematron`. Solució aplicada: credential helper configurat **només a nivell local d'aquest repo** (no global) perquè faci servir un fitxer de credencials separat (`~/.git-credentials-hollywood`) amb el token del compte `Xematron`, sense tocar la configuració global de git (que segueix fent servir `xtrech` per a la resta de projectes d'aquest PC). Important recordar-ho si el push torna a fallar amb error 403 "denied to xtrech" en aquest ordinador.
- 2026-10-24: Accés local a l'app via domini fals `hollywoodland.local:8080` (entrada a `/etc/hosts` apuntant a `127.0.0.1`, per màquina, no versionada). El port `:8080` es manté a l'URL (hosts no remapeja ports); `localhost:8080` segueix funcionant igual.
- 2026-10-24: **Estructura base del projecte creada**: `public/` com a document root únic d'Apache (repoint fet al `docker/php/Dockerfile` amb `sed` sobre la config d'Apache, mantenint tot el repo muntat al contenidor via `docker-compose.yml`), `src/` (fora d'abast web) amb `Config/`, `Models/`, `Controllers/`, `Core/`. Autoloader PSR-4 bàsic (sense Composer), ara centralitzat a `src/bootstrap.php` (inclòs per cada entry point de `public/`) junt amb `session_start()`. `src/Config/Database.php` centralitza la connexió PDO en un singleton (`Database::getConnection()`).
- 2026-10-24: **Login/logout i control d'accés per rol implementats** (comença funcionalitat "Login i gestió d'usuaris", triada per fer-se abans que Homepage perquè és transversal a la resta). `src/Models/Usuari.php` (findByEmail/findById/create/verifyCredentials amb `password_hash`/`password_verify`). `public/login.php` + `public/logout.php` amb sessió PHP (`session_regenerate_id` en login correcte). `src/Core/Auth.php` amb `requireLogin()`/`requireRole()`/`user()` per protegir pàgines (redirect a login si no hi ha sessió, HTTP 403 si rol no coincideix). Usuari de prova a BD: `admin@hl.com` / `test1234` (rol admin, creat manualment via `docker exec`, no versionat a seed SQL encara). Criteri decidit: `index.php` (homepage/cartellera) serà **sempre públic**, sense login — el login només protegeix accions concretes (compra, panell admin/treballador, inscripció debat).
- 2026-10-24: **Homepage/cartellera implementada (versió final).** Separada en dues pàgines: `public/cartellera.php` (llistat detallat en taula de totes les sessions futures — pel·lícula, data, hora, sala, preu, subtítols) i `public/index.php` (Home visual): cards amb cartell (placeholder via `placehold.co`, sense drets d'autor), agrupades per **dia concret** (no per cap de setmana sencer) amb capçalera en català (ex. "Dissabte, 31 d'octubre"), en grid fixa de 4 columnes des de `md` (`row-cols-md-4`, Bootstrap fixa l'amplada de columna independentment de quantes cards hi hagi). Models `Pelicula.php` (`all()`) i `Sessio.php` (`cartellera()`: JOIN Sessio+Pelicula+Sala, `WHERE data >= CURDATE()`) a `src/Models/`. Bootstrap 5.3.3 via CDN.
- 2026-10-24: **Calendari de negoci**: sessions només dijous-diumenge (divendres/dissabte amb doble sessió 16h/19h; dijous amb sessió única a les 20h). Seed dividit en `sql/seed/01_demo.sql` (catàleg base: 2 sales, 8 pel·lícules) i `sql/seed/02_demo_sessions.sql` (calendari de 2 setmanes d'exemple). Diumenge 1/11 substitueix el programa habitual per un **marató temàtic** (trilogia Halloween, 3 sessions seguides), demostrant el concepte de "reposicions temàtiques" del negoci.
- 2026-10-24: **Esquema BD ampliat** més enllà de les 9 taules originals "tancades": `Debat` guanya `titol` (opcional, sobreescriu el nom de la pel·lícula enllaçada quan cal — ex. cine-fòrum "Halloween" en lloc del títol exacte de l'última pel·lícula del marató) i `durada_min`. Nova taula `Activitat` (genèrica, camp `tipus` ENUM taller/xerrada/podcast/altres) per esdeveniments que no són ni projecció ni debat (ex. gravació de podcast). Dades de demo a `sql/seed/03_demo_extra.sql`: cine-fòrum d'1h després de l'última sessió de cada dia, taller "Nit de terror" el dia del marató de Halloween.
- 2026-10-24: **Footer genèric** (`public/partials/footer.php`: nom, contacte fictici, horari de taquilla, copyright), inclòs a totes les pàgines públiques (`index.php`, `cartellera.php`, `login.php`, `registre.php`).
- 2026-10-24: **Pendent detectat**: ara mateix pel·lícules/sessions/debats/activitats es gestionen via seed SQL manual, no és la manera real de gestionar contingut — falta CRUD d'admin per `Pelicula`/`Sala`/`Sessio` (mateix patró que `usuaris.php`). Pròxima sessió: aquest CRUD, o Venda d'entrades.
- 2026-10-24: **"Login i gestió d'usuaris" tancat.** Registre públic (`public/registre.php`): sempre crea rol `client` fix al codi, mai triable des del formulari (evitar escalada de privilegis). CRUD d'usuaris per admin (`public/admin/usuaris.php`): llistat/crear/editar/eliminar, amb protecció perquè un admin no es pugui eliminar ni canviar el propi rol a ell mateix. Model `Usuari` ampliat amb `all()`/`update()`/`delete()`. Estructura de zones per rol: `public/admin/` (dashboard + gestió usuaris, només rol `admin`) i `public/treballador/` (stub pendent de desenvolupar — venda d'entrades al taulell —, accessible per `taquiller` i `admin`; nom genèric "treballador" en lloc de "taquiller" per si en el futur calgués agrupar-hi més rols de personal). `index.php` mostra enllaços "Zona Admin"/"Zona Treballador" condicionats al rol de la sessió. Fix important: `Auth::requireLogin()` ara redirigeix a ruta absoluta `/login.php` (abans era relativa i trencava des de subcarpetes com `admin/`). Pròxima sessió: Homepage/cartellera (Pel·lícula, Sessio, Sala) o Venda d'entrades.
- 2026-10-26: **CRUD admin de Sales/Pel·lícules/Sessions tancat** (commit `5855b46`). `Sala`: **sense crear/eliminar** — una sala és espai físic real, no té sentit de negoci crear-ne/eliminar-ne des d'un panell; `admin/sales.php` només permet editar el nom. `capacitat` ja no es llegeix de la columna estàtica, es calcula amb `COUNT(Butaca)` (sempre consistent amb butaques reals). Columna nova `Butaca.operativa` (`TINYINT(1) DEFAULT 1`) per marcar butaques temporalment fora de servei (obres/desperfectes) sense esborrar-les mai (esborrar trencaria `Entrada` per FK i perdria històric) — gestionat a `admin/butaques.php` (checkboxes per fila). Incidència resolta: les 2 sales del seed no tenien cap fila `Butaca` generada (mai van passar pel generador, que només vivia dins el `Sala::create()` ja eliminat) — afegit `sql/seed/04_demo_butaques.sql` (8×10 Sala 1, 6×10 Sala 2). `Pelicula`: CRUD complet (`admin/pelicules.php`), sí té sentit crear/eliminar pel·lícules habitualment. `Sessio`: CRUD a `admin/sessions.php` amb selector d'hora tancat (`<select>` cada 15 min, no `input type=time` lliure) validat també al servidor; no es pot crear/programar cap sessió amb data passada; si una sessió ja és passada **no es pot editar cap camp**, només eliminar-se (`Sessio::findById()` comprova la data original abans de l'update, per si algú salta la UI). Conveni (no forçat per codi, "en principi"): pel·lícules es projecten només a Sala 1 - Lumière, Sala 2 - Bogart reservada per activitats/debats. Fix addicional: `login.php`/`registre.php` no carregaven el CDN de Bootstrap (`index.php` sí) — footer sortia sense estils, corregit. Totes les decisions i alternatives descartades d'aquesta sessió detallades a `presentacio.md`. Pròxima sessió: Venda d'entrades (el gran mòdul que queda, 25h estimades).

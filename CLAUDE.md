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

# Floraffeine Boutique — UI Shell Blueprint

## 1. Direcția generală

Proiectul trebuie să aibă 3 experiențe vizuale distincte, dar coerente între ele:

### A. Public / Guest

Rol: imagine, brand, conversie, prezentare.

Caracter:

* premium
* elegant
* memorabil
* aerisit
* orientat pe storytelling și încredere

### B. Authenticated App (user / merchant)

Rol: lucru curent, orientare rapidă, stare clară.

Caracter:

* organizat
* cald, dar mai sobru decât public
* clar
* ușor de scanat

### C. Admin

Rol: control, eficiență, management.

Caracter:

* practic
* densitate bună de informație
* full-width sau aproape full-width
* fără elemente decorative inutile
* focus pe filtre, listări, statusuri și acțiuni

---

## 2. Shell-urile principale

## 2.1 Public Guest Shell

### Structură

* Header sticky elegant
* Logo stânga
* Navigație principală centru/dreapta
* CTA-uri în header
* Hero / page intro pentru paginile publice importante
* Conținut în secțiuni late, aerisite
* Footer bine structurat

### Header guest

Elemente:

* Logo Floraffeine Boutique
* Acasă
* Despre Boutique
* Creatori / Comercianți
* Evenimente / Showcase
* Cum funcționează
* Autentificare
* Creează cont

### Componente publice cheie

* hero section
* split content section
* premium cards
* featured creators grid
* CTA banner
* testimonials / trust blocks
* info highlights
* FAQ section
* footer columns

### Responsive

* desktop: header complet
* tablet: nav comprimată
* mobile: off-canvas menu + CTA clar

---

## 2.2 Authenticated App Shell (user / merchant)

### Structură

* Sidebar stânga
* Topbar sus
* Content area principală
* Breadcrumb / page title
* Quick actions în header de pagină

### Topbar

Elemente:

* Page title / breadcrumb
* Search opțional
* Notifications placeholder
* Profile dropdown
* Logout

### Sidebar user / merchant

Elemente bază:

* Dashboard
* Profil
* Date cont
* Comenzi / activitate
* Favorite / salvate (dacă va exista)

### Sidebar merchant

Elemente probabile:

* Dashboard
* Profil merchant
* Produse
* Cereri / comenzi
* Evenimente / showcase
* Abonament / plan
* Setări

### Componente authenticated

* page header
* stats cards
* information cards
* empty states
* profile summary block
* tabs / subnavigation
* action toolbar

### Responsive

* desktop: sidebar fix
* tablet: sidebar colapsabil
* mobile: drawer navigation + topbar simplificat

---

## 2.3 Admin Shell

### Structură

* Sidebar administrativ clar
* Topbar utilitar
* Main content full-width / wide-container
* Filtre sus
* Tabele sub filtre
* Acțiuni pe rând / bulk actions unde va fi cazul

### Obiectiv admin

Adminul nu trebuie să fie "deosebit" ca publicul.
Trebuie să fie:

* foarte lizibil
* foarte organizat
* rapid de folosit
* predictibil

### Sidebar admin

Elemente inițiale recomandate:

* Dashboard
* Merchants
* Users
* Products
* Orders
* Events
* Promotions
* Subscriptions
* Settings

### Topbar admin

Elemente:

* titlu zonă
* eventual quick search
* profil admin
* logout

### Componente admin cheie

* filter bar
* table wrapper
* sortable table headers
* status badges
* row actions dropdown
* page actions area
* empty state admin
* pagination bar
* confirm dialog pattern
* details panel / side summary card

### Responsive

* desktop: wide table layout
* tablet: compact filters + scroll container pentru tabele
* mobile: stacked cards pentru listări critice unde e necesar

---

## 3. Componente de bază care trebuie standardizate

## 3.1 Layout / navigație

* app logo
* sidebar nav item
* topbar profile dropdown
* header navigation item
* mobile menu
* page container
* page title block
* breadcrumb

## 3.2 Acțiuni / controale

* primary button
* secondary button
* ghost button
* danger button
* icon button
* dropdown actions
* filter toggle button
* reset filters button

## 3.3 Form / filtre

* input
* select
* textarea
* checkbox
* date range input
* search input
* filter row
* filter card
* inline form actions

## 3.4 Tabele / date

* table wrapper
* table toolbar
* table header cell sortable
* badge status
* row actions
* empty state
* pagination
* summary counters

## 3.5 Feedback

* alert success
* alert error
* alert warning
* info banner
* empty state
* loading state

---

## 4. Pagini-cheie pentru mockupuri

## Public

1. Homepage guest
2. Pagină de prezentare generică (ex: Cum funcționează)
3. Pagină listare publică (ex: comercianți / creatori)

## Authenticated

4. Dashboard user / merchant

## Admin

5. Admin list page cu filtre + tabel + acțiuni
6. Admin detail / review page

---

## 5. Reguli vizuale

### Public

* mai multă personalitate
* spațiu alb generos
* blocuri mari, elegante
* carduri premium
* accent clar de brand

### Logged-in

* echilibru între brand și utilitate
* mai multă structură, mai puțin decor
* accent pe claritate

### Admin

* accent pe utilitate
* contrast bun
* ierarhie clară
* filtre foarte ușor de scanat
* tabele curate și stabile vizual

---

## 6. Recomandare de execuție

### Task 006

Application Shells and Navigation System

Să includă:

* public guest shell
* authenticated shell
* admin shell
* navigație responsive
* sidebar + topbar + profile menu
* bază de page headers
* bază de filter bars și table wrappers pentru admin

### După Task 006

* admin list foundations
* public homepage redesign premium
* logged-in dashboard foundations

---

## 7. Ce NU facem încă

* nu refacem toate paginile business din proiect
* nu implementăm toate tabelele și toate CRUD-urile acum
* nu facem încă design final pentru fiecare modul
* nu amestecăm admin cu public ca stil

---

## 8. Rezultat urmărit

La finalul acestei direcții trebuie să existe:

* un produs care arată matur și organizat
* o diferență clară între public și admin
* o bază reutilizabilă pentru toate paginile viitoare
* responsive real
* o impresie vizuală net superioară față de starea actuală

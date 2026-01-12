# Ticket Wave

Ticket Wave is een Laravel applicatie voor events en tickets:
- Events bekijken (publiek)
- Events beheren (admin/owner)
- Favorieten (ingelogde gebruikers)
- Tickets beheren per event (admin/owner)
- Tickets reserveren (ingelogde gebruikers, met beschikbaarheidscontrole)
- Profielpagina (reservaties + favorieten)
- Password reset (Laravel standaard, lokaal testbaar via log mailer)

Repository: https://github.com/RobinSchepersPXL/Web_Expert_1_PE_25-26

---

## Vereisten

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL/MariaDB
- (Aanbevolen) DDEV

---

## Installatie (DDEV)

### Clone de repository:
git clone https://github.com/RobinSchepersPXL/Web_Expert_1_PE_25-26.git
cd Web_Expert_1_PE_25-26

### Start DDEV:
ddev start

### Installeer dependencies:
ddev exec composer install
ddev exec npm install


### Maak .env aan:
cp .env.example .env
ddev exec php artisan key:generate


### Database importeren (Blackboard export):
ddev import-db --file=database.sql

### Migrations (indien nodig):
ddev exec php artisan migrate

### Storage link:
ddev exec php artisan storage:link

### Vite(indien nodig)
ddev exec npm run build

### Cache leegmaken:
ddev exec php artisan optimize:clear

## Testaccounts / Rollen
Admins worden herkend via users.role = 'admin'.

## Password reset testen (lokaal)
### Gebruik log mailer in .env:
MAIL_MAILER=log


Reset flow:

ga naar /password/reset

vul e-mail in

check reset-link in:
storage/logs/laravel.log

open link en zet nieuw wachtwoord






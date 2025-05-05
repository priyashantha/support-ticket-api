# Support Ticket System — Laravel Backend

This is the backend API for the Support Ticket System, built using Laravel and designed to support customer ticket submissions and support agent interactions.

---

## Features

- Public ticket submission (no login required)
- Email confirmation on ticket creation
- Ticket status check by reference number
- Agent authentication (Laravel Sanctum)
- Agent API to:
    - View/search tickets
    - Reply to tickets
    - Auto-update ticket status on first view

---

## Requirements

- PHP 8.1+
- Composer
- MySQL/MariaDB
- [DDEV](https://ddev.com/) (for local development)

---

## Setup Instructions

This project includes a .ddev/config.yaml file, so setup is quick:

```bash
git clone https://github.com/priyashantha/support-ticket-api.git
cd support-ticket-api

# Start DDEV environment
ddev start

# Install dependencies
ddev composer install

# Copy the environment file
cp .env.example .env

# Generate app key
ddev artisan key:generate

# Run migrations
ddev artisan migrate
```

### Notes
* DDEV automatically provisions a PHP, MySQL, and web server environment.
* After running ddev start, your project will be accessible at: `https://support-ticket-api.ddev.site`
* To run Laravel artisan commands: `ddev artisan <command>`

---

## Authentication

* Sanctum is used for token-based authentication 
* Agent login: `POST /api/login`
* Authenticated routes require Bearer token

---

## Creating Support Agent Accounts

Use the following command to create a new agent account:
`ddev artisan tinker`

Then run:
```php
\App\Models\Agent::create([
    'name' => 'Agent Name',
    'email' => 'agent@example.com',
    'password' => bcrypt('password'), 
]);
```

---

## Mailing

* Laravel's mail system is used to send confirmation emails 
* You may need to configure .env with a mail service (Mailtrap, SMTP, etc.)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@example.com
```

---

## How to Test the API
### 1. Start the server:

`ddev start`

### 2. Use Postman or curl to test:

* Submit a ticket:
```POST /api/tickets```
* Check ticket status:
```GET /api/tickets/{reference_number}```
* Agent login:
```POST /api/login```
* Reply to a ticket:
```POST /api/agent/tickets/{reference_number}/reply```

---

## Improvements Made

* Created a TicketResource to keep API responses clean and consistent 
* Automatically updated ticket_status when an agent views a ticket 
* Included replies_count in listing for better frontend UX 
* Added validation responses with JSON formatting 
* Used Laravel FormRequest classes to validate ticket submissions and replies

---

## Assumptions Made

* All support agents use a shared admin panel — no ticket assignment yet 
* Customers are not registered/logged in — only reference number is used to access their ticket 
* Emails are not sent in local/dev unless Mailtrap or SMTP is configured 
* Tokens expire only if manually revoked


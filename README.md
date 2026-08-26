# CaveJoz — Web Client

The CodeIgniter 4 front end for **CaveJoz**, a social media application. This repository renders the user interface and talks to the [CaveJoz Go API](https://github.com/BatJoz21/cavejoz-go-api) over HTTP and WebSocket.

CaveJoz is built as a full-stack learning project: a Go/Gin REST API backed by MySQL, with this CI4 application as the client.

---

## Features

**Accounts**
- Registration with avatar upload, login, and logout
- Session-based auth holding the access and refresh tokens issued by the API
- Editable profile (full name, username, email, bio, avatar) with live avatar preview

**Posts**
- Create, edit, and delete posts with an image and caption
- Public or friends-only visibility
- Paginated feed and profile timelines ("Load More")
- Likes with live counts, and threaded comments with pagination

**Social**
- Friend requests: send, accept, decline, and remove
- User search
- Public and friends-only profiles

**Notifications**
- Dedicated notifications page plus a bell dropdown
- Live push over WebSocket for likes, comments, and friend activity
- Unread indicator and per-notification read tracking

**Direct Messages**
- One-to-one conversations
- Real-time delivery over WebSocket
- Typing indicator, cursor-based history loading, and read watermarks

**Interface**
- Dark, cave-inspired theme with a warm amber accent
- Responsive down to mobile, with an overlay sidebar on small screens

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | CodeIgniter 4 |
| Language | PHP 8.1+ |
| HTTP client | Guzzle |
| Styling | Bootstrap 5 + custom CSS |
| Icons | Bootstrap Icons |
| Scripting | Vanilla JavaScript (no build step) |
| Backend | [CaveJoz Go API](https://github.com/BatJoz21/cavejoz-go-api) (Go, Gin, MySQL) |

---

## Prerequisites

- PHP 8.1 or newer, with the `intl`, `mbstring`, and `curl` extensions enabled
- Composer
- A running instance of the CaveJoz Go API
- MySQL (used by the API, not directly by this application)

---

## Getting Started

**1. Clone the repository**

```bash
git clone https://github.com/BatJoz21/cavejoz-ci4.git
cd cavejoz-ci4
```

**2. Install dependencies**

```bash
composer install
```

**3. Create your environment file**

```bash
cp env .env
```

**4. Configure it**

```dotenv
CI_ENVIRONMENT = development

app.baseURL = 'http://cavejoz.localhost/'

# WebSocket endpoint exposed by the Go API
api.wsBaseURL = 'ws://localhost:8080'
```

**5. Start the application**

```bash
php spark serve
```

The app is then available at `http://localhost:8080`, or at your configured `app.baseURL` if you are running it through Apache or Nginx.

Make sure the Go API is running before you sign in — this client has no local database of its own.

---

## Project Structure

```
app/
├── Cells/          View cells (e.g. the notification bell)
├── Config/         Framework and application configuration
├── Controllers/    Request handling and API orchestration
├── Filters/        Auth and session filters
├── Helpers/        Shared helpers (e.g. relative time formatting)
├── Services/       Guzzle-based clients for the Go API
└── Views/
    ├── Layouts/    Main and auth layouts, header, sidebar
    └── ...         Page views

public/
└── assets/
    ├── css/
    └── js/         utils.js and page scripts
```

---

## Notes on Configuration

**Timestamps.** The API stores and returns timestamps in UTC. Relative times ("2 hours ago") are calculated client-side and in PHP helpers, so no timezone conversion is needed in this application.

**CSRF.** CSRF protection is enabled. Forms use `csrf_field()`; JavaScript requests read the token from meta tags in the layout head.

**Uploads.** Avatars and post images are served through the API. The relevant base URLs are exposed to JavaScript from the layout.

---

## Related Repositories

- **CaveJoz Go API** — [CaveJoz Go API](https://github.com/BatJoz21/cavejoz-go-api)

---

## License

MIT

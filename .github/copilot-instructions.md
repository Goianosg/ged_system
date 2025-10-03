## Copilot Instructions for GED System

### Project Overview
This is a PHP MVC application for document and collaborator management (GED = Gestão Eletrônica de Documentos). It uses custom routing, controllers, models, and views, with authentication and permission logic. The UI is based on the NiceAdmin Bootstrap template.

### Architecture & Key Files
- **Entry Point:** `public/index.php` initializes config, core classes, and starts routing.
- **Config:** `config/config.php` defines DB credentials, app root, and URL constants.
- **Routing:** `app/core/Core.php` parses URLs and dispatches to controllers/methods. Default is `AuthController@login`.
- **Controllers:** `app/controllers/` handle HTTP requests, session checks, and permission logic. Example: `DashboardController`, `ColaboradoresController`, `PdfsController`, `UsersController`, `GroupsController`.
- **Models:** `app/models/` encapsulate DB logic. All use a shared `Database` class (PDO wrapper in `app/core/Database.php`).
- **Views:** `app/views/` are plain PHP files, rendered via `Controller->view()`.
- **Helpers:** `app/helpers/format_helper.php` for formatting utilities (e.g., file sizes).
- **Uploads:** Files are stored in `public/uploads/` and referenced in DB.

### Data & Permissions
- **Users:** Linked to groups; permissions are checked via `$_SESSION['user_permissions']`.
- **Groups:** Manage permissions via `GroupsController` and `Group` model.
- **Colaboradores:** Rich profile, validation for unique email/CPF/RG.
- **Files/PDFs:** Linked to users/groups/colaboradores; deletion removes both DB record and physical file.

### Developer Workflows
- **Local Dev:** Run via XAMPP (Apache + MySQL). Point browser to `http://localhost/ged_system/public`.
- **DB Schema:** See `config/colaboradores.sql` for table structure. Other tables: `usuarios`, `grupos`, `permissoes`, `arquivos`, etc.
- **Error Debugging:** Errors are displayed (`ini_set('display_errors', 1)` in `index.php`).
- **Session Auth:** Most controllers check `$_SESSION['user_id']` and redirect if not set.
- **Permission Checks:** Use `in_array('permission_key', $_SESSION['user_permissions'])` before sensitive actions.

### Project Conventions
- **Controller Naming:** Always ends with `Controller` (e.g., `UsersController`).
- **Model Naming:** Singular, matches table (e.g., `User`, `Group`).
- **View Naming:** Follows controller/action (e.g., `users/index.php`, `colaboradores/show.php`).
- **DB Access:** Always via model, never directly in controllers/views.
- **Uploads:** Use `uniqid()` for file names; store physical path in DB.
- **Redirects:** Use `header('Location: ...')` and `exit()` after state changes.

### External Dependencies
- **TinyMCE:** Rich text editor in `public/assets/vendor/tinymce/` (see its README for usage).
- **Bootstrap/NiceAdmin:** UI assets in `public/assets/`.

### Examples
- **Add User:** `UsersController@create` → `User::create()` → redirects to `/users`.
- **Delete PDF:** `PdfsController@deletePdf` removes file from disk and DB, then redirects.
- **Permission Enforcement:**
	```php
	if (!in_array('delete_user', $_SESSION['user_permissions'])) { header('Location: ...'); exit(); }
	```

### Tips for AI Agents
- Always use models for DB access.
- Always check session and permissions before mutating actions.
- Use config constants (`APPROOT`, `URLROOT`) for paths/URLs.
- When adding new features, follow the MVC pattern and naming conventions above.

---
If updating these instructions, merge new insights and preserve any future valuable content.

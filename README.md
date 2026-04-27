# YBMedicalClinic

YBMedicalClinic is a Laravel medical appointment management app for patients, doctors, and admins. It manages clinic services, doctors, users, appointments, notifications, profile updates, and appointment emails.

## Tech Stack

- Laravel 12
- Laravel Breeze authentication structure
- Blade templates
- Tailwind CSS
- Vite
- Alpine.js
- Axios
- MySQL or SQLite through Laravel database configuration
- Pest for tests

## Main Features And Where They Are Used

### Authentication And Layouts

Authentication is handled with Laravel Breeze-style controllers, routes, and Blade views.

Main files:

- `routes/auth.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Controllers/Auth/VerifyEmailController.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/verify-email.blade.php`

Layouts are separated by page type:

- `resources/views/layouts/guest.blade.php` for login, register, password reset, and email verification screens.
- `resources/views/layouts/main.blade.php` for the main authenticated dashboard, sidebar, top navbar, profile link, logout button, and notifications dropdown.
- `resources/views/layouts/app.blade.php` and `resources/views/layouts/navigation.blade.php` are Breeze layout files still available in the project.
- `resources/views/partials/favicon.blade.php` loads the browser tab icons.

### Migrations, Seeders, And Factories

The database structure is created with Laravel migrations.

Important migrations:

- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2026_04_23_164853_create_services_table.php`
- `database/migrations/2026_04_23_164939_create_appointments_table.php`
- `database/migrations/2026_04_23_175255_add_role_to_users_table.php`
- `database/migrations/2026_04_27_000001_create_notifications_table.php`
- `database/migrations/2026_04_27_000002_create_doctor_service_table.php`
- `database/migrations/2026_04_27_000003_add_profile_photo_path_to_users_table.php`

Seeders and factories generate test/development data:

- `database/seeders/DatabaseSeeder.php`
- `database/seeders/UserSeeder.php`
- `database/seeders/ServiceSeeder.php`
- `database/seeders/AppointmentSeeder.php`
- `database/factories/UserFactory.php`
- `database/factories/ServiceFactory.php`
- `database/factories/AppointmentFactory.php`

Main models:

- `app/Models/User.php`
- `app/Models/Service.php`
- `app/Models/Appointment.php`

The models define relationships such as:

- A patient has many appointments.
- A doctor has many appointments.
- A doctor belongs to many services through the `doctor_service` pivot table.
- A service has many appointments.
- An appointment belongs to a patient, doctor, and service.

### CRUD Management

The app uses Laravel resource routes and controllers for the main business logic.

Services CRUD:

- Routes: `routes/web.php`
- Controller: `app/Http/Controllers/ServiceController.php`
- Views:
  - `resources/views/services/index.blade.php`
  - `resources/views/services/create.blade.php`
  - `resources/views/services/edit.blade.php`

Appointments CRUD:

- Routes: `routes/web.php`
- Controller: `app/Http/Controllers/AppointmentController.php`
- Views:
  - `resources/views/appointments/index.blade.php`
  - `resources/views/appointments/create.blade.php`
  - `resources/views/appointments/edit.blade.php`

Doctors management:

- Routes: `routes/web.php`
- Controller: `app/Http/Controllers/DoctorController.php`
- Views:
  - `resources/views/doctors/index.blade.php`
  - `resources/views/doctors/create.blade.php`
  - `resources/views/doctors/edit.blade.php`

Users and roles:

- Controller: `app/Http/Controllers/UserController.php`
- View: `resources/views/users/index.blade.php`
- Admins can update user roles.

Profile management:

- Controller: `app/Http/Controllers/ProfileController.php`
- Request validation: `app/Http/Requests/ProfileUpdateRequest.php`
- Views:
  - `resources/views/profile/edit.blade.php`
  - `resources/views/profile/partials/update-profile-information-form.blade.php`
  - `resources/views/profile/partials/update-password-form.blade.php`
  - `resources/views/profile/partials/delete-user-form.blade.php`

Role-based behavior:

- Admins can manage services, doctors, and user roles.
- Patients can create appointments for themselves.
- Doctors can view assigned appointments and update appointment status.

### Modal Windows And Interface

The app uses modal windows for confirmation and detail views.

Where modals are used:

- Appointment delete confirmation and appointment detail view: `resources/views/appointments/index.blade.php`
- Service delete confirmation and service detail view: `resources/views/services/index.blade.php`
- Profile delete confirmation: `resources/views/profile/partials/delete-user-form.blade.php`
- Reusable Alpine modal component: `resources/views/components/modal.blade.php`

Internationalization support is partially present through Laravel helpers such as `__()` in Breeze/profile components, but most custom dashboard text is currently written directly in English inside Blade files. There is no custom `resources/lang` directory in the current code.

### Dynamic Search With Axios

Appointment search is dynamic and uses Axios.

Where it is used:

- Axios setup: `resources/js/bootstrap.js`
- Appointment search input and JavaScript: `resources/views/appointments/index.blade.php`
- Search route: `routes/web.php`
- Search method: `app/Http/Controllers/AppointmentController.php`

The search endpoint:

```text
GET /appointments/search?q=search-text
```

It returns JSON appointment data with patient, doctor, and service relationships loaded.

### Mailing And Automatic Notifications

The app sends mail and stores database notifications for important appointment and service events.

Appointment creation:

- Mail class: `app/Mail/AppointmentCreatedMail.php`
- Email view: `resources/views/emails/appointment_created.blade.php`
- Sent from: `app/Http/Controllers/AppointmentController.php`

When a patient creates an appointment:

- The assigned doctor receives a database notification.
- The patient receives an appointment confirmation email.

Appointment status updates:

- Notification: `app/Notifications/AppointmentStatusUpdatedNotification.php`
- Triggered from: `app/Http/Controllers/AppointmentController.php`

When a doctor confirms or cancels an appointment:

- The patient receives a database notification.
- The patient can also receive a mail notification.

New service notifications:

- Notification: `app/Notifications/NewServiceNotification.php`
- Triggered from: `app/Http/Controllers/ServiceController.php`

When an admin creates a new service:

- Patients and doctors receive a database notification.

Notification display and actions:

- Layout dropdown: `resources/views/layouts/main.blade.php`
- Controller: `app/Http/Controllers/NotificationController.php`
- Routes: `routes/web.php`

### REST API And JSON Endpoints

The app has basic JSON endpoints in `routes/api.php`.

Current API routes:

```text
GET  /api/appointments
POST /api/appointments
```

What they do:

- `GET /api/appointments` returns appointments with patient, doctor, and service relationships.
- `POST /api/appointments` creates an appointment from the request data.

The web appointment search route also returns JSON:

```text
GET /appointments/search?q=...
```

Current note: the API is basic. It currently uses route closures and does not have a full API controller, authentication, update/delete endpoints, or custom API request validation.

## Important Web Routes

```text
GET    /
GET    /dashboard
GET    /appointments
POST   /appointments
GET    /appointments/create
GET    /appointments/search
PUT    /appointments/{appointment}/status
GET    /services
POST   /services
GET    /services/create
GET    /doctors
POST   /doctors
GET    /users
PUT    /users/{user}/role
GET    /profile
PATCH  /profile
DELETE /profile
```

Authentication routes include login, register, logout, password reset, password confirmation, and email verification routes in `routes/auth.php`.

## Installation

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the app key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Optional: seed the database:

```bash
php artisan db:seed
```

Start the Laravel server:

```bash
php artisan serve
```

Start Vite:

```bash
npm run dev
```

The project also has a combined dev script in `composer.json`:

```bash
composer run dev
```

## Testing

Run the test suite:

```bash
php artisan test
```

Or use the Composer script:

```bash
composer test
```

Tests are located in:

- `tests/Feature`
- `tests/Unit`

Feature tests cover authentication, profile updates, appointment behavior, doctor management, notifications, and dashboard behavior.

## Current Notes

- `routes/api.php` contains basic JSON endpoints, but the API is not yet a complete REST API.
- Some Laravel resource routes register `show` endpoints for services and appointments, but the current controllers focus on index, create, store, edit, update, and destroy behavior.
- Internationalization is only partially present through Laravel translation helper usage. Most custom UI text is not yet moved into language files.
- Mail is configured through Laravel's normal mail settings in `.env`; the default example configuration uses the log mailer.

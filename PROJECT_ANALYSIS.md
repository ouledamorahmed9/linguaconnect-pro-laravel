# LinguaConnect Pro — Deep Project Study

Prepared as a reference for future feature planning, triage, and implementation.

## Snapshot
- **Stack**: Laravel 9 (PHP ≥ 8.0.2), Sanctum (API tokens), Breeze (auth scaffolding), Vite ^4 + Tailwind ^3.1 + Alpine ^3.4.2 + Axios ^1.1 (frontend), Spatie Activity Log, Doctrine DBAL.  
- **Roles**: Admin, Coordinator, Teacher, Client, Guest. Authorization mostly enforced via route middleware (`role:*`, `auth`, `verified`).
- **Key domains**: Scheduling (weekly slots), Appointments with verification/disputes, Subscriptions, Messaging/Inbox, Reviews, Referrals, Study Subjects, Session reporting.

## Domain model (high level)
- `User`: Role-based, referral code/referrer, profile & banner media, study subject relation, teacher/client pivot (`client_teacher`), weekly slot membership (`weekly_slot_student`), unread message count accessor.
- `WeeklySlot`: Teacher-owned recurring slot; legacy single `client_id` plus multi-student pivot.
- `Appointment`: Client ↔ Teacher session with statuses, teacher notes, proof attachment id, completion status, Meet report relation; disputes relation.
- `Subscription`: Client subscriptions (status, totals vs. lessons used); coordinated/admin-created paths and client self-purchase routes.
- `Message`: Sender/recipient with read state; inbox helpers/scopes.
- `Review`: Client reviews for teachers, with average rating accessor on `User`.
- `StudySubject`, `Dispute`, `MeetReport`: Supporting domain objects for cataloging subjects, handling disputes, and meet/session reports.

## Core flows & surfaces
- **Public**: Landing (`/`), Teachers index/detail with reviews, pricing, contact, legal pages, referral links that set a cookie for signup attribution.
- **Dashboards**: Role-aware `/dashboard` dispatcher redirects to admin/teacher/client panels; coordinators reuse admin dashboard route.
- **Scheduling**: Teachers manage weekly slots (create/update/delete) and session logs; roster management for Admins/Coordinators.
- **Appointments**: Verification and cancellation flows for Admin/Coordinator; disputes lifecycle with resolve/cancel; teacher session logging.
- **Subscriptions**: Admin/Coordinator can create/destroy client subscriptions; clients have self-serve create/store routes.
- **Messaging**: Inbox pages per role with read/unread controls; unread counts via model accessor.
- **Reviews & referrals**: Clients can review teachers; automatic referral code generation on user create and referrer->referral relations.
- **Activity log**: Spatie package hooked in (routes for admin activity log view).
- **API touchpoints**: Meet report controller under `Api`; Sanctum token generation route for teachers (`/profile/token`).

## Tooling, build, and tests
- **Backend**: `composer install` then `php artisan test` (tests exist under `tests/Feature`, e.g., auth/profile). Current install fails in this environment at `artisan package:discover` due to missing DB connection; configure `.env`/DB before running.
- **Frontend**: `npm install`, `npm run dev` for local Vite server, `npm run build` for production assets.
- **Quality**: Laravel Pint present for formatting (`vendor/bin/pint`). No custom CI config observed locally.

## Observations / risks
- DB connection is required during package discovery; provide `.env` with reachable DB (or set `DB_CONNECTION=sqlite` with file) to run installs/tests locally.
- Role checks rely on `User::hasRole` string comparison; define central role constants/enum and enforce via policies/middleware to prevent mismatched strings or bypasses.
- Many controllers assume existing related records; add validation/authorization policies when extending to APIs or exposing new endpoints.
- Storage-backed media (profile/banner) and session proof IDs expect configured filesystem disk; confirm `FILESYSTEM_DISK`/S3 settings before rollout.

## Opportunity areas for future improvements
- **Authorization hardening**: Add policies/gates for admin/coordinator actions and inbox/message access; centralize role constants/enums.
- **Scheduling UX**: Clarify multi-student weekly slot behavior and legacy `client_id`; consider availability conflicts and timezone handling.
- **Subscriptions & billing**: Define plan catalog, payment provider integration, and lesson-usage reconciliation; surface remaining lessons to users.
- **Session lifecycle**: Standardize appointment statuses, proof storage, and meet reporting; add audit events to Activity Log.
- **Testing**: Expand feature tests around role-based routing (`/dashboard`), roster CRUD, referral link flow, and messaging read/unread logic.
- **Observability**: Add logging/metrics around disputes, verification actions, and token generation; ensure activity log pruning/PII handling.

Use this document as a launchpad for requirement gathering and grooming when adding or refining features. If a new feature touches any flow above, align it with the stated models, routes, and role expectations.

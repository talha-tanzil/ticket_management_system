# Ticket Management System

A web-based Ticket Management System designed to streamline ticket creation, tracking, and administrative setup using **PHP**, **MySQLi**, **Bootstrap 5**, **jQuery**, and **AJAX**.

## Key Features

- **Ticket Workflow & Creation**: Create and manage support/service tickets with dynamic AJAX-powered dropdowns and structured form handling.
- **Role-Based Access Control**: Session-based access checks (`user_level` / designations such as Admin, Group Admin, and User) to protect administrative and operational views.
- **Modular Management Modules**: Dedicated management views and AJAX modal workflows for:
  - **Users** (create, toggle status, delete, assign departments and roles)
  - **Categories** (create with duplicate checking, toggle status, delete)
  - **Subcategories** (linked to parent categories with category-scoped duplicate validation)
  - **Departments** (create with duplicate checking, toggle status, delete)
- **Asynchronous UI (AJAX + Bootstrap Modals)**: Loads modal forms dynamically, submits serialized form data without full page reloads, displays validation/duplicate alerts inline, and refreshes data cleanly on success.

## Tech Stack

- **Backend**: PHP, MySQLi
- **Frontend**: HTML5, CSS3, Bootstrap 5, jQuery, AJAX, Font Awesome
- **Database**: MySQL

## Project Structure (Overview)

- `db.php` — Database connection configuration
- `header.php` / `footer.php` — Shared layout, navigation, and asset includes
- `create_ticket.php` — Ticket creation interface with dynamic AJAX interactions
- `add_new_user.php`, `add_new_category.php`, `add_new_subcategory.php`, `add_new_department.php` — Management pages with tables, status toggles, deletions, and modal containers
- `action-*-form.php` — Backend handlers for loading modal forms (`CREATE_MODAL_OPEN`) and processing insertions/validations.

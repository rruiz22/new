# Vehicles Module

## Overview

The Vehicles module is a standalone module for managing and tracking vehicle information independently from the Recon Orders module. This separation provides better organization and permission management.

## Features

- **Vehicle Registry**: Complete list of all vehicles with search and filtering
- **Vehicle Details**: Detailed view of individual vehicles with service history
- **Statistics Dashboard**: Overview of vehicle statistics and metrics
- **Service History**: Timeline of all services performed on each vehicle
- **Search Functionality**: Search vehicles by VIN, make, model, or year

## Structure

```
app/Modules/Vehicles/
├── Controllers/
│   └── VehiclesController.php
├── Views/
│   └── vehicles/
│       ├── index.php
│       └── view.php
├── Config/
│   ├── Routes.php
│   └── Vehicles.php
├── Models/
├── Database/
└── README.md
```

## Routes

- `GET /vehicles` - Vehicle registry index
- `GET /vehicles/view/{id}` - Vehicle details view
- `GET /vehicles/search` - Vehicle search endpoint
- `POST /vehicles/data` - DataTables data endpoint
- `GET /vehicles/stats` - Vehicle statistics endpoint

## Database

The module uses the existing `recon_orders` table to extract vehicle information. It groups records by VIN number to create unique vehicle entries.

## Permissions

The module requires user authentication and uses the `sessionauth` filter for all routes.

## Usage

1. Access the vehicle registry via the sidebar menu
2. Search for vehicles using the search bar
3. Click "View History" to see detailed vehicle information
4. View service history and statistics for each vehicle

## Configuration

The module can be configured through the `app/Modules/Vehicles/Config/Vehicles.php` file, which includes:

- Search fields configuration
- Display fields mapping
- Vehicle types and status options
- Pagination settings

## Dependencies

- CodeIgniter 4
- Bootstrap 5
- DataTables
- Remix Icons

## Installation

The module is automatically loaded when the application starts. No additional installation steps are required.

## Updates

### Version 1.0.0
- Initial release
- Basic vehicle management functionality
- Statistics dashboard
- Service history tracking 
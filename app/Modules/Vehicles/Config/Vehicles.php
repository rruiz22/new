<?php

namespace Modules\Vehicles\Config;

use CodeIgniter\Config\BaseConfig;

class Vehicles extends BaseConfig
{
    /**
     * Module name
     */
    public $moduleName = 'Vehicles';

    /**
     * Module description
     */
    public $moduleDescription = 'Vehicle management module for tracking and managing vehicle information';

    /**
     * Module version
     */
    public $moduleVersion = '1.0.0';

    /**
     * Module author
     */
    public $moduleAuthor = 'MDA System';

    /**
     * Default page size for vehicle listings
     */
    public $defaultPageSize = 25;

    /**
     * Maximum page size for vehicle listings
     */
    public $maxPageSize = 100;

    /**
     * Vehicle search fields
     */
    public $searchFields = [
        'vehicle_info',
        'vin_number',
        'make',
        'model',
        'year'
    ];

    /**
     * Vehicle display fields
     */
    public $displayFields = [
        'vehicle_info' => 'Vehicle Info',
        'vin_number' => 'VIN Number',
        'year' => 'Year',
        'make' => 'Make',
        'model' => 'Model',
        'color' => 'Color'
    ];

    /**
     * Vehicle status options
     */
    public $statusOptions = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'maintenance' => 'In Maintenance'
    ];

    /**
     * Vehicle types
     */
    public $vehicleTypes = [
        'car' => 'Car',
        'truck' => 'Truck',
        'suv' => 'SUV',
        'van' => 'Van',
        'motorcycle' => 'Motorcycle',
        'other' => 'Other'
    ];
} 
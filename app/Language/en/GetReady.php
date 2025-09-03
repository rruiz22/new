<?php

return [
    // Module Info
    'module_title' => 'Get Ready',
    'module_description' => 'Vehicle preparation tracking and workflow management system',
    'get_ready_dashboard' => 'Get Ready Dashboard',

    // Steps
    'in_transit' => 'In Transit',
    'in_detail' => 'In Detail', 
    'in_service' => 'In Service',
    'in_bodyshop' => 'In Bodyshop',
    'completed' => 'Completed',

    // Step Descriptions
    'in_transit_desc' => 'Vehicle is being transported to the facility',
    'in_detail_desc' => 'Vehicle is being detailed and cleaned',
    'in_service_desc' => 'Vehicle is being serviced by technicians',
    'in_bodyshop_desc' => 'Vehicle is in bodyshop for repairs',
    'completed_desc' => 'Vehicle preparation completed',

    // Navigation
    'dashboard' => 'Dashboard',
    'service_manager' => 'Service Manager',
    'analytics' => 'Analytics',
    'settings' => 'Settings',

    // Metrics & Stats
    'vehicles_in_step' => 'Vehicles in Step',
    'average_time' => 'Average Time',
    'days' => 'Days',
    'hours' => 'Hours', 
    'minutes' => 'Minutes',
    'longest_wait' => 'Longest Wait',
    'today_arrivals' => "Today's Arrivals",
    'total_active' => 'Total Active',
    'completed_today' => 'Completed Today',
    'overdue_vehicles' => 'Overdue Vehicles',
    'by_step' => 'By Step',

    // Table Headers
    'vehicle_info' => 'Vehicle Info',
    'client' => 'Client',
    'days_in_step' => 'Days in Step',
    'total_time' => 'Total Time',
    'assigned_to' => 'Assigned To',
    'location' => 'Location',
    'status' => 'Status',
    'actions' => 'Actions',
    'created_at' => 'Created',
    'updated_at' => 'Updated',

    // Actions
    'scan_nfc' => 'Scan NFC',
    'move_to_next' => 'Move to Next Step',
    'assign_tech' => 'Assign Technician',
    'add_photos' => 'Add Photos',
    'view_details' => 'View Details',
    'edit_vehicle' => 'Edit Vehicle',
    'delete_vehicle' => 'Delete Vehicle',
    'print_sheet' => 'Print Get Ready Sheet',
    'download_pdf' => 'Download PDF',
    'pause_timer' => 'Pause Timer',
    'resume_timer' => 'Resume Timer',
    'update_location' => 'Update Location',

    // Vehicle Form
    'add_vehicle' => 'Add Vehicle',
    'edit_vehicle' => 'Edit Vehicle',
    'vin_number' => 'VIN Number',
    'stock_number' => 'Stock Number',
    'year' => 'Year',
    'make' => 'Make',
    'model' => 'Model',
    'color' => 'Color',
    'mileage' => 'Mileage',
    'client' => 'Client',
    'contact' => 'Contact',
    'priority' => 'Priority',
    'expected_completion' => 'Expected Completion',
    'notes' => 'Notes',
    'internal_notes' => 'Internal Notes',

    // Priority Levels
    'normal' => 'Normal',
    'urgent' => 'Urgent',
    'high' => 'High',
    'low' => 'Low',

    // Status Values  
    'active' => 'Active',
    'on_hold' => 'On Hold',
    'cancelled' => 'Cancelled',

    // Service Manager
    'unassigned_vehicles' => 'Unassigned Vehicles',
    'tech_workload' => 'Tech Workload',
    'assign_technician' => 'Assign Technician',
    'technician' => 'Technician',
    'current_workload' => 'Current Workload',
    'assign' => 'Assign',
    'unassign' => 'Unassign',

    // Timer & Time Tracking
    'timer' => 'Timer',
    'time_in_step' => 'Time in Step', 
    'time_paused' => 'Timer Paused',
    'time_running' => 'Timer Running',
    'pause' => 'Pause',
    'resume' => 'Resume',
    'elapsed_time' => 'Elapsed Time',
    'total_elapsed' => 'Total Elapsed',

    // Photos & Media
    'photos' => 'Photos',
    'upload_photos' => 'Upload Photos',
    'photo_gallery' => 'Photo Gallery',
    'no_photos' => 'No photos available',
    'photos_uploaded' => 'photos uploaded',
    'delete_photo' => 'Delete Photo',

    // Activities & History
    'activity_log' => 'Activity Log',
    'recent_activities' => 'Recent Activities',
    'no_activities' => 'No activities yet',
    'created' => 'Created',
    'moved_to_step' => 'Moved to Step',
    'assigned_tech' => 'Assigned Tech',
    'added_photos' => 'Added Photos',
    'updated_location' => 'Updated Location',
    'nfc_scanned' => 'NFC Scanned',
    'timer_paused' => 'Timer Paused',
    'timer_resumed' => 'Timer Resumed',
    'notes_added' => 'Notes Added',
    'status_changed' => 'Status Changed',
    'deleted' => 'Deleted',

    // NFC & Mobile
    'nfc_token' => 'NFC Token',
    'scan_qr_nfc' => 'Scan QR or NFC',
    'mobile_interface' => 'Mobile Interface',
    'quick_actions' => 'Quick Actions',

    // Notifications
    'vehicle_entered_step_notification' => 'Vehicle %s (%s) has entered %s at %s',
    'tech_assignment_notification' => 'Hi %s, vehicle %s (%s) has been assigned to you in %s at %s',
    'overdue_vehicle_notification' => 'ALERT: Vehicle %s (%s) is %d days overdue in %s at %s',
    'completion_notification' => 'Hi %s, your vehicle %s (%s) is ready! Total time: %s at %s',
    'emergency_notification' => '%s ALERT: Vehicle %s (%s) requires immediate attention in %s',
    'daily_summary_header' => 'Get Ready Daily Summary',
    'total_active_vehicles' => 'Total Active: %d vehicles',
    'overdue_vehicles' => 'Overdue: %d vehicles', 
    'test_notification_message' => 'This is a test notification from Get Ready system',
    'generated_by_mda' => 'Generated by My Detail Area',

    // Messages
    'order_created_successfully' => 'Get Ready order created successfully',
    'failed_to_create_order' => 'Failed to create Get Ready order',
    'vehicle_updated_successfully' => 'Vehicle updated successfully',
    'failed_to_update_vehicle' => 'Failed to update vehicle',
    'vehicle_deleted_successfully' => 'Vehicle deleted successfully',
    'failed_to_delete_vehicle' => 'Failed to delete vehicle',
    'vehicle_moved_successfully' => 'Vehicle moved to %s successfully',
    'failed_to_move_vehicle' => 'Failed to move vehicle',
    'technician_assigned_successfully' => 'Technician %s assigned successfully',
    'failed_to_assign_technician' => 'Failed to assign technician',
    'location_updated_successfully' => 'Location updated successfully',
    'failed_to_update_location' => 'Failed to update location',
    'photos_added_successfully' => '%d photos added successfully',
    'photos_uploaded_successfully' => '%d photos uploaded successfully',
    'failed_to_add_photos' => 'Failed to add photos',
    'failed_to_upload_photos' => 'Failed to upload photos',
    'photo_deleted_successfully' => 'Photo deleted successfully',
    'failed_to_delete_photo' => 'Failed to delete photo',
    'timer_paused_successfully' => 'Timer paused successfully',
    'failed_to_pause_timer' => 'Failed to pause timer',
    'timer_resumed_successfully' => 'Timer resumed successfully', 
    'failed_to_resume_timer' => 'Failed to resume timer',

    // Validation Messages
    'vin_required' => 'VIN number is required',
    'vin_unique' => 'This VIN number already exists',
    'client_required' => 'Client is required',
    'step_required' => 'Step is required',

    // Print & Reports
    'get_ready_sheet' => 'Get Ready Sheet',
    'progress_report' => 'Progress Report',
    'completion_certificate' => 'Completion Certificate',
    'analytics_report' => 'Analytics Report',
    'checklist' => 'Checklist',
    'exterior_inspection' => 'Exterior Inspection',
    'interior_inspection' => 'Interior Inspection',
    'mechanical_systems' => 'Mechanical Systems',
    'electrical_systems' => 'Electrical Systems',
    'safety_systems' => 'Safety Systems',
    'technology_systems' => 'Technology Systems',
    'final_quality_check' => 'Final Quality Check',
    'generation_date' => 'Generation Date',
    'certificate_number' => 'Certificate Number',

    // Common
    'loading' => 'Loading...',
    'no_results' => 'No results found',
    'search' => 'Search',
    'filter' => 'Filter',
    'refresh' => 'Refresh',
    'close' => 'Close',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'yes' => 'Yes',
    'no' => 'No',
    'confirm' => 'Confirm',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'view' => 'View',
    'add' => 'Add',
    'update' => 'Update',
    'submit' => 'Submit',

    // Time Formats
    'time_ago' => '%s ago',
    'just_now' => 'just now',
    'minutes_ago' => '%dm ago',
    'hours_ago' => '%dh ago',
    'days_ago' => '%dd ago',

    // Errors
    'vehicle_not_found' => 'Vehicle not found',
    'step_not_found' => 'Step not found',
    'invalid_nfc_token' => 'Invalid NFC token',
    'access_denied' => 'Access denied',
    'something_went_wrong' => 'Something went wrong',

    // Success Messages
    'operation_successful' => 'Operation completed successfully',
    'changes_saved' => 'Changes saved successfully',
    'data_refreshed' => 'Data refreshed successfully',
];
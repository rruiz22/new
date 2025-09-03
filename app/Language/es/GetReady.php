<?php

return [
    // Module Info
    'module_title' => 'Get Ready',
    'module_description' => 'Sistema de seguimiento y gestión de flujo de trabajo de preparación de vehículos',
    'get_ready_dashboard' => 'Panel Get Ready',

    // Steps
    'in_transit' => 'En Tránsito',
    'in_detail' => 'En Detallado', 
    'in_service' => 'En Servicio',
    'in_bodyshop' => 'En Carrocería',
    'completed' => 'Completado',

    // Step Descriptions
    'in_transit_desc' => 'El vehículo está siendo transportado a las instalaciones',
    'in_detail_desc' => 'El vehículo está siendo detallado y limpiado',
    'in_service_desc' => 'El vehículo está siendo reparado por técnicos',
    'in_bodyshop_desc' => 'El vehículo está en carrocería para reparaciones',
    'completed_desc' => 'Preparación del vehículo completada',

    // Navigation
    'dashboard' => 'Panel',
    'service_manager' => 'Gerente de Servicio',
    'analytics' => 'Analíticas',
    'settings' => 'Configuración',

    // Metrics & Stats
    'vehicles_in_step' => 'Vehículos en Paso',
    'average_time' => 'Tiempo Promedio',
    'days' => 'Días',
    'hours' => 'Horas', 
    'minutes' => 'Minutos',
    'longest_wait' => 'Espera Más Larga',
    'today_arrivals' => 'Llegadas de Hoy',
    'total_active' => 'Total Activos',
    'completed_today' => 'Completados Hoy',
    'overdue_vehicles' => 'Vehículos Atrasados',
    'by_step' => 'Por Paso',

    // Table Headers
    'vehicle_info' => 'Info del Vehículo',
    'client' => 'Cliente',
    'days_in_step' => 'Días en Paso',
    'total_time' => 'Tiempo Total',
    'assigned_to' => 'Asignado a',
    'location' => 'Ubicación',
    'status' => 'Estado',
    'actions' => 'Acciones',
    'created_at' => 'Creado',
    'updated_at' => 'Actualizado',

    // Actions
    'scan_nfc' => 'Escanear NFC',
    'move_to_next' => 'Mover al Siguiente Paso',
    'assign_tech' => 'Asignar Técnico',
    'add_photos' => 'Agregar Fotos',
    'view_details' => 'Ver Detalles',
    'edit_vehicle' => 'Editar Vehículo',
    'delete_vehicle' => 'Eliminar Vehículo',
    'print_sheet' => 'Imprimir Hoja Get Ready',
    'download_pdf' => 'Descargar PDF',
    'pause_timer' => 'Pausar Timer',
    'resume_timer' => 'Reanudar Timer',
    'update_location' => 'Actualizar Ubicación',

    // Vehicle Form
    'add_vehicle' => 'Agregar Vehículo',
    'edit_vehicle' => 'Editar Vehículo',
    'vin_number' => 'Número VIN',
    'stock_number' => 'Número de Stock',
    'year' => 'Año',
    'make' => 'Marca',
    'model' => 'Modelo',
    'color' => 'Color',
    'mileage' => 'Millaje',
    'client' => 'Cliente',
    'contact' => 'Contacto',
    'priority' => 'Prioridad',
    'expected_completion' => 'Finalización Esperada',
    'notes' => 'Notas',
    'internal_notes' => 'Notas Internas',

    // Priority Levels
    'normal' => 'Normal',
    'urgent' => 'Urgente',
    'high' => 'Alta',
    'low' => 'Baja',

    // Status Values  
    'active' => 'Activo',
    'on_hold' => 'En Espera',
    'cancelled' => 'Cancelado',

    // Service Manager
    'unassigned_vehicles' => 'Vehículos Sin Asignar',
    'tech_workload' => 'Carga de Trabajo del Técnico',
    'assign_technician' => 'Asignar Técnico',
    'technician' => 'Técnico',
    'current_workload' => 'Carga de Trabajo Actual',
    'assign' => 'Asignar',
    'unassign' => 'Desasignar',

    // Timer & Time Tracking
    'timer' => 'Timer',
    'time_in_step' => 'Tiempo en Paso', 
    'time_paused' => 'Timer Pausado',
    'time_running' => 'Timer Ejecutándose',
    'pause' => 'Pausar',
    'resume' => 'Reanudar',
    'elapsed_time' => 'Tiempo Transcurrido',
    'total_elapsed' => 'Total Transcurrido',

    // Photos & Media
    'photos' => 'Fotos',
    'upload_photos' => 'Subir Fotos',
    'photo_gallery' => 'Galería de Fotos',
    'no_photos' => 'No hay fotos disponibles',
    'photos_uploaded' => 'fotos subidas',
    'delete_photo' => 'Eliminar Foto',

    // Activities & History
    'activity_log' => 'Registro de Actividades',
    'recent_activities' => 'Actividades Recientes',
    'no_activities' => 'No hay actividades aún',
    'created' => 'Creado',
    'moved_to_step' => 'Movido al Paso',
    'assigned_tech' => 'Técnico Asignado',
    'added_photos' => 'Fotos Agregadas',
    'updated_location' => 'Ubicación Actualizada',
    'nfc_scanned' => 'NFC Escaneado',
    'timer_paused' => 'Timer Pausado',
    'timer_resumed' => 'Timer Reanudado',
    'notes_added' => 'Notas Agregadas',
    'status_changed' => 'Estado Cambiado',
    'deleted' => 'Eliminado',

    // NFC & Mobile
    'nfc_token' => 'Token NFC',
    'scan_qr_nfc' => 'Escanear QR o NFC',
    'mobile_interface' => 'Interfaz Móvil',
    'quick_actions' => 'Acciones Rápidas',

    // Notifications
    'vehicle_entered_step_notification' => 'El vehículo %s (%s) ha entrado a %s en %s',
    'tech_assignment_notification' => 'Hola %s, el vehículo %s (%s) te ha sido asignado en %s en %s',
    'overdue_vehicle_notification' => 'ALERTA: El vehículo %s (%s) tiene %d días de retraso en %s en %s',
    'completion_notification' => 'Hola %s, tu vehículo %s (%s) está listo! Tiempo total: %s en %s',
    'emergency_notification' => 'ALERTA %s: El vehículo %s (%s) requiere atención inmediata en %s',
    'daily_summary_header' => 'Resumen Diario Get Ready',
    'total_active_vehicles' => 'Total Activos: %d vehículos',
    'overdue_vehicles' => 'Atrasados: %d vehículos', 
    'test_notification_message' => 'Esta es una notificación de prueba del sistema Get Ready',
    'generated_by_mda' => 'Generado por My Detail Area',

    // Messages
    'order_created_successfully' => 'Orden Get Ready creada exitosamente',
    'failed_to_create_order' => 'No se pudo crear la orden Get Ready',
    'vehicle_updated_successfully' => 'Vehículo actualizado exitosamente',
    'failed_to_update_vehicle' => 'No se pudo actualizar el vehículo',
    'vehicle_deleted_successfully' => 'Vehículo eliminado exitosamente',
    'failed_to_delete_vehicle' => 'No se pudo eliminar el vehículo',
    'vehicle_moved_successfully' => 'Vehículo movido a %s exitosamente',
    'failed_to_move_vehicle' => 'No se pudo mover el vehículo',
    'technician_assigned_successfully' => 'Técnico %s asignado exitosamente',
    'failed_to_assign_technician' => 'No se pudo asignar el técnico',
    'location_updated_successfully' => 'Ubicación actualizada exitosamente',
    'failed_to_update_location' => 'No se pudo actualizar la ubicación',
    'photos_added_successfully' => '%d fotos agregadas exitosamente',
    'photos_uploaded_successfully' => '%d fotos subidas exitosamente',
    'failed_to_add_photos' => 'No se pudieron agregar las fotos',
    'failed_to_upload_photos' => 'No se pudieron subir las fotos',
    'photo_deleted_successfully' => 'Foto eliminada exitosamente',
    'failed_to_delete_photo' => 'No se pudo eliminar la foto',
    'timer_paused_successfully' => 'Timer pausado exitosamente',
    'failed_to_pause_timer' => 'No se pudo pausar el timer',
    'timer_resumed_successfully' => 'Timer reanudado exitosamente', 
    'failed_to_resume_timer' => 'No se pudo reanudar el timer',

    // Validation Messages
    'vin_required' => 'El número VIN es requerido',
    'vin_unique' => 'Este número VIN ya existe',
    'client_required' => 'El cliente es requerido',
    'step_required' => 'El paso es requerido',

    // Print & Reports
    'get_ready_sheet' => 'Hoja Get Ready',
    'progress_report' => 'Reporte de Progreso',
    'completion_certificate' => 'Certificado de Finalización',
    'analytics_report' => 'Reporte de Analíticas',
    'checklist' => 'Lista de Verificación',
    'exterior_inspection' => 'Inspección Exterior',
    'interior_inspection' => 'Inspección Interior',
    'mechanical_systems' => 'Sistemas Mecánicos',
    'electrical_systems' => 'Sistemas Eléctricos',
    'safety_systems' => 'Sistemas de Seguridad',
    'technology_systems' => 'Sistemas de Tecnología',
    'final_quality_check' => 'Verificación Final de Calidad',
    'generation_date' => 'Fecha de Generación',
    'certificate_number' => 'Número de Certificado',

    // Common
    'loading' => 'Cargando...',
    'no_results' => 'No se encontraron resultados',
    'search' => 'Buscar',
    'filter' => 'Filtrar',
    'refresh' => 'Actualizar',
    'close' => 'Cerrar',
    'save' => 'Guardar',
    'cancel' => 'Cancelar',
    'yes' => 'Sí',
    'no' => 'No',
    'confirm' => 'Confirmar',
    'delete' => 'Eliminar',
    'edit' => 'Editar',
    'view' => 'Ver',
    'add' => 'Agregar',
    'update' => 'Actualizar',
    'submit' => 'Enviar',

    // Time Formats
    'time_ago' => 'hace %s',
    'just_now' => 'ahora mismo',
    'minutes_ago' => 'hace %dm',
    'hours_ago' => 'hace %dh',
    'days_ago' => 'hace %dd',

    // Errors
    'vehicle_not_found' => 'Vehículo no encontrado',
    'step_not_found' => 'Paso no encontrado',
    'invalid_nfc_token' => 'Token NFC inválido',
    'access_denied' => 'Acceso denegado',
    'something_went_wrong' => 'Algo salió mal',

    // Success Messages
    'operation_successful' => 'Operación completada exitosamente',
    'changes_saved' => 'Cambios guardados exitosamente',
    'data_refreshed' => 'Datos actualizados exitosamente',
];
<?php

return [
    // Module Info
    'module_title' => 'Get Ready',
    'module_description' => 'Sistema de rastreamento e gestão de fluxo de trabalho de preparação de veículos',
    'get_ready_dashboard' => 'Painel Get Ready',

    // Steps
    'in_transit' => 'Em Trânsito',
    'in_detail' => 'Em Detalhamento', 
    'in_service' => 'Em Serviço',
    'in_bodyshop' => 'Na Funilaria',
    'completed' => 'Completado',

    // Step Descriptions
    'in_transit_desc' => 'O veículo está sendo transportado para as instalações',
    'in_detail_desc' => 'O veículo está sendo detalhado e limpo',
    'in_service_desc' => 'O veículo está sendo reparado por técnicos',
    'in_bodyshop_desc' => 'O veículo está na funilaria para reparos',
    'completed_desc' => 'Preparação do veículo completada',

    // Navigation
    'dashboard' => 'Painel',
    'service_manager' => 'Gerente de Serviço',
    'analytics' => 'Análises',
    'settings' => 'Configurações',

    // Metrics & Stats
    'vehicles_in_step' => 'Veículos na Etapa',
    'average_time' => 'Tempo Médio',
    'days' => 'Dias',
    'hours' => 'Horas', 
    'minutes' => 'Minutos',
    'longest_wait' => 'Maior Espera',
    'today_arrivals' => 'Chegadas de Hoje',
    'total_active' => 'Total Ativos',
    'completed_today' => 'Completados Hoje',
    'overdue_vehicles' => 'Veículos Atrasados',
    'by_step' => 'Por Etapa',

    // Table Headers
    'vehicle_info' => 'Info do Veículo',
    'client' => 'Cliente',
    'days_in_step' => 'Dias na Etapa',
    'total_time' => 'Tempo Total',
    'assigned_to' => 'Atribuído a',
    'location' => 'Localização',
    'status' => 'Status',
    'actions' => 'Ações',
    'created_at' => 'Criado',
    'updated_at' => 'Atualizado',

    // Actions
    'scan_nfc' => 'Escanear NFC',
    'move_to_next' => 'Mover para Próxima Etapa',
    'assign_tech' => 'Atribuir Técnico',
    'add_photos' => 'Adicionar Fotos',
    'view_details' => 'Ver Detalhes',
    'edit_vehicle' => 'Editar Veículo',
    'delete_vehicle' => 'Excluir Veículo',
    'print_sheet' => 'Imprimir Folha Get Ready',
    'download_pdf' => 'Baixar PDF',
    'pause_timer' => 'Pausar Timer',
    'resume_timer' => 'Retomar Timer',
    'update_location' => 'Atualizar Localização',

    // Vehicle Form
    'add_vehicle' => 'Adicionar Veículo',
    'edit_vehicle' => 'Editar Veículo',
    'vin_number' => 'Número VIN',
    'stock_number' => 'Número do Estoque',
    'year' => 'Ano',
    'make' => 'Marca',
    'model' => 'Modelo',
    'color' => 'Cor',
    'mileage' => 'Quilometragem',
    'client' => 'Cliente',
    'contact' => 'Contato',
    'priority' => 'Prioridade',
    'expected_completion' => 'Conclusão Esperada',
    'notes' => 'Notas',
    'internal_notes' => 'Notas Internas',

    // Priority Levels
    'normal' => 'Normal',
    'urgent' => 'Urgente',
    'high' => 'Alta',
    'low' => 'Baixa',

    // Status Values  
    'active' => 'Ativo',
    'on_hold' => 'Em Espera',
    'cancelled' => 'Cancelado',

    // Service Manager
    'unassigned_vehicles' => 'Veículos Não Atribuídos',
    'tech_workload' => 'Carga de Trabalho do Técnico',
    'assign_technician' => 'Atribuir Técnico',
    'technician' => 'Técnico',
    'current_workload' => 'Carga de Trabalho Atual',
    'assign' => 'Atribuir',
    'unassign' => 'Desatribuir',

    // Timer & Time Tracking
    'timer' => 'Timer',
    'time_in_step' => 'Tempo na Etapa', 
    'time_paused' => 'Timer Pausado',
    'time_running' => 'Timer Executando',
    'pause' => 'Pausar',
    'resume' => 'Retomar',
    'elapsed_time' => 'Tempo Decorrido',
    'total_elapsed' => 'Total Decorrido',

    // Photos & Media
    'photos' => 'Fotos',
    'upload_photos' => 'Carregar Fotos',
    'photo_gallery' => 'Galeria de Fotos',
    'no_photos' => 'Nenhuma foto disponível',
    'photos_uploaded' => 'fotos carregadas',
    'delete_photo' => 'Excluir Foto',

    // Activities & History
    'activity_log' => 'Log de Atividades',
    'recent_activities' => 'Atividades Recentes',
    'no_activities' => 'Ainda não há atividades',
    'created' => 'Criado',
    'moved_to_step' => 'Movido para Etapa',
    'assigned_tech' => 'Técnico Atribuído',
    'added_photos' => 'Fotos Adicionadas',
    'updated_location' => 'Localização Atualizada',
    'nfc_scanned' => 'NFC Escaneado',
    'timer_paused' => 'Timer Pausado',
    'timer_resumed' => 'Timer Retomado',
    'notes_added' => 'Notas Adicionadas',
    'status_changed' => 'Status Alterado',
    'deleted' => 'Excluído',

    // NFC & Mobile
    'nfc_token' => 'Token NFC',
    'scan_qr_nfc' => 'Escanear QR ou NFC',
    'mobile_interface' => 'Interface Mobile',
    'quick_actions' => 'Ações Rápidas',

    // Notifications
    'vehicle_entered_step_notification' => 'O veículo %s (%s) entrou em %s em %s',
    'tech_assignment_notification' => 'Olá %s, o veículo %s (%s) foi atribuído a você em %s em %s',
    'overdue_vehicle_notification' => 'ALERTA: O veículo %s (%s) está %d dias em atraso em %s em %s',
    'completion_notification' => 'Olá %s, seu veículo %s (%s) está pronto! Tempo total: %s em %s',
    'emergency_notification' => 'ALERTA %s: O veículo %s (%s) requer atenção imediata em %s',
    'daily_summary_header' => 'Resumo Diário Get Ready',
    'total_active_vehicles' => 'Total Ativos: %d veículos',
    'overdue_vehicles' => 'Em Atraso: %d veículos', 
    'test_notification_message' => 'Esta é uma notificação de teste do sistema Get Ready',
    'generated_by_mda' => 'Gerado por My Detail Area',

    // Messages
    'order_created_successfully' => 'Ordem Get Ready criada com sucesso',
    'failed_to_create_order' => 'Falha ao criar a ordem Get Ready',
    'vehicle_updated_successfully' => 'Veículo atualizado com sucesso',
    'failed_to_update_vehicle' => 'Falha ao atualizar o veículo',
    'vehicle_deleted_successfully' => 'Veículo excluído com sucesso',
    'failed_to_delete_vehicle' => 'Falha ao excluir o veículo',
    'vehicle_moved_successfully' => 'Veículo movido para %s com sucesso',
    'failed_to_move_vehicle' => 'Falha ao mover o veículo',
    'technician_assigned_successfully' => 'Técnico %s atribuído com sucesso',
    'failed_to_assign_technician' => 'Falha ao atribuir o técnico',
    'location_updated_successfully' => 'Localização atualizada com sucesso',
    'failed_to_update_location' => 'Falha ao atualizar a localização',
    'photos_added_successfully' => '%d fotos adicionadas com sucesso',
    'photos_uploaded_successfully' => '%d fotos carregadas com sucesso',
    'failed_to_add_photos' => 'Falha ao adicionar as fotos',
    'failed_to_upload_photos' => 'Falha ao carregar as fotos',
    'photo_deleted_successfully' => 'Foto excluída com sucesso',
    'failed_to_delete_photo' => 'Falha ao excluir a foto',
    'timer_paused_successfully' => 'Timer pausado com sucesso',
    'failed_to_pause_timer' => 'Falha ao pausar o timer',
    'timer_resumed_successfully' => 'Timer retomado com sucesso', 
    'failed_to_resume_timer' => 'Falha ao retomar o timer',

    // Validation Messages
    'vin_required' => 'O número VIN é obrigatório',
    'vin_unique' => 'Este número VIN já existe',
    'client_required' => 'O cliente é obrigatório',
    'step_required' => 'A etapa é obrigatória',

    // Print & Reports
    'get_ready_sheet' => 'Folha Get Ready',
    'progress_report' => 'Relatório de Progresso',
    'completion_certificate' => 'Certificado de Conclusão',
    'analytics_report' => 'Relatório de Análises',
    'checklist' => 'Lista de Verificação',
    'exterior_inspection' => 'Inspeção Externa',
    'interior_inspection' => 'Inspeção Interna',
    'mechanical_systems' => 'Sistemas Mecânicos',
    'electrical_systems' => 'Sistemas Elétricos',
    'safety_systems' => 'Sistemas de Segurança',
    'technology_systems' => 'Sistemas de Tecnologia',
    'final_quality_check' => 'Verificação Final de Qualidade',
    'generation_date' => 'Data de Geração',
    'certificate_number' => 'Número do Certificado',

    // Common
    'loading' => 'Carregando...',
    'no_results' => 'Nenhum resultado encontrado',
    'search' => 'Buscar',
    'filter' => 'Filtrar',
    'refresh' => 'Atualizar',
    'close' => 'Fechar',
    'save' => 'Salvar',
    'cancel' => 'Cancelar',
    'yes' => 'Sim',
    'no' => 'Não',
    'confirm' => 'Confirmar',
    'delete' => 'Excluir',
    'edit' => 'Editar',
    'view' => 'Ver',
    'add' => 'Adicionar',
    'update' => 'Atualizar',
    'submit' => 'Enviar',

    // Time Formats
    'time_ago' => '%s atrás',
    'just_now' => 'agora mesmo',
    'minutes_ago' => '%dm atrás',
    'hours_ago' => '%dh atrás',
    'days_ago' => '%dd atrás',

    // Errors
    'vehicle_not_found' => 'Veículo não encontrado',
    'step_not_found' => 'Etapa não encontrada',
    'invalid_nfc_token' => 'Token NFC inválido',
    'access_denied' => 'Acesso negado',
    'something_went_wrong' => 'Algo deu errado',

    // Success Messages
    'operation_successful' => 'Operação concluída com sucesso',
    'changes_saved' => 'Alterações salvas com sucesso',
    'data_refreshed' => 'Dados atualizados com sucesso',
];
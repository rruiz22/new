// DataTables configuration
document.addEventListener('DOMContentLoaded', function() {
    // Default DataTables settings
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            // Use local language files instead of CDN to avoid CORS issues
            url: function() {
                // Get current language from session/localStorage or html lang attribute
                const currentLang = document.documentElement.lang || 
                                   localStorage.getItem('locale') || 
                                   'en';
                
                // Use window.baseUrl if available, otherwise construct it
                const baseUrl = window.baseUrl || '/mda/';
                
                // Map to correct file based on language
                const langMap = {
                    'es': baseUrl + 'assets/libs/datatables/i18n/es-ES.json',
                    'pt': baseUrl + 'assets/libs/datatables/i18n/pt-BR.json',
                    'en': baseUrl + 'assets/libs/datatables/i18n/en-US.json'
                };
                
                console.log('📊 DataTables language URL:', langMap[currentLang] || langMap['en']);
                return langMap[currentLang] || langMap['en'];
            }(),
            processing: "...",
            search: "",
            searchPlaceholder: "Buscar...",
            info: "_START_ - _END_ de _TOTAL_",
            infoEmpty: "0 - 0 de 0",
            lengthMenu: "_MENU_ por página",
            zeroRecords: "Sin resultados"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        responsive: true
    });
}); 
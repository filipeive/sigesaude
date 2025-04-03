$(document).ready(function() {
    // Validação de notas
    $('.nota-input').on('input', function() {
        let value = parseFloat($(this).val());
        if (isNaN(value)) return;
        if (value < 0) $(this).val(0);
        if (value > 20) $(this).val(20);
    });

    // Formatação de números decimais
    $('.nota-input').on('blur', function() {
        let value = $(this).val();
        if (value !== '' && !isNaN(parseFloat(value))) {
            $(this).val(parseFloat(value).toFixed(1));
        }
    });
    
    // Confirmação antes de enviar o formulário
    $('#form-notas-exames').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Confirmar lançamento',
            text: "Deseja confirmar o lançamento das notas?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
    
    // Inicializar DataTables para melhor visualização
    $('#tabela-notas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json'
        },
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false
    });
});

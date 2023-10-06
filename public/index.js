const tableContent = $("#tableContent");
const errorContent = $("#error");

const dicionarioStatus = {
    A: 'Alugado',
    V: 'Vendido',
    D: 'Disponível'
};
const dicionarioTipo = {
    T: 'Terreno',
    A: 'Apartamento',
    C: 'Casa'
};

// Valores iniciais do formulário
$(document).ready(function() {
    $('#preco').maskMoney({
      prefix: 'R$ ', // Prefixo que será exibido antes do valor (no caso, "R$ ")
      allowNegative: false, // Impede valores negativos
      thousands: '.', // Separador de milhares
      decimal: ',', // Separador decimal
      affixesStay: false, // Remove o prefixo/sufixo quando o usuário digitar
    });

    let loadedImovel = {};
    refresh();
});

function refresh() {
    tableContent.html();
    let serializedData = $('#searchForm').serialize();

    $.get('/list', serializedData, function (response) {
        let tableString = "";
        response.forEach((element) => {
            tableString += `<tr>
                <td>${dicionarioTipo[element.tipo]}</td>
                <td>${element.endereco}</td>
                <td>R$ ${element.preco.toString().replace('.', ',')}</td>
                <td>${dicionarioStatus[element.status]}</td>
                <td><button type="button" class="btn btn-primary" onclick="loadEdit(${element.id})"><i class="bi bi-pencil-square">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </i></button></td>
                <td><button type="button" class="btn btn-danger" onclick="deleteImovel(${element.id})"><i class="bi bi-dash-square">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-x" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708z"/>
                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                    </svg>
                </i></button></td>
            </tr>`;
        });
        tableContent.html(tableString);
    });
}

function send(event) {
    event.preventDefault();

    // Retira temporariamente a mascara do preço para envio do formuário
    var precoComMascara = $('#preco').val();
    var precoSemMascara = precoComMascara.replace(/\./g, '').replace('R$ ', '').replace(',', '.'); // Remove todos os caracteres não numéricos e converte para um número de ponto flutuante
    $('#preco').val(precoSemMascara);
    
    let serializedData = $('#imovelForm').serialize();

    if (loadedImovel.id) {
        serializedData += '&id=' + loadedImovel.id;
    }

    $.post('/create', serializedData, function (response) {
        refresh();
        resetFields();

        $('#exampleModal').modal('hide');
    }).fail(function (response) {
        errorContent.html(response.responseText);
        errorContent.show().delay(2500).fadeOut();
    });

    return;
}

function loadEdit(id) {
    $.get('/get/' + id, function (response) {
        $('#tipo').val(response.tipo);
        $('#endereco').val(response.endereco);
        $('#preco').maskMoney('mask', response.preco);
        $('#status').val(response.status);
        loadedImovel = response;

        let modal = bootstrap.Modal.getOrCreateInstance($('#exampleModal'));
        modal.show();
    }).fail(function (response) {
        errorContent.html(response.responseText);
        errorContent.show().delay(2500).fadeOut();
    });
}

function deleteImovel(id) {
    $.get('/delete/' + id, function () {
        refresh();
    }).fail(function (response) {
        errorContent.html(response.responseText);
        errorContent.show().delay(2500).fadeOut();
    });
}

var myModalEl = document.getElementById('exampleModal')
myModalEl.addEventListener('hide.bs.modal', function (event) {
    resetFields();
})

function resetFields() {
    loadedImovel = {};
    $('#endereco').val('');
    $('#tipo').val('');
    $('#status').val('');
    $('#preco').val('');
    $('#search').val('');
}
const formularioMoto = document.getElementById("formMoto");

formularioMoto.addEventListener("submit", function(event) {

    const campos = [
        "marca",
        "modelo",
        "cor",
        "ano",
        "quilometragem",
        "datacompra",
        "placa"
    ];

    for (let campo of campos) {

        const valor = document.getElementById(campo).value.trim();

        if (valor === "") {
            alert("Preencha todos os campos da moto!");
            event.preventDefault();
            return;
        }
    }

});

// Selecionar o formulário de manutenção
const formularioManutencao = document.getElementById("formManutencao");

// Verificar o envio do formulário
formularioManutencao.addEventListener("submit", function(event) {

    const campos = [
        "tipo",
        "data",
        "km",
        "valor"
    ];

    // Verificar se algum campo está vazio
    for (let campo of campos) {

        const valor = document.getElementById(campo).value.trim();

        if (valor === "") {
            alert("Preencha todos os campos obrigatórios da manutenção!");
            event.preventDefault();
            return;
        }
    }

});
class Despesa{
    constructor(ano, mes, dia, tipo, descricao, valor){
        this.ano = ano;
        this.mes = mes;
        this.dia = dia;
        this.tipo = tipo;
        this.descricao = descricao;
        this.valor = valor;
    }

    validarDados(){
        for(let i in this){
            //condições dos dados
            if(i, this[i] == undefined || this[i] == "" || this[i] == null){
                return false;
            }
        }
        return true;
    }
}

class Bd{

    constructor(){
        let id = localStorage.getItem("id");

        //se não exisitir um id no localsotrage
        if(id === null){
            localStorage.setItem("id", 0);
        }
    }

    getProximoId(){
        let proximoId = localStorage.getItem("id");
        return parseInt(proximoId)+1;
    }

    gravar(d){
        let id = this.getProximoId();
        localStorage.setItem(id, JSON.stringify(d));
        localStorage.setItem("id", id);
    }

    recuperarTodosRegistros(){
        //Array de despesas
        let despesas = Array();
        
        let id = localStorage.getItem("id");

        //recuperar todas as despesas cadastradas em localStorage
        for (let i = 1; i <= id; i++) {
            let despesa = JSON.parse(localStorage.getItem(i));
            //A cada interação adicionamos a despesa dentro do Array despesas
            //existe a possibilida de haver índicies que foram pulados/removidos(vamos pular estes índicies)
            if(despesa === null){
                continue;
            }
            
            despesa.id = i;
            despesas.push(despesa);
        }
        return despesas;
    }

    pesquisar(despesa){
 
        let despesasFiltradas = Array();
        despesasFiltradas = this.recuperarTodosRegistros();

        console.log(despesa);
        console.log(despesasFiltradas);

        //ano
        if(despesa.ano != ""){
            console.log("filto de ano");
            despesasFiltradas = despesasFiltradas.filter(d => d.ano == despesa.ano);
        }

        //mes
        if(despesa.mes != ""){
            console.log("filtro de mes");
            despesasFiltradas = despesasFiltradas.filter(d => d.mes == despesa.mes);
        }

        //dia
        if(despesa.dia != ""){
            console.log("filtro de dia");
            despesasFiltradas = despesasFiltradas.filter(d => d.dia == despesa.dia);
        }

        //tipo
        if(despesa.tipo != ""){
            console.log("filtro de tipo");
            despesasFiltradas = despesasFiltradas.filter(d => d.tipo == despesa.tipo);
        }

        //descricao
        if(despesa.descricao != ""){
            console.log("filtro de descricao");
            despesasFiltradas = despesasFiltradas.filter(d => d.descricao == despesa.descricao);
        }

        //valor
        if(despesa.valor != ""){
            console.log("filtro de valor");
            despesasFiltradas = despesasFiltradas.filter(d => d.valor == despesa.valor);
        }


        return despesasFiltradas;
        
    }

    remover(id){
        localStorage.removeItem(id);
    }
}

let bd = new Bd();

function cadastrarDespesa() {
    let ano = document.getElementById("ano");
    let mes = document.getElementById("mes");
    let dia = document.getElementById("dia");
    let tipo = document.getElementById("tipo");
    let descricao = document.getElementById("descricao");
    let valor = document.getElementById("valor");

    //console.log(ano.value, mes.value, dia.value, tipo.value, descricao.value, valor.value);
    
    let despesa = new Despesa(
        ano.value, 
        mes.value, 
        dia.value, 
        tipo.value, 
        descricao.value, 
        valor.value
    );

    if(despesa.validarDados()){
        
        bd.gravar(despesa);
        
        document.getElementById("modal_titulo").innerHTML = "Despesa cadastrada";
        document.getElementById("modal_tituloDIV").className = "modal-header text-success";
        document.getElementById("modal_conteudo").innerHTML = "Despesa foi cadastrada com sucesso";
        document.getElementById("modal_btn").innerHTML = "Voltar";
        document.getElementById("modal_btn").className = "btn btn-success";
        $("#registraDespesa").modal("show");

        ano.value = ""
        mes.value = ""
        dia.value = ""
        tipo.value = ""
        descricao.value = ""
        valor.value = ""
    
    }else{
        
        document.getElementById("modal_tituloDIV").className = "modal-header text-danger";
        document.getElementById("modal_titulo").innerHTML = "Erro ao cadastrar dados!";
        document.getElementById("modal_conteudo").innerHTML = "Despesa não foi cadastrada, verifique se todos os campos foram preenchidos!";
        document.getElementById("modal_btn").innerHTML = "Voltar e corrigir";
        document.getElementById("modal_btn").className = "btn btn-danger";
        $("#registraDespesa").modal("show");
    }
}

function carregaListaDespesas(despesas = Array(), filtro = false) {
    
    //se o array não possuir valores
    //se o filtro for igual a false significa que não é uma açao de filtragem
    if(despesas.length == 0 && filtro == false){
        despesas = bd.recuperarTodosRegistros();
    }
    
  
    //elemento tbody da tabela de despesas
    let listaDespesas = document.getElementById("listaDespesas");
    listaDespesas.innerHTML = "";//limpar

    //percorrer o array despesas
    despesas.forEach(function(d) {
    //criando a linha(tr)
    console.log(d);
    let linha = listaDespesas.insertRow();

    //criando colunas(td)
    linha.insertCell(0).innerHTML = `${d.dia}/${d.mes}/${d.ano}`;
    
    //ajustar o tipo
    switch(d.tipo){
        case "1": d.tipo = "Alimentação"
            break;
        case "2": d.tipo = "Educação"
            break;
        case "3": d.tipo = "Lazer"
            break;
        case "4": d.tipo = "Saúde"
            break;
        case "5": d.tipo = "Transporte"
            break;
    }
    linha.insertCell(1).innerHTML = d.tipo;
    linha.insertCell(2).innerHTML = d.descricao;
    linha.insertCell(3).innerHTML = d.valor;

    //criar o botão de excluir
    let btn = document.createElement("button");
    //add classe no botao
    btn.className = "btn btn-danger";
    //configs e recursos do font a. no botão
    btn.innerHTML = '<i class="fas fa-times"></i>';
    //pega id do objeto da despesa e passa pro btn
    btn.id = `id_despesa_${d.id}`;
   
   
    //ação do btn ao clicar
    btn.onclick= function(){

        //substitui id por um valor vazio
        let id = this.id.replace("id_despesa_", "");
        
        //alert("O item " + d.descricao + ", foi removido com sucesso!");
        
        document.getElementById("modal_titulo").innerHTML = "Despesa removida!";
        document.getElementById("modal_tituloDIV").className = "modal-header text-warning";
        document.getElementById("modal_conteudo").innerHTML = "Despesa de id " + id + ", foi deletada com sucesso! Pode atualuzar a página para ver os novos valores da tabela.";
        document.getElementById("modal_btn").innerHTML = "OK";
        document.getElementById("modal_btn").className = "btn btn-warning";
        $("#excluirDespesa").modal("show");
        
        bd.remover(id);

        //atualizar a pagina
        //retirei esta funcao pois esta fazendo a lógica do modal de excluir n funcionar.
       // window.location.reload();
        
    }
    //add o btn na tela
    linha.insertCell(4).append(btn);

    console.log(d);

    });

}

function pesquisarDespesa() {
    
    let ano = document.getElementById("ano").value;
    let mes = document.getElementById("mes").value;
    let dia = document.getElementById("dia").value;
    let tipo = document.getElementById("tipo").value;
    let descricao = document.getElementById("descricao").value;
    let valor = document.getElementById("valor").value; 

    let despesa = new Despesa(ano, mes, dia, tipo, descricao, valor);
    //console.log(despesa);

    let despesas = bd.pesquisar(despesa);

    carregaListaDespesas(despesas, true);
}
var btnEntrar = $("#btnEntrar");
var usuario = $("#usuario");
var senha = $("#senha");
var container = $(".container");
var btnBloquear = $("#bloquear");
var loader = $(".loader");

//Inicializador da página 
$(function() {
    inicializaMarcadores();
    $("tr:odd").css({
        "background-color":"#ffffff"
    });
    //verificaLogin();
    //verificaCadastro();
});


        //Função para trocar borda do container na página de edição
        function inicializaMarcadores() {
            usuario.on("input", function() {
                if(usuario.val().length == 0 || senha.val().length == 0){
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }else if (usuario.val().length >= 50 || senha.val().length >= 50 ||
                usuario.val().length < 8 || senha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
                });
                senha.on("input", function() {
                    if(usuario.val().length == 0 || senha.val().length == 0){
                        container.addClass("borda-padrao");
                        container.removeClass("borda-verde");
                        container.removeClass("borda-vermelha");
                    }else if (usuario.val().length >= 50 || senha.val().length >= 50 ||
                    usuario.val().length < 8 || senha.val().length < 8) {
                        container.addClass("borda-vermelha");
                        container.removeClass("borda-verde");
                    } else {
                        container.addClass("borda-verde");
                        container.removeClass("borda-vermelha");
                    }
                });
            }

//Função para verificar condições do login
function verificaLogin(){
    btnEntrar.on("click", function() {
        if(usuario.val().length > 50){
            alert("Login não pode exceder 50 caracteres!")
        }else if(senha.val().length > 50){
            alert("Senha não pode exceder 50 caracteres!")
        }else if(usuario.val().length < 9){
            alert("Login precisa ter no mínimo 8 caracteres!")
        }else if(senha.val().length < 9){
            alert("Senha precisa ter no mínimo 8 caracteres!")
        }
    });
}

//Função para verificar se as senhas estão iguais
function verificaCadastro(){
    var btnCadastrar = $("#btnCadastrar");
    var confirmarSenha = $("#confirmarSenha");
    var usuarioCad = $("#usuarioCad");
    var senhaCad = $("#senhaCad");
    btnCadastrar.on("click", function() {
    if(usuarioCad.val().length > 50){
        alert("Login não pode exceder 50 caracteres!")
    }else if(senhaCad.val().length > 50){
        alert("Senha não pode exceder 50 caracteres!")
    }else if(usuarioCad.val().length < 9){
        alert("Login precisa ter no mínimo 8 caracteres!")
    }else if(senhaCad.val().length < 9){
        alert("Senha precisa ter no mínimo 8 caracteres!")
    }
    if(senhaCad.val() != confirmarSenha.val()){
        alert("As senhas precisam ser iguais!")
    }
});
}
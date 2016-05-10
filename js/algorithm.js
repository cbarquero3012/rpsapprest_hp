

////////// Algoritmo que determina las posibilidades de RPS
var game = function (op1,op2)
{
//////// Posibles Victorias "Jugador 1"
var win_cont1=0;
var win_cont2=0;
if (( op1 == "R" & op2 == "S") || (op1 == "S" & op2 == "P") || (op1 == "P" & op2 == "R"))
{
///// Alerta al Ganar
var valor = document.getElementById('l_player_1');
alert(valor.innerHTML+" is the Winner!!!");
}
//////// Posibles Victorias "Jugador 2"
if (( op2 == "R" & op1 == "S") || (op2 == "S" & op1 == "P") || (op2 == "P" & op1 == "R"))
{
///// Alerta al Ganar
var valor = document.getElementById('l_player_2');
alert(valor.innerHTML+" is the Winner!!!");
}
/////// En caso de Empate
if (( op1 == "R" & op2 == "R") || (op1 == "S" & op2 == "S") || (op1 == "P" & op2 == "P"))
{
///// Alerta al Empatar
var valor = document.getElementById('l_player_1');
alert("Tie, "+valor.innerHTML+" is the Winner!!!");
}
///// Al obtener una accion indebida
if ((op1 == "R" & op2 == "") || (op1 == "S" & op2 == "") || (op1 == "P" & op2 == "") || ( op1 == "" & op2 == "R") || (op1 == "" & op2 == "S") || (op1 == "" & op2 == "P"))
{
alert("Invalid Choice!! You need choose Rock , Paper or Scissor on both players for play!");
}
document.getElementById("main_form").reset();

};

 $(function() {
        $("#b_play").click(function() {
            var wins1 = win_cont1++;
            updateResult(wins1);
            return false;
        });
    });

function updateResult(wins1) {
        var url = "http://localhost/rpsapp/";     
        $("#wins1").load(url);
};


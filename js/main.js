//Modulo de Angular para llamado de usuarios con Puntuacion mayor

angular.module("rps_app",[])
       .controller("controller1",function ($scope,$http){
       	$scope.users = [];
       	$scope.newPost = {};

       	$http.get("http://localhost/api/championship/top")

       	   .success(function(data){
             console.log(data);
             $scope.users = data;
       	    })
       	    .error(function(err){

       	    });

       	 $scope.addPost = function(){
         $http.users("http://jsonplaceholder.typicode.com/posts",{
                  title:$scope.newPost.title,
                  body:$scope.newPost.body,
                  userId:1
               })
               .success(function(data,status,headers,config){	
               	$scope.posts.push($scope.newPost);
                $scope.newPost = {};
               })
               .error(function(error,status,headers,config){
                   console.log(error);
               });
       	    }
       });
// Funcion Cerrar

function cerrar(){ 
window.close() 
};

// Visibilidad de 
function visible_text(game_type){

  document.getElementById("t_player_name_1").style.visibility='visible'
  document.getElementById("t_player_name_2").style.visibility='visible'
  document.getElementById("b_start_game").style.visibility='hidden'
  var g_type = game_type;
  type_game_select(g_type);

};

//Visibilidad de Inicio de Juego
var visible_game = document.onkeydown = function(e){
    
    if(e.keyCode === 13){
            document.getElementById("t_game").style.visibility='visible'
            document.getElementById("d_play_button").style.visibility='visible'
            document.getElementById("score_board").style.visibility='visible'
            
            document.getElementById("t_player_name_1").style.visibility='hidden'
            document.getElementById("t_player_name_2").style.visibility='hidden'
            get_user();
            
                         }

};

// Obtener Usuario y hacerlo funcional
var get_user = $(document).ready(function (){
    $('#t_player_name_1').on('keypress', function(){
            var valor = $('#t_player_name_1').val();
            $('#l_player_1').text(valor);
            
        });
    $('#t_player_name_2').on('keypress', function(){
            var valor1 = $('#t_player_name_2').val();
            $('#l_player_2').text(valor1);
            
        });
    $('#t_player_name_1').on('keypress', function(){
            var valor3 = $('#t_player_name_1').val();
            $('#score_player1').text(valor3);
            
        });
    $('#t_player_name_2').on('keypress', function(){
            var valor4 = $('#t_player_name_2').val();
            $('#score_player2').text(valor4);
            
        });
  });


// Selecionar Tipo de Juego
function type_game_select (type){

if(type == 's_player'){

        $('#s_type_game').text('One Game');
        document.getElementById("s_type_game").style.visibility='visible'         
  }         
  else{

        $('#s_type_game').text('Multipleplayer');
        document.getElementById("s_type_game").style.visibility='visible'
  }
}

function exit_game(){

            document.getElementById("t_game").style.visibility='hidden'
            document.getElementById("d_play_button").style.visibility='hidden'
            document.getElementById("score_board").style.visibility='hidden'
            document.getElementById("b_start_game").style.visibility='visible'
}
// ToolTip Informativo

$("#main_form :select").tooltip({
 
      // place tooltip on the right edge
      position: "center right",
      // a little tweaking of the position
      offset: [-2, 10],
      // use the built-in fadeIn/fadeOut effect
      effect: "fade",
      // custom opacity setting
      opacity: 0.7
}); 










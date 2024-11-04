// Mostrar y ocultar el modal
var modal = document.getElementById("login");
var btn = document.getElementById("loginBtn");//Boton principal
var btn2 = document.getElementById("loginBtn2");//barra de apoyo
var closeBtn = document.getElementsByClassName("close")[0];

// Abrir modal al hacer clic en el botón
btn.onclick = function() {
    modal.style.display = "block";
}
//Abrir modal al hacer clic en la barra de apoyo
btn2.onclick=function(event){
    event.preventDefault();//Prevenir el comportamiento del defecto del enlace
    modal.style.display="block";
}

// Cerrar modal al hacer clic en el botón de cerrar
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// Cerrar modal si se hace clic fuera del mismo
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

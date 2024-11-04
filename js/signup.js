function showCustomAlert() {
    document.getElementById("customAlert").style.display = "flex";
    document.getElementById("overlay").style.display = "block";
}
function closeCustomAlert() {
    document.getElementById("customAlert").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
window.onload = showCustomAlert;
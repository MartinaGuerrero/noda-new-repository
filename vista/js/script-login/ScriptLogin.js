function closeErrorBox() {
    const errorBox = document.querySelector('.container-error');
    if (errorBox) {
        errorBox.style.display = 'none';
    }
}

const conteiner = document.querySelector('.Container');
const form = document.getElementById('form');
const usuario = document.getElementById("usuario");
const divlogin = document.querySelector('.DivLogin'); 
const password = document.getElementById("password");
const titulo = document.getElementById("titulo");
const candadoicon = document.querySelector(".candado");
const divsignup = document.querySelector('.DivSignUp');
const form2 = document.getElementById('form2');
const usuarioicon = document.querySelector(".usuario");
const titulo2 = document.getElementById("titulo2");
const cargo = document.querySelector(".cargo");
const usuario2 = document.querySelector(".usuario2");
const candado2 = document.querySelector(".candado2");

// Inicializar ocultando los campos de "Sign Up"
if (divsignup.contains(form2)) divsignup.removeChild(form2);
if (divsignup.contains(titulo2)) divsignup.removeChild(titulo2);
if (conteiner.contains(usuario2)) conteiner.removeChild(usuario2);
if (conteiner.contains(candado2)) conteiner.removeChild(candado2);
if (conteiner.contains(cargo)) conteiner.removeChild(cargo);

// Crear los divs transparentes
const transparente1 = document.createElement('div');
transparente1.setAttribute('id', 'transparente1');
conteiner.appendChild(transparente1);

const transparente2 = document.createElement('div');
transparente2.setAttribute('id', 'transparente2');
transparente2.style.display = 'none';
conteiner.appendChild(transparente2);

function switchToSignUp() {
    transparente2.style.display = 'block';
    const SignUp = document.getElementById('signup');
    if (!SignUp) return; // Si el elemento no existe, salir

    const h1 = document.getElementById("h1");
    conteiner.appendChild(transparente2);
    if (conteiner.contains(transparente1)) conteiner.removeChild(transparente1);
    
    SignUp.style.transition = 'all 0.5s ease';
    SignUp.style.transform = 'translateX(-137.5%)';
    SignUp.setAttribute('id', 'login');
    h1.innerHTML = "Login";

    // Mostrar elementos de "Sign Up"
    if (titulo2) conteiner.appendChild(titulo2);
    if (form2) conteiner.appendChild(form2);
    if (usuario2) conteiner.appendChild(usuario2);
    if (candado2) conteiner.appendChild(candado2);
    if (cargo) conteiner.appendChild(cargo);
    //if (divlogin) divlogin.style.display='none';
    // Ocultar elementos de "Login"
    if (divlogin.contains(titulo)) divlogin.removeChild(titulo);
    if (divlogin.contains(form)) divlogin.removeChild(form);
    if (conteiner.contains(usuarioicon)) conteiner.removeChild(usuarioicon);
    if (conteiner.contains(candadoicon)) conteiner.removeChild(candadoicon);

    if (window.innerWidth < 650) {
        divlogin.style.display = 'none'; // Oculta el divlogin
         if (transparente2) transparente2.style.display = 'none';
        SignUp.style.transform = 'translateX(1%)';
    }
}

function switchToLogin() {
    const login = document.getElementById('login');
    if (!login) return; // Si el elemento no existe, salir
    divlogin.style.display = 'block';
    const h1 = document.getElementById("h1");
    conteiner.appendChild(transparente1);
    if (conteiner.contains(transparente2)) conteiner.removeChild(transparente2);
    
    login.style.transition = 'all 0.5s ease';
    login.style.transform = 'translateX(0%)';
    login.setAttribute('id', 'signup');
    h1.innerHTML = "Sign Up";

    // Mostrar elementos de "Login"
    if (usuario) usuario.style.display = "block";
    if (titulo) divlogin.appendChild(titulo);
    if (form) divlogin.appendChild(form);
    if (usuarioicon) conteiner.appendChild(usuarioicon);
    if (candadoicon) conteiner.appendChild(candadoicon);
    //if (divlogin) divlogin.style.display='block';
    // Ocultar elementos de "Sign Up"
    if (conteiner.contains(form2)) conteiner.removeChild(form2);
    if (conteiner.contains(titulo2)) conteiner.removeChild(titulo2);
    if (conteiner.contains(usuario2)) conteiner.removeChild(usuario2);
    if (conteiner.contains(candado2)) conteiner.removeChild(candado2);
    if (conteiner.contains(cargo)) conteiner.removeChild(cargo);
}

function handleResize() {
    if (window.innerWidth < 650) {
        const signUpButton = document.getElementById('signupt');
        const loginButton = document.getElementById('logint');
        if (signUpButton && !signUpButton.dataset.listenerAdded) {
            signUpButton.addEventListener('click', switchToSignUp);
            signUpButton.dataset.listenerAdded = true;
        }
        if (loginButton && !loginButton.dataset.listenerAdded) {
            loginButton.addEventListener('click', switchToLogin);
            loginButton.dataset.listenerAdded = true;
        }
    }
}

// Inicializar los eventos al cargar la página
handleResize();

// Agregar el evento de resize
window.addEventListener('resize', handleResize);

// Establecer el comportamiento inicial
if (window.innerWidth < 650) {
    switchToLogin(); // o switchToSignUp() según lo que quieras mostrar inicialmente
}

// Añadir los eventos de click para los elementos transparentes
const trans1 = document.getElementById('transparente1');
const trans2 = document.getElementById('transparente2');

if (trans1) trans1.addEventListener('click', switchToSignUp);
if (trans2) trans2.addEventListener('click', switchToLogin);

// Configurar botones de "Sign Up" y "Login"
const signUpBtn = document.getElementById('signupt');
const loginBtn = document.getElementById('logint');

if (signUpBtn) signUpBtn.addEventListener('click', switchToSignUp);
if (loginBtn) loginBtn.addEventListener('click', switchToLogin);

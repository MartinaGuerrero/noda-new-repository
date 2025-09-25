const btnescuela = document.getElementById('escuela');
const btnliceo = document.getElementById('liceo');

        function moverLiceo() {
            const liceo = document.getElementById('liceo');
            const escuela = document.getElementById('escuela');
            const escuelaTexto = document.querySelector('#escuela h1');
            const liceoTexto = document.querySelector('#liceo h1');

            liceo.style.width = '11%'; // Cambia el ancho de Liceo
            escuela.style.width = '61%'; // Cambia el ancho de Escuela
            escuela.style.zIndex = '3';
            escuela.style.justifyContent = 'flex-start'; // Alinear el texto hacia la izquierda
            escuelaTexto.style.marginLeft = '15px';
            liceoTexto.style.marginRight = '65px';

            // Redirigir a otra página después de mover
            setTimeout(() => {
                location.href = 'menuliderP.php';
            }, 500);
        }

        function moverEscuela() {
            const liceo = document.getElementById('liceo');
            const escuela = document.getElementById('escuela');
            const escuelaTexto = document.querySelector('#escuela h1');
            const liceoTexto = document.querySelector('#liceo h1');

            liceo.style.width = '62%'; // Cambia el ancho de Liceo
            escuela.style.width = '10%'; // Cambia el ancho de Escuela
            escuela.style.zIndex = '2';
            escuelaTexto.style.marginLeft = '70px';
            liceoTexto.style.marginRight = '15px';

            // Redirigir a otra página después de mover
            setTimeout(() => {
                location.href = 'menulider.php';
            }, 500);
        }

        function verificarDimensiones() {
            const anchoVentana = window.innerWidth;
            const altoVentana = window.innerHeight;

            // Si la ventana es 375x667, se adaptan los tamaños
            if (anchoVentana === 375 && altoVentana === 667) {
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '35%'; // Cambia el ancho de Liceo
                    escuela.style.width = '43%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '80px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '48%'; // Cambia el ancho de Liceo
                    escuela.style.width = '30%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '30px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            }else if(anchoVentana === 414 && altoVentana === 896){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '33%'; // Cambia el ancho de Liceo
                    escuela.style.width = '48%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '120px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '54%'; // Cambia el ancho de Liceo
                    escuela.style.width = '30%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '41px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            }else if(anchoVentana === 390 && altoVentana === 844){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '33%'; // Cambia el ancho de Liceo
                    escuela.style.width = '47%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '100px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '28px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '51%'; // Cambia el ancho de Liceo
                    escuela.style.width = '30%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '35px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '17px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });

            } else if(anchoVentana === 430 && altoVentana === 932){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '30%'; // Cambia el ancho de Liceo
                    escuela.style.width = '51%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '137px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '54%'; // Cambia el ancho de Liceo
                    escuela.style.width = '30%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '47px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });

            }else if(anchoVentana === 412 && altoVentana === 915) {
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '33%'; // Cambia el ancho de Liceo
                    escuela.style.width = '48%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '113px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '54%'; // Cambia el ancho de Liceo
                    escuela.style.width = '30%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '41px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });


            }else if(anchoVentana === 768 && altoVentana === 1024){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '17%'; // Cambia el ancho de Liceo
                    escuela.style.width = '66%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '83%'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '69%'; // Cambia el ancho de Liceo
                    escuela.style.width = '16%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '41px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });

            }else if(anchoVentana === 820 && altoVentana === 1180){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '17%'; // Cambia el ancho de Liceo
                    escuela.style.width = '67%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '84%'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '70%'; // Cambia el ancho de Liceo
                    escuela.style.width = '15%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '41px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            }else if(anchoVentana === 1024 && altoVentana === 1366){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '13%'; // Cambia el ancho de Liceo
                    escuela.style.width = '74%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '89%'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '76%'; // Cambia el ancho de Liceo
                    escuela.style.width = '12%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '41px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });

            }else if(anchoVentana === 912 && altoVentana === 1368){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '14%'; // Cambia el ancho de Liceo
                    escuela.style.width = '72%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '87%'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '74%'; // Cambia el ancho de Liceo
                    escuela.style.width = '12%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '26px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            } else if(anchoVentana === 540 && altoVentana === 720){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '23%'; // Cambia el ancho de Liceo
                    escuela.style.width = '58%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '73%'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '60%'; // Cambia el ancho de Liceo
                    escuela.style.width = '20%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '26px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            } else if(
                anchoVentana >= 850 && anchoVentana <= 855 && 
                altoVentana >= 1275 && altoVentana <= 1285
            ){
                console.log("Detectada resolución cercana a 853x1280");
                
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');
            
                    liceo.style.width = '15%';
                    escuela.style.width = '71%';
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '86%';
                    liceoTexto.style.marginRight = '30px';
            
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });
            
                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');
            
                    liceo.style.width = '73%';
                    escuela.style.width = '14%';
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '37px';
                    liceoTexto.style.marginRight = '15px';
            
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            } else if(anchoVentana === 412 && altoVentana === 914){
                btnescuela.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '33%'; // Cambia el ancho de Liceo
                    escuela.style.width = '48%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '3';
                    escuelaTexto.style.marginRight = '113px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '30px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menuliderP.php';
                    }, 500);
                });

                btnliceo.addEventListener('click', function() {
                    const liceo = document.getElementById('liceo');
                    const escuela = document.getElementById('escuela');
                    const escuelaTexto = document.querySelector('#escuela h1');
                    const liceoTexto = document.querySelector('#liceo h1');

                    liceo.style.width = '54%'; // Cambia el ancho de Liceo
                    escuela.style.width = '30%'; // Cambia el ancho de Escuela
                    escuela.style.zIndex = '2';
                    escuelaTexto.style.marginLeft = '41px'; // Ajusta el margen
                    liceoTexto.style.marginRight = '15px'; // Ajusta el margen

                    // Redirigir a otra página después de mover
                    setTimeout(() => {
                        location.href = 'menulider.php';
                    }, 500);
                });
            } else {
                // Comportamiento predeterminado para otras dimensiones
                btnescuela.onclick = moverLiceo;
                btnliceo.onclick = moverEscuela;
            }
        }

        // Llama a la función al cargar la página
        window.onload = verificarDimensiones;

        // Llama a la función al redimensionar la ventana
        window.onresize = verificarDimensiones;

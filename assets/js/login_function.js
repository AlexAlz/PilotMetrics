async function login(username, password, context) {
    const apiUrl = 'https://sistematpilot.com/Apis/Attpilot/operadores';
    const headers = {
        'Content-Type': 'application/json'
    };

    const data = JSON.stringify({
        data: {
            Usuario: username,
            password: password,
            Apikey: 'R%2T@F3qAP2x5/y;hUB.kWAtGPG]3b' 
        }
    });

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: headers,
            body: data
        });

        if (response.ok) {
            const jsonData = await response.json();
            const okLog = jsonData.result.loginbd;
            const activeUser = jsonData.result.nombre;
            const nomina = jsonData.result.nomina;
            const area = jsonData.result.area;
            const fecha = jsonData.result.fechaexpi;

            if (okLog === 'OK') {
                console.log(`Usuario: ${username}`);
                console.log('Acceso permitido');
                console.log(area);

                // Actualizar el valor de la variable nomina

                switch (area) {
                    case 'Mantenimiento':
                        window.location.href = 'pages/dashboard.php'; // Reemplaza con tu ruta correcta
                        break;
                    case 'Combustibles':
                        window.location.href = 'pages/dashboard.php'; // Reemplaza con tu ruta correcta
                        break;
                    case 'Sistemas':
                        window.location.href = 'pages/dashboard.php'; // Reemplaza con tu ruta correcta
                        break;
                    default:
                        window.location.href = 'pages/dashboard.php'; // Reemplaza con tu ruta correcta
                        break;
                }
            } else {
                console.log('Usuario o contraseña incorrectos');
                // Mostrar alerta de contraseña incorrecta
            }
        } else {
            console.log('Acceso denegado');
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}

// Uso de la función
const username = 'nombre_de_usuario';
const password = 'contraseña';
const context = {
    pushNamed: (route, arguments) => {
        console.log(`Navegando a la ruta ${route} con argumentos:`, arguments);
    }
};

login(username, password, context);

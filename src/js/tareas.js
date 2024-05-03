(function () {


    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    //boton para mostrar el Modal para agregar tarea
    const nuevaTarea = document.querySelector('#agregar-tarea');
    nuevaTarea.addEventListener('click', function () {
        mostrarFormulario();
    });

    //Filtros de busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e) {
        const filtro = e.target.value;
        if (filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }

        mostrarTareas();
    }


    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = window.location.origin + `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            // console.log(tareas);
            mostrarTareas();
        } catch (error) {

        }
    }

    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletas();
        const arrayTareas = filtradas.length ? filtradas : tareas;
        if (arrayTareas.length === 0) {
            const contenidoTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');
            contenidoTareas.appendChild(textoNoTareas);
            return;
        }
        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        };

        arrayTareas.forEach(tarea => {
            const contenidoTarea = document.createElement('LI');
            contenidoTarea.dataset.tareaId = tarea.id;
            contenidoTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function () {
                mostrarFormulario(true, tarea);
            }


            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opcciones');

            //Botones
            const btnEstadoTarea = document.createElement('button');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            //Cuando se da doble click
            btnEstadoTarea.ondblclick = function () {
                cambiarEstadoTarea({ ...tarea });
            };

            const btnEliminarTarea = document.createElement('button');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function () {
                confirmarEliminarTarea({ ...tarea });
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);


            contenidoTarea.appendChild(nombreTarea);
            contenidoTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenidoTarea);
        });
    }

    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector('#pendientes');
        
        if (totalPendientes.length === 0) {
            
            pendientesRadio.disabled=true;
        }else{
            pendientesRadio.disabled = false;
        }
    }

    function totalCompletas(){
        const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
        const completadasRadio = document.querySelector('#completadas');
        if (totalCompletas.length === 0) {
            completadasRadio.disabled = true;
        }else{
            completadasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}) {


        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
        <form class="formulario nueva-tarea">
            <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="${tarea.nombre ? 'Editar la tarea ' : 'Añadir Tarea al Poryecto'}" id="tarea" value="${tarea.nombre ? tarea.nombre : ''}"/>
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea"  value="${tarea.nombre ? 'Actualizar Tarea' : 'Añadir Tarea'}" name="" id=""/>
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>      
        </form> 
        
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 300);

        modal.addEventListener('click', function (e) {
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);

            }

            if (e.target.classList.contains('submit-nueva-tarea')) {
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if (nombreTarea === '') {
                    //mostrar una alerta
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('legend'));
                    return;
                }
                if (editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }
            }
        });
        document.querySelector('.dashboard').appendChild(modal);
    }


    function mostrarAlerta(mensaje, tipo, referencia) {
        //Previene la creacion de varias alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        //Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //Eliminar alerta despues de 5 segundos
        setTimeout(() => {

            alerta.remove();
        }, 5000);
    }

    //Consultar el servidor para agregar una nueva tarea a la base de datos
    async function agregarTarea(tarea) {
        //Construir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());
        try {
            const url = window.location.origin + '/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('legend'));
            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1500);
                //agregar el objeto de tarea al global tareas

                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                };
                tareas = [...tareas, tareaObj];

                mostrarTareas();

            }
        } catch (error) {

        }
    }

    function cambiarEstadoTarea(tarea) {
        //Cambia el estado de estado
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);

    }

    async function actualizarTarea(tarea) {
        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = window.location.origin + '/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if (resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    'Actualizado',
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');
                if (modal) {
                    modal.remove();
                }

            }
            tareas = tareas.map(tareaMemoria => {
                if (tareaMemoria.id === id) {
                    tareaMemoria.estado = estado;
                    tareaMemoria.nombre = nombre;
                }

                return tareaMemoria;
            });
            mostrarTareas();

        } catch (error) {

        }

    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Estas Seguro?',
            text: "Una vez Eliminado no lo Podras Recueprar",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si,Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);

            }
        })
    }

    async function eliminarTarea(tarea) {
        const { estado, id, nombre } = tarea;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());
        try {

            const url = window.location.origin + '/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if (resultado.resultado) {
                Swal.fire(
                    'Eliminado!',
                    resultado.mensaje,
                    'success'
                )
            }

            //trae todas las que sean diferentes a la que elimine
            tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
            mostrarTareas();
        } catch (error) {

        }
    }
    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;

    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }


})();
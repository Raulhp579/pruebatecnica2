const parametros = new URLSearchParams(window.location.search);
const id = parametros.get("id");


document.addEventListener("DOMContentLoaded", () => {
    getTareas();
});

const getTareas = async () => {
    try{
        const response = await fetch(`/api/tareasProyecto/${id}`);
        const data = await response.json();
        cargarVista(data);
    }catch(error){
        console.log(error);
    }
}

const cargarVista = (tareas) => {
    const colPorAprobar = document.getElementById("col-por-aprobar");
    const colDesarrollo = document.getElementById("col-desarrollo");
    const colTesting = document.getElementById("col-testing");
    const colFinalizado = document.getElementById("col-finalizado");
    

    tareas.forEach(tarea => {

        console.log(tarea);
        const card = document.createElement("div");
        card.classList.add("card");
        card.classList.add("card-outline");
        card.classList.add("card-info");
        card.innerHTML = `
            <div class="card-header bg-light">
                <h3 class="card-title">
                    ${tarea.descripcion}
                </h3>
            </div>
            <div class="card-body">
                <p><strong>Fecha inicio:</strong> ${tarea.tiempo_inicio}</p>
                <p><strong>Fecha fin:</strong> ${tarea.tiempo_fin}</p>
                <p><strong>Prioridad:</strong> ${tarea.prioridad}</p>
                <p><strong>Estado:</strong> ${tarea.estado}</p>
            </div>
        `;    
        if(tarea.estado === null){
            colPorAprobar.appendChild(card);
        }else if(tarea.estado === "aprobado"){
            colAprobado.appendChild(card);
        }else if(tarea.estado === "desarrollo"){
            colDesarrollo.appendChild(card);
        }else if(tarea.estado === "testing"){
            colTesting.appendChild(card);
        }else if(tarea.estado === "finalizado"){
            colFinalizado.appendChild(card);
        }
    });
}
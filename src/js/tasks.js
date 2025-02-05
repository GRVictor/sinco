(function() {
    
    getTasks();
    let tasks = [];
    let filtered = [];

    // Button to show modal form for adding a new task
    const addTaskButton = document.querySelector('#add-task');
    
    addTaskButton.addEventListener('click', function() {
        showForm();
    });

    // Search filters
    const filters = document.querySelectorAll('.filter input[type="radio"]');
    filters.forEach(radio => {
        radio.addEventListener('input', filterTasks);
    });

    function filterTasks(e) {
        
        const filters = e.target.value;

        if(filters !== '') {
            filtered = tasks.filter(task => task.status === filters);
        } else {
            filtered = [];
        }

        showTasks();

    }

    async function getTasks() {
        try {
            const id = getProject();
            const url = `/api/tasks?url=${id}`;
            const response = await fetch(url);
            const result = await response.json();
            
            tasks = result.tasks;
            showTasks();

        } catch (error) {
            console.log(error);
        }
    }

    function showTasks() {
        clearTasks();
        totalPendingTasks();
        totalCompletedTasks();

        const arrayTasks = filtered.length ? filtered : tasks;

        // Text if there are no tasks
        if(arrayTasks.length === 0) {
            const tasksContainer = document.querySelector('#task-list');
            const noTaskText = document.createElement('LI');
            noTaskText.textContent = 'No hay tareas en este proyecto';
            noTaskText.classList.add('no-tasks');

            tasksContainer.appendChild(noTaskText); 
            return;
        }

        const status = {
            0: 'Pendiente',
            1: 'Completada'
        };

        arrayTasks.forEach(task => {
            const tasksContainer = document.createElement('LI');
            tasksContainer.dataset.taskId = task.id;
            tasksContainer.classList.add('task');

            const taskName = document.createElement('P');
            taskName.textContent = task.name;
            taskName.ondblclick = function() {
                showForm(edit = true, {...task});
            }

            const divOptions = document.createElement('DIV');
            divOptions.classList.add('options');

            // Buttons
            const StatusTaskBtn = document.createElement('BUTTON');
            StatusTaskBtn.classList.add('status-task');
            StatusTaskBtn.classList.add(`${status[task.status].toLowerCase()}`);
            StatusTaskBtn.textContent = status[task.status];
            StatusTaskBtn.dataset.status = task.status;
            StatusTaskBtn.ondblclick = function() {
                changeStatusTask({...task});
            }
            

            const DeleteTaskBtn = document.createElement('BUTTON');
            DeleteTaskBtn.classList.add('delete-task');
            DeleteTaskBtn.dataset.taskId = task.id;
            DeleteTaskBtn.textContent = 'Eliminar';
            DeleteTaskBtn.ondblclick = function() { 
                confirmDeleteTask({...task});
             };

            divOptions.appendChild(StatusTaskBtn);
            divOptions.appendChild(DeleteTaskBtn);

            tasksContainer.appendChild(taskName);
            tasksContainer.appendChild(divOptions);

            const tasksList = document.querySelector('#task-list');
            tasksList.appendChild(tasksContainer);

        });

    }

    function totalPendingTasks() {
        const totalPending = tasks.filter(task => task.status === "0").length;
        const pendingRadio = document.querySelector('#pending'); 

        if(totalPending === 0) {
            pendingRadio.disabled = true;
        } else {
            pendingRadio.disabled = false;
        }
            
    }

    function totalCompletedTasks() {
        const totalCompleted = tasks.filter(task => task.status === "1").length;
        const completedRadio = document.querySelector('#completed');

        if(totalCompleted === 0) {
            completedRadio.disabled = true;
        } else {
            completedRadio.disabled = false;
        }
    }

    function showForm(edit = false, task = {}) {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="form new-task">
                <legend>${edit ? 'Editar tarea' : 'Añade una nueva tarea'}</legend>
                <div class="field">
                    <label for="task">Nombre de la tarea</label>
                    <input type="text" id="task" name="task" value="${task.name ? task.name : ''}" placeholder="${task.name ? "Editar tarea" : 'Ingresa el nombre de la tarea'}"/>
                </div>

                <div class="options">
                    <input type="submit" class="submit-new-task" value="Aceptar"/>
                    <button type="button" class="close-modal">Cancelar</button>
                </div>

            </form>
            `;

        setTimeout(() => {
            const form = document.querySelector('.form');
            form.classList.add('animate');
        }, 0);

        modal.addEventListener('click', (e) => {
            e.preventDefault();

            if (e.target.classList.contains('close-modal')) {
                const form = document.querySelector('.form');
                form.classList.add('close');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            } 
            
            if (e.target.classList.contains('submit-new-task')) {
                const nameTask = document.querySelector('#task').value.trim();
                if (nameTask === '') {
                    // Show error message
                    showAlert('El nombre de la tarea es obligatorio', 'error', document.querySelector('.form legend'));
                    return;
                }

                if (edit) {
                    task.name = nameTask;
                    updateTask(task)
                } else {
                    addTask(nameTask);
                } 

            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function showAlert(message, type, reference) {
        // Prevent multiple alerts
        const prevAlert = document.querySelector('.alert');
        if (prevAlert) {
            prevAlert.remove();
        }

        const alert = document.createElement('DIV');
        alert.classList.add('alert', type);
        alert.textContent = message;
        
        reference.parentElement.insertBefore(alert, reference.nextElementSibling);

        // Remove alert after 3 seconds
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }

    // Query to add a new task
    async function  addTask(task) {
        // Construct the request
        const data = new FormData();
        data.append('name', task);
        data.append('projectId', getProject());

        try {
            const url = 'http://localhost:3000/api/tasks';
            const response = await fetch(url, {
                method: 'POST',
                body: data
            });

            const result = await response.json();
            showAlert(result.message, result.type, document.querySelector('.form legend'));

            if(result.type === 'success') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1200);

                // Add object to the global array of tasks
                const taskObj = {
                    id: String(result.id),
                    name: task,
                    status: "0",
                    projectId: result.projectId
                } 

                tasks = [...tasks, taskObj];
                showTasks();

            }

        } catch (error) {
            console.log(error);
        }
    }

    function changeStatusTask(task) {
        const newStatus = task.status === "1" ? "0" : "1";
        task.status = newStatus;
        updateTask(task);
    }

    async function updateTask(task) {

        const {id, name, status, projectId} = task;

        const data = new FormData();
        data.append('id', id);
        data.append('name', name);
        data.append('status', status);
        data.append('projectId', projectId);
        data.append('url', getProject());

        // for(let value of data.values()) {
        //     console.log(value);
        // }

        try {
            const url = 'http://localhost:3000/api/tasks/update';

            const response = await fetch(url, {
                method: 'POST',
                body: data
            });
            const result = await response.json();
            
            if(result.response.type === 'success') {
                // showAlert(result.response.message, result.response.type, document.querySelector('.container-new-task'));

                Swal.fire(
                    result.response.message,
                    result.response.message,
                    'success'
                );
                const modal = document.querySelector('.modal');
                if(modal) {
                    modal.remove();
                }

                tasks = tasks.map(taskMemory => {
                    if(taskMemory.id === id) {
                        taskMemory.status = status;
                        taskMemory.name = name;

                    } 
                    return taskMemory;
                });
                showTasks();
            }

        } catch (error) {
            console.log(error);
        }

    }
    
    function confirmDeleteTask(task) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Este cambio no se puede revertir",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#CFD2B2",
            cancelButtonColor: "#BF0603",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar"
          }).then((result) => {
            if (result.isConfirmed) {
                deleteTask(task);
            }
          });
    }

    async function deleteTask(task) {

        const {id, name, status} = task;
        const data = new FormData();
        data.append('id', id);
        data.append('name', name);
        data.append('status', status);
        data.append('url', getProject());

        try {
            const url = 'http://localhost:3000/api/tasks/delete';
            const response = await fetch(url, {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            if(result.result) {
                // showAlert(result.message, result.type, document.querySelector('.container-new-task'));

                Swal.fire(
                    'Tarea eliminada',
                    result.message,
                    'success'
                );
                
                tasks = tasks.filter(taskMemory => taskMemory.id !== id);
                showTasks();
            }

        } catch (error) {
            console.log(error);
        }

    }

    function getProject() {
        const project = Object.fromEntries(
            new URLSearchParams(window.location.search)
        );
        return project.url;
    }

    function clearTasks() {
        const tasksList = document.querySelector('#task-list');
        while(tasksList.firstChild) {
            tasksList.removeChild(tasksList.firstChild);
        }
    }

})();
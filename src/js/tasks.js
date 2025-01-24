(function() {
    // Button to show modal form for adding a new task
    const addTaskButton = document.querySelector('#add-task');
    addTaskButton.addEventListener('click', showForm);

    function showForm() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="form new-task">
                <legend>Agregar una tarea</legend>
                <div class="field">
                    <label for="task">Nombre de la tarea</label>
                    <input type="text" id="task" name="task" placeholder="Ingresa el nombre de la tarea"/>
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
                submitFormNewTask();
            }


        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormNewTask() {
        const task = document.querySelector('#task').value.trim();
        console.log(task);
        if (task === '') {
            // Show error message
            showAlert('El nombre de la tarea es obligatorio', 'error', document.querySelector('.form legend'));
            return;
        }

        // Add task to the database
        addTask(task);

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
    async function addTask(task) {
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
            console.log(result);
            showAlert(result.message, result.type, document.querySelector('.form legend'));

            if(result.type === 'success') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2500);
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

})();
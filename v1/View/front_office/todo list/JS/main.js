// Selectors

const toDoInput = document.querySelector('.todo-input');
const toDoBtn = document.querySelector('.todo-btn');
const toDoList = document.querySelector('.todo-list');
const standardTheme = document.querySelector('.standard-theme');
const lightTheme = document.querySelector('.light-theme');
const darkerTheme = document.querySelector('.darker-theme');
const taskForm = document.getElementById("taskForm");


// Event Listeners

toDoBtn.addEventListener('click', addToDoPrime);
toDoList.addEventListener('click', deletecheck);
document.addEventListener("DOMContentLoaded", getTodos);
standardTheme.addEventListener('click', () => changeTheme('standard'));
lightTheme.addEventListener('click', () => changeTheme('light'));
darkerTheme.addEventListener('click', () => changeTheme('darker'));
taskForm.addEventListener("submit", function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Call your function here
    addToDoPrime(event);
});

// Check if one theme has been set previously and apply it (or std theme if not found):
let savedTheme = localStorage.getItem('savedTheme');
savedTheme === null ?
    changeTheme('standard')
    : changeTheme(localStorage.getItem('savedTheme'));

// Functions;
function addToDoPrime(event) {
    var xhr = new XMLHttpRequest();
    var url = './php/add_task.php'; // URL to send the data to
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Prepare the data to be sent
    var data = 'task=' + encodeURIComponent(toDoInput.value);

    // Define what happens on successful data submission
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var responseText = xhr.responseText;
            if (responseText.includes('Task added successfully')) {
                parts = responseText.split(':');
                id_task = parts[1].trim();
                addToDo(event, id_task);
            }
        }
    };

    if (toDoInput.value === '') {
        alert("You must write something!");
    } 
    else {
        // Send the data
        xhr.send(data);
    }
}

function addToDo(event, id_task) {
    // Prevents form from submitting / Prevents form from relaoding;
    event.preventDefault();

    // toDo DIV;
    const toDoDiv = document.createElement("div");
    toDoDiv.classList.add('todo', `${savedTheme}-todo`);
    toDoDiv.id = 'task-id-' + id_task;

    // Create LI
    const newToDo = document.createElement('li');
    if (toDoInput.value === '') {
            alert("You must write something!");
        } 
    else {
        // newToDo.innerText = "hey";
        newToDo.innerText = toDoInput.value;
        newToDo.classList.add('todo-item');
        toDoDiv.appendChild(newToDo);

        // Adding to local storage;
        //savelocal(toDoInput.value);

        // check btn;
        const checked = document.createElement('button');
        checked.innerHTML = '<i class="fas fa-check"></i>';
        checked.classList.add('check-btn', `${savedTheme}-button`);

        checked.addEventListener('click', function() {
            updateTask(id_task);
        });

        toDoDiv.appendChild(checked);
        // delete btn;
        const deleted = document.createElement('button');
        deleted.innerHTML = '<i class="fas fa-trash"></i>';
        deleted.classList.add('delete-btn', `${savedTheme}-button`);
        toDoDiv.appendChild(deleted);

        // Append to list;
        toDoList.appendChild(toDoDiv);

        // CLearing the input;
        toDoInput.value = '';
    }

}   

function deletecheck(event){

    // console.log(event.target);
    const item = event.target;

    // delete
    if(item.classList[0] === 'delete-btn')
    {
        // item.parentElement.remove();
        // animation
        item.parentElement.classList.add("fall");

        //removing local todos;
        //removeLocalTodos(item.parentElement);
        //console.log(item.parentElement.children[0].innerText);
        //console.log(item.parentElement.id);

        taskIdItem = item.parentElement.id;
        taskId = taskIdItem.match(/\d+/)[0];

        task_content = item.parentElement.children[0].innerText

        deleteTask(item, taskId);

        item.parentElement.addEventListener('transitionend', function(){
            item.parentElement.remove();
        })
    }

    // check
    if(item.classList[0] === 'check-btn')
    {
        item.parentElement.classList.toggle("completed");
    }


}

function deleteTask(item, id_task) {
    var xhr = new XMLHttpRequest();
    var url = './php/delete_task.php'; // URL to send the data to
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Prepare the data to be sent
    var data = 'task_id=' + encodeURIComponent(id_task);

    // Define what happens on successful data submission
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var responseText = xhr.responseText;
            console.log(responseText);
            if (responseText.includes('Task deleted successfully')) {
                item.parentElement.addEventListener('transitionend', function(){
                    item.parentElement.remove();
                })
            }
        }
    };

    
    // Send the data
    xhr.send(data);
    
}

function updateTask(id_task) {
    var xhr = new XMLHttpRequest();
    var url = './php/update_task_state.php'; // URL to send the data to
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Prepare the data to be sent
    var data = 'task_id=' + encodeURIComponent(id_task);

    // Define what happens on successful data submission
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var responseText = xhr.responseText;
            console.log(responseText);
            if (responseText.includes('Task updated successfully')) {
                console.log('Task updated successfully');
            }
        }
    };

    
    // Send the data
    xhr.send(data);
    
}

// Saving to local storage:
function savelocal(todo){
    console.log(todo);
    //Check: if item/s are there;
    let todos;
    if(localStorage.getItem('todos') === null) {
        todos = [];
    }
    else {
        todos = JSON.parse(localStorage.getItem('todos'));
    }

    todos.push(todo);
    localStorage.setItem('todos', JSON.stringify(todos));
}


function getTodosAndAddThem(todos) {

    todos.forEach(function(todo) {
        // toDo DIV;
        const toDoDiv = document.createElement("div");
        if (todo.status === 'done') {
            toDoDiv.classList.add("todo", `${savedTheme}-todo`, "completed");
        } else {
            toDoDiv.classList.add("todo", `${savedTheme}-todo`);
        }

        toDoDiv.id = 'task-id-' + todo.id;

        // Create LI
        const newToDo = document.createElement('li');
        
        newToDo.innerText = todo.task;
        newToDo.classList.add('todo-item');
        toDoDiv.appendChild(newToDo);

        // check btn;
        const checked = document.createElement('button');
        checked.innerHTML = '<i class="fas fa-check"></i>';
        checked.classList.add("check-btn", `${savedTheme}-button`);

        checked.addEventListener('click', function() {
            updateTask(todo.id);
        });

        toDoDiv.appendChild(checked);
        // delete btn;
        const deleted = document.createElement('button');
        deleted.innerHTML = '<i class="fas fa-trash"></i>';
        deleted.classList.add("delete-btn", `${savedTheme}-button`);
        toDoDiv.appendChild(deleted);

        // Append to list;
        toDoList.appendChild(toDoDiv);
    });
}

function getTodos1() {
    //Check: if item/s are there;
    let todos;
    todos = [];
   
    todos = JSON.parse(localStorage.getItem('todos'));
    

    todos.forEach(function(todo) {
        // toDo DIV;
        const toDoDiv = document.createElement("div");
        toDoDiv.classList.add("todo", `${savedTheme}-todo`);

        // Create LI
        const newToDo = document.createElement('li');
        
        newToDo.innerText = todo;
        newToDo.classList.add('todo-item');
        toDoDiv.appendChild(newToDo);

        // check btn;
        const checked = document.createElement('button');
        checked.innerHTML = '<i class="fas fa-check"></i>';
        checked.classList.add("check-btn", `${savedTheme}-button`);
        toDoDiv.appendChild(checked);
        // delete btn;
        const deleted = document.createElement('button');
        deleted.innerHTML = '<i class="fas fa-trash"></i>';
        deleted.classList.add("delete-btn", `${savedTheme}-button`);
        toDoDiv.appendChild(deleted);

        // Append to list;
        toDoList.appendChild(toDoDiv);
    });
}

function getTodos() {
    // Create a new XMLHttpRequest object
    const xhr = new XMLHttpRequest();

    // Define the URL of the PHP script
    const url = './php/get_profile_tasks.php';

    // Define the function to handle the response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // If the request is successful, parse the JSON response
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                // Handle the response data here
                console.log(response);
                // Call your function here after successful response
                getTodosAndAddThem(response);
            } else {
                // If there is an error, handle it here
                console.error('Error:', xhr.status);
            }
        }
    };

    // Open a GET request to the PHP script URL
    xhr.open('POST', url, true);

    // Set the Content-Type header for POST requests
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Send the request with any necessary data
    xhr.send();
}


function removeLocalTodos(todo){
    //Check: if item/s are there;
    let todos;
    if(localStorage.getItem('todos') === null) {
        todos = [];
    }
    else {
        todos = JSON.parse(localStorage.getItem('todos'));
    }

    const todoIndex =  todos.indexOf(todo.children[0].innerText);
    // console.log(todoIndex);
    todos.splice(todoIndex, 1);
    // console.log(todos);
    localStorage.setItem('todos', JSON.stringify(todos));
}

// Change theme function:
function changeTheme(color) {
    localStorage.setItem('savedTheme', color);
    savedTheme = localStorage.getItem('savedTheme');

    document.body.className = color;
    // Change blinking cursor for darker theme:
    color === 'darker' ? 
        document.getElementById('title').classList.add('darker-title')
        : document.getElementById('title').classList.remove('darker-title');

    document.querySelector('input').className = `${color}-input`;
    // Change todo color without changing their status (completed or not):
    document.querySelectorAll('.todo').forEach(todo => {
        Array.from(todo.classList).some(item => item === 'completed') ? 
            todo.className = `todo ${color}-todo completed`
            : todo.className = `todo ${color}-todo`;
    });
    // Change buttons color according to their type (todo, check or delete):
    document.querySelectorAll('button').forEach(button => {
        Array.from(button.classList).some(item => {
            if (item === 'check-btn') {
              button.className = `check-btn ${color}-button`;  
            } else if (item === 'delete-btn') {
                button.className = `delete-btn ${color}-button`; 
            } else if (item === 'todo-btn') {
                button.className = `todo-btn ${color}-button`;
            }
        });
    });
}

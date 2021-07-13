//слой модели данных
const tasks = {};

function addTaskIntoModelTasks(task) {
    tasks[task.id] = task;
}

//слой связи с сервером
async function apiGetTaskFromBackend() {
    const response = await fetch('server/update.php', {method: "POST"});
    const jsonResponse = await response.json();
    return jsonResponse;
}

async function apiSendTaskToBackend(dataTask) {
    const response = await fetch('server/server.php', {
        method: "POST",
        body: dataTask,
    });
    const jsonResponse = await response.json();
    return jsonResponse;
}

async function apiRemoveTaskOnBackend(removeTask) {
    const response = await fetch('server/delete.php', {
        method: 'POST',
        body: removeTask
    })
    return response;
}

async function apiEditTaskOnBackend(editTask) {
    const response = await fetch('server/editTask.php', {
        method: "POST",
        body: editTask
    });
    return response;
}

async function apiSendColorOnBackend(colorTask) {
    const response = await fetch('server/changeColorTask.php', {
        method: "POST",
        body: colorTask,
    });
}

//слой вывода данных пользователю
function createNodeTask(task) {
    /*
    //пометить комментами этапы работы функции!
    let taskDiv = document.createElement('div');
    taskDiv.setAttribute('id', task.id);
    taskDiv.setAttribute('class', 'task');
    
    let textDiv = document.createElement('textarea');
    textDiv.setAttribute('class', 'task-text');
    textDiv.setAttribute("readonly", "");
    
    let editDiv = document.createElement('div');
    editDiv.setAttribute('class', 'task-edit');
    editDiv.setAttribute('onclick', `startEditTaskController(${task.id})`)
    let buttonDiv = document.createElement('div');
    buttonDiv.setAttribute('class', 'task-button');
    buttonDiv.setAttribute('onclick', `removeTaskController(${task.id})`)

    
    let text = document.createTextNode(task.textTask);
    textDiv.appendChild(text);
    editDiv.appendChild(document.createTextNode('edit'));
    taskDiv.appendChild(textDiv);
    taskDiv.appendChild(editDiv);
    taskDiv.appendChild(buttonDiv);
    let divAdd = document.getElementById('list');
    divAdd.appendChild(taskDiv); 
    
    //вынести в отдельную функцию
    
    let sizeBlock = textDiv.offsetWidth - 12;
    document.getElementById('test-size').innerText = task.textTask;
    let sizeText = document.getElementById('test-size').offsetWidth;
    textDiv.setAttribute("rows", Math.ceil(sizeText / sizeBlock));
    */ 
    //родительский элемент Task
    let color = getColor(Number(task.colorId));
    let taskDiv = document.createElement('div');
    taskDiv.setAttribute('id', task.id);
    taskDiv.setAttribute('class', 'task');
    taskDiv.style.backgroundColor = `${color}`;
    taskDiv.style.borderColor = `dark${color}`;
    //Дочерние элементы Task
    let divMovementBlock = document.createElement('div');
    divMovementBlock.setAttribute('class', 'movement-block');
    let divTextBlock = document.createElement('div');
    divTextBlock.setAttribute('class', 'text-block');
    let divControlBlock = document.createElement('div');
    divControlBlock.setAttribute('class', 'control-block');
    //Дочерний элемент text-block
    let textarea = document.createElement('textarea');
    textarea.setAttribute('readonly', '');
    textarea.setAttribute('class', 'task-text');
    textarea.setAttribute('id', 'task-text-' + task.id);
    textarea.style.backgroundColor = `light${color}`;
    let text = document.createTextNode(task.textTask);
    textarea.appendChild(text);
    //Дочерние элементы control-block
    let buttonControlEdit = document.createElement('div');
    buttonControlEdit.setAttribute('onClick', `startEditTaskController(${task.id})`);
    buttonControlEdit.setAttribute('id', 'button-edit');
    let buttonControlColor = document.createElement('div');
    buttonControlColor.setAttribute('id', 'button-color');
    
    //test
    buttonControlColor.setAttribute('onclick', `createTimedElementForChoiceColor(${task.id})`);
    buttonControlColor.addEventListener('mouseover', function () {
        createTimedElementForChoiceColor(task.id);
    });
    buttonControlColor.addEventListener('mouseout', function() {
        if (event.relatedTarget.id !== "color-palette-" + task.id) {
            deleteTimedElementForChoiceColor(task.id);
        }
    });

    let buttonControlDelete = document.createElement('div');
    buttonControlDelete.setAttribute('id', 'button3');
    buttonControlDelete.setAttribute('onclick', `removeTaskController(${task.id})`);
    let buttonControlInfo = document.createElement('div');
    buttonControlInfo.setAttribute('id', 'button4');

    divControlBlock.appendChild(buttonControlEdit);
    divControlBlock.appendChild(buttonControlColor);
    divControlBlock.appendChild(buttonControlDelete);
    divControlBlock.appendChild(buttonControlInfo);

    divTextBlock.appendChild(textarea);

    taskDiv.appendChild(divMovementBlock);
    taskDiv.appendChild(divTextBlock);
    taskDiv.appendChild(divControlBlock);

    let divAdd = document.getElementById('list');
    divAdd.appendChild(taskDiv);
}

function deleteNodeTask(delId) {
    let nodeTask = document.getElementById(delId);
    nodeTask.remove();
}

//ивенты
//переделать
const testEvent = document.getElementById('input');
testEvent.addEventListener('keydown', function(key) {
    if (key.keyCode === 13) {
        addTaskController();
    }
});


/*
--------------------УПРАВЛЯЮЩИЕ ФУНКЦИИ--------------------
*/
async function loadTaskController() {
    let loadTasksFromBackend = await apiGetTaskFromBackend();
    for(let key in loadTasksFromBackend) {
        addTaskIntoModelTasks(loadTasksFromBackend[key]);
        createNodeTask(loadTasksFromBackend[key]);
    }
}
//добавить дефолтную отправку цвета в базу, поправить запись цвета на сервере
async function addTaskController() {
    const dataTask = new FormData();
    dataTask.append('input', document.getElementById('input').value);
    dataTask.append('color', 5); 
    let loadTasksFromBackend = await apiSendTaskToBackend(dataTask);
    console.log(loadTasksFromBackend);
    addTaskIntoModelTasks(loadTasksFromBackend);
    createNodeTask(loadTasksFromBackend);
    document.getElementById('input').value = "";
}

async function removeTaskController(taskId) {
    const removeTask = new FormData();
    removeTask.append('delId', taskId);
    let responseFromServer = await apiRemoveTaskOnBackend(removeTask);
    deleteNodeTask(taskId);
    delete tasks[taskId];
}

function startEditTaskController(taskId) {
    let editTextarea = document.getElementById('task-text-' + taskId);
    editTextarea.removeAttribute("readonly");
    editTextarea.addEventListener('keydown', function(key) {
        if (key.keyCode === 13) {
            endEditTaskController(taskId)
        } 
    });

    buttonEndEdit = document.getElementById(taskId).getElementsByClassName('control-block')[0].firstChild;
    buttonEndEdit.style.background = 'url(/img/check2.svg) no-repeat center center';
    buttonEndEdit.setAttribute('onclick', `endEditTaskController(${taskId})`);

    editTextarea.focus();
    let shiftFocus = editTextarea.value;
    editTextarea.value = "";
    editTextarea.value = shiftFocus;
}

function endEditTaskController(taskId) {
    let endEditTextarea = document.getElementById('task-text-' + taskId);
    endEditTextarea.setAttribute("readonly", '');
    const editTask = new FormData();
    editTask.append('editedTask', endEditTextarea.value);
    editTask.append('editId', taskId);
    
    buttonStartEdit = document.getElementById(taskId).getElementsByClassName('control-block')[0].firstChild;
    buttonStartEdit.style.background = 'url(/img/pencil .svg) no-repeat center center';
    buttonStartEdit.setAttribute('onclick', `startEditTaskController(${taskId})`);
    
    apiEditTaskOnBackend(editTask);
}

//сделать не по нажатию а по наведению выбор цвета отправку хранение и выгрузку из бд
function createTimedElementForChoiceColor(taskId) {
    if (document.getElementById('color-palette-' + taskId) === null) {
        let testUpperDiv = document.createElement('div');
        testUpperDiv.setAttribute('class', 'color-palette');
        testUpperDiv.setAttribute('id', 'color-palette-' + taskId);

        //Добавляются блоки цветов
        let i = 0;
        while (++i <= 5) {
            let colorElement = document.createElement('div');
            colorElement.setAttribute('class', 'color-element');
            colorElement.setAttribute('id', `color-element-${i}`);
            colorElement.setAttribute('onclick', `changeColorTaskController(${taskId}, ${i})`);
            testUpperDiv.appendChild(colorElement);
            console.log(i, '--');
        }

        let tTest = document.getElementById(taskId).getElementsByClassName('control-block')[0];
        let insertTest = document.getElementById(taskId).getElementsByClassName('control-block')[0].getElementsByTagName('div')[2];
        tTest.insertBefore(testUpperDiv, insertTest);
        testUpperDiv.addEventListener('mouseleave', function() {
            deleteTimedElementForChoiceColor(taskId);
        });
    }
}
//доделать отправку цветов в базу данных
function changeColorTaskController(taskId, colorId) {
    let color = getColor(colorId);
    let textareaColor = document.getElementById('task-text-' + taskId);
    textareaColor.style.backgroundColor = `light${color}`;
    let divColor = document.getElementById(taskId);
    divColor.style.backgroundColor = `${color}`;
    divColor.style.borderColor = `dark${color}`;
}
//подобрать приятные сочетания цветов и возвращать их в виде массива
function getColor(colorId) {
    switch(colorId) {
        case 1: 
            return 'blue';
        case 2:
            return 'cyan';
        case 3:
            return 'green';
        case 4:
            return 'salmon';
        default:
            return 'gray';
    }
}

function deleteTimedElementForChoiceColor(taskId) {
    let deleteElement = document.getElementById('color-palette-' + taskId);
    if (deleteElement !== null) {
        deleteElement.remove();
    }
}


loadTaskController();
console.log("model.tasks: ", tasks);
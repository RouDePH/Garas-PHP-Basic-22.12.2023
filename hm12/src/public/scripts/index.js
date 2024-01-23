const BASE_API_URL = "http://localhost:8081/api/";

const TASK_LIST = document.getElementById('task-list');
const TASK_TEMPLATE = document.getElementById('taskTemplate{{id}}').innerHTML;

const submitTaskForm = async (event) => {
    try {
        event.preventDefault();

        const formData = new FormData(event.target);
        const body = {};
        formData.forEach((value, key) => {
            body[key] = value;
        });

        await sendRequest('POST', `${BASE_API_URL}task/`, body).then(() => refreshTaskList());
    } catch (e) {
        alert("Something went wrong")
    }
};

const deleteTask = async (event) => {
    try {
        const id = event.target.parentNode.dataset.taskId;
        await sendRequest('DELETE', `${BASE_API_URL}task/`, {id});
        event.target.parentNode.remove();
    } catch (e) {
        alert("Something went wrong");
    }
};

const completeTask = async (event) => {
    try {
        let {dataset: data, innerHTML: node} = event.target.parentNode;

        const id = data.taskId;
        const response = await sendRequest('PATCH', `${BASE_API_URL}task/`, {id, 'status': 'completed'});
        const {data: task} = response;
        const renderedTask = TASK_TEMPLATE
            .replace('{{id}}', task.id)
            .replace('{{name}}', task.name)
            .replace('{{priority}}', task.priority)
            .replace('{{status}}', task.status);

        event.target.parentNode.innerHTML = renderedTask;

    } catch (e) {
        console.log(e)
        alert("Something went wrong");
    }
};


const refreshTaskList = async () => {
    const tasks = await sendRequest('GET', `${BASE_API_URL}task/`);
    TASK_LIST.innerHTML = '';

    tasks.data.forEach(task => {
        const taskNode = renderTaskNode(task);
        TASK_LIST.appendChild(taskNode);
    });
}

const sendRequest = async (method, url, body = null) => {
    const headers = {
        'Content-Type': 'application/json',
    };

    const response = await fetch(url, {
        method: method,
        headers: headers,
        body: body ? JSON.stringify(body) : null,
    });

    if (!response.ok) {
        const errorMessage = await response.text();
        throw new Error(`Error: ${response.status} - ${errorMessage}`);
    }
    return response.json();
}

const renderTaskNode = (task) => {
    const rawTaskNode = TASK_TEMPLATE
        .replace('{{id}}', task.id)
        .replace('{{name}}', task.name)
        .replace('{{status}}', task.status);
    const taskNode = document.createElement('div');
    taskNode.innerHTML = rawTaskNode;
    taskNode.className = "task-item";
    taskNode.id = `taskTemplate${task.id}`;
    taskNode.dataset.taskId = task.id;
    return taskNode;
}
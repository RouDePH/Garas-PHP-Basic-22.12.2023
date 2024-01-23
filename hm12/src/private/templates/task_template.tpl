<li class="task-item" style="display: none;" id="taskTemplate{{id}}" data-task-id="{{id}}">
    <p>Title: {{name}}, status: {{status}}</p>
    <p>Priority: {{priority}}</p>
    <button onclick="deleteTask(event)">Delete</button>
    <button onclick="completeTask(event)">Complete</button>
</li>
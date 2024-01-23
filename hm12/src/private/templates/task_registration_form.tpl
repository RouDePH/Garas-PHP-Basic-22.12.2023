<form class="task-registration-form" onsubmit="submitTaskForm(event)">
    <label for="taskName">Task Name:</label>
    <input type="text" id="taskName" name="name" required>
    <label for="taskPriority">Priority:</label>
    <input type="number" id="taskPriority" name="priority" required>
    <button type="submit">Add Task</button>
</form>
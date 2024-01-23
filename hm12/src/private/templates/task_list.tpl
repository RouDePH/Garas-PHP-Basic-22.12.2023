<ul id="task-list" class="task-list">
    <?php $taskManager = new TaskManager(PRIVATE_ROOT_PATH . "tasks.json"); ?>
    <?php foreach ($taskManager->getTasks() as $task): ?>
    <?php
            $template = file_get_contents(PRIVATE_ROOT_PATH . "/templates/task_template.tpl");
            $template = str_replace('{{id}}', $task->getId(), $template);
    $template = str_replace('{{name}}', $task->getName(), $template);
    $template = str_replace('{{priority}}', $task->getPriority(), $template);
    $template = str_replace('{{status}}', $task->getStringStatus(), $template);
    $template = str_replace('style="display: none;"', '', $template);
    echo $template;
    ?>
    <?php endforeach; ?>
</ul>
document.addEventListener('DOMContentLoaded', function() {
    const taskList = document.getElementById('task-list');
    const addTaskButton = document.getElementById('add-task-button');
    const taskTemplate = document.getElementById('task-template');

    if (!taskList || !addTaskButton || !taskTemplate) {
        console.error('Required elements for task form not found.');
        return;
    }

    let taskIndex = taskList.querySelectorAll('.task-item').length;

    addTaskButton.addEventListener('click', function() {
        const templateContent = taskTemplate.content.cloneNode(true);
        const newTaskItem = templateContent.querySelector('.task-item');

        // Update name attributes with the correct index
        newTaskItem.querySelectorAll('[name]').forEach(input => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace('INDEX', taskIndex));
        });

        taskList.appendChild(newTaskItem);
        taskIndex++;
        updateRemoveButtons(); // Update visibility after adding
    });

    taskList.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-task')) {
            const itemToRemove = e.target.closest('.task-item');
            if (itemToRemove) {
                 // Check if it's the only item left before removing
                 if (taskList.querySelectorAll('.task-item').length > 1) {
                    itemToRemove.remove();
                 } else {
                     // If it's the last one, just clear the input
                     const input = itemToRemove.querySelector('input[type="text"]');
                     if (input) input.value = '';
                     alert('Minimal harus ada satu baris tugas.');
                 }
                 updateRemoveButtons(); // Update visibility after removing
            }
        }
    });

    // Function to show/hide remove buttons
    function updateRemoveButtons() {
        const taskItems = taskList.querySelectorAll('.task-item');
        taskItems.forEach((item, index) => {
            const removeButton = item.querySelector('.btn-remove-task');
            if (removeButton) {
                // Hide remove button if only one item remains
                removeButton.style.display = taskItems.length > 1 ? 'block' : 'none';
            }
        });
    }

    // Initial check for remove buttons visibility
    updateRemoveButtons();
});

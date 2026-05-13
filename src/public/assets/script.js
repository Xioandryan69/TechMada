// Star rating
function setStars(n) {
  document.querySelectorAll('#stars span').forEach((s, i) => {
    s.classList.toggle('on', i < n);
  });
}

// Range label sync (called inline via oninput, but also wired here for flexibility)
document.addEventListener('DOMContentLoaded', () => {
  const range = document.getElementById('priority-range');
  const label = document.getElementById('range-label');
  if (range && label) {
    range.addEventListener('input', () => { label.textContent = range.value; });
  }

  const rowsContainer = document.getElementById('note-rows');
  const addRowBtn = document.getElementById('add-note-row');

  if (rowsContainer && addRowBtn) {
    const bindRemove = (row) => {
      const removeBtn = row.querySelector('.note-remove');
      if (removeBtn) {
        removeBtn.addEventListener('click', () => {
          if (rowsContainer.children.length > 1) {
            row.remove();
          }
        });
      }
    };

    rowsContainer.querySelectorAll('.note-row').forEach(bindRemove);

    addRowBtn.addEventListener('click', () => {
      const firstRow = rowsContainer.querySelector('.note-row');
      if (!firstRow) {
        return;
      }

      const clone = firstRow.cloneNode(true);
      clone.querySelectorAll('input').forEach((input) => {
        input.value = '';
      });
      clone.querySelectorAll('select').forEach((select) => {
        select.selectedIndex = 0;
      });

      rowsContainer.appendChild(clone);
      bindRemove(clone);
    });
  }
});

// Confirm before destructive actions.
document.querySelectorAll('.delete-form').forEach(f => {
  f.addEventListener('submit', e => {
    if (!confirm('Delete this product? This cannot be undone.')) e.preventDefault();
  });
});

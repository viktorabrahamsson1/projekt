let currentIncidentId = null;

function openCommentModal(incidentId) {
    currentIncidentId = incidentId;
    document.getElementById('commentText').value = '';
    document.getElementById('commentModal').removeAttribute('hidden');
}

function closeCommentModal() {
    document.getElementById('commentModal').setAttribute('hidden', '');
}

function submitComment() {
    const text = document.getElementById('commentText').value.trim();

    if (text === '') {
        alert('Comment cannot be empty');
        return;
    }

    // Koppla till hidden form
    document.getElementById('incident_id').value = currentIncidentId;
    document.getElementById('comment').value = text;

    // POST → add_comment.php
    document.getElementById('commentForm').submit();
}
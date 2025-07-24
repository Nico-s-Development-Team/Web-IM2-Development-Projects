document.querySelector('.profile-save-button').addEventListener('click', () => {
    const payload = {
        first_name: document.getElementById('first-name').value,
        last_name: document.getElementById('last-name').value,
        email: document.getElementById('email').value,
        mobile: document.getElementById('mobile-num').value,
        new_password: document.getElementById('newPassword').value
    };

    fetch('update_profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            location.reload(); // or show a success toast
        }
    });
});

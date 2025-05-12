function switchForm(formType) {
    const loginBlock = document.querySelector('.login-block');
    const registerBlock = document.querySelector('.register-block');
    
    if (formType === 'register') {
        loginBlock.classList.remove('active');
        registerBlock.classList.add('active');
    } else {
        registerBlock.classList.remove('active');
        loginBlock.classList.add('active');
    }
}

function handleFormSubmit(formId, action) {
    const form = document.getElementById(formId);
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        if (formId === 'loginForm') {
            formData.append('action', 'login');
        }
        
        fetch(action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                alert(data.message || 'Произошла ошибка!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке формы');
        });
    });
}

handleFormSubmit('loginForm', 'auth_handler.php');
handleFormSubmit('registerForm', 'register.php');

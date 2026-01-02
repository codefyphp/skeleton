document.addEventListener('alpine:init', () => {
    Alpine.data('addUserForm', () => ({
        username: '',
        firstname: '',
        middlename: '',
        lastname: '',
        email: '',
        role: '',
        password: '',
        showPass: false,
        usernameLength: 0,
        passwordLength: 0,
    }))
})
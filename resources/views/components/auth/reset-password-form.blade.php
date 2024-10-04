<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <label>New Password</label>
                    <input id="password" name="password" placeholder="New Password" class="form-control" type="password"/>
                    <br/>
                    <label>Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="ResetPassword()" class="btn w-100 bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function ResetPassword() {
        let password = document.getElementById('password').value;
        let password_confirmation = document.getElementById('password_confirmation').value;

        showLoader();
        try {
            let res = await axios.post('/reset-password', {
                password: password,
                password_confirmation: password_confirmation
            });
            hideLoader();
            if (res.status === 200 && res.data['status'] === 'success') {
                successToast(res.data['message']);
                setTimeout(function() {
                    window.location.href = '/userLogin';
                })
            }
        }
        catch(error) {
            hideLoader();
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    errorToast(`${errors[key][0]}`);
                });
            } else if (error.response.status === 401) {
                errorToast(error.response.data['message']);
            } else {
                errorToast('An error occurred. Please try again.');
            }
        }
    }

</script>

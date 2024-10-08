<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>6 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function VerifyOtp() {
        let otp = document.getElementById('otp').value;

        showLoader();
        try {
            let res = await axios.post('/verify-otp', {otp : otp, email: sessionStorage.getItem('email')});
            hideLoader()
            if (res.status === 200 && res.data['status'] === 'success') {
                successToast(res.data['message']);
                sessionStorage.clear();
                setTimeout(function() {
                    window.location.href = '/resetPassword';
                }, 1000);
            }
        }
        catch (error) {
            hideLoader();
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    errorToast(`${errors[key][0]}`);
                });
            } else if (error.response.status === 401) {
                errorToast(error.response.data['message']);
            } else {
                errorToast("An error occurred. Please try again.");
            }
        }
    }
</script>

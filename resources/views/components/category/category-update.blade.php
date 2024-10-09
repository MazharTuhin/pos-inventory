<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryNameUpdate">
                                <input class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="CategoryUpdate()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function FillUpdateForm(id) {
        document.getElementById('updateID').value = id;
        showLoader();
        let res = await axios.post('/category-by-id', {id: id});
        hideLoader()

        document.getElementById('categoryNameUpdate').value = res.data['name'];
    }

    async function CategoryUpdate() {
        let categoryName = document.getElementById('categoryNameUpdate').value;
        let categoryId = document.getElementById('updateID').value;

        try {
            showLoader();
            let res = await axios.post('/category-update', {name: categoryName, id: categoryId});
            hideLoader();

            if (res.status === 200 && res.data['status'] === 'success') {
                document.getElementById('update-modal-close').click();
                successToast(res.data['message']);
                document.getElementById('update-form').reset();

                await GetList();
            }
        }
        catch(error) {
            hideLoader();
            if(error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    errorToast(`${errors[key][0]}`)
                });
            }
            else {
                errorToast("An error occurred. Please try again.")
            }
        }
    }
</script>
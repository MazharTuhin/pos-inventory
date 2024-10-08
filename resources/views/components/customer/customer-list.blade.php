<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Customer</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0 bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    GetCustomerList();
    async function GetCustomerList() {
        showLoader();
        let res = await axios.get('/customer-list');
        hideLoader();

        let tableData = $('#tableData');
        let tableList = $('#tableList');

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach((item, index) => {
            let row =   `<tr>
                            <td>${index+1}</td>
                            <td>${item['name']}</td>
                            <td>${item['email']}</td>
                            <td>${item['mobile']}</td>
                            <td>
                                <button data-id="${item['id']}" class="btn editBtn btn-sm bg-gradient-success">Edit</button>   
                                <button data-id="${item['id']}" class="btn deleteBtn btn-sm bg-gradient-danger">Delete</button>
                            </td>
                        </tr>`
            tableList.append(row);
        });

        $('.editBtn').on('click', async function() {
            let id = $(this).data('id');
            await FillUpdateForm(id);
            $('#update-modal').modal('show');
        });

        $('.deleteBtn').on('click', async function() {
            let id = $(this).data('id');
            $('#delete-modal').modal('show');
            $('#deleteID').val(id)
        })


        new DataTable('#tableData', {
            order: [[0, 'desc']],
            lengthMenu: [10, 20, 30, 50, 70, 100]
        })
    }
</script>

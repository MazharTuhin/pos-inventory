<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Category</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0 bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-secondary" />
                <div class="table-responsive">
                    <table class="table" id="tableData">
                        <thead>
                            <tr class="bg-light">
                                <th>No.</th>
                                <th>Category</th>
                                <th>Created At</th>
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
</div>

<script>
    GetList();
    async function GetList() {
        showLoader();
        let res=await axios.get("/category-list")
        hideLoader();

        let tableList = $('#tableList');
        let tableData = $('#tableData');

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach((item, index) => {
            const date = new Date(item['created_at']);
            const formattedDate = `${date.getDate()} ${date.toLocaleString('en-US', { month: 'long' })} ${date.getFullYear()}, ${date.getHours()}:${date.getMinutes()} ${date.getHours() >= 12 ? 'PM' : 'AM'}`;

            let row =   `<tr>
                            <td>${index+1}</td>
                            <td>${item['name']}</td>
                            <td>${formattedDate}</td>
                            <td>
                                <button data-id="${item['id']}" class="btn editBtn btn-sm bg-gradient-success">Edit</button>   
                                <button data-id="${item['id']}" class="btn deleteBtn btn-sm bg-gradient-danger">Delete</button>   
                            </td>
                
                        </tr>`

            tableList.append(row)
        })

        $('.editBtn').on('click', async function() {
            let id = $(this).data('id');
            await FillUpdateForm(id);
            $("#update-modal").modal('show');
        })

        $('.deleteBtn').on('click', function() {
            let id = $(this).data('id');
            $("#delete-modal").modal('show');
            $("#deleteID").val(id);
        })



        // tableData.DataTable({
        //     order: [[0, 'asc']],
        //     lengthMenu: [5, 10, 15, 20, 25, 30]
        // });

        new DataTable('#tableData', {
            order: [[0, 'desc']],
            lengthMenu: [10, 20, 30, 50, 70]
        })


    }
</script>

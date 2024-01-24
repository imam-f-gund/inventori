@extends('template.adminlte.layouts.app')

@section('content')
    <div class="container">

         <!-- Modal -->
        <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="display: none">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form" id="formRejected">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" name="status" id="status" value="rejected">
                            </div>   
                            
                        </form>
                        <form action="" class="form" id="formApproved">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" name="status" id="status" value="approved">
                            </div>   
                            
                        </form>
                        <div id="tmbdata"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <div class="btnsimpan"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <div class="col">
                            <h2 class="main-title my-auto">Data Request Barang</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <div class="col-12 col-md-12 col-sm-12">
                            <button type="button" id="btnModals" class="btn btn-primary btn-sm float-right">
                               <i class="fas fa-plus"> Tambah</i>
                            </button>
                        </div> --}}
                        <div class="table-responsive">
                            <table id="dataTable" class="mt-2 table table-stripped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Perequest</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="link">
                                
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('js')
    <script>
        var url = "{{ url('/api/user-request') }}";
        var me = "{{ url('api/me') }}";

        $('.btn-close')
        function start() {
            loadingTable('#data');
            
            getData(url, token).done(function(response) {
                isi = ``;
                if (response.data.data.length > 0) {
                    nomer = 1;
                    
                    $.each(response.data.data, function(i, val) {
                        status = '';
                        btnAction = '';
                        if (val.status == 'pending') {
                            status = '<span class="badge badge-warning">pending</span>';
                            btnAction = `<button class="btn btn-danger btn-sm" id="btnRejected" data-id="` + val.id + `"><i class="icon fas fa-ban"> Rejected</i></button>
                            <button class="btn btn-success btn-sm" id="btnApproved" data-id="` + val.id + `"><i class="icon fas fa-check"> Approved</i></button>`;
                        }else if(val.status == 'approved'){
                            status = '<span class="badge badge-success">approved</span>';
                        }else{
                            status = '<span class="badge badge-danger">rejected</span>';
                        }
                        isi += `
                    <tr>
                        <td>` + nomer + `</td>
                        <td>` + val.user.first_name +` `+ val.user.last_name + `</td>
                        <td>` + val.product.product_name + `</td>
                        <td>` + val.qty + `</td>
                        <td>` + status + `</td>
                        <td class="col-3">`
                            +btnAction+
                            `</td>
                    </tr>
                `;
                        nomer++;
                    });
                
                    btnPrev = '';
                if (response.data.prev_page_url == null) {
                    btnPrev = `<button type="button" class="next btn btn-md btn-primary" data-value="`+response.data.prev_page_url+`" style="min-width: 50%" disabled>
                            < Prev
                    </button>`;
                }else{
                    btnPrev = ` <button type="button" class="next btn btn-md btn-primary" data-value="`+response.data.prev_page_url+`" style="min-width: 50%" >
                                        < Prev
                                </button>`;
                }

                btnNext = '';
                if (response.data.next_page_url == null) {
                    btnNext = `<button type="button" class="next btn btn-md btn-primary" data-value="`+response.data.next_page_url+`" style="min-width: 50%" disabled>
                            Next >  
                    </button>`;
                }else{
                    btnNext = ` <button type="button" class="next btn btn-md btn-primary" data-value="`+response.data.next_page_url+`" style="min-width: 50%" >
                                    Next >
                                </button>`;
                }

                link = `<div class="card">
                        <div class="card-body row text-center">
                            <div class="col-5">
                                `+btnPrev+`
                            </div>
                            <div class="col-2">Pages `+response.data.current_page+`</div>
                            <div class="col-5">
                               `+btnNext+`
                            </div>
                            </div>
                                    </div>`;
                }
                

                $('#data').html(isi);
                $('#link').html(link);
                
            
            });

            getData(me, token).done(function(response) {
                $('#infoRequest').html(response.infoRequest);
            });
        }

        start();


        $(document).on("click", '#btnRejected', function() {
            id = $(this).data('id');
            form = $('#formRejected');
            Swal.fire({
                title: "Apakah kamu yakin ?",
                text: "Status Akan Direjected dan tidak bisa diubah",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, reject !",
                cancelButtonText: "Tidak, batalkan !"
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    updateData(url+'/'+id, token, form).done(function(response, responseText, xhr) {
                        if (xhr.status === 201) {
                        
                        } else {
                            successAlert(response.message);
                            start();
                        }
                        // successAlert(data.message);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        var err = JSON.parse(jqXHR.responseText);
                        errorAlert(err.message);
                    
                    });
                } else if (result.isDismissed) {
                    Swal.fire("Dibatalkan", "Data batal direject", "error");
                }
            });


        });

        $(document).on("click", '#btnApproved', function() {
            id = $(this).data('id');
            form = $('#formApproved');
            Swal.fire({
                title: "Apakah kamu yakin ?",
                text: "Status Akan Diapproved dan tidak bisa diubah",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, approve !",
                cancelButtonText: "Tidak, batalkan !"
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    updateData(url+'/'+id, token, form).done(function(response, responseText, xhr) {
                        if (xhr.status === 201) {
                        
                        } else {
                            successAlert(response.message);
                            start();
                        }
                        // successAlert(data.message);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        var err = JSON.parse(jqXHR.responseText);
                        errorAlert(err.message);
                    
                    });
                } else if (result.isDismissed) {
                    Swal.fire("Dibatalkan", "Data batal direject", "error");
                }
            });


        });
    </script>
    @endsection

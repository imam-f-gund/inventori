@extends('template.adminlte.layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="col">
                        <h2 class="main-title my-auto">Dashboard</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row admin" style="display: none">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                        
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3 id="data-total-product">150</h3>
                                        <p>Total Barang</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                        </div>
                                    <a href="{{ url('/monitoring-stok') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-12">
                            
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3 id="data-transaksi-masuk">53</h3>
                                        <p>Total Transaksi Masuk</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="{{ url('/transaksi-in') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                        
                                <div class="small-box bg-secondary">
                                    <div class="inner">
                                        <h3 id="data-transaksi-keluar">44</h3>
                                        <p>Total Transaksi Keluar</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                    <a href="{{ url('/transaksi-in') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-12">
                            
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3 id="data-sisa-stock">65</h3>
                                        <p>Total Stock</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>
                                    <a href="{{ url('/monitoring-stok') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row user" style="display: none">
                        <div class="col-lg-12 col-12">
                        
                            <div class="small-box bg-info">
                                <div class="inner text-center">
                                    <h3 id="data">150</h3>
                                    <p>Total pemesanan</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                    </div>
                                <a href="{{ url('/laporan-pemesanan') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        var url = "{{ url('/api/dashboard') }}";
        $(document).ready(function() {
            if (localStorage.getItem('menu') == 'admin') {
                $('.admin').css('display','block');
            }else{
                $('.user').css('display','block');
            } 
        });
        
        function start() {
            $('#data').html(`<div class="spinner-border spinner-sm text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>`);
            $('#data-total-product').html(`<div class="spinner-border spinner-sm text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>`);
            $('#data-transaksi-masuk').html(`<div class="spinner-border spinner-sm text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>`);
            $('#data-transaksi-keluar').html(`<div class="spinner-border spinner-sm text-warning" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>`);
            $('#data-sisa-stock').html(`<div class="spinner-border spinner-sm text-sucess" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>`);
        
            getData(url, token).done(function(response) {

                $('#data').html(response.data.sisa_stock);
                $('#data-total-product').html(response.data.total_product);
                $('#data-transaksi-masuk').html(response.data.transaksi_masuk);
                $('#data-transaksi-keluar').html(response.data.transaksi_keluar);
                $('#data-sisa-stock').html(response.data.sisa_stock);
                
            });
        }

        start();


        var id = '';
        $(document).on("click", '#btnModals', function() {

            id = $(this).attr("data-id");

            var data = $(this).attr("data-value");
            parm = $(this).attr("data-btn");
            if (parm == 'edit') {
                $('#exampleModalLabel').html('Ubah Kategori');
                $('.btnsimpan').html(`<button type="button" class="btn btn-primary btnsimpan" id="btnSimpanUpdate">Simpan</button>`);
                $('#category_name').val(data.split('|')[0]);
                $('#exampleModal').modal('show');
            }else{
                $('#form').trigger("reset");
                $('#exampleModalLabel').html('Tambah Kategori');
                $('.btnsimpan').html(`<button type="button" class="btn btn-primary" id="btnSimpanTambah">Simpan</button>`);
                $('#exampleModal').modal('show');
            }
        });

        $(document).on("click", '#btnSimpanTambah', function() {
            loading('#btnSimpanTambah',true,'Simpan');
            document.getElementById("btnSimpanTambah").disabled = true;
            form = $('#form');

            postData(url, token, form).done(function(response, responseText, xhr) {
                if (xhr.status === 201) {
                    var errVal = response.data.message;
                    $.each(errVal, function(i, val) {
                        $('label[for="' + i + '"]').addClass('text-danger');
                        let input = document.getElementById(i)
                        let messageInput = document.getElementById(i + "Help");
                        messageInput.style.display = "block";
                        messageInput.innerHTML = val;
                        input.classList.add('is-invalid');
                        input.classList.add('text-danger');
                    });
                    loading('#btnSimpanTambah',false,'Simpan');
                    document.getElementById("btnSimpanTambah").disabled = false;
                } else {
                    $('#exampleModal').modal('hide');
                    successAlert(response.message);
                    $('#exampleModal').modal('toggle');
                    start();
                    loading('#btnSimpanTambah',false,'Simpan');
                    document.getElementById("btnSimpanTambah").disabled = false;
                }
                // successAlert(data.message);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                var err = JSON.parse(jqXHR.responseText);
                errorAlert(err.message);
                loading('#btnSimpanTambah',false,'Simpan');
                document.getElementById("btnSimpanTambah").disabled = false;
                // window.location = "{{ url('data-proyek') }}";
                // document.getElementById("btnSimpan").disabled = false;
            });
        });

        $(document).on("click", '#btnSimpanUpdate', function() {
            loading('#btnSimpanUpdate',true,'Simpan');
            document.getElementById("btnSimpanUpdate").disabled = true;
            form = $('#form');

            updateData(url+'/'+id, token, form).done(function(response, responseText, xhr) {
                if (xhr.status === 201) {
                    var errVal = response.data.message;
                
                    $.each(errVal, function(i, val) {
                        $('label[for="' + i + '"]').addClass('text-danger');
                        let input = document.getElementById(i)
                        let messageInput = document.getElementById(i + "Help");
                        messageInput.style.display = "block";
                        messageInput.innerHTML = val;
                        input.classList.add('is-invalid');
                        input.classList.add('text-danger');
                    });
                    loading('#btnSimpanUpdate',false,'Simpan');
                    document.getElementById("btnSimpanUpdate").disabled = false;
                } else {
                    successAlert(response.message);
                    $('#exampleModal').modal('toggle');
                    start();
                    loading('#btnSimpanUpdate',false,'Simpan');
                    document.getElementById("btnSimpanUpdate").disabled = false;
                }
                // successAlert(data.message);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                var err = JSON.parse(jqXHR.responseText);
                errorAlert(err.message);
                $('#exampleModal').modal('toggle');
                loading('#btnSimpanUpdate',false,'Simpan');
                document.getElementById("btnSimpanUpdate").disabled = false;
                // window.location = "{{ url('data-proyek') }}";
                // document.getElementById("btnSimpanUpdate").disabled = false;
            });
        });

        $(document).on("click", '#btnHapus', function() {
            id = $(this).data('id');
            Swal.fire({
                title: "Apakah kamu yakin ?",
                text: "Data akan terhapus di database",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, hapus !",
                cancelButtonText: "Tidak, batalkan !"
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteData(url + '/' + id, token).done(function(data) {
                        start();
                        successAlert(data.message);
                    }).fail(function(e) {
                        var data = e.responseJSON;
                        errorAlert(data.message);
                    });
                } else if (result.isDismissed) {
                    Swal.fire("Dibatalkan", "Data batal dihapus", "error");
                }
            });


        });
    </script>
@endsection

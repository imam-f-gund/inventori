@extends('template.adminlte.layouts.app')

@section('content')
    <div class="container">
        

        <div class="row">
            <div class="col-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <div class="col">
                            <h2 class="main-title my-auto"  id="title_product">Monitoring Kas</h2>
                        </div>
                    </div>
                    <div class="col">
                            <label for="" class="mt-3">Tanggal</label>
                            <div class="form-group">
                                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="start date" />
                            </div>
                    
                            <div class="form-group input-group input-group-md">
                                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="end date" />
                                <span class="input-group-append">
                                    <button type="button" id="date" class="btn btn-primary float-right">
                                        Filter
                                    </button>
                                </span>
                            </div>
                            <button type="button" onclick="exportReportToExcel()" class="btn btn-secondary float-right">
                                Excel
                            </button>
                    
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-stripped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Tanggal</th>
                                        <th>Produk</th>
                                        <th>Total Qty Masuk</th>
                                        <th>Total Qty Keluar</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var parmas  = window.location.href.split('-').reverse()[0];
        var url = "{{ url('/api/transaksi') }}";
        var title;
      
        var date ='';
        $('#date').click(function(){
            var $start = $('#start_date').val();

            var $end = $('#end_date').val();
           
            
            if ($start != '' && $end != '') {
                date = '&start_date='+$start+'&end_date='+$end;
            }else{
                date = '';
            }
            start(date);

        });
    
        function exportReportToExcel() {
            let table = document.getElementsByTagName("table");
            // you can use document.getElementById('tableId') as well by providing id to the table tag
            TableToExcel.convert(table[0], {
                // html code may contain multiple tables so here we are refering to 1st table tag

                name: `export-kas.xlsx`, // fileName you could use any name
                sheet: {
                    name: 'Sheet 1' // sheetName
                },

            });
        }

        function start(date) {
            loadingTable('#data');
        
            getData(url+'?type='+parmas+date, token).done(function(response) {
                if (response.code == 201) {
                    notAuthorized();
                }
                isi = ``;

                if (response.data.stock.length > 0) {
                    nomer = 1;
                    type = '';
                    sumqtykeluar = 0;
                    sumqtymasuk = 0;
                    sumpricekeluar = 0;
                    sumpricemasuk = 0;
                    QtyMasuk = 0;
                    QtyKeluar = 0;
                    priceIn = 0;
                    priceOut = 0;
                    data='';
                    sum=0;
                    $.each(response.data.stock, function(i, val) {
                        
                        
                        if(val.type == 'in'){
                            priceIn = val.product.price;
                            sumqtymasuk += val.qty;
                            sumpricemasuk += val.product.price;
                            QtyMasuk=val.qty;
                            type = `<span class="badge text-bg-success">Masuk</span>`;
                        }else{
                            priceOut = val.product.selling_price;
                            sumqtykeluar += val.qty;
                            sumpricekeluar += val.product.selling_price;
                            QtyKeluar=val.qty;
                            type = `<span class="badge text-bg-danger">Keluar</span>`;
                        }
                        
                        isi += `
                        <tr>
                            <td>`+val.date+`</td>
                            <td>`+val.product.product_name+`</td>
                            <td>`+QtyMasuk+`</td>
                            <td>`+QtyKeluar+`</td>
                            <td>Rp.`+priceIn+`.000</td>
                            <td>Rp.`+priceOut+`.000</td>
                            <td></td>
                        </tr>
                        `;
                        nomer++;
                    });
                    
                }
                sum =sumpricekeluar-sumpricemasuk;
                if (response.data.stock.length > 0) {
                    data = `<tr>
                                <td><span class="badge text-bg-success">Total QTY</span></td>
                                <td></td>
                                <td><span class="badge text-bg-success">`+sumqtymasuk+`</span></td>
                                <td><span class="badge text-bg-success">`+sumqtykeluar+`</span></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><span class="badge text-bg-success">Total</span></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="badge text-bg-success">Rp.`+sumpricemasuk+`.000</span></td>
                                <td><span class="badge text-bg-success">Rp.`+sumpricekeluar+`.000</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><span class="badge text-bg-success">Keuntungan</span></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="badge text-bg-success">Rp.`+sum+`.000</span></td>
                            </tr>
                           `;
                }else{
                    data='';
                }
                $('#data').html(isi+data);
                
            });
        }

        start(date);
function notAuthorized() {
            $('#data').html(`
            <tr>
                <td colspan="8" class="text-center">Kamu tidak memiliki akses !</td>
            </tr>
            `);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Kamu tidak memiliki akses !',
                footer: '<a href="{{ url('/') }}">Kembali ke Home</a>'
            })
        }
    </script>
    @endsection

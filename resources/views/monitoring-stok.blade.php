@extends('template.adminlte.layouts.app')

@section('content')
    <div class="container">
        
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <div class="col">
                            <h2 class="main-title my-auto"  id="title_product">Monitoring Stok</h2>
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
                                        <th>Brand</th>
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

                name: `export-stock.xlsx`, // fileName you could use any name
                sheet: {
                    name: 'Sheet 1' // sheetName
                },

            });
        }
        
        function start(date) {
            loadingTable('#data');
        
            getData(url+'?type='+parmas+date, token).done(function(response) {
                isi = ``;

                if (response.data.stock.length > 0) {
                    nomer = 1;
                    type = '';
                    sumqtykeluar = 0;
                    sumqtymasuk = 0;
                    QtyMasuk = 0;
                    QtyKeluar = 0;
                    data='';
                    $.each(response.data.stock, function(i, val) {
                        
                        
                        if(val.type == 'in'){
                            sumqtymasuk += val.qty;
                            QtyMasuk=val.qty;
                            type = `<span class="badge text-bg-success">Masuk</span>`;
                        }else{
                            sumqtykeluar += val.qty;
                            QtyKeluar=val.qty;
                            type = `<span class="badge text-bg-danger">Keluar</span>`;
                        }

                        isi += `
                        <tr>
                            <td>`+val.date+`</td>
                            <td>`+val.product.product_name+`</td>
                          <td>`+QtyMasuk+`</td>
                            <td>`+QtyKeluar+`</td>
                            <td>`+val.product.brand+`</td>
                        </tr>
                        `;
                        nomer++;
                    });
                    
                }
                if (response.data.stock.length > 0) {
                    data = `<tr>
                                <td><span class="badge text-bg-success">Total</span></td>
                                <td></td>
                                <td><span class="badge text-bg-success">`+sumqtymasuk+`</span></td>
                                <td><span class="badge text-bg-success">`+sumqtykeluar+`</span></td>
                                <td></td>
                            </tr>`;
                }else{
                    data='';
                }
                $('#data').html(isi+data);
                
            });
        }

        start(date);

    </script>
    @endsection

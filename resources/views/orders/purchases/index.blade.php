@extends('layouts.app', ['title' => 'Data Pembelian'])

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">{{ $title ?? '' }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"></li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid mb-3 d-flex justify-content-end">
    <div class="row">
        <div class="col-12">
            {{-- <a class="btn btn-sm btn-success" data-toggle="modal" data-target="#importExcel">Impor <i
                class="fa fa-file-import"></i></a>
            <a href="{{ route('members.export') }}" class="btn btn-sm btn-success">Ekspor <i class="fa fa-file-export"></i></a>
            <a href="{{ route('members.printpdf') }}" class="btn btn-sm btn-danger">Print PDF <i class="fa fa-file-pdf"></i></a> --}}
            <a class="btn btn-sm bg-navy" href="{{ route('purchases.create') }}">Tambah <i class="fa fa-plus"></i></a>
            <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Hapus Semua</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    {{-- @include('components.alerts') --}}
    <div class="card">
        <div class="card-header bg-navy">
            <h3 class="card-title">Data Pembelian</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <table id="data-table" class="table table-sm table-bordered table-striped">
                <thead class="bg-navy">
                    <tr>
                        <th style="width: 1%">No.</th>
                        <th>Kode</th>
                        <th>Status</th>
                        {{-- <th>Pemasok</th> --}}
                        <th class="text-center" style="width: 5%"><i class="fas fa-cogs"></i> </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<!-- MODAL SHOW BOOK -->
{{-- <div class="modal fade show" id="modalProduct" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Buku</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><img src="" id="imageMember" alt="default.jpg" class="img-fluid" width="50%"></li>
                    <li class="list-group-item">Nama : <i id="nameMember"></i></li>
                    <li class="list-group-item">Jenis Kelamin : <i id="genderMember"></i></li>
                    <li class="list-group-item">Email : <i id="emailMember"></i></li>
                    <li class="list-group-item">No HP : <i id="category"></i></li>
                    <li class="list-group-item">Alamat : <i id="addressMember"></i></li>
                    <li class="list-group-item">Status : <i id="statusMember"></i></li>
                    <li class="list-group-item">Jumlah Pinjaman : <i id="totalLoan"></i></li>
                    <li class="list-group-item">Jumlah Denda : <i id=""></i></li>
                </ul>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div> --}}

@endsection

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/toastr/toastr.min.css">
@endpush
@push('scripts')

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/toastr/toastr.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function () {
            let table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('purchases.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'dt-body-center'},
                    {data: 'order_number', name: 'order_number'},
                    {data: 'status', name: 'status'},
                    // {data: 'supplier_id', name: 'supplier.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'dt-body-center'},
                ],
            }).on('draw', function(){
                $('input[name="checkbox"]').each(function(){this.checked = false;});
                $('input[name="main_checkbox"]').prop('checked', false);
                $('button#deleteAllBtn').addClass('d-none');
            });

            $('#createNewItem').click(function () {
                setTimeout(function () {
                    $('#name').focus();
                }, 500);
                $('#saveBtn').removeAttr('disabled');
                $('#saveBtn').html("Simpan");
                $('#item_id').val('');
                $('#itemForm').trigger("reset");
                $('#modal-title').html("Tambah Product");
                $('#modal-md').modal('show');
            });

            // $('body').on('click', '#showProduct', function() {
            //     var product_id = $(this).data('id');
            //     $.get("{{ route('products.index') }}" + '/' + product_id, function(data) {
            //         $('#modalProduct').modal('show');
            //         $('#product_id').val(data.id);
            //         // $('#imageProduct').attr('src', '/storage/' + data.image);
            //         $('#name').html(data.name);
            //         $('#price').html(data.gender);
            //         $('#quantity').html(data.quantity);
            //         $('#category').html(data.phone_number);
            //     })
            // });

            $('body').on('click', '#editProduct', function () {
                var product_id = $(this).data('id');
                $.get("{{ route('products.index') }}" +'/' + product_id +'/edit', function (data) {
                    $('#modal-md').modal('show');
                    setTimeout(function () {
                        $('#name').focus();
                    }, 500);
                    $('#modal-title').html("Edit Product");
                    $('#saveBtn').removeAttr('disabled');
                    $('#saveBtn').html("Simpan");
                    $('#product_id').val(data.id);
                    $('#name').val(data.name);
                    $('#price').val(data.price);
                    $('#quantity').val(data.quantity);
                    $('#category_id').val(data.category_id);
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                var formData = new FormData($('#itemForm')[0]);
                $.ajax({
                    data: formData,
                    url: "{{ route('products.store') }}",
                    contentType : false,
                    processData : false,
                    type: "POST",
                    success: function (data) {
                        $('#saveBtn').attr('disabled', 'disabled');
                        $('#saveBtn').html('Simpan ...');
                        $('#itemForm').trigger("reset");
                        $('#modal-md').modal('hide');
                        toastr.success('Data berhasil disimpan  ');
                        table.draw();
                    },
                    error: function (data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Coba kembali isi data dengan benar!',
                        });
                    }
                });
            });

            $(document).on('click','input[name="main_checkbox"]', function(){
                if (this.checked) {
                    $('input[name="checkbox"]').each(function(){
                        this.checked = true;
                    });
                } else {
                    $('input[name="checkbox"]').each(function(){
                        this.checked = false;
                    });
                }
                toggledeleteAllBtn();
            });

            $(document).on('change','input[name="checkbox"]', function(){
                if ($('input[name="checkbox"]').length == $('input[name="checkbox"]:checked').length ){
                   $('input[name="main_checkbox"]').prop('checked', true);
                } else {
                    $('input[name="main_checkbox"]').prop('checked', false);
                }
                toggledeleteAllBtn();
            });

            function toggledeleteAllBtn() {
                if ($('input[name="checkbox"]:checked').length > 0 ){
                   $('button#deleteAllBtn').text('Hapus ('+$('input[name="checkbox"]:checked').length+')').removeClass('d-none');
                } else {
                   $('button#deleteAllBtn').addClass('d-none');
                }
            }

            $(document).on('click','button#deleteAllBtn', function(){
                var checkedItem = [];
                $('input[name="checkbox"]:checked').each(function(){
                   checkedItem.push($(this).data('id'));
                });
                var url = '{{ route("products.deleteSelected") }}';
                if(checkedItem.length > 0){
                    swal.fire({
                        title:'Apakah yakin?',
                        html:'Ingin menghapus <b>('+checkedItem.length+')</b> anggota?',
                        showCancelButton:true,
                        showCloseButton:true,
                        confirmButtonText:'Ya Hapus',
                        cancelButtonText:'Tidak',
                        confirmButtonColor:'#556ee6',
                        cancelButtonColor:'#d33',
                        width:300,
                        allowOutsideClick:false
                    }).then(function(result){
                        if(result.value){
                            $.post(url,{id:checkedItem},function(data){
                                if(data.code == 1){
                                    table.draw();
                                    toastr.success(data.msg);
                                }
                            },'json');
                        }
                    })
                }
            });

        });
    </script>

@endpush

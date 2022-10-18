@extends('layouts.app', compact('title'))

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
            <button class="btn btn-sm btn-primary" id="createNewItem">Tambah <i class="fa fa-plus"></i></button>
            <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Hapus Semua</button>
        </div>
    </div>
</div>

<div class="container">
    {{-- @include('components.alerts') --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Data Produk</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <table id="data-table" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 1%">No.</th>
                        <th class="text-center"><input type="checkbox" name="main_checkbox"><label></label></th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Kuantitas</th>
                        <th>Kategori</th>
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

<!-- MODAL -->
<div class="modal fade" id="modal-md">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="itemForm" name="itemForm">
                @csrf
                <input type="hidden" name="product_id" id="product_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control form-control-sm mr-2" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control form-control-sm mr-2">
                            <option selected disabled>Pilih Kategori</option>
                            @foreach ($categories as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" class="form-control form-control-sm mr-2" name="quantity" id="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control form-control-sm mr-2" name="price" id="price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" id="saveBtn" value="create">Save</button>
                </div>
            </form>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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
    <script src="{{ asset('assets') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
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
            bsCustomFileInput.init();
            let table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('products.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'dt-body-center'},
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'dt-body-center'},
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'category_id', name: 'category.name'},
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
                    $('#gender').val(data.gender);
                    $('#email').val(data.email);
                    $('#phone_number').val(data.phone_number);
                    $('#address').val(data.address);
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
                        table.draw();
                    },
                    error: function (data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data masih kosong!',
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


@extends('layouts.app', ['title' => 'Data Transaksi'])

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title ?? 'Tambah Penjualan' }}</h1>
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
                <a href="{{ route('sales.index') }}" class="btn btn-sm bg-navy">Kembali <i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-navy">
                <h3 class="card-title">Tambah Penjualan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <form action="{{ route('sales.store') }}" id="formMultipleAdd" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id">Pelanggan <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-control form-control-sm select2">
                                    <option selected disabled>Pilih Pelanggan</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_id">Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="btnAddInput" class="form-control form-control-sm select2">
                                    <option selected disabled>Pilih Produk</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-id="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" name="date">
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control-sm form-control select2">
                                    <option selected disabled>Pilih Tipe Pembayaran</option>
                                    <option value="pending">Debit</option>
                                    <option value="paid">Cash</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <table class="table table-sm table-bordered">
                        <thead class="bg-navy">
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok Tersisa</th>
                                <th>Kuantiti</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody class="formAdd">

                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <p class="lead">
                                Kode Invoice : {{ $orderNumber }}
                            </p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%">Total : </th>
                                            <td><input type="text" class="form-control form-control-sm" id="total_banget" disabled></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row no-print">
                        <div class="col-12">
                            {{-- <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a> --}}
                            <button type="submit" class="btn btn-sm bg-navy float-right btnSaveAll"><i class="far fa-credit-card"></i>
                                Submit
                            </button>
                            <button type="reset" class="btn btn-sm btn-warning float-right" style="margin-right: 5px;">
                                <i class="fas fa-sync-alt"></i> Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
@endpush
@push('scripts')
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $('.select2').select2();

            $('#formMultipleAdd').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.btnSaveAll').attr('disabled', 'disabled');
                        $('.btnSaveAll').html('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.btnSaveAll').removeAttr('disabled');
                        $('.btnSaveAll').html('Submit');
                    },
                    success: function(response) {
                        if (response.success) {
                            swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                html: `${response.success}`,
                            }).then((result) => {
                                if (result.value) {
                                    window.location.href = (
                                        "{{ route('sales.index') }}")
                                }
                            })
                        } else {
                            if (response.error) {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    html: `${response.error}`,
                                });
                            }
                        }
                    },
                    error: function(data) {
                        $('.btnSaveAll').removeAttr('disabled');
                        $('.btnSaveAll').html('Simpan');
                        swal.fire({
                            icon: 'error',
                            title: 'Gagal disimpan',
                            html: `${error.statusText}`,
                        });
                    }
                });
                return false;
            });

            $('#btnAddInput').change(function(e) {
                e.preventDefault();
                var product_id = $(this).children(":selected").attr("data-id");
                $.get("{{ route('products.index') }}" + '/' + product_id, function(data) {
                    $('.formAdd').append(`
                    <tr>
                        <td>
                            <input type="hidden" name="product[]" value=${data.id}>
                            <input type="text" class="form-control form-control-sm" value="${data.name}" disabled>
                        </td>
                        <td>
                            <input type="text" name="price[]" class="form-control form-control-sm" value=${data.selling_price} readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" value=${data.quantity} disabled>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" id="quantity" class="form-control form-control-sm">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger btnDeleteForm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    `);
                })
            });

            $('input[name="quantity"]').on("input", function() {
                let price = $('input[name="price"]').val();
                $('#total_banget').val(this.value * price);
                console.log(this.value);
            });

            $(document).on('click', '.btnDeleteForm', function(e) {
                e.preventDefault();
                $(this).parents('tr').remove();
            });
        });

    </script>
@endpush

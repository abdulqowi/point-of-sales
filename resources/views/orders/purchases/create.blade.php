@extends('layouts.app', ['title' => 'Data Transaksi'])

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
                <a href="{{ route('purchases.index') }}" class="btn btn-sm bg-navy">Kembali <i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        {{-- @include('components.alerts') --}}
        <div class="card">
            <div class="card-header bg-navy">
                <h3 class="card-title">Tambah Pembelian</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <form action="{{ route('purchases.store') }}" id="formMultipleAdd" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Pemasok</label>
                                <select name="supplier_id" class="form-control form-control-sm select2">
                                    <option selected disabled>Pilih Pemasok</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_id">Product</label>
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
                                <label for="date">Tanggal</label>
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
                                <th>Kuantiti</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody class="formAdd">

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <p class="lead">
                                <?php
                                $expNum = explode('-', $order_number);
                                $nextInvoiceNumber = $expNum[0] . '-' . $expNum[1] . '-' . ($expNum[2] + '1');
                                echo $nextInvoiceNumber;
                                ?>
                            </p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td id="subtotal"></td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td id="total"></td>
                                        </tr>
                                        <tr>
                                            <th>Bayar</th>
                                            <td>
                                                <input type="text" id="rupiah" class="form-control form-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Kembalian</th>
                                            <td>
                                                <input type="number" id="charge" disabled class="form-control form-control-sm">
                                            </td>
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
                                        "{{ route('purchases.index') }}")
                                }
                            })
                        }
                    },
                    error: function(response) {
                        $('.btnSaveAll').removeAttr('disabled');
                        $('.btnSaveAll').html('Simpan');
                        // alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        if (response.success) {
                            swal.fire({
                                icon: 'Gsgal',
                                title: 'Berhasil',
                                html: `${response.success}`,
                            });
                        }
                        // console.log (xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
                return false;
            });

            $('#btnAddInput').change(function(e) {
                e.preventDefault();
                var product_id = $(this).children(":selected").attr("data-id");
                $.get("{{ route('products.index') }}" + '/' + product_id, function(data) {
                    console.log(data.name);
                    $('.formAdd').append(`
                    <tr>
                        <td>
                            <input type="hidden" name="product[]" value=${data.id}>
                            <input type="text" class="form-control form-control-sm" disabled value=${data.name}>
                        </td>
                        <td>
                            <input type="number" name="quantity[]" id="quantity" class="form-control form-control-sm">
                        </td>
                        <td>
                            <input type="text" name="price[]" class="form-control form-control-sm" value=${data.price}>
                        </td>
                        <td>
                            <input type="number" name="total_price[]" class="form-control form-control-sm" disabled>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger btnDeleteForm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    `);
                })
            });

            // $('input[name="quantity"]').on("input", function() {
            //     let price = $('input[name="price"]').val();
            //     $('input[name="total_price"]').val(this.value * price);
            // });

            $(document).on('click', '.btnDeleteForm', function(e) {
                e.preventDefault();
                $(this).parents('tr').remove();
            });

            var rupiah = document.getElementById("price");
            rupiah.addEventListener("keyup", function(e) {
            // tambahkan 'Rp.' pada saat form di ketik
            // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
            rupiah.value = formatRupiah(this.value, "Rp. ");
            });

            function formatRupiah(angka, prefix){
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split   		= number_string.split(','),
                sisa     		= split[0].length % 3,
                rupiah     		= split[0].substr(0, sisa),
                ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
        });

    </script>
@endpush

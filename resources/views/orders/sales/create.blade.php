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
                <a href="{{ route('sales.index') }}" class="btn btn-sm bg-navy">Kembali <i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        {{-- @include('components.alerts') --}}
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
                                <label for="customer_id">Pelanggan</label>
                                <select name="customer_id" class="form-control form-control-sm select2">
                                    <option selected disabled>Pilih Pelanggan</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Tanggal</label>
                                <input type="date" class="form-control form-control-sm" name="date">
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
                            <tr>
                                <td>
                                    <select name="product[]" class="form-control form-control-sm">
                                        <option selected disabled>Pilih Produk</option>
                                        <?php foreach($products as $product) : ?>
                                        <option value="<?= $product->id ?>"><?= $product->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="quantity]" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="number" name="price[]" class="form-control form-control-sm">
                                </td>
                                <td>
                                    <input type="number" name="total_price[]" class="form-control form-control-sm"
                                        disabled>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm bg-navy btnAddForm"><i
                                            class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">
                            <p class="lead">Payment Methods:</p>

                            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango
                                imeem
                                plugg
                                dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                            </p>
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <p class="lead">INV-1012-10001</p>
                            <p class="lead">Amount Due 2/22/2014</p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>$250.30</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>$265.24</td>
                                        </tr>
                                        <tr>
                                            <th>Bayar</th>
                                            <td>
                                                <input type="number" id="cash" class="form-control form-control-sm">
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
@endpush
@push('scripts')
    <script>
        $(document).ready(function(e) {

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
                        $('.btnSaveAll').html('Simpan');
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
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
                return false;
            });

            $('.btnAddForm').click(function(e) {
                e.preventDefault();

                $('.formAdd').append(`
                    <tr>
                        <td>
                            <select name="product[]" class="form-control form-control-sm">
                                <option selected disabled>Pilih Produk</option>
                                <?php foreach($products as $product) : ?>
                                <option value="<?= $product->id ?>"><?= $product->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="quantity]" class="form-control form-control-sm">
                        </td>
                        <td>
                            <input type="number" name="price[]" class="form-control form-control-sm">
                        </td>
                        <td>
                            <input type="number" name="total_price[]" class="form-control form-control-sm" disabled>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger btnDeleteForm"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                `);
            });
        });

        $(document).on('click', '.btnDeleteForm', function(e) {
            e.preventDefault();
            $(this).parents('tr').remove();
        });
    </script>
@endpush

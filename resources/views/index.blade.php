<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>INVOICE</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <div class="container">
        <div class="card" style="margin-top:5px;">
            <div class="card-body">


                <h2>INVOICE</a></h2>

                <h3>DATA INVOICE</h3>

                <p>Cari Data Invoice :</p>
                <a href="/invoice/tambah" class="btn btn-primary"> + Tambah Invoice</a>
                <div class="form-group">

                </div>
                <form action="/invoice/cari" method="GET" class="form-inline">
                    <input class="form-control" type="text" name="cari" placeholder="Cari Invoice .."
                        value="{{ old('cari') }}">
                    <input class="btn btn-success ml-3" type="submit" value="CARI">
                </form>

                <br />
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>INVOICE ID</th>
                            <th>ISSUE DATE</th>
                            <th>DUE DATE</th>
                            <th>SUBJECT</th>
                            <th>FROM</th>
                            <th>FOR</th>
                            <th>SUBTOTAL</th>
                            <th>TAX (10%)</th>
                            <th>PAYMENT</th>
                            <th>AMOUNT DUE</th>
                            <th>OPTION</th>
                        </tr>
                        @foreach ($TBL_INVOICE as $p)
                            <tr>
                                <td>{{ $p->INVOICE_ID }}</td>
                                <td>{{ $p->ISSUE_DATE }}</td>
                                <td>{{ $p->DUE_DATE }}</td>
                                <td>{{ $p->SUBJECT }}</td>
                                <td>{{ $p->POPULATE_FROM }}</td>
                                <td>{{ $p->POPULATE_FOR }}</td>
                                <td>{{ $p->SUBTOTAL }}</td>
                                <td>{{ $p->TAX_10 }}</td>
                                <td>{{ $p->PAYMENT }}</td>
                                <td>{{ $p->AMOUNT_DUE }}</td>
                                <td>
                                    <a href="/invoice/edit/{{ $p->ID }}" class="btn btn-warning">Edit</a>
                                    <a href="/invoice/hapus/{{ $p->ID }}" class="btn btn-danger"
                                        style="margin-top:5px;" onclick="return confirm('Are you sure delete?')">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <br />
                <b>
                Halaman : {{ $TBL_INVOICE->currentPage() }} <br />
                Jumlah Data : {{ $TBL_INVOICE->total() }} <br />
                Data Per Halaman : {{ $TBL_INVOICE->perPage() }} <br />
                </b>
                <br />
                <div class="d-flex">
                    {{ $TBL_INVOICE->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
</body>

</html>

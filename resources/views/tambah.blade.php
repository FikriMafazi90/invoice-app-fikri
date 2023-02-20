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

                <h3>TAMBAH INVOICE</h3>
                <form action="/invoice/store" method="post">
                    {{ csrf_field() }}

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="INVOICE_ID">INVOICE ID</label>
                            <input type="text" class="form-control" id="INVOICE_ID" placeholder="INVOICE ID"
                                name="INVOICE_ID" required="required" value="{{ $ID_INVOICE }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ID_MST_POPULATE_FROM">FROM</label>
                            <select class="form-control" id="ID_MST_POPULATE_FROM" name="ID_MST_POPULATE_FROM">
                                @foreach ($TBL_MST_POPULATE_FROM as $from)
                                    <option value="{{ $from->ID }}">{{ $from->NAME }} | {{ $from->DESCRIPTION }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="ISSUE_DATE">ISSUE DATE</label>
                            <input class="form-control" id="ISSUE_DATE" placeholder="ISSUE DATE" type="date"
                                name="ISSUE_DATE" required="required">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="ID_MST_POPULATE_FOR">FOR</label>
                            <select class="form-control" id="ID_MST_POPULATE_FOR" name="ID_MST_POPULATE_FOR">
                                @foreach ($TBL_MST_POPULATE_FOR as $from)
                                    <option value="{{ $from->ID }}">{{ $from->NAME }} | {{ $from->DESCRIPTION }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="DUE_DATE">DUE DATE</label>
                            <input class="form-control" id="DUE_DATE" placeholder="DUE DATE" type="date"
                                name="DUE_DATE" required="required">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="SUBJECT">SUBJECT</label>
                            <textarea class="form-control" id="SUBJECT" rows="3" required="required" name="SUBJECT"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-warning add_field_button" style="margin-top:5px;margin-bottom:5px;">+
                        Add Item</button>
                    <div class="form-row input_fields_wrap">
                        <div style="margin-top:5px;margin-bottom:5px;width:100%;margin-left:5px;">
                            <select id="idForm" name="idForm[]" style="width:50%;padding:0.2rem;">
                                @foreach ($ITEM as $r)
                                    <option value="{{ $r->ID }}">{{ $r->NAME }} | {{ $r->DESCRIPTION }} |
                                        {{ $r->PRICE }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="qtyForm[]" id="qtyForm" placeholder="QTY" required="required"
                                style="width:20%;" />
                            <a href="#" class="remove_field btn btn-sm btn-danger" style="margin-bottom:5px;"> X
                                Remove </a>
                        </div>
                    </div>
                    <div class="col-md-12 bg-light text-right" style="margin-top:10px;">
                        <button type="reset" class="btn btn-warning">Cancel</button>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var max_fields = 100; //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button).click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append(
                        '<div style="margin-top:5px;margin-bottom:5px;width:100%;margin-left:5px;">' +
                        '<select id="idForm" name="idForm[]" style="width:50%;padding:0.2rem;">' +
                        @foreach ($ITEM as $r)
                            '<option value="{{ $r->ID }}">{{ $r->NAME }} | {{ $r->DESCRIPTION }} | {{ $r->PRICE }}' +
                            '</option>' +
                        @endforeach
                        '</select> ' +
                        '<input type="number" name="qtyForm[]" id="qtyForm" placeholder="QTY" required="required" style="width:20%;" /> ' +
                        '<a href="#" class="remove_field btn btn-sm btn-danger" style="margin-bottom:5px;"> X Remove </a>' +
                        '</div>'
                    ); //add input box
                }
            });

            $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
        });
    </script>
</body>

</html>

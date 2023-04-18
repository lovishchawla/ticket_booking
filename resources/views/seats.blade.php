<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="https://img.icons8.com/ios-filled/256/tasklist.png">
    <title>Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        .row {
            display: initial;
        }
    </style>
</head>
<body>
<main>
    <div class="container justify-content-center">
        @if(Session::has('success'))<h5 style="color: green">{{Session::get('success')}}</h5>@endif
        @if($errors->any())<h5 style="color: red">{{$errors->first()}}</h5>@endif
        <div class="row">
            Seats
        </div>
        <br>
            @php $index = 0; @endphp
            @for($i=0; $i<sizeof($rows); $i++)
                @for($j=0; $j<sizeof($columns); $j++)
                    @php $seat = $seats[$index]; @endphp
                    @if($seat->booking_status == 1)
                        <button style="height:50px;width:50px" type="button" class="btn btn-outline-secondary" disabled>{{$seat->name}}</button>
                    @else
                        <button style="height:50px;width:50px" type="button" class="btn btn-outline-danger" id="{{$seat->name}}" seatId="{{$seat->id}}" onclick="SelectSeat.call(this)">{{$seat->name}}</button>
                    @endif
                    @php $index++; @endphp
                @endfor
                <br>
            @endfor
        <br>
        <form method="POST" action="{{route('seats.book')}}">
            @csrf
            <div class="row">
                <div class="col-3 form-group">
                    <label class="form-label" for="seatCount">Total seats</label>
                    <input type="number" class="form-control" id="seatCount" name="seatCount" min="1" max="{{ env('MAX_SEAT', 5) }}" required placeholder="Enter number of seats">
                </div>
                <br>
                <input type="hidden" id="seatId" name="seatId">
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">Book</button>
                </div>
            </div>
        </form>
    </div>
</main>
</body>
<footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        let seat, seatId
        function SelectSeat() {
            $(`#${seat}`).toggleClass('btn-outline-danger btn-success')
            $(this).toggleClass('btn-outline-danger btn-success')
            seat = $(this).attr('id')
            seatId = $(this).attr('seatId')
            $('#seatId').val(seatId)
        }
    </script>
</footer>
</html>

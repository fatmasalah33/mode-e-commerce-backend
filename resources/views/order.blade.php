

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Hi {{ $name }},
    <br>
   here your new orders , please prepare this orders then contact us to come to take it .

  @foreach ( $details as  $detail )

      this quantity  {{$detail->quantity}} from product with id {{$detail->product_id}} with size {{$detail->size_id}} .
  @endforeach


</div>

</body>
</html>


{
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDER #{{ $data['order']->order_code }}</title>
</head>

<body>
    <p><img src="assets/img/logo_clean.png" alt="S MODE" width="100"></p>
    <br>
    <div style="font-size: 80%">
        <p>
            <b>ORDER <span style="color: #666;">#{{ $data['order']->order_code }}</span></b><br>
            <br>
            Season: {{ $data['order']->season_name }}<br>
            Customer name: {{ $data['retailer']->retailer_fullName }}<br>

</body>

</html>
}

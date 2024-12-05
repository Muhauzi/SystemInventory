<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Reminder</title>
</head>
<body>
<h1>Reminder: Upcoming Return Due</h1>
    <p>Dear {{ $user->name }},</p>
    @if (!$data)
        <p>There is no data available.</p>
    @endif
    <p>This is a reminder that your loaned item is due to be returned on <strong>{{ $data->tgl_tenggat }}</strong>.</p>
    <p>Item Details:</p>
    <ul>
        @foreach ($barang as $item)
            <li>{{ $item->id_barang }} - {{ $item->nama_barang }}</li>
        @endforeach     
    </ul>
    @if (!$barang)
        <p>There is no item available.</p>
    @endif
    <p>Please return it on or before the due date.</p>
</body>
</html>
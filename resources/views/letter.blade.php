<!DOCTYPE html>
<html>

<head>
    <title>Notice</title>
    <style>
        /* Add custom PDF styling here */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Database Records</h1>
    <p style="overflow-x: scroll;"> </p>
    <img src="{{$image_path}}" alt="Description of Image" style="max-width: 100%; height: auto;">

    @foreach ($data as $item)
    <li>{{ $item->date }}</li> <!-- Adjust according to your data structure -->
    <li>{{ $item->To }}</li> <!-- Adjust according to your data structure -->
    <h1>{{ $item->subject }}</h1> <!-- Adjust according to your data structure -->
    <p>{{ $item->message}}</p>
    <h5>{{$item->carbon_copy_to}}</h5>
    @endforeach
    <table>
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$name}}</td>
                <td>Hagos{{ $image_path }}</td>
            </tr>
            <tr>
                <td>Berhe</td>
                <td>Hagos</td>
            </tr>
            <tr>
                <td>Berhe</td>
                <td>Hagos</td>
            </tr>
            <tr>
                <td>Berhe</td>
                <td>Hagos</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
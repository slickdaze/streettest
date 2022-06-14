<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Name Formatter</title>


    </head>
    <body>

        <h1>Name Formatter</h1>

        <p>People from CSV file:</p>

        <style>
            table {
            border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid;
            }
            th, td {
                padding: 3px 5px;
            }
        </style>

        <table>
            <thead>
                <th>Title</th>
                <th>First Name</th>
                <th>Initial</th>
                <th>Last Name</th>
            </thead>
            <tbody>
                @foreach ($people as $person)
                <tr>
                    <td>{{ $person['title'] }}</td>
                    <td>{{ $person['first_name'] }}</td>
                    <td>{{ $person['initial'] }}</td>
                    <td>{{ $person['last_name'] }}</td>
                </tr>
                @endforeach
            </tbody>


    </body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Export Data Table</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables library -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- Include DataTables Buttons extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

</head>
<body>
<table id="example">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>John Doe</td>
        <td>johndoe@example.com</td>
        <td>1234567890</td>
    </tr>
    <tr>
        <td>Jane Doe</td>
        <td>janedoe@example.com</td>
        <td>0987654321</td>
    </tr>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ]
        });
    } );
</script>
</body>
</html>




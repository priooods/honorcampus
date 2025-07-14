<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji - {{ $data->dosen->name }}</title>
</head>
<body>
    <h1 style="text-align: center; font-weight:bold; font-size: 1rem; margin-bottom: 1.5rem">Slip Honor Bimbingan & Seminar Proposal Skripsi</h1>
    <table style="
        width: 100%; 
        border-collapse: collapse; 
        font-family: Arial, sans-serif;
        border: 1px solid #333;
    ">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="
                    padding: 8px; 
                    font-size: 15px;
                    border: 1px solid #333;
                ">Mahasiswa</th>
                <th style="
                    padding: 8px; 
                    font-size: 15px;
                    border: 1px solid #333;
                ">Prodi</th>
                <th style="
                    padding: 8px;font-size: 15px; 
                    border: 1px solid #333;
                ">Keterangan</th>
                <th style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333;
                ">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333;
                ">{{ $data->mahasiswa->name }} / {{ $data->mahasiswa->nim }}</td>
                <td style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333;
                ">{{ $data->mahasiswa->prodi }}</td>
                <td style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333;
                ">{{ $data->type_request->title }} {{ $data->type_request_detail->title }}</td>
                <td style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333; 
                    text-align: right;
                ">Rp {{ $data->honor }}</td>
            </tr>
            <tr>
                <td colspan="3" style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333; 
                    text-align: right; 
                    font-weight: bold;
                ">Total</td>
                <td style="
                    padding: 8px; font-size: 15px;
                    border: 1px solid #333; 
                    text-align: right; 
                    font-weight: bold;
                ">Rp {{ $data->honor }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
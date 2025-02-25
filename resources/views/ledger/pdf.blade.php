<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ledger Report</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            /* padding: 0; */
            font-size: 18px;
            padding:0px 15px 0px 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 8px; /* Adjust font size for PDF */
        }
        th {
            background-color: #f2f2f2;
        }
        /* Page setup for PDF */
        @page {
            size: A4 portrait;
            /* margin: 5mm; */
            margin: 1mm;
        }

        /* Ensuring the content does not overflow */
        table {
            width: 100%;
            page-break-inside: avoid;
        }

        /* Styling for headers and page breaks */
        h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 8px;
        }

        p {
            font-size: 10px;
        }

        /* Ensuring long text is wrapped properly */
        td, th {
            word-wrap: break-word;
        }

        /* Optional: Add page numbers or other footer elements */
        footer {
            text-align: center;
            font-size: 8px;
            position: fixed;
            bottom: 10mm;
            width: 100%;
        }
          /* Optional: Column Width Adjustments */
            /* th:nth-child(1), td:nth-child(1) { width: 15%; }
            th:nth-child(2), td:nth-child(2) { width: 15%; }
            th:nth-child(3), td:nth-child(3) { width: 20%; }
            th:nth-child(4), td:nth-child(4) { width: 20%; }
            th:nth-child(5), td:nth-child(5) { width: 10%; }
            th:nth-child(6), td:nth-child(6) { width: 10%; }
            th:nth-child(7), td:nth-child(7) { width: 10%; } */
    </style>

</head>
<body>
  
    <p>
        @if($select_user_name != 'All')
        {{ucfirst($user_type) }} Name: {{  strtoupper($select_user_name ?? 'All') }} <br>
        @endif
        Date Range: {{ $from_date }} to {{ $to_date }}
    </p>
    @php
        $groupedData = $data->groupBy('user_type');
    @endphp
    @foreach($groupedData as $type => $entries)
    @if($select_user_name == 'All')
    <p>{{ strtoupper($type) }} LEDGER</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Update Date</th>
                <th>Date</th>
                <th>Transaction ID/ Voucher No</th>
                <th>Purpose</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Bank/Cash</th>
                <th>Entered Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $entry)
                <tr>
                    <!-- Assuming 'entry_date', 'description', 'transaction_id', 'purpose', 'debit', 'credit', 'bank_cash', 'entered_date', and 'amount' exist in your Ledger model -->
                    <td>{{ date('d/m/Y', strtotime($entry->entry_date)) }}</td>
                    <td>{{ date('d/m/Y', strtotime($entry->created_at)) }}</td>
                    <td>{{ $entry->transaction_id }}</td>
                    <td>{{ ucwords(str_replace('_', ' ',$entry->purpose)) }}</td>
                    <td>{{ number_format($entry->debit, 2) }}</td>
                    <td>{{ number_format($entry->credit, 2) }}</td>
                    <td>{{ $entry->bank_cash }}</td>
                    <td>{{ date('d/m/Y', strtotime($entry->entered_date)) }}</td>
                    <td>{{ number_format($entry->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
</body>
</html>
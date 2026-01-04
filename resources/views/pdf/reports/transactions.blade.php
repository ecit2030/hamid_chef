<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير المعاملات</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 11px;
            direction: rtl;
            padding: 15px;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #083064;
        }
        
        .header h1 {
            font-size: 20px;
            color: #083064;
            margin-bottom: 5px;
        }
        
        .header .date {
            font-size: 12px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #083064;
            color: white;
            padding: 10px 8px;
            text-align: right;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #083064;
        }
        
        td {
            padding: 8px;
            text-align: right;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-success {
            color: #059669;
            font-weight: bold;
        }
        
        .text-danger {
            color: #dc2626;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير المعاملات المالية</h1>
        <div class="date">{{ $date }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">التاريخ</th>
                <th style="width: 35%;">الوصف</th>
                <th style="width: 15%;">المبلغ</th>
                <th style="width: 10%;">النوع</th>
                <th style="width: 20%;">الطاهي</th>
                <th style="width: 5%;">#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $transaction->description ?? '-' }}</td>
                <td class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                    {{ number_format($transaction->amount, 2) }} ر.س
                </td>
                <td>
                    <span class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->type === 'credit' ? 'إيداع' : 'سحب' }}
                    </span>
                </td>
                <td>{{ $transaction->chef->user->first_name }} {{ $transaction->chef->user->last_name }}</td>
                <td>{{ $transaction->id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>نظام إدارة الطهاة - تم إنشاء التقرير في: {{ now()->format('Y-m-d H:i') }}</p>
        <p>إجمالي المعاملات: {{ $stats['total_transactions'] }} | الإيداعات: {{ number_format($stats['total_credits'], 2) }} ر.س | السحوبات: {{ number_format($stats['total_debits'], 2) }} ر.س</p>
    </div>
</body>
</html>

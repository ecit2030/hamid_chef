<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير العملاء</title>
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
            font-size: 22px;
            color: #083064;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header .date {
            font-size: 14px;
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
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير العملاء</h1>
        <div class="date">{{ $date }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>تاريخ التسجيل</th>
                <th>إجمالي الإنفاق</th>
                <th>الحجوزات</th>
                <th>الهاتف</th>
                <th>البريد الإلكتروني</th>
                <th>اسم العميل</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->created_at ? $customer->created_at->format('Y-m-d') : '-' }}</td>
                <td>{{ number_format($customer->bookings_sum_total_amount ?? 0, 2) }} ر.س</td>
                <td>{{ $customer->bookings_count ?? 0 }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                <td>{{ $customer->id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>نظام إدارة الطهاة - تم إنشاء التقرير في: {{ now()->format('Y-m-d H:i') }}</p>
        <p>إجمالي العملاء: {{ $stats['total_customers'] }} | إجمالي الحجوزات: {{ $stats['total_bookings'] }} | إجمالي الإيرادات: {{ number_format($stats['total_revenue'], 2) }} ر.س</p>
    </div>
</body>
</html>

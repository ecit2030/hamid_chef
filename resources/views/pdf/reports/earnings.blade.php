<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير الأرباح</title>
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
        
        .text-success {
            color: #059669;
            font-weight: bold;
        }
        
        .text-danger {
            color: #dc2626;
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
        <h1>تقرير الأرباح اليومية</h1>
        <div class="date">{{ $date }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>صافي الأرباح</th>
                <th>العمولة</th>
                <th>الإجمالي</th>
                <th>الساعات</th>
                <th>الحجوزات</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyEarnings as $day)
            <tr>
                <td class="text-success">{{ number_format($day->net, 2) }} ر.س</td>
                <td class="text-danger">{{ number_format($day->commission, 2) }} ر.س</td>
                <td>{{ number_format($day->total, 2) }} ر.س</td>
                <td>{{ $day->hours }}</td>
                <td>{{ $day->bookings_count }}</td>
                <td>{{ $day->date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>نظام إدارة الطهاة - تم إنشاء التقرير في: {{ now()->format('Y-m-d H:i') }}</p>
        <p>إجمالي الأرباح: {{ number_format($summary['total_earnings'], 2) }} ر.س | العمولة: {{ number_format($summary['total_commission'], 2) }} ر.س | صافي الأرباح: {{ number_format($summary['net_earnings'], 2) }} ر.س</p>
    </div>
</body>
</html>

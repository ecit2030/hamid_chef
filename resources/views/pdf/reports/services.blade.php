<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير الخدمات</title>
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
        
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-active { 
            background-color: #d1fae5; 
            color: #065f46;
        }
        .status-inactive { 
            background-color: #f3f4f6; 
            color: #6b7280;
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
        <h1>تقرير الخدمات</h1>
        <div class="date">{{ $date }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>الطاهي</th>
                <th>الأرباح</th>
                <th>الساعات</th>
                <th>الحجوزات</th>
                <th>السعر</th>
                <th>الحالة</th>
                <th>اسم الخدمة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->chef && $service->chef->user ? $service->chef->user->first_name . ' ' . $service->chef->user->last_name : '-' }}</td>
                <td>{{ number_format($service->total_earnings ?? 0, 2) }} ر.س</td>
                <td>{{ $service->total_hours ?? 0 }}</td>
                <td>{{ $service->total_bookings ?? 0 }}</td>
                <td>{{ number_format($service->price, 2) }} ر.س</td>
                <td>
                    <span class="status status-{{ $service->is_active ? 'active' : 'inactive' }}">
                        {{ $service->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </td>
                <td>{{ $service->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>نظام إدارة الطهاة - تم إنشاء التقرير في: {{ now()->format('Y-m-d H:i') }}</p>
        <p>إجمالي الخدمات: {{ $stats['total_services'] }} | إجمالي الحجوزات: {{ $stats['total_bookings'] }} | إجمالي الأرباح: {{ number_format($stats['total_earnings'], 2) }} ر.س</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير الحجوزات</title>
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
        
        .status-completed { 
            background-color: #d1fae5; 
            color: #065f46;
        }
        .status-pending { 
            background-color: #fef3c7; 
            color: #92400e;
        }
        .status-accepted { 
            background-color: #dbeafe; 
            color: #1e40af;
        }
        .status-rejected { 
            background-color: #fee2e2; 
            color: #991b1b;
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
        <h1>تقرير الحجوزات</h1>
        <div class="date">{{ $date_range ?? $date }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>الحالة</th>
                <th>المبلغ</th>
                <th>الضيوف</th>
                <th>الساعات</th>
                <th>التاريخ</th>
                <th>الخدمة</th>
                <th>الطاهي</th>
                <th>العميل</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>
                    <span class="status status-{{ $booking->booking_status }}">
                        @switch($booking->booking_status)
                            @case('pending') قيد الانتظار @break
                            @case('accepted') مقبول @break
                            @case('rejected') مرفوض @break
                            @case('completed') مكتمل @break
                            @case('cancelled_by_customer') ملغي @break
                            @case('cancelled_by_chef') ملغي @break
                            @case('cancelled_by_admin') ملغي @break
                            @default {{ $booking->booking_status }}
                        @endswitch
                    </span>
                </td>
                <td>{{ number_format($booking->total_amount, 2) }} ر.س</td>
                <td>{{ $booking->number_of_guests }}</td>
                <td>{{ $booking->hours_count }}</td>
                <td>{{ $booking->date }}</td>
                <td>{{ $booking->service?->name ?? '-' }}</td>
                <td>{{ $booking->chef && $booking->chef->user ? $booking->chef->user->first_name . ' ' . $booking->chef->user->last_name : '-' }}</td>
                <td>{{ $booking->customer ? $booking->customer->first_name . ' ' . $booking->customer->last_name : '-' }}</td>
                <td>{{ $booking->id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>نظام إدارة الطهاة - تم إنشاء التقرير في: {{ now()->format('Y-m-d H:i') }}</p>
        <p>إجمالي الحجوزات: {{ $stats['total'] }} | المكتملة: {{ $stats['completed'] }} | المبلغ الإجمالي: {{ number_format($stats['total_amount'], 2) }} ر.س</p>
    </div>
</body>
</html>

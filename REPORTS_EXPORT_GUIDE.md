# Reports Export Guide - Visual Overview

## What You'll See

### 1. Bookings Report Page
```
┌─────────────────────────────────────────────────────────────┐
│  تقرير الحجوزات                                             │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  [Period Filter ▼]  [Status Filter ▼]   [📥 Export Excel]  │
│                                          [📥 Export PDF]     │
│                                                              │
│  ┌──────────┬──────────┬──────────┬──────────┐             │
│  │ Total    │ Completed│ Earnings │ Hours    │             │
│  │ 150      │ 120      │ 45,000   │ 360      │             │
│  └──────────┴──────────┴──────────┴──────────┘             │
│                                                              │
│  [Chart: Bookings Overview]                                 │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ # │ Customer │ Service │ Date │ Hours │ Amount │ Status│
│  ├───┼──────────┼─────────┼──────┼───────┼────────┼───────┤
│  │ 1 │ Ahmed    │ BBQ     │ ...  │ 3     │ 450    │ ✓     │
│  │ 2 │ Sara     │ Dinner  │ ...  │ 4     │ 600    │ ⏳    │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### 2. Earnings Report Page
```
┌─────────────────────────────────────────────────────────────┐
│  تقرير الأرباح اليومية                                      │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  [Period Filter ▼]   [📥 Export Excel]  [📥 Export PDF]    │
│                                                              │
│  ┌──────┬──────┬──────┬──────┬──────┐                      │
│  │Total │ Net  │Comm. │Books │Hours │                      │
│  │45,000│40,500│4,500 │ 120  │ 360  │                      │
│  └──────┴──────┴──────┴──────┴──────┘                      │
│                                                              │
│  [Chart: Earnings Over Time]                                │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Date       │ Bookings │ Hours │ Total │ Comm │ Net   │  │
│  ├────────────┼──────────┼───────┼───────┼──────┼───────┤  │
│  │ 2026-01-03 │    5     │  15   │ 2,250 │ 225  │ 2,025 │  │
│  │ 2026-01-02 │    4     │  12   │ 1,800 │ 180  │ 1,620 │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### 3. Services Report Page
```
┌─────────────────────────────────────────────────────────────┐
│  تقرير أداء الخدمات                                         │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  [Period Filter ▼]   [📥 Export Excel]  [📥 Export PDF]    │
│                                                              │
│  ┌──────────┬──────────┬──────────┬──────────┐             │
│  │ Services │ Active   │ Bookings │ Earnings │             │
│  │    8     │    7     │   120    │  45,000  │             │
│  └──────────┴──────────┴──────────┴──────────┘             │
│                                                              │
│  [Chart: Earnings by Service]  [Chart: Bookings by Service] │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │Service│Status│Books│Completed│Conv%│Hours│Earn│Rating│  │
│  ├───────┼──────┼─────┼─────────┼─────┼─────┼────┼──────┤  │
│  │ BBQ   │ ✓    │  45 │   40    │ 89% │ 120 │15K │ 4.8  │  │
│  │Dinner │ ✓    │  38 │   35    │ 92% │ 140 │18K │ 4.9  │  │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

## Export Button Behavior

### Excel Export (Green Button)
- **Icon**: 📥 Download icon
- **Color**: Green (#10b981)
- **Format**: .xlsx file
- **Features**:
  - Opens in Microsoft Excel, Google Sheets, LibreOffice
  - Editable spreadsheet format
  - Formatted columns with proper widths
  - Bold headers
  - Arabic text fully supported

### PDF Export (Red Button)
- **Icon**: 📥 Download icon
- **Color**: Red (#ef4444)
- **Format**: .pdf file
- **Features**:
  - Professional print-ready document
  - Fixed layout with header and footer
  - Color-coded statistics
  - Landscape orientation for tables
  - Arabic text with Cairo font

## File Downloads

### Bookings Export
**Excel**: `report_bookings_2026-01-03.xlsx`
- Columns: ID, Customer, Email, Phone, Service, Date, Hours, Guests, Amount, Commission, Net, Status, Created

**PDF**: `report_bookings_2026-01-03.pdf`
- Header: "تقرير الحجوزات" + Chef Name + Date
- Stats Cards: Total, Completed, Earnings, Hours
- Full table with color-coded status badges

### Earnings Export
**Excel**: `report_earnings_2026-01-03.xlsx`
- Columns: Date, Bookings, Hours, Total, Commission, Net

**PDF**: `report_earnings_2026-01-03.pdf`
- Header: "تقرير الأرباح اليومية" + Chef Name + Date
- Stats Cards: Total, Net, Commission, Bookings, Hours
- Daily breakdown table

### Services Export
**Excel**: `report_services_2026-01-03.xlsx`
- Columns: Name, Status, Price, Total Bookings, Completed, Conversion%, Hours, Earnings, Rating

**PDF**: `report_services_2026-01-03.pdf`
- Header: "تقرير أداء الخدمات" + Chef Name + Date
- Stats Cards: Total Services, Active, Bookings, Earnings
- Performance table with color-coded conversion rates

## Filter Integration

### Bookings Report Filters:
- **Period**: Week, Month, Quarter, Year, All Time
- **Status**: All, Pending, Accepted, Completed, Rejected
- Both filters apply to exports

### Earnings Report Filters:
- **Period**: Week, Month, Quarter, Year, All Time
- Filter applies to exports

### Services Report Filters:
- **Period**: Week, Month, Quarter, Year, All Time
- Filter applies to exports

## Color Coding

### Status Colors (Bookings):
- **Completed**: Blue badge
- **Pending**: Yellow badge
- **Accepted**: Green badge
- **Rejected**: Red badge
- **Cancelled**: Gray badge

### Conversion Rate Colors (Services):
- **≥80%**: Green (Excellent)
- **≥50%**: Yellow (Good)
- **<50%**: Red (Needs Improvement)

### Financial Colors:
- **Earnings/Net**: Green
- **Commission**: Red
- **Total**: Blue

## Usage Tips

### For Daily Reports:
1. Select "This Week" or "This Month"
2. Export to Excel for analysis
3. Export to PDF for printing/sharing

### For Financial Records:
1. Select "This Month" or "This Quarter"
2. Export Earnings report to Excel
3. Use for accounting/bookkeeping

### For Performance Analysis:
1. Select "This Month" or "This Year"
2. Export Services report to Excel
3. Analyze conversion rates and earnings

### For Client Presentations:
1. Select appropriate period
2. Export to PDF for professional look
3. Print or email to stakeholders

## Technical Notes

### Excel Files:
- UTF-8 encoding with BOM
- Compatible with all major spreadsheet software
- Formulas can be added after export
- Data can be sorted and filtered

### PDF Files:
- A4 landscape orientation
- Print-ready quality
- Embedded fonts for Arabic
- Non-editable (secure)

### Performance:
- Large datasets (1000+ records) export quickly
- No timeout issues
- Streaming download (no memory issues)
- Background processing not needed

## Troubleshooting

### If Excel doesn't open:
- Ensure Microsoft Excel or compatible software installed
- Try opening with Google Sheets
- Check file isn't corrupted (re-download)

### If PDF doesn't display Arabic:
- Ensure PDF reader supports Arabic fonts
- Try Adobe Reader or modern browser
- Check internet connection (fonts loaded from Google)

### If export button doesn't work:
- Check browser console for errors
- Ensure logged in as chef
- Verify filters are valid
- Try refreshing page

## Browser Compatibility

### Tested Browsers:
- ✅ Chrome/Edge (Latest)
- ✅ Firefox (Latest)
- ✅ Safari (Latest)
- ✅ Mobile browsers

### Download Behavior:
- Desktop: Downloads to default folder
- Mobile: Opens download manager
- All: Filename includes timestamp

# Test Cancel Booking API
# Usage: .\test_cancel_simple.ps1

$API_URL = "http://localhost:8000/api"
$EMAIL = "test.user@example.com"
$PASSWORD = "password"
$BOOKING_ID = "1"

Write-Host "=========================================`n" -ForegroundColor Cyan

# Step 1: Login
Write-Host "Step 1: Login..." -ForegroundColor Yellow

$loginBody = @{
    email = $EMAIL
    password = $PASSWORD
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$API_URL/login" `
        -Method Post `
        -Headers @{
            "Content-Type" = "application/json"
            "Accept" = "application/json"
        } `
        -Body $loginBody

    Write-Host "Login Response:" -ForegroundColor Green
    $loginResponse | ConvertTo-Json -Depth 10
    Write-Host ""

    $TOKEN = $null
    if ($loginResponse.data.token) {
        $TOKEN = $loginResponse.data.token
    } elseif ($loginResponse.token) {
        $TOKEN = $loginResponse.token
    }

    if (-not $TOKEN) {
        Write-Host "ERROR: No token received" -ForegroundColor Red
        exit 1
    }

    Write-Host "SUCCESS: Logged in" -ForegroundColor Green
    Write-Host "Token: $($TOKEN.Substring(0, [Math]::Min(20, $TOKEN.Length)))...`n" -ForegroundColor Green

    # Step 2: Cancel Booking
    Write-Host "Step 2: Cancel Booking #${BOOKING_ID}..." -ForegroundColor Yellow

    $cancelBody = @{
        cancellation_reason = "I need to travel urgently and cannot attend the booking"
    } | ConvertTo-Json

    $cancelResponse = Invoke-RestMethod -Uri "$API_URL/bookings/$BOOKING_ID/cancel-by-customer" `
        -Method Post `
        -Headers @{
            "Content-Type" = "application/json"
            "Accept" = "application/json"
            "Authorization" = "Bearer $TOKEN"
        } `
        -Body $cancelBody

    Write-Host "Cancel Response:" -ForegroundColor Green
    $cancelResponse | ConvertTo-Json -Depth 10
    Write-Host ""

    if ($cancelResponse.success -eq $true) {
        Write-Host "SUCCESS: Booking cancelled!" -ForegroundColor Green
    } else {
        Write-Host "ERROR: Failed to cancel" -ForegroundColor Red
        if ($cancelResponse.message) {
            Write-Host "Message: $($cancelResponse.message)" -ForegroundColor Red
        }
    }

} catch {
    Write-Host "ERROR:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red

    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Details:" -ForegroundColor Red
        Write-Host $responseBody -ForegroundColor Red
    }
}

Write-Host "`n=========================================" -ForegroundColor Cyan

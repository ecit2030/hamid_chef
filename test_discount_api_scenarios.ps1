# Test Discount Code API - Multiple Scenarios

$token = "2|DYBtg2F3WhY5lOLudWOmdlsZhpN5SeY9ZWTGe8yV7b006715"
$baseUrl = "http://127.0.0.1:8000/api/discount-codes/validate"

$headers = @{
    "Authorization" = "Bearer $token"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

function Test-DiscountCode {
    param(
        [string]$TestName,
        [string]$Code,
        [decimal]$Amount,
        [bool]$ExpectSuccess = $true
    )

    Write-Host ""
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray
    Write-Host "🧪 Test: $TestName" -ForegroundColor Cyan
    Write-Host "   Code: $Code | Amount: $Amount" -ForegroundColor Gray

    $body = @{
        code = $Code
        amount = $Amount
    } | ConvertTo-Json

    try {
        $response = Invoke-RestMethod -Uri $baseUrl -Method Post -Headers $headers -Body $body
        if ($ExpectSuccess) {
            Write-Host "   ✅ PASS - Success as expected" -ForegroundColor Green
            Write-Host "   💰 Original: $($response.data.original_amount) | Discount: $($response.data.discount_amount) | Final: $($response.data.final_amount)" -ForegroundColor Yellow
        } else {
            Write-Host "   ❌ FAIL - Expected error but got success" -ForegroundColor Red
        }
    } catch {
        if (-not $ExpectSuccess) {
            $errorDetails = $_.ErrorDetails.Message | ConvertFrom-Json
            Write-Host "   ✅ PASS - Error as expected" -ForegroundColor Green
            Write-Host "   📋 Message: $($errorDetails.message)" -ForegroundColor Yellow
        } else {
            Write-Host "   ❌ FAIL - Expected success but got error" -ForegroundColor Red
            Write-Host "   📋 Error: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Write-Host ""
Write-Host "🚀 Starting Discount Code API Tests" -ForegroundColor Magenta
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray

# Test 1: Valid code with amount above minimum
Test-DiscountCode -TestName "Valid Code - Amount Above Minimum" -Code "TEST2024" -Amount 200 -ExpectSuccess $true

# Test 2: Valid code with amount at minimum
Test-DiscountCode -TestName "Valid Code - Amount At Minimum" -Code "TEST2024" -Amount 100 -ExpectSuccess $true

# Test 3: Invalid code - below minimum
Test-DiscountCode -TestName "Invalid - Amount Below Minimum" -Code "TEST2024" -Amount 50 -ExpectSuccess $false

# Test 4: Invalid code - non-existent
Test-DiscountCode -TestName "Invalid - Non-Existent Code" -Code "INVALID123" -Amount 200 -ExpectSuccess $false

# Test 5: Valid code with high amount (test max discount)
Test-DiscountCode -TestName "Valid Code - High Amount (Max Discount Test)" -Code "TEST2024" -Amount 500 -ExpectSuccess $true

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor DarkGray
Write-Host "✅ All tests completed!" -ForegroundColor Green
Write-Host ""

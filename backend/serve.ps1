$ErrorActionPreference = "Stop"

$phpExe = if (Test-Path "C:\xampp\php\php.exe") { "C:\xampp\php\php.exe" } else { "php" }
$sourceIni = "C:\xampp\php\php.ini"

if (Test-Path $sourceIni) {
    $tmpIniDir = Join-Path $env:TEMP "porto-php-ini"
    New-Item -ItemType Directory -Path $tmpIniDir -Force | Out-Null
    $iniContent = Get-Content $sourceIni -Raw
    $iniContent = $iniContent -replace '(?<=="|\\|=)\\xampp\\', 'C:\xampp\' -replace ';extension=zip', 'extension=zip'
    Set-Content -Path (Join-Path $tmpIniDir "php.ini") -Value $iniContent -Encoding ASCII
    $env:PHPRC = $tmpIniDir
}

$backendDir = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $backendDir

if (-not (Test-Path "database\database.sqlite")) {
    New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
}

& $phpExe artisan serve --host=127.0.0.1 --port=8000

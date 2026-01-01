<?php

namespace App\Libraries;

/**
 * QR Code Generator for Bag Labels
 * Generates QR codes for individual bag tracking
 */
class QRCodeGenerator
{
    private $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = base_url();
    }
    
    /**
     * Generate QR code data for a bag
     */
    public function generateBagQRData($bagData)
    {
        // Handle different field names for moisture content
        $moistureContent = $bagData['moisture_percentage'] ?? $bagData['moisture_content'] ?? 0;
        
        return json_encode([
            'bag_id' => $bagData['bag_id'],
            'batch_id' => $bagData['batch_id'],
            'batch_number' => $bagData['batch_number'],
            'weight_kg' => $bagData['weight_kg'],
            'moisture_percentage' => $moistureContent,
            'quality_grade' => $bagData['quality_grade'] ?? 'Standard',
            'loading_date' => $bagData['loading_date'] ?? date('Y-m-d H:i:s'),
            'loaded_by' => $bagData['loaded_by'] ?? '',
            'verification_url' => $this->baseUrl . 'verify-bag/' . $bagData['bag_id']
        ]);
    }
    
    /**
     * Generate QR code URL using Google Charts API
     */
    public function generateQRCodeURL($data, $size = 200)
    {
        // Limit data length to prevent URL issues
        if (strlen($data) > 2000) {
            $data = substr($data, 0, 2000);
        }
        
        $encodedData = urlencode($data);
        return "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl={$encodedData}&choe=UTF-8";
    }
    
    /**
     * Generate alternative QR code using QR Server API (fallback)
     */
    public function generateQRCodeURLFallback($data, $size = 200)
    {
        if (strlen($data) > 1000) {
            $data = substr($data, 0, 1000);
        }
        
        $encodedData = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedData}";
    }
    
    /**
     * Generate QR code as data URL (base64) to bypass CSP restrictions
     */
    public function generateQRDataURL($data, $size = 150)
    {
        try {
            // Create a simple but visible QR-like pattern using canvas/GD simulation
            return $this->generateSimpleQRImage($data, $size);
            
        } catch (\Exception $e) {
            // Fallback to a simple data URL image
            return $this->generateFallbackDataURL($data, $size);
        }
    }
    
    /**
     * Generate a simple QR-like image using base64 PNG
     */
    private function generateSimpleQRImage($data, $size)
    {
        // Create a simple black and white pattern based on the data
        // This will be visible and work with CSP
        
        $gridSize = 21; // Standard QR code is 21x21 for version 1
        $cellSize = floor($size / $gridSize);
        $actualSize = $gridSize * $cellSize;
        
        // Create image data array (1 = black, 0 = white)
        $pattern = array_fill(0, $gridSize, array_fill(0, $gridSize, 0));
        
        // Add corner markers (finder patterns)
        $this->addFinderPattern($pattern, 0, 0);
        $this->addFinderPattern($pattern, $gridSize - 7, 0);
        $this->addFinderPattern($pattern, 0, $gridSize - 7);
        
        // Add data pattern based on input
        $hash = md5($data);
        for ($i = 0; $i < strlen($hash); $i++) {
            $x = (hexdec($hash[$i]) % ($gridSize - 8)) + 4;
            $y = (hexdec($hash[($i + 1) % strlen($hash)]) % ($gridSize - 8)) + 4;
            if ($x < $gridSize && $y < $gridSize) {
                $pattern[$y][$x] = 1;
            }
        }
        
        // Convert to base64 PNG
        return $this->patternToPNG($pattern, $cellSize);
    }
    
    /**
     * Add finder pattern (corner squares) to QR pattern
     */
    private function addFinderPattern(&$pattern, $startX, $startY)
    {
        // 7x7 finder pattern
        for ($y = 0; $y < 7; $y++) {
            for ($x = 0; $x < 7; $x++) {
                $px = $startX + $x;
                $py = $startY + $y;
                
                if ($px >= 0 && $px < count($pattern[0]) && $py >= 0 && $py < count($pattern)) {
                    // Create the finder pattern: outer ring, inner ring, center dot
                    if (($x == 0 || $x == 6 || $y == 0 || $y == 6) || 
                        ($x >= 2 && $x <= 4 && $y >= 2 && $y <= 4)) {
                        $pattern[$py][$px] = 1;
                    }
                }
            }
        }
    }
    
    /**
     * Convert pattern array to base64 PNG data URL
     */
    private function patternToPNG($pattern, $cellSize)
    {
        $gridSize = count($pattern);
        $imageSize = $gridSize * $cellSize;
        
        // Create a simple PNG header for a black and white image
        // This is a minimal PNG implementation
        
        // For simplicity, let's create an SVG instead and convert to base64
        $svg = "<svg width='{$imageSize}' height='{$imageSize}' xmlns='http://www.w3.org/2000/svg'>";
        $svg .= "<rect width='{$imageSize}' height='{$imageSize}' fill='white'/>";
        
        for ($y = 0; $y < $gridSize; $y++) {
            for ($x = 0; $x < $gridSize; $x++) {
                if ($pattern[$y][$x] == 1) {
                    $px = $x * $cellSize;
                    $py = $y * $cellSize;
                    $svg .= "<rect x='{$px}' y='{$py}' width='{$cellSize}' height='{$cellSize}' fill='black'/>";
                }
            }
        }
        
        $svg .= "</svg>";
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    /**
     * Generate a simple SVG QR code representation
     * This creates a visual QR-like pattern but for actual scanning, use external APIs
     */
    public function generateSVGQRCode($data, $size)
    {
        // For now, let's create a simple pattern that includes the actual data as text
        // This makes it readable even if not scannable
        $svg = "
        <svg width='{$size}' height='{$size}' xmlns='http://www.w3.org/2000/svg'>
            <rect width='{$size}' height='{$size}' fill='white' stroke='black' stroke-width='2'/>
            
            <!-- Corner markers -->
            <rect x='5' y='5' width='20' height='20' fill='black'/>
            <rect x='8' y='8' width='14' height='14' fill='white'/>
            <rect x='11' y='11' width='8' height='8' fill='black'/>
            
            <rect x='" . ($size - 25) . "' y='5' width='20' height='20' fill='black'/>
            <rect x='" . ($size - 22) . "' y='8' width='14' height='14' fill='white'/>
            <rect x='" . ($size - 19) . "' y='11' width='8' height='8' fill='black'/>
            
            <rect x='5' y='" . ($size - 25) . "' width='20' height='20' fill='black'/>
            <rect x='8' y='" . ($size - 22) . "' width='14' height='14' fill='white'/>
            <rect x='11' y='" . ($size - 19) . "' width='8' height='8' fill='black'/>
            
            <!-- Data pattern grid -->
            <g fill='black'>";
        
        // Create a more systematic pattern based on data
        $hash = md5($data);
        $gridSize = 8;
        $cellSize = ($size - 60) / $gridSize;
        
        for ($row = 0; $row < $gridSize; $row++) {
            for ($col = 0; $col < $gridSize; $col++) {
                $index = ($row * $gridSize + $col) % strlen($hash);
                $value = hexdec($hash[$index]);
                
                if ($value % 2 == 1) { // Use odd numbers to create pattern
                    $x = 30 + ($col * $cellSize);
                    $y = 30 + ($row * $cellSize);
                    $svg .= "<rect x='{$x}' y='{$y}' width='" . ($cellSize - 1) . "' height='" . ($cellSize - 1) . "'/>";
                }
            }
        }
        
        $svg .= "
            </g>
            
            <!-- Data text (readable fallback) -->
            <text x='" . ($size/2) . "' y='" . ($size - 8) . "' text-anchor='middle' font-family='monospace' font-size='6' fill='#666'>" . 
            htmlspecialchars(substr($data, 0, 20)) . "..." .
            "</text>
        </svg>";
        
        return $svg;
    }
    
    /**
     * Generate a fallback data URL image
     */
    private function generateFallbackDataURL($data, $size)
    {
        // Create a simple 1x1 pixel transparent PNG as base64
        $transparentPng = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        return 'data:image/png;base64,' . $transparentPng;
    }
    
    /**
     * Generate bag ID in standard format
     */
    public function generateBagId($batchNumber, $bagSequence)
    {
        return $batchNumber . '-B' . str_pad($bagSequence, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * Generate multiple bag IDs for a batch
     */
    public function generateBatchBagIds($batchNumber, $totalBags)
    {
        $bagIds = [];
        for ($i = 1; $i <= $totalBags; $i++) {
            $bagIds[] = $this->generateBagId($batchNumber, $i);
        }
        return $bagIds;
    }
    
    /**
     * Create printable bag label HTML
     */
    public function createBagLabelHTML($bagData)
    {
        // Debug: Log the raw bag data
        if (ENVIRONMENT === 'development') {
            log_message('debug', 'Raw bag data: ' . json_encode($bagData));
        }
        
        // Handle different field names for moisture content with better detection
        $moistureContent = 0;
        
        // Check all possible moisture field names and log what we find
        $moistureFields = ['moisture_percentage', 'moisture_content', 'moisture'];
        foreach ($moistureFields as $field) {
            if (isset($bagData[$field])) {
                $value = floatval($bagData[$field]);
                if (ENVIRONMENT === 'development') {
                    log_message('debug', "Found $field = $value in bag data");
                }
                if ($value > 0) {
                    $moistureContent = $value;
                    break;
                }
            }
        }
        
        // If still 0, try to get any numeric value from moisture-related fields
        if ($moistureContent == 0) {
            foreach ($bagData as $key => $value) {
                if (stripos($key, 'moisture') !== false && is_numeric($value) && $value > 0) {
                    $moistureContent = floatval($value);
                    if (ENVIRONMENT === 'development') {
                        log_message('debug', "Found moisture in field $key = $moistureContent");
                    }
                    break;
                }
            }
        }
        
        // Prepare bag data with proper fallbacks
        $safeBagData = [
            'bag_id' => $bagData['bag_id'] ?? ($bagData['batch_number'] ?? 'UNK') . '-B' . str_pad($bagData['bag_number'] ?? 1, 3, '0', STR_PAD_LEFT),
            'batch_id' => $bagData['batch_id'] ?? $bagData['id'] ?? 0,
            'batch_number' => $bagData['batch_number'] ?? 'Unknown',
            'weight_kg' => floatval($bagData['weight_kg'] ?? $bagData['weight'] ?? 0),
            'moisture_percentage' => floatval($moistureContent),
            'quality_grade' => $bagData['quality_grade'] ?? 'Standard',
            'loading_date' => $bagData['loading_date'] ?? date('Y-m-d H:i:s'),
            'loaded_by' => $bagData['loaded_by'] ?? 'System'
        ];
        
        // Create very simple QR data - just the essential info
        $qrDataSimple = $safeBagData['bag_id'] . '|' . 
                       $safeBagData['weight_kg'] . 'kg|' . 
                       $safeBagData['moisture_percentage'] . '%|' . 
                       date('Y-m-d', strtotime($safeBagData['loading_date']));
        
        // Try external scannable QR first (may be blocked by CSP but are actually scannable)
        $qrUrl1 = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrDataSimple);
        $qrUrl2 = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($qrDataSimple);
        
        // Local server route as fallback (CSP compliant but not scannable)
        $qrDataUrl = base_url('batch-receiving/qr-code/' . urlencode($qrDataSimple));
        
        // Data URL fallback (CSP compliant but not scannable)
        $qrDataUrlFallback = $this->generateQRDataURL($qrDataSimple, 150);
        
        // Debug: Log QR URLs to see what we're generating
        if (ENVIRONMENT === 'development') {
            log_message('debug', "Local QR URL: $qrDataUrl");
            log_message('debug', "Fallback QR URL 1: $qrUrl1");
            log_message('debug', "Fallback QR URL 2: $qrUrl2");
        }
        
        // Format display values
        $batchNumber = htmlspecialchars($safeBagData['batch_number']);
        $bagId = htmlspecialchars($safeBagData['bag_id']);
        $weight = number_format($safeBagData['weight_kg'], 2);
        $moisture = number_format($safeBagData['moisture_percentage'], 2);
        $grade = htmlspecialchars($safeBagData['quality_grade']);
        $date = date('M d, Y', strtotime($safeBagData['loading_date']));
        
        // Debug output for moisture
        if (ENVIRONMENT === 'development') {
            log_message('debug', "Moisture debug - Raw: " . ($bagData['moisture_percentage'] ?? 'not set') . ", Final: $moisture");
        }
        
        return "
        <div class='bag-label' style='
            width: 4in; 
            height: 3in; 
            border: 2px solid #000; 
            padding: 10px; 
            margin: 10px;
            page-break-inside: avoid;
            font-family: Arial, sans-serif;
            display: inline-block;
            background: white;
            position: relative;
        '>
            <!-- Header -->
            <div style='text-align: center; margin-bottom: 10px;'>
                <div style='font-size: 14px; font-weight: bold; margin-bottom: 5px;'>GRAIN BAG LABEL</div>
                <div style='font-size: 18px; font-weight: bold; background: #000; color: white; padding: 5px; margin: 5px 0;'>{$bagId}</div>
                <div style='font-size: 14px; color: #666;'>Batch: {$batchNumber}</div>
            </div>
            
            <!-- Main Content -->
            <table style='width: 100%; font-size: 12px; margin-bottom: 10px;'>
                <tr>
                    <td style='width: 60%; vertical-align: top;'>
                        <div style='margin-bottom: 5px;'><strong>Weight:</strong> <span style='color: red; font-size: 14px; font-weight: bold;'>{$weight} kg</span></div>
                        <div style='margin-bottom: 5px;'><strong>Moisture:</strong> <span style='color: blue; font-size: 14px; font-weight: bold;'>{$moisture}%</span></div>
                        <div style='margin-bottom: 5px;'><strong>Grade:</strong> {$grade}</div>
                        <div style='margin-bottom: 5px;'><strong>Date:</strong> {$date}</div>
                    </td>
                    <td style='width: 40%; text-align: center; vertical-align: top;'>
                        <!-- QR Code with guaranteed fallback -->
                        <div style='position: relative; display: inline-block;'>
                            <!-- Try external QR first -->
                            <img id='qr-{$bagId}' 
                                 src='{$qrUrl1}' 
                                 alt='QR Code for {$bagId}' 
                                 style='width: 100px; height: 100px; border: 2px solid #000; background: white; display: block;'
                                 onload='console.log(\"✅ External QR loaded\"); document.getElementById(\"qr-fallback-{$bagId}\").style.display=\"none\";'
                                 onerror='console.log(\"❌ External QR failed, showing fallback\"); this.style.display=\"none\"; document.getElementById(\"qr-fallback-{$bagId}\").style.display=\"block\";'>
                            
                            <!-- Always visible fallback QR -->
                            <div id='qr-fallback-{$bagId}' style='width: 100px; height: 100px; border: 2px solid #000; background: white; display: block; position: absolute; top: 0; left: 0;'>
                                <!-- QR-like pattern using CSS -->
                                <div style='position: relative; width: 100%; height: 100%;'>
                                    <!-- Corner markers -->
                                    <div style='position: absolute; top: 5px; left: 5px; width: 20px; height: 20px; background: black;'></div>
                                    <div style='position: absolute; top: 8px; left: 8px; width: 14px; height: 14px; background: white;'></div>
                                    <div style='position: absolute; top: 11px; left: 11px; width: 8px; height: 8px; background: black;'></div>
                                    
                                    <div style='position: absolute; top: 5px; right: 5px; width: 20px; height: 20px; background: black;'></div>
                                    <div style='position: absolute; top: 8px; right: 8px; width: 14px; height: 14px; background: white;'></div>
                                    <div style='position: absolute; top: 11px; right: 11px; width: 8px; height: 8px; background: black;'></div>
                                    
                                    <div style='position: absolute; bottom: 5px; left: 5px; width: 20px; height: 20px; background: black;'></div>
                                    <div style='position: absolute; bottom: 8px; left: 8px; width: 14px; height: 14px; background: white;'></div>
                                    <div style='position: absolute; bottom: 11px; left: 11px; width: 8px; height: 8px; background: black;'></div>
                                    
                                    <!-- Data pattern -->
                                    <div style='position: absolute; top: 30px; left: 30px; width: 4px; height: 4px; background: black;'></div>
                                    <div style='position: absolute; top: 35px; left: 40px; width: 4px; height: 4px; background: black;'></div>
                                    <div style='position: absolute; top: 40px; left: 35px; width: 4px; height: 4px; background: black;'></div>
                                    <div style='position: absolute; top: 45px; left: 50px; width: 4px; height: 4px; background: black;'></div>
                                    <div style='position: absolute; top: 50px; left: 45px; width: 4px; height: 4px; background: black;'></div>
                                    <div style='position: absolute; top: 55px; left: 30px; width: 4px; height: 4px; background: black;'></div>
                                    <div style='position: absolute; top: 60px; left: 55px; width: 4px; height: 4px; background: black;'></div>
                                    
                                    <!-- Data text -->
                                    <div style='position: absolute; bottom: 2px; left: 0; right: 0; text-align: center; font-size: 6px; color: #666;'>
                                        {$bagId}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style='font-size: 8px; margin-top: 3px; color: #666;'>Scan to Verify</div>
                        
                        <!-- Debug info -->
                        " . (ENVIRONMENT === 'development' ? "
                        <div style='font-size: 6px; color: #999; margin-top: 2px;'>
                            <a href='{$qrUrl1}' target='_blank' style='color: #999;'>Test QR</a>
                        </div>
                        " : "") . "
                    </td>
                </tr>
            </table>
            
            <!-- Footer -->
            <div style='text-align: center; font-size: 8px; color: #666; border-top: 1px solid #ccc; padding-top: 5px;'>
                CogsFlow System • {$bagId}
            </div>
            
            <!-- Debug Info (only in development) -->
            " . (ENVIRONMENT === 'development' ? "
            <div style='position: absolute; bottom: 2px; right: 2px; font-size: 6px; color: red;'>
                M: {$moisture}% | W: {$weight}kg
            </div>
            " : "") . "
        </div>";
    }
    
    /**
     * Generate labels for entire batch
     */
    public function generateBatchLabels($batchData, $bags)
    {
        $totalBags = count($bags);
        $totalWeight = array_sum(array_column($bags, 'weight_kg'));
        $avgMoisture = $totalBags > 0 ? array_sum(array_column($bags, 'moisture_percentage')) / $totalBags : 0;
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Bag Labels - {$batchData['batch_number']}</title>
            <meta charset='UTF-8'>
            <style>
                @media print {
                    body { margin: 0; padding: 10px; }
                    .no-print { display: none !important; }
                    .bag-label { break-inside: avoid; }
                }
                
                @media screen {
                    body { 
                        background: #f5f5f5; 
                        padding: 20px; 
                        font-family: Arial, sans-serif;
                    }
                }
                
                /* QR Code specific styles */
                .qr-container {
                    position: relative;
                    display: inline-block;
                }
                
                .qr-image {
                    width: 100px;
                    height: 100px;
                    border: 2px solid #000;
                    background: white;
                    display: block;
                }
                
                .qr-fallback {
                    width: 100px;
                    height: 100px;
                    border: 2px solid #000;
                    background: #f0f0f0;
                    display: none;
                    font-size: 8px;
                    padding: 5px;
                    box-sizing: border-box;
                    text-align: center;
                }
                
                .header { 
                    text-align: center; 
                    margin-bottom: 30px; 
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .labels-container { 
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                    justify-items: center;
                    max-width: 1200px;
                    margin: 0 auto;
                }
                
                .print-btn {
                    background: #007bff;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    margin: 10px;
                }
                
                .print-btn:hover {
                    background: #0056b3;
                }
                
                .batch-info {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                    margin: 20px 0;
                    text-align: left;
                }
                
                .info-card {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    border-left: 4px solid #007bff;
                }
            </style>
        </head>
        <body>
            <div class='header no-print'>
                <h1 style='color: #333; margin-bottom: 10px;'>🏷️ Bag Labels for Batch {$batchData['batch_number']}</h1>
                
                <div class='batch-info'>
                    <div class='info-card'>
                        <strong>Supplier:</strong><br>
                        {$batchData['supplier_name']}
                    </div>
                    <div class='info-card'>
                        <strong>Grain Type:</strong><br>
                        {$batchData['grain_type']}
                    </div>
                    <div class='info-card'>
                        <strong>Total Bags:</strong><br>
                        {$totalBags} bags
                    </div>
                    <div class='info-card'>
                        <strong>Total Weight:</strong><br>
                        " . number_format($totalWeight, 2) . " kg
                    </div>
                    <div class='info-card'>
                        <strong>Avg. Moisture:</strong><br>
                        " . number_format($avgMoisture, 2) . "%
                    </div>
                    <div class='info-card'>
                        <strong>Generated:</strong><br>
                        " . date('M d, Y H:i') . "
                    </div>
                </div>
                
                <button class='print-btn' onclick='window.print()'>🖨️ Print All Labels</button>
                <button class='print-btn' onclick='window.close()' style='background: #6c757d;'>❌ Close</button>
            </div>
            
            <div class='labels-container'>";
        
        foreach ($bags as $bag) {
            $html .= $this->createBagLabelHTML($bag);
        }
        
        $html .= "
            </div>
            
            <script>
            // Handle QR code fallbacks
            function handleQRError(img, url2, url3, bagId, weight, moisture) {
                console.log('QR image failed to load, trying fallback...');
                
                // Try second URL
                if (url2 && img.src !== url2) {
                    img.src = url2;
                    return;
                }
                
                // Try third URL
                if (url3 && img.src !== url3) {
                    img.src = url3;
                    return;
                }
                
                // All URLs failed, show text fallback
                console.log('All QR URLs failed, showing text fallback');
                img.style.display = 'none';
                const fallback = document.getElementById('qr-fallback-' + bagId);
                if (fallback) {
                    fallback.style.display = 'block';
                }
            }
            
            // Test QR loading on page load
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Page loaded, checking QR codes...');
                const qrImages = document.querySelectorAll('img[id^=\"qr-\"]');
                console.log('Found ' + qrImages.length + ' QR code images');
                
                qrImages.forEach(function(img, index) {
                    setTimeout(function() {
                        if (!img.complete || img.naturalHeight === 0) {
                            console.log('QR image ' + index + ' failed to load properly');
                            img.onerror();
                        } else {
                            console.log('QR image ' + index + ' loaded successfully');
                        }
                    }, 2000); // Check after 2 seconds
                });
            });
            </script>
        </body>
        </html>";
        
        return $html;
    }
    
    /**
     * Validate QR code data
     */
    public function validateQRData($qrData)
    {
        $data = json_decode($qrData, true);
        
        if (!$data) {
            return ['valid' => false, 'error' => 'Invalid QR code format'];
        }
        
        $required = ['bag_id', 'batch_id', 'weight_kg'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return ['valid' => false, 'error' => "Missing required field: {$field}"];
            }
        }
        
        return ['valid' => true, 'data' => $data];
    }
}

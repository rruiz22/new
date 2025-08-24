<?php

// Test the formatValuesForDisplay method logic
function formatValuesForDisplay($values)
{
    if (empty($values) || !is_array($values)) {
        return [];
    }
    
    $formatted = [];
    foreach ($values as $key => $value) {
        // Skip empty values
        if (empty($value) && $value !== '0' && $value !== 0) {
            continue;
        }
        
        if (is_array($value)) {
            $formatted[$key] = count($value) . ' items';
        } else {
            $displayValue = getDisplayValueForField($key, $value);
            
            // For comment fields, create truncated version with full content for tooltip
            if ($key === 'comment' && is_string($displayValue) && strlen($displayValue) > 25) {
                $formatted[$key] = [
                    'truncated' => substr($displayValue, 0, 25) . '...',
                    'full' => $displayValue,
                    'is_truncated' => true
                ];
            } elseif (is_string($displayValue) && strlen($displayValue) > 50) {
                // For other long fields, use the original truncation
                $formatted[$key] = [
                    'truncated' => substr($displayValue, 0, 50) . '...',
                    'full' => $displayValue,
                    'is_truncated' => true
                ];
            } else {
                $formatted[$key] = $displayValue;
            }
        }
    }
    
    return $formatted;
}

function getDisplayValueForField($fieldName, $value)
{
    switch ($fieldName) {
        case 'status':
            return ucfirst(str_replace('_', ' ', $value));
        case 'pictures':
            return $value ? 'Pictures Done' : 'No pictures';
        default:
            return $value;
    }
}

// Test data
$testOldValues = ['status' => 'pending'];
$testNewValues = ['status' => 'completed'];

echo "=== TEST FORMAT VALUES FOR DISPLAY ===\n\n";

echo "Old Values Input: " . json_encode($testOldValues) . "\n";
echo "New Values Input: " . json_encode($testNewValues) . "\n\n";

$formattedOld = formatValuesForDisplay($testOldValues);
$formattedNew = formatValuesForDisplay($testNewValues);

echo "Old Values Formatted: " . json_encode($formattedOld) . "\n";
echo "New Values Formatted: " . json_encode($formattedNew) . "\n\n";

echo "Expected Output:\n";
echo "- Old: {\"status\": \"Pending\"}\n";
echo "- New: {\"status\": \"Completed\"}\n";

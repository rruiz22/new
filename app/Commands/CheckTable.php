<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckTable extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'check:table';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Check sales_orders table structure and data';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'check:table';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Checking sales_orders table...', 'yellow');
        
        $db = \Config\Database::connect();
        
        // Check table structure
        CLI::write('Table Structure:', 'green');
        CLI::write('================', 'green');
        
        $query = $db->query('DESCRIBE sales_orders');
        $fields = $query->getResultArray();
        
        foreach($fields as $field) {
            CLI::write($field['Field'] . " - " . $field['Type'] . " - Key: " . $field['Key'] . " - Extra: " . $field['Extra']);
        }
        
        // Check for empty order_number records
        CLI::write("\nChecking for problematic records:", 'yellow');
        CLI::write('==================================', 'yellow');
        
        try {
            $emptyRecords = $db->query("SELECT id, COALESCE(order_number, 'NULL') as order_number FROM sales_orders WHERE order_number IS NULL OR order_number = ''")->getResultArray();
            CLI::write("Records with empty order_number: " . count($emptyRecords));
            
            if (count($emptyRecords) > 0) {
                foreach($emptyRecords as $record) {
                    CLI::write("ID: " . $record['id'] . " - order_number: " . $record['order_number']);
                }
            }
        } catch (\Exception $e) {
            CLI::write("Error checking order_number field: " . $e->getMessage(), 'red');
            CLI::write("This might mean the order_number field doesn't exist yet.", 'yellow');
        }
        
        // Check for any duplicate entries
        CLI::write("\nChecking for duplicate issues:", 'yellow');
        CLI::write('==============================', 'yellow');
        
        try {
            $duplicates = $db->query("SELECT order_number, COUNT(*) as count FROM sales_orders WHERE order_number != '' AND order_number IS NOT NULL GROUP BY order_number HAVING COUNT(*) > 1")->getResultArray();
            CLI::write("Duplicate order_numbers found: " . count($duplicates));
            
            foreach($duplicates as $dup) {
                CLI::write("order_number: '" . $dup['order_number'] . "' appears " . $dup['count'] . " times");
            }
        } catch (\Exception $e) {
            CLI::write("Error checking duplicates: " . $e->getMessage(), 'red');
        }
        
        CLI::write("\nDone!", 'green');
    }
}

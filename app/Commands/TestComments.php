<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestComments extends BaseCommand
{
    protected $group = 'Demo';
    protected $name = 'test:comments';
    protected $description = 'Creates sample comments for testing the comments functionality';

    public function run(array $params)
    {
        CLI::write('Testing Comments Functionality', 'yellow');
        CLI::newLine();

        // Initialize models
        $commentModel = new \App\Models\SalesOrderCommentModel();
        $salesOrderModel = new \App\Models\SalesOrderModel();
        $userModel = new \App\Models\UserModel();

        // Get the first sales order
        $firstOrder = $salesOrderModel->select('id')->where('deleted', 0)->first();
        if (!$firstOrder) {
            CLI::error('No sales orders found! Please create a sales order first.');
            return;
        }

        $orderId = $firstOrder['id'];
        CLI::write('Testing with Order ID: ' . $orderId, 'green');

        // Get the first user
        $firstUser = $userModel->select('id, first_name, last_name')->first();
        if (!$firstUser) {
            CLI::error('No users found!');
            return;
        }

        $userId = $firstUser['id'];
        $userName = trim($firstUser['first_name'] . ' ' . $firstUser['last_name']);
        CLI::write('Testing with User: ' . $userName . ' (ID: ' . $userId . ')', 'green');
        CLI::newLine();

        // Clear existing comments for this order
        CLI::write('1. Clearing existing comments...', 'yellow');
        $db = \Config\Database::connect();
        $deleted = $db->table('portal_sales_orders_comments')->where('order_id', $orderId)->delete();
        CLI::write('✓ Existing comments cleared (deleted: ' . $deleted . ')', 'green');
        CLI::newLine();

        // Add test comments
        CLI::write('2. Adding test comments...', 'yellow');

        $testComments = [
            [
                'description' => 'Customer confirmed the appointment time for tomorrow.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'description' => 'Vehicle inspection completed. Ready for service.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'description' => 'Parts have been ordered and should arrive by Friday.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'description' => 'Customer called to reschedule. New time: 2:00 PM.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
            ],
            [
                'description' => 'Just a simple test comment.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ],
            [
                'description' => 'Completed diagnostic check. All systems normal.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))
            ],
            [
                'description' => 'Customer approved the quote. Work will begin Monday.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-8 hours'))
            ],
            [
                'description' => 'Ordered replacement parts from supplier. ETA: 3 business days.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 hours'))
            ],
            [
                'description' => 'Vehicle cleaned and ready for pickup.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours'))
            ],
            [
                'description' => 'Quality check completed. Everything looks good.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-14 hours'))
            ],
            [
                'description' => 'Customer notified about completion.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 hours'))
            ],
            [
                'description' => 'Additional notes: Customer prefers morning appointments.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 hours'))
            ]
        ];

        foreach ($testComments as $index => $comment) {
            $commentData = [
                'order_id' => $orderId,
                'description' => $comment['description'],
                'created_by' => $userId,
                'created_at' => $comment['created_at']
            ];
            
            $result = $commentModel->insert($commentData);
            if ($result) {
                CLI::write('✓ Comment ' . ($index + 1) . ' added: "' . $comment['description'] . '"', 'green');
            } else {
                CLI::write('✗ Failed to add comment ' . ($index + 1), 'red');
            }
        }
        CLI::newLine();

        // Test retrieving comments
        CLI::write('3. Testing comment retrieval...', 'yellow');
        $comments = $commentModel->getOrderComments($orderId);

        if (count($comments) > 0) {
            CLI::write('✓ Successfully retrieved ' . count($comments) . ' comments', 'green');
            CLI::newLine();
            
            CLI::write('Comments found:', 'yellow');
            foreach ($comments as $comment) {
                $authorName = $comment['created_by_name'] ?? 'Anonymous';
                $formattedDate = date('M j, Y \a\t g:i A', strtotime($comment['created_at']));
                CLI::write('- ' . $authorName . ' (' . $formattedDate . '): ' . $comment['description'], 'white');
            }
        } else {
            CLI::write('✗ No comments retrieved', 'red');
        }
        CLI::newLine();

        // Show URLs to test
        CLI::write('4. Test URLs:', 'yellow');
        CLI::write('Controller endpoint: http://localhost:8080/sales_orders/getComments/' . $orderId, 'cyan');
        CLI::write('View page: http://localhost:8080/sales_orders/view/' . $orderId, 'cyan');
        CLI::newLine();

        CLI::write('✅ Test completed!', 'green');
        CLI::write('You can now test the comments functionality on the sales order view page.', 'white');
        CLI::write('Try adding a new comment and it should show the correct user name.', 'white');
    }
} 
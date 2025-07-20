<?php
/**
 * Database Structure and Data Verification Script
 * 
 * This script checks the database structure and data to identify
 * potential issues with the notes sharing problem.
 * 
 * Run this script in your browser: http://localhost/Backend/check_database.php
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Note;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Check</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .section { margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔍 Database Structure & Data Check</h1>
    
    <?php
    
    try {
        // Check database connection
        echo "<div class='section'>";
        echo "<h2>🔌 Database Connection</h2>";
        DB::connection()->getPdo();
        echo "<p class='success'>✅ Database connection successful</p>";
        echo "<p><strong>Database:</strong> " . DB::connection()->getDatabaseName() . "</p>";
        echo "</div>";
        
        // Check tables existence
        echo "<div class='section'>";
        echo "<h2>📊 Tables Check</h2>";
        
        $tables = ['users', 'notes', 'personal_access_tokens'];
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                echo "<p class='success'>✅ Table '$table' exists</p>";
            } else {
                echo "<p class='error'>❌ Table '$table' does not exist</p>";
            }
        }
        echo "</div>";
        
        // Check users table structure
        echo "<div class='section'>";
        echo "<h2>👤 Users Table Structure</h2>";
        
        if (DB::getSchemaBuilder()->hasTable('users')) {
            $columns = DB::getSchemaBuilder()->getColumnListing('users');
            echo "<p><strong>Columns:</strong> " . implode(', ', $columns) . "</p>";
            
            $requiredColumns = ['id', 'name', 'email', 'password'];
            foreach ($requiredColumns as $column) {
                if (in_array($column, $columns)) {
                    echo "<p class='success'>✅ Column '$column' exists</p>";
                } else {
                    echo "<p class='error'>❌ Column '$column' missing</p>";
                }
            }
        }
        echo "</div>";
        
        // Check notes table structure
        echo "<div class='section'>";
        echo "<h2>📝 Notes Table Structure</h2>";
        
        if (DB::getSchemaBuilder()->hasTable('notes')) {
            $columns = DB::getSchemaBuilder()->getColumnListing('notes');
            echo "<p><strong>Columns:</strong> " . implode(', ', $columns) . "</p>";
            
            $requiredColumns = ['id', 'user_id', 'title', 'body', 'created_at', 'updated_at'];
            foreach ($requiredColumns as $column) {
                if (in_array($column, $columns)) {
                    echo "<p class='success'>✅ Column '$column' exists</p>";
                } else {
                    echo "<p class='error'>❌ Column '$column' missing</p>";
                }
            }
            
            // Check foreign key constraint
            $foreignKeys = DB::select("
                SELECT 
                    CONSTRAINT_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'notes' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (count($foreignKeys) > 0) {
                echo "<p class='success'>✅ Foreign key constraints found:</p>";
                foreach ($foreignKeys as $fk) {
                    echo "<p class='info'>   - {$fk->COLUMN_NAME} → {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}</p>";
                }
            } else {
                echo "<p class='warning'>⚠️ No foreign key constraints found</p>";
            }
        }
        echo "</div>";
        
        // Check users data
        echo "<div class='section'>";
        echo "<h2>👥 Users Data</h2>";
        
        $userCount = User::count();
        echo "<p><strong>Total Users:</strong> $userCount</p>";
        
        if ($userCount > 0) {
            $users = User::select('id', 'name', 'email', 'created_at')->get();
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user->id}</td>";
                echo "<td>{$user->name}</td>";
                echo "<td>{$user->email}</td>";
                echo "<td>{$user->created_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='warning'>⚠️ No users found in database</p>";
        }
        echo "</div>";
        
        // Check notes data
        echo "<div class='section'>";
        echo "<h2>📋 Notes Data</h2>";
        
        $noteCount = Note::count();
        echo "<p><strong>Total Notes:</strong> $noteCount</p>";
        
        if ($noteCount > 0) {
            $notes = Note::with('user')->select('id', 'user_id', 'title', 'created_at')->get();
            echo "<table>";
            echo "<tr><th>ID</th><th>User ID</th><th>User Name</th><th>Title</th><th>Created</th></tr>";
            foreach ($notes as $note) {
                $userClass = $note->user ? 'success' : 'error';
                $userName = $note->user ? $note->user->name : 'USER NOT FOUND';
                
                echo "<tr>";
                echo "<td>{$note->id}</td>";
                echo "<td>{$note->user_id}</td>";
                echo "<td class='$userClass'>{$userName}</td>";
                echo "<td>{$note->title}</td>";
                echo "<td>{$note->created_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Check for orphaned notes
            $orphanedNotes = Note::whereNotIn('user_id', User::pluck('id'))->count();
            if ($orphanedNotes > 0) {
                echo "<p class='error'>❌ Found $orphanedNotes orphaned notes (notes without valid users)</p>";
            } else {
                echo "<p class='success'>✅ No orphaned notes found</p>";
            }
            
            // Check notes per user
            echo "<h3>Notes per User</h3>";
            $notesPerUser = Note::select('user_id', DB::raw('count(*) as count'))
                                ->groupBy('user_id')
                                ->with('user')
                                ->get();
            
            foreach ($notesPerUser as $stat) {
                $userName = $stat->user ? $stat->user->name : 'Unknown User';
                echo "<p>User {$stat->user_id} ($userName): {$stat->count} notes</p>";
            }
        } else {
            echo "<p class='warning'>⚠️ No notes found in database</p>";
        }
        echo "</div>";
        
        // Check Laravel models relationships
        echo "<div class='section'>";
        echo "<h2>🔗 Model Relationships Test</h2>";
        
        if ($userCount > 0) {
            $firstUser = User::first();
            $userNotes = $firstUser->notes;
            echo "<p class='success'>✅ User → Notes relationship working: User '{$firstUser->name}' has {$userNotes->count()} notes</p>";
        }
        
        if ($noteCount > 0) {
            $firstNote = Note::first();
            $noteUser = $firstNote->user;
            if ($noteUser) {
                echo "<p class='success'>✅ Note → User relationship working: Note '{$firstNote->title}' belongs to '{$noteUser->name}'</p>";
            } else {
                echo "<p class='error'>❌ Note → User relationship broken: Note '{$firstNote->title}' has no user</p>";
            }
        }
        echo "</div>";
        
        // Check authentication tokens
        echo "<div class='section'>";
        echo "<h2>🔑 Authentication Tokens</h2>";
        
        if (DB::getSchemaBuilder()->hasTable('personal_access_tokens')) {
            $tokenCount = DB::table('personal_access_tokens')->count();
            echo "<p><strong>Total Active Tokens:</strong> $tokenCount</p>";
            
            if ($tokenCount > 0) {
                $tokens = DB::table('personal_access_tokens')
                           ->select('id', 'tokenable_id', 'name', 'created_at', 'last_used_at')
                           ->orderBy('created_at', 'desc')
                           ->limit(10)
                           ->get();
                
                echo "<table>";
                echo "<tr><th>ID</th><th>User ID</th><th>Name</th><th>Created</th><th>Last Used</th></tr>";
                foreach ($tokens as $token) {
                    echo "<tr>";
                    echo "<td>{$token->id}</td>";
                    echo "<td>{$token->tokenable_id}</td>";
                    echo "<td>{$token->name}</td>";
                    echo "<td>{$token->created_at}</td>";
                    echo "<td>" . ($token->last_used_at ? $token->last_used_at : 'Never') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
        echo "</div>";
        
        // Configuration check
        echo "<div class='section'>";
        echo "<h2>⚙️ Configuration Check</h2>";
        
        echo "<p><strong>App Environment:</strong> " . config('app.env') . "</p>";
        echo "<p><strong>App Debug:</strong> " . (config('app.debug') ? 'Enabled' : 'Disabled') . "</p>";
        echo "<p><strong>Database Driver:</strong> " . config('database.default') . "</p>";
        echo "<p><strong>Default Auth Guard:</strong> " . config('auth.defaults.guard') . "</p>";
        echo "<p><strong>Sanctum Guard:</strong> " . implode(', ', config('sanctum.guard')) . "</p>";
        
        echo "</div>";
        
        // Recommendations
        echo "<div class='section'>";
        echo "<h2>💡 Recommendations</h2>";
        
        echo "<h3>If users are seeing each other's notes:</h3>";
        echo "<ul>";
        echo "<li>Check if the frontend is sending the Authorization header properly</li>";
        echo "<li>Verify the token is valid and not expired</li>";
        echo "<li>Check if the middleware 'auth:sanctum' is applied to the routes</li>";
        echo "<li>Ensure the Note model has the user_id column and it's properly populated</li>";
        echo "<li>Check the Laravel logs for authentication errors</li>";
        echo "</ul>";
        
        echo "<h3>Next steps:</h3>";
        echo "<ul>";
        echo "<li>Run the comprehensive API test: <a href='test_api_comprehensive.php'>test_api_comprehensive.php</a></li>";
        echo "<li>Run the frontend test: <a href='frontend-test.html'>frontend-test.html</a></li>";
        echo "<li>Check Laravel logs: <code>storage/logs/laravel.log</code></li>";
        echo "<li>Test with multiple users to reproduce the issue</li>";
        echo "</ul>";
        
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div class='section'>";
        echo "<h2 class='error'>❌ Error</h2>";
        echo "<p class='error'>Error checking database: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "</div>";
    }
    
    ?>
</body>
</html>

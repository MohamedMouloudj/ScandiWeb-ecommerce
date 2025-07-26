<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class DirectSQLExecutor
{
    private $pdo;
    private $dbType;

    public function __construct($dbType = 'mysql', $config = [])
    {
        $this->dbType = $dbType;
        $this->connect($config);
    }

    private function connect($config)
    {
        try {
            if ($this->dbType === 'mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            }
            // elseif ($this->dbType === 'sqlite') {
            //     $dsn = "sqlite:{$config['database']}";
            //     $this->pdo = new PDO($dsn, null, null, [
            //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            //     ]);
            // }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function executeSQLFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("SQL file not found: $filePath");
        }

        $sql = file_get_contents($filePath);

        // Split SQL statements (simple approach)
        $statements = $this->splitSQLStatements($sql);

        $this->pdo->beginTransaction();

        try {
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && !$this->isComment($statement)) {
                    echo "Executing: " . substr($statement, 0, 50) . "...\n";
                    $this->pdo->exec($statement);
                }
            }

            $this->pdo->commit();
            echo "SQL file executed successfully!\n";
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw new Exception("Error executing SQL: " . $e->getMessage());
        }
    }

    private function splitSQLStatements($sql)
    {
        // Remove comments and split by semicolon
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // Split by semicolon but be careful with strings
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = null;

        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];

            if (!$inString && ($char === '"' || $char === "'")) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar) {
                $inString = false;
                $stringChar = null;
            } elseif (!$inString && $char === ';') {
                $statements[] = trim($current);
                $current = '';
                continue;
            }

            $current .= $char;
        }

        if (trim($current)) {
            $statements[] = trim($current);
        }

        return array_filter($statements);
    }

    private function isComment($statement)
    {
        return strpos(trim($statement), '--') === 0 ||
            strpos(trim($statement), '/*') === 0;
    }
}

// ==========================================

class CommandLineExecutor
{

    /**
     * Execute MySQL SQL file using command line
     */
    public static function executeMySQLFile($host, $port, $username, $password, $database, $sqlFile)
    {
        $command = "mysql -h $host -P $port -u $username -p$password $database < $sqlFile";

        echo "Executing: $command\n";

        $output = [];
        $returnCode = 0;

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            echo "MySQL SQL file executed successfully!\n";
        } else {
            echo "Error executing MySQL SQL file. Return code: $returnCode\n";
            echo implode("\n", $output);
        }

        return $returnCode === 0;
    }

    /**
     * Execute SQLite SQL file using command line
     */
    // public static function executeSQLiteFile($databasePath, $sqlFile)
    // {
    //     $command = "sqlite3 $databasePath < $sqlFile";

    //     echo "Executing: $command\n";

    //     $output = [];
    //     $returnCode = 0;

    //     exec($command, $output, $returnCode);

    //     if ($returnCode === 0) {
    //         echo "SQLite SQL file executed successfully!\n";
    //     } else {
    //         echo "Error executing SQLite SQL file. Return code: $returnCode\n";
    //         echo implode("\n", $output);
    //     }

    //     return $returnCode === 0;
    // }

    /**
     * Execute SQL file with mysqldump-style import
     */
    public static function executeMySQLImport($host, $port, $username, $password, $database, $sqlFile)
    {
        $command = "mysql -h $host -P $port -u $username -p$password $database --execute=\"SOURCE $sqlFile\"";

        echo "Executing: $command\n";

        $output = [];
        $returnCode = 0;

        exec($command, $output, $returnCode);

        return $returnCode === 0;
    }
}


// ==========================================

class DropAllTables
{
    public static function dropAllTables($config, $driver)
    {
        if ($driver === 'mysql') {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password']);

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `$table`");
                echo "Dropped table: $table\n";
            }

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            echo "All MySQL tables dropped successfully!\n";
        }
        // elseif ($driver === 'sqlite') {
        //     $dsn = "sqlite:{$config['sqliteDatabase']}";
        //     $pdo = new PDO($dsn, null, null);

        //     $pdo->exec("PRAGMA foreign_keys = OFF");

        //     $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
        //         ->fetchAll(PDO::FETCH_COLUMN);
        //     foreach ($tables as $table) {
        //         $pdo->exec("DROP TABLE IF EXISTS \"$table\"");
        //         echo "Dropped table: $table\n";
        //     }

        //     $pdo->exec("PRAGMA foreign_keys = ON");
        //     echo "All SQLite tables dropped successfully!\n";
        // } elseif ($driver === 'all') {
        //     self::dropAllTables($config, 'mysql');
        //     self::dropAllTables($config, 'sqlite');
        // } 
        else {
            echo "Unsupported driver: $driver\n";
        }
    }
}

// ==========================================
// PROMPT FUNCTION
// ==========================================
function prompt(string $message): string
{
    return strtolower(trim(readline($message)));
}

<?php
class Settings {
    private static array $data = [];

    public static function load(array $config): void {
        self::$data = array_merge(self::$data, $config);
    }

    public static function set(string $key, mixed $value): void {
        self::$data[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed {
        return self::$data[$key] ?? $default;
    }

    public static function all(): array {
        return self::$data;
    }

    public static function has(string $key): bool {
        return array_key_exists($key, self::$data);
    }

    public static function initDb(array $params): PDO {
        try {  
            
            if ((bool)$params['usesqlite']) {
                
                $dbPath = $params['sqlitepath'];
                $conn = new PDO("sqlite:$dbPath");
            }
            else
            {
                
                $dsn = "mysql:host={$params['host']};dbname={$params['dbname']};charset=utf8mb4";
                $conn = new PDO($dsn, $params['user'], $params['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            }

            self::set('db', $conn);
            return $conn;
        } catch (PDOException $e) {
            die("Errore connessione DB: " . $e->getMessage());
        }
    }
}

include_once 'dbconfig.php';

if(isset($_POST['Action'])) 
{
    $Action = $_POST['Action'];
    /*
    ███████╗███████╗████████╗    ██████╗ ██████╗ 
    ██╔════╝██╔════╝╚══██╔══╝    ██╔══██╗██╔══██╗
    ███████╗█████╗     ██║       ██║  ██║██████╔╝
    ╚════██║██╔══╝     ██║       ██║  ██║██╔══██╗
    ███████║███████╗   ██║       ██████╔╝██████╔╝
    ╚══════╝╚══════╝   ╚═╝       ╚═════╝ ╚═════╝ 
    */
    if ($Action == "SetDb") 
    {
        

        

        $usesqlite = filter_var($_POST['UseSqlite'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $newConfig = [
            'host' => $_POST['Host'],
            'dbname' => $_POST['DbName'],
            'user' => $_POST['Username'],
            'password' => $_POST['Password'],
            'usesqlite' => $usesqlite,
            'sqlitepath' => $_POST['SqlitePath']
        ];
       
        // test connection
        $error = false;
        try {
            $dsn = "mysql:host={$newConfig['host']};dbname={$newConfig['dbname']};charset=utf8mb4";
            $conn = new PDO($dsn, $newConfig['user'], $newConfig['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $error = 0;
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }

        if ($error == 0) {
            // write on file
            $export = var_export($newConfig, true);
            file_put_contents(__DIR__ . '/dbsettings.php', "<?php\nreturn $export;\n");
        }

        $jresult = array('Action' => $Action,
                         'Config' => $newConfig,
                         'Debug' => $usesqlite,
                         'Error' => $error);

        echo json_encode($jresult);

        exit;
    }
                                                               
    /*
     ██████╗ ███████╗████████╗██╗███╗   ██╗███████╗ ██████╗   ██████╗ ██████╗ 
    ██╔════╝ ██╔════╝╚══██╔══╝██║████╗  ██║██╔════╝██╔═══██╗  ██╔══██╗██╔══██╗
    ██║  ███╗█████╗     ██║   ██║██╔██╗ ██║█████╗  ██║   ██║  ██║  ██║██████╔╝
    ██║   ██║██╔══╝     ██║   ██║██║╚██╗██║██╔══╝  ██║   ██║  ██║  ██║██╔══██╗
    ╚██████╔╝███████╗   ██║   ██║██║ ╚████║██║     ╚██████╔╝  ██████╔╝██████╔╝
     ╚═════╝ ╚══════╝   ╚═╝   ╚═╝╚═╝  ╚═══╝╚═╝      ╚═════╝   ╚═════╝ ╚═════╝ 
    */

    else if ($Action == "GetInfoDb")
    {
        $deviceId = $_POST['DeviceId'];

         
            
        // Carica la configurazione attuale
        $config = include __DIR__ . '/dbsettings.php';

        $servername = htmlspecialchars($config['host']);
        $dbname = htmlspecialchars($config['dbname']);
        $username = htmlspecialchars($config['user']);
        $password = htmlspecialchars($config['password']);
        $usesqlite = $config['usesqlite'];
        $sqlitepath = $config['sqlitepath'];
        //$usesqlite = filter_var($_POST['usesqlite'] ?? false, FILTER_VALIDATE_BOOLEAN);
    
        $jresult = array('Action' => $Action,
                        'DeviceId' => $deviceId,
                        'Servername' => $servername,
                        'Dbname' => $dbname,
                        'Username' => $username,
                        'Password' => $password,
                        'Usesqlite' => $usesqlite,
                        'SqlitePath' => $sqlitepath);

        echo json_encode($jresult);

        exit;
    }
}
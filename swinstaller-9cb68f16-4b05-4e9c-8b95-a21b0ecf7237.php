<?php
    if (isset($_GET["test"])) {
        die("Test OK");
    }
    
    $software = array();

    function exception_error_handler($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    
    function fatal_handler() {
        $error = error_get_last();
        if ($error !== NULL) {
            $software["error"] = $error["message"];
        
            http_response_code(500);
            
            echo json_encode($software);
        }
    }
    

    function download($zip_file, $config) {
        // download zip file
        
        file_put_contents($zip_file, file_get_contents($config["swinstaller"]["software"]["origin_url"]));
        
        // extract zip file
        
        $zip = new ZipArchive;

        if ($zip->open($zip_file) === TRUE) {
            $zip->extractTo('.');
            $zip->close();

            $files = scandir('wordpress');
            $files = array_diff($files, array('.', '..'));

            foreach ($files as $file) {
                rename('wordpress/' . $file, './' . $file);
            }
            
            rmdir('wordpress');
        } 
        else {
            throw new Exception("Failed to unzip $zip_file");
        }        
        
        unlink($zip_file);
    }

    
    function configure($config) {
	$config_file = file('wp-config-sample.php');

        $secret_keys = explode("\n", file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/'));

        foreach ($secret_keys as $k => $v) {
                $secret_keys[$k] = substr($v, 28, 64);
        }

        $key = 0;
        foreach ( $config_file as &$line ) {
                if (!preg_match( '/^define\(([ ]+)\'([A-Z_]+)\',([ ]+)/', $line, $match)) {
                        continue;
                }

                $constant = $match[2];

                switch ($constant) {
                        case 'DB_NAME'     :
                                $line = "define('DB_NAME', '" . $config["swinstaller"]["database"]["name"] . "');\r\n";
                                break;
                        case 'DB_USER'     :
                                $line = "define('DB_USER', '" . $config["swinstaller"]["database"]["username"] . "');\r\n";
                                break;
                        case 'DB_PASSWORD' :
                                $line = "define('DB_PASSWORD', '" . $config["swinstaller"]["database"]["password"] . "');\r\n";
                                break;
                        case 'DB_HOST'     :
                                $line = "define('DB_HOST', '" . $config["swinstaller"]["database"]["hostname"] . "');\r\n";
                                break;
                        case 'AUTH_KEY'         :
                        case 'SECURE_AUTH_KEY'  :
                        case 'LOGGED_IN_KEY'    :
                        case 'NONCE_KEY'        :
                        case 'AUTH_SALT'        :
                        case 'SECURE_AUTH_SALT' :
                        case 'LOGGED_IN_SALT'   :
                        case 'NONCE_SALT'       :
                                $line = "define('" . $constant . "', '" . $secret_keys[$key++] . "');\r\n";
                                break;
                }
        }

        unset($line);

        $fp = fopen('wp-config.php', 'w');

        foreach ($config_file as $line) {
                fwrite($fp, $line);
        }

        fclose($fp);

        chmod('wp-config.php', 0644);
    }


    function install($config) {
        global $software;
    
        define('WP_INSTALLING', true);
        
        /** Load WordPress Bootstrap */
        require_once('wp-load.php');

        /** Load WordPress Administration Upgrade API */
        require_once('wp-admin/includes/upgrade.php');

        /** Load wpdb */
        require_once('wp-includes/wp-db.php');

        // WordPress installation
        wp_install('Wordpress', $config["swinstaller"]["software"]["username"], $config["swinstaller"]["software"]["username"], True, '', $config["swinstaller"]["software"]["password"]);

        // We update the options with the right siteurl et homeurl value
        $protocol = 'https'; // ! is_ssl() ? 'http' : 'https';
        //$get = basename( dirname( __FILE__ ) ) . '/index.php/wp-admin/install.php?action=install_wp';
        $dir = str_replace( '../', '', '.');
        $link = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/';
        //$url = str_replace( $get, $dir, $link );
        //$url = trim( $url, '/' );
        $url = $link;
        
        $software["url"] = $url;

        update_option( 'siteurl', $url );
        update_option( 'home', $url );        
        update_option( 'timezone_string', 'Europe/Amsterdam');
    }


    set_error_handler("exception_error_handler");
    register_shutdown_function("fatal_handler");
    
    try {
        $script_name = basename(__FILE__, ".php");
        $config_file = $script_name . "-config.php";
        $zip_file = $script_name . ".zip";
        
        $fp = fopen($config_file, "r");
        
        $data = fread($fp, filesize($config_file));
        
        fclose($fp);

        $data = str_replace("<?php\n", "", $data);
        
        $config = json_decode($data, true);
        
        download($zip_file, $config);
        
        configure($config);
        
        install($config);
        
        $software["error"] = NULL;

        echo json_encode($software);
        
    } catch (Exception $exception) {
        $software["error"] = $exception->getMessage();

        http_response_code(500);

        echo json_encode($software);
    }
?>
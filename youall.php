<?php
    /**
     * This script runs in a loop to pull changes from git
     */

     

     function kmu(){
        set_time_limit(0);
        while(true){   
            $cmd = "git pull origin develop";
            $output = shell_exec($cmd);
            echo "$output";
            sleep(30);
        }
       
     }
     //names

     function listenAndPush(){
         $app = "./App";

        $filesToObserve = [
            "$app/logic/database/db_requirements",
            "$app/logic/database/migration.php"
        ]; //including their directories for example ./APP/logic/classes/writer.class.php
        $filesModificationTime = [];
        $commitMessage = "Working on the database";

        echo "Listening to these files for changes:
            \n";
        print_r($filesToObserve);
        echo "\n";

        while(true){
            foreach($filesToObserve as $filename){
                //init the modification time array
                if(!isset($filesModificationTime[$filename])){
                    if(file_exists($filename)){
                        //check the modification time
                        $filesModificationTime[$filename] = filemtime($filename);
                    }
                    continue;
                }
    
                //the two arrays are now aligned. The next runnuing of the loop will start this listening
                //so listen for changes
                if(isset($filesModificationTime[$filename])){
    
                    if(file_exists($filename)){
                        //check the modification time
                        if($filesModificationTime[$filename] != filemtime($filename)){
                            //a change occurred
                            //push the changes
                            $changeTime = filemtime($filename);
                            $return = null;
                            $output = null;
    
                            $cmd = "git add *";
                            exec($cmd, $output, $return);
    
                            $return = null;
                            $cmd = "git commit -a -m \"$commitMessage\"";
                            exec($cmd, $output, $return);
                            $return = null;
                            $cmd = "git push origin develop";
                            exec($cmd, $output, $return);
                            print_r($output);

                            //update change time
                            $filesModificationTime[$filename] = $changeTime;
                        }
                    }else{
                        echo "Can find $filename\n";
                        break;
                    }
                }    
            }
        }
        
     }

     if($argc > 1){
         $parameters = $argv;

         switch($parameters[1])
         {
             case "--kmu":
                {
                    kmu();
                    break;
                }
            case "--pta": //push them all
                {
                    set_time_limit(0);
                    listenAndPush();
                    break;
                }
             case "--help":
                {
                    echo "--kmu:   keep Me Updated\n --pta: Push Them all\n--help: help";
                    
                    break;
                }
             default:
             {
                 echo "I don't know what you are asking\nPlease type php youall --help for help\n";
             }
         }
     }else{
        echo "You should ask something...\nPlease type php youall --help for help\n";
     }
?>
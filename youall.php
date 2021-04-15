<?php
    /**
     * Create a file in the root folder (your pangaea folder not APP - outside the APP folder) with one of the following name:
     * 1. levi
     * 2. susan
     * 3. tony
     * 4. abart
     * 5. emmanuel
     * 
     * Copy the code from this file into the created file.
     * Set the files you want to observe in the listenAndPush function.
     * for the commands: --kmu, --help, --migrate, --pta, use the name of the file you created.
     * for example `php levi --kmu` if your file name is levi.
     * 
     * further changes will be made to this file and you will receive them if you have --kmu pulling
     * and --pta pushing in continously
     */

     

     function kmu(){
        set_time_limit(0);
        $output = [];
        $prev_output = [];
        while(true){   
            $cmd = "git pull origin develop";
            exec($cmd, $output);
            if($prev_output != $output){
                print_r($output);
                $prev_output = $output;
            }
             $output = [];
            sleep(1);
        }
       
     }
     //names

     function listenAndPush(){
         $app = "./App";

        $filesToObserve = [
            //"$app/logic/classes/writer.class.php",
           // "$app/logic/classes/reader.class.php",
           // "$app/logic/classes/article.class.php",
           // "$app/logic/classes/utility.class.php",
        ]; //including their directories for example ./APP/logic/classes/writer.class.php
        $filesModificationTime = [];
        $commitMessage = "Finishing the login function";

    
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
            //sleep(1);
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
            case "--migrate":
                {

                    echo "\nmigrating\n";
                    $output = null;
                    $cmd = "php ./App/logic/database/migration.php";
                    exec($cmd, $output);
                    print_r($output);
                    break;
                }
             case "--help":
                {
                    echo "--kmu:   keep Me Updated\n--pta: Push Them all\n--migrate: run the database migration file\n--help: help";
                    
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
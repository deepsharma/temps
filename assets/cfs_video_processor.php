<?php
$output1 = array();
$process_list1 = "ps aux | grep '/usr/bin/php /root/mount_point/qustn/html/qustn/alpha/dev/www/index.php enterprise moveProcessdVideo'";
exec($process_list1,$output1);
if(count($output1)>3){
        print_r($output1);
        exit;
}

$output = array();
$process_list = "ps aux | grep '/usr/bin/php /root/mount_point/qustn/html/qustn/alpha/dev/www/assets/cfs_video_processor.php'";
exec($process_list,$output);
if(count($output)>3){
        print_r($output);
        exit;
}

//Moving files to the processing Server
$filepath = "/root/mount_point/qustn/html/qustn/alpha/dev/www/assets/unprocessed/*";
`rsync -avh $filepath ec2-user@172.31.29.164:/mount_point/processor/CFSVideoProcesing/raw_files/`;
//Taking the backup of files and deleting it from the main server
$backup_dir = "/root/mount_point/backup/cfs_uploaded_videos/".date('Ymd')."/";
`rsync --remove-source-files -zvh $filepath $backup_dir`;

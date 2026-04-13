<?php
function eg($filename,$content){
    $file=fopen($filename,"w");
    
    fwrite($file,$content);
    
    fclose($file);
    echo "file written successfully";
    echo"<br>";
}
eg("example.txt","hello php file");
function eg1($filename){
    $file=fopen("example.txt","r");
    echo fread($file,filesize("example.txt"));
    echo "<br>";

    echo "file read successfully";
    fclose($file);
}
eg1("example.txt");
function eg2($filename,$content){
    $file=fopen("example.txt","a");
    fwrite($file,$content);
    echo "<br>";
    echo "appended successfull";
    fclose($file);
    echo "<br>";
}
// eg2("example.txt","\nthis text is appended to privious file");
function using($filename){
echo file_get_contents($filename);
echo "<br>";
}
using("example.txt");

function eg3($filename,$content){
    echo file_put_contents($filename,$content);
    echo "<br>";
}
//eg3("example.txt","writes the data without using fopen tags");
$arr=file("example.txt");
print_r($arr);
echo "<br>";
function info(){
echo "exists:".file_exists("example.txt")."<br>";
echo "size:".filesize("example.txt")."<br>";
echo "type:".filetype("example.txt")."<br>";
echo "lastaccessed:".date("d/m/y H:i:s",fileatime("example.txt"))."<br>";
echo "lastmodified:".date("d/m/y H:i:s",filemtime("example.txt"))."<br>";
echo "owner:".fileowner("example.txt")."<br>";
echo "inode:".fileinode("example.txt")."<br>";
echo "group:".filegroup("example.txt")."<br>";
echo "created:".date("d-m-y H:i:s",filectime("example.txt"))."<br>";
echo fileperms('example.txt')."<br>";
}
function sub(){
$y=file(substr(sprintf("%0",fileperms("example.txt")),-2))."<br>";
print_r($y);
}
info();
function manage(){
$t=copy("example.txt","demo.txt");
echo"$t"."<br>";
rename("example.txt","example1.txt");
//unlink("example.txt");
//mkdir("C:\xampp\htdocs\example.php");
is_file("example");
//is_dir("practice");
}
//sub();
manage();
function directory(){
    scandir("C:\xampp\htdocs");
    opendir("C:\xampp\htdocs");
    readdir("C:\xampp\htdocs");
    closedir("C:\xampp\htdocs");
    getcwd("C:\xampp\htdocs");
    chdir("htdocs");
}
if(file_exists("example1.txt")){
fopen("example1.txt","r");
fopen("example1.txt","w");
fopen("example1.txt","a");
fopen("example1.txt","r+");
fopen("example1.txt","w+");
fopen("example1.txt","a+");
}
?>

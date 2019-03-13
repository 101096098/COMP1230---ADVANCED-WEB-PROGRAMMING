<?php
/*
****************************************************************************************
                    File with functions used in contact program
                    Reusability (use of functions)
****************************************************************************************
*/

/*
====================================================
    Function Open File (return $bigArray)
====================================================
*/
function openfile(){

    $fh = fopen("contact.txt", "r+")or die ("Cannot open file!");
    $stat = fstat($fh);
    if (substr(file_get_contents("contact.txt"),-1) ==',')
    {
        ftruncate($fh, $stat['size'] - 1);
        fclose($fh);
    }

    $myFileString = "[" . file_get_contents("contact.txt") . "]";
    $bigArray = json_decode($myFileString, true);
    return $bigArray;
}
/*
====================================================
    Function Close file
====================================================
*/
function endfile(){

    $fo = file_get_contents("contact.txt") . ',';
    file_put_contents("contact.txt", $fo);
}

/*
====================================================
    Function Create ID
====================================================
*/
function getID()
{
    $file_name = 'ids';
    if(!file_exists($file_name)){
        touch($file_name);
        $handle = fopen($file_name, 'r+');
        $id=0;
    }
    else{
        $handle = fopen($file_name, 'r+');
        $id = fread($handle,filesize($file_name));
        settype($id,"integer");
    }
    rewind($handle);
    fwrite($handle,++$id);
    fclose($handle);
    return $id;
}
/*
====================================================
    Function Create New Contact
====================================================
*/
function addContact ($id,$title, $fname, $lname, $email, $site, $cellnumber, $homenumber, $officenumber,
                     $twitter, $facebook, $comments)
{
    $myfile = fopen("contact.txt", "a") or die ("Cannot open file!");
    $arr = array();
    $arr['id'] = $id; // Hide this information in the form
    $arr["title"] = $title;
    $arr["fname"] = $fname;
    $arr["lname"] = $lname;
    $arr["email"] = $email;
    $arr["site"] = $site;
    $arr["cellnumber"] = $cellnumber;
    $arr["homenumber"] = $homenumber;
    $arr["officenumber"] = $officenumber;
    $arr["twitter"] = $twitter;
    $arr["facebook"] = $facebook;
    $arr["comments"] = $comments;
    fwrite($myfile, json_encode($arr));
    fwrite($myfile, ",");
    fclose($myfile);

}
/*
====================================================
    Function to Sort the Array
====================================================
 */
function cmp($a, $b)
{
    return strcmp(strtolower($a['fname']),strtolower($b['fname']));
}

/*
====================================================
    Function Find Contact by ID
====================================================
 */
function findContact($id)
{
    $bigArray = openfile();
    $contact = [];
    for ($i = 0; $i < count($bigArray); $i++) {
        if ($bigArray[$i]['id'] == $id) {
            $contact = $bigArray[$i];
        }
    }
    endfile();
    return $contact;
}
/*
====================================================
    Function Save Edited Contact
====================================================
 */
function savefile()
{
    $arr = array();
    $arr['id'] = @$_POST['id'];
    $arr['title'] = @$_POST['title'];
    $arr['fname'] = @$_POST['fname'];
    $arr['lname'] = @$_POST['lname'];
    $arr['email'] = @$_POST['email'];
    $arr['site'] = @$_POST['site'];
    $arr['cellnumber'] = @$_POST['cellnumber'];
    $arr['homenumber'] = @$_POST['homenumber'];
    $arr['officenumber'] = @$_POST['officenumber'];
    $arr['twitter'] = @$_POST['twitter'];
    $arr['facebook'] = @$_POST['facebook'];
    $arr['comments'] = @$_POST['comments'];

    $bigArray = openfile();
    for ($i = 0; $i < count($bigArray); $i++) {
        if ($bigArray[$i]['id'] == $arr['id']) {
            $bigArray[$i] = $arr;

        }
    }

    endfile();
    $fh = fopen("contact.txt", "w") or die ("Cannot open file!");
    $f_out = json_encode($bigArray);
    $f_out= trim($f_out,"[]");
    $f_out = $f_out . ",";
    fwrite($fh, $f_out);
    fclose($fh);    header('Location: index.phtml');
}

/*
====================================================
    Function Delete Contact
====================================================
 */

function delete(){
    $id= @$_POST['BTDelete'];
    $bigArray = openfile();

    $array = ['id' => null,'title' => null,'fname' => null,'lname' => null,'email'=> null,
    'site' => null, 'cellnumber' => null, 'homenumber'=>null, 'officenumber' => null,
    'twitter' => null,'facebook' => null, 'comments'=> null];
     for ($i = 0; $i < count($bigArray); $i++) {
        if ($bigArray[$i]['id'] == $id) {
            $bigArray[$i] = $array;
        }
    }
    endfile();

    $fh = fopen("contact.txt", "w") or die ("Cannot open file!");
    $f_out = json_encode($bigArray);
    $f_out= trim($f_out,"[]");
    $f_out = $f_out . ",";
    fwrite($fh, $f_out);
    fclose($fh);
    header('Location: index.phtml');
    die();

}

/*
====================================================
    View Source link
====================================================
 */

echo"<a href=\"/folder_view/vs.php?s=<?php echo __FILE__?>\" target=\"_blank\">View Source</a>";


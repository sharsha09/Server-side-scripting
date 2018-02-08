<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Homework 6</title>
        <style>
            .first {
                background-color:  whitesmoke;;
                height:22%;
                width:40%;
                margin: 0 auto;
                border-style: solid;
                border-color: gainsboro;
            }
            #extrafields {
                display: none;
            }
            #buttons{
                margin-top:1.5em;
                margin-bottom: 1em;
            }
            body {
                font-size: 14px;
                font-family: Helvetica, sans-serif;
            }
            /*below are for p tag*/
            #albums, #posts {
                color: blue;
                width: 45%;
                background-color: gainsboro;
                margin: auto;
                margin-bottom: 1em;
                font-size: 16px;
                text-decoration: underline;
                clear: both;
            }
            #noalbums, #noposts, #norecords {
                width: 45%;
                background-color: ghostwhite;
                margin: auto;
                margin-bottom: 1em;
                font-size: 16px;
                clear: both;
                border-color: gainsboro;
                border-style: solid;
            }
            #albumstable {
                width: 45%;
                margin: 2em auto;
                border: 3px solid gainsboro;
                word-wrap: break-word;
                white-space: normal;
                clear: both;
                display: none;
                overflow:auto;
            }
            #poststable{
                width: 45%;
                margin: auto;
                margin-top: 2em;
                margin-bottom: 0em;
                border: 3px solid gainsboro;
                word-wrap: break-word;
                white-space: normal;
                clear: both;
                display: none;
            }
            table{
                table-layout: fixed;
                border: 3px solid gainsboro;
            }
            td {
                border: 2px solid gainsboro;
            }
            table td {
                word-wrap: break-word;
                white-space: normal;
            }
            #image10, #image20, #image11, #image21, #image12, #image22, #image13, #image23, #image14, #image24{
                display: none;
                vertical-align: bottom;
                
            }
            #albumnames{
              margin: 0em;
              clear: both;
            }
            #postsmessage{
                margin: 0em;
            }
            #msg {
                text-align: left;
                font-weight: bold;
                background-color: whitesmoke; 
                margin: 0em;
            }
            .imagecontainer0, .imagecontainer1, .imagecontainer2, .imagecontainer3, .imagecontainer4{
                font-size: 0;
                float: left;
                margin-right: 5px;
            }
            th {
                text-align: left;
                margin: 0.5em;
                border: 2px solid gainsboro;
                font-size: 14px;
                background-color: whitesmoke;
            }
            .cursorchange{
                cursor: context-menu;
            }
        </style>
    </head>
    <body>
        <div class="first">
            <p style="font-size:24px;font-style:italic;text-align:center;margin:0.3em;">Facebook Search</p>
            <hr width="98%" style="margin-bottom: 0.5em;margin-top: 0.5em;">
            <form action="search.php" method="post">
                &nbsp;&nbsp;Keyword&nbsp;<input id='tex' type="text" name="keyword" value="<?php if(isset($_POST['keyword'])){echo $_POST['keyword'];} else if(isset($_GET['keyword'])){echo $_GET['keyword'];} else {echo '';} ?>" required><br>
                &nbsp;&nbsp;Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="types" name="types" onchange="addfields(this.value);">
                    <option value="Users" <?php echo ((isset($_POST['types']) && $_POST['types'] == 'Users' ) || (isset($_GET['type']) && $_GET['type'] == 'Users' ))?'selected="selected"':''; ?> >Users</option>
                    <option value="Pages" <?php echo ((isset($_POST['types']) && $_POST['types'] == 'Pages') || (isset($_GET['type']) && $_GET['type'] == 'Pages' ))?'selected="selected"':''; ?> >Pages</option>
                    <option value="Events" <?php echo ((isset($_POST['types']) && $_POST['types'] == 'Events') || (isset($_GET['type']) && $_GET['type'] == 'Events' ))?'selected="selected"':''; ?> >Events</option>
                    <option value="Places" <?php echo ((isset($_POST['types']) && $_POST['types'] == 'Places') || (isset($_GET['type']) && $_GET['type'] == 'Places' ))?'selected="selected"':''; ?> >Places</option>
                    <option value="Groups" <?php echo ((isset($_POST['types']) && $_POST['types'] == 'Groups') || (isset($_GET['type']) && $_GET['type'] == 'Groups' ))?'selected="selected"':''; ?> >Groups</option>
                </select><br>
                <!--for adding extra 2 fields, location and distance-->
                <div id="extrafields" <?php if(isset($_POST['location']) && $_POST['types'] == 'Places'){echo 'style="display:block;"';} else if(isset($_GET['locale']) && $_GET['type'] == 'Places'){echo 'style="display:block;"';} else {echo 'style="display:none;"';} ?>>
                    &nbsp;&nbsp;Location&nbsp;&nbsp;<input type="text" name="location" value="<?php if(isset($_POST['location'])){echo $_POST['location'];} else if(isset($_GET['locale'])){echo $_GET['locale'];} else {echo '';} ?>">
                    Distance(meters)&nbsp;<input type="text" name="distance" value="<?php if(isset($_POST['distance'])){echo $_POST['distance'];} else if(isset($_GET['dist'])){echo $_GET['dist'];} else {echo '';} ?>">
                </div>
                <!--submit and clear button -->
                <div id="buttons">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Search">
                <input type="reset" name="clear" value="Clear" onclick="clearfields(this.form);">
                </div>
            </form>
        </div>
        <pre>
        <?php
            require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
            $fb = new Facebook\Facebook([
                'app_id' => '1544435705596784',
                'app_secret' => 'e1919aac80fab5a77da85ec4b6c28c8e',
                'default_graph_version' => 'v2.8',
            ]);
            if(isset($_GET["id"])){
                echo "<div>";
                $id = $_GET["id"];
                $accessToken= "EAAV8pZCWcS3ABAFS8FRgHota6c9KZCoR41Hy9EfZCTbT1YWdGHpVGZCsguMAh8ZBgPJNlgVBkZBYUu0gdUg1Ps24UUBonZA7rO83WsR68SWz2ceoXo9dCpG2xNsPPjpS4aRwG37Dzfm7vAXWpy9NueVMr1DqzmCBOcZD";
                $detailsurl = "/$id?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)&access_token=$accessToken";
                    $detailsjsonobject = $fb->get($detailsurl);
                    $detailsjson = $detailsjsonobject->getDecodedBody();
                    if(!isset($detailsjson["albums"])){
                        echo "<p align='center' id='noalbums'>No Albums have been found</p>";
                    } else {
                    echo "<p align='center' id='albums'><a href='#' onclick='showalbumtable();event.preventDefault();'>Albums</a></p>";
                    echo "<div id='albumstable' >";
                        $tempname0 = !empty($detailsjson["albums"]["data"][0]["name"]);
                        $temppic10 = !empty($detailsjson["albums"]["data"][0]["photos"]["data"][0]["picture"]);
                        $temppic20 = !empty($detailsjson["albums"]["data"][0]["photos"]["data"][1]["picture"]);
                        if($tempname0 == 1){
                            $name0 = $detailsjson["albums"]["data"][0]["name"];
                            if(empty($detailsjson["albums"]["data"][0]["photos"]["data"])){
                                echo "<p id='albumnames'>$name0</p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>"; 
                            } else {
                                echo "<p id='albumnames'><a align='center' href='#' onclick='showimage0();event.preventDefault();'>$name0</a></p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                            }
                        }
                        if($temppic10 == 1){
                            $pic10 = $detailsjson["albums"]["data"][0]["photos"]["data"][0]["picture"];
                            $id10 = $detailsjson["albums"]["data"][0]["photos"]["data"][0]["id"];
                            $largerimageurl10 = "https://graph.facebook.com/v2.8/$id10/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer0' >";
                            echo "<a class='cursorchange' href=$largerimageurl10 target='_blank'><img id='image10' src=$pic10 height='80' width='80'></a>&nbsp;";
                            echo "</div>";
                        }
                        if($temppic20 == 1){
                            $pic20 = $detailsjson["albums"]["data"][0]["photos"]["data"][1]["picture"];
                            $id20 = $detailsjson["albums"]["data"][0]["photos"]["data"][1]["id"];
                            $largerimageurl20 = "https://graph.facebook.com/v2.8/$id20/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer0' >";
                            echo "<a class='cursorchange' href=$largerimageurl20 target='_blank'><img id='image20' src=$pic20 height='80' width='80'></a>";
                            echo "</div>";
                            
                        }
                        $tempname1 = !empty($detailsjson["albums"]["data"][1]["name"]);
                        $temppic11 = !empty($detailsjson["albums"]["data"][1]["photos"]["data"][0]["picture"]);
                        $temppic21 = !empty($detailsjson["albums"]["data"][1]["photos"]["data"][1]["picture"]);
                        if($tempname1 == 1){
                            $name1 = $detailsjson["albums"]["data"][1]["name"];
                            if(empty($detailsjson["albums"]["data"][1]["photos"]["data"])){
                                echo "<p id='albumnames'>$name1</p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                            } else {
                                echo "<p id='albumnames'><a align='center' href='#' onclick='showimage1();event.preventDefault();'>$name1</a></p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                            }
                        }
                        if($temppic11 == 1){
                            $pic11 = $detailsjson["albums"]["data"][1]["photos"]["data"][0]["picture"];
                            $id11 = $detailsjson["albums"]["data"][1]["photos"]["data"][0]["id"];
                            $largerimageurl11 = "https://graph.facebook.com/v2.8/$id11/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer1' >";
                            echo "<a class='cursorchange' href=$largerimageurl11 target='_blank'><img id='image11' src=$pic11 height='80' width='80'></a>&nbsp;";
                            echo "</div>";
                        }
                        if($temppic21 == 1){
                            $pic21 = $detailsjson["albums"]["data"][1]["photos"]["data"][1]["picture"];
                            $id21 = $detailsjson["albums"]["data"][1]["photos"]["data"][1]["id"];
                            $largerimageurl21 = "https://graph.facebook.com/v2.8/$id21/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer1' >";
                            echo "<a class='cursorchange' href=$largerimageurl21 target='_blank'><img id='image21' src=$pic21 height='80' width='80'></a>";
                            echo "</div>";
                        }
                        $tempname2 = !empty($detailsjson["albums"]["data"][2]["name"]);
                        $temppic12 = !empty($detailsjson["albums"]["data"][2]["photos"]["data"][0]["picture"]);
                        $temppic22 = !empty($detailsjson["albums"]["data"][2]["photos"]["data"][1]["picture"]);
                        if($tempname2 == 1){
                            $name2 = $detailsjson["albums"]["data"][2]["name"];
                            if(empty($detailsjson["albums"]["data"][2]["photos"]["data"])){
                                echo "<p id='albumnames'>$name2</p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";   
                            } else {
                                echo "<p id='albumnames'><a align='center' href='#' onclick='showimage2();event.preventDefault();'>$name2</a></p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                            }
                        }
                        if($temppic12 == 1){
                            $pic12 = $detailsjson["albums"]["data"][2]["photos"]["data"][0]["picture"];
                            $id12 = $detailsjson["albums"]["data"][2]["photos"]["data"][0]["id"];
                            $largerimageurl12 = "https://graph.facebook.com/v2.8/$id12/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer2' >";
                            echo "<a class='cursorchange' href=$largerimageurl12 target='_blank'><img id='image12' src=$pic12 height='80' width='80'></a>&nbsp;";
                            echo "</div>";
                        }
                        if($temppic22 == 1){
                            $pic22 = $detailsjson["albums"]["data"][2]["photos"]["data"][1]["picture"];
                            $id22 = $detailsjson["albums"]["data"][2]["photos"]["data"][1]["id"];
                            $largerimageurl22 = "https://graph.facebook.com/v2.8/$id22/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer2' >";
                            echo "<a class='cursorchange' href=$largerimageurl22 target='_blank'><img id='image22' src=$pic22 height='80' width='80'></a>";
                            echo "</div>";
                        }
                        $tempname3 = !empty($detailsjson["albums"]["data"][3]["name"]);
                        $temppic13 = !empty($detailsjson["albums"]["data"][3]["photos"]["data"][0]["picture"]);
                        $temppic23 = !empty($detailsjson["albums"]["data"][3]["photos"]["data"][1]["picture"]);
                        if($tempname3 == 1){
                            $name3 = $detailsjson["albums"]["data"][3]["name"];
                            if(empty($detailsjson["albums"]["data"][3]["photos"]["data"])){
                                echo "<p id='albumnames'>$name3</p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";    
                            } else {
                                echo "<p id='albumnames'><a align='center' href='#' onclick='showimage3();event.preventDefault();'>$name3</a></p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                            }
                        }
                        if($temppic13 == 1){
                            $pic13 = $detailsjson["albums"]["data"][3]["photos"]["data"][0]["picture"];
                            $id13 = $detailsjson["albums"]["data"][3]["photos"]["data"][0]["id"];
                            $largerimageurl13 = "https://graph.facebook.com/v2.8/$id13/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer3' >";
                            echo "<a class='cursorchange' href=$largerimageurl13 target='_blank'><img id='image13' src=$pic13 height='80' width='80'></a>&nbsp;";
                            echo "</div>";
                        }
                        if($temppic23 == 1){
                            $pic23 = $detailsjson["albums"]["data"][3]["photos"]["data"][1]["picture"];
                            $id23 = $detailsjson["albums"]["data"][3]["photos"]["data"][1]["id"];
                            $largerimageurl23 = "https://graph.facebook.com/v2.8/$id23/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer3' >";
                            echo "<a class='cursorchange' href=$largerimageurl23 target='_blank'><img id='image23' src=$pic23 height='80' width='80'></a>";
                            echo "</div>";
                        }
                        $tempname4 = !empty($detailsjson["albums"]["data"][4]["name"]);
                        $temppic14 = !empty($detailsjson["albums"]["data"][4]["photos"]["data"][0]["picture"]);
                        $temppic24 = !empty($detailsjson["albums"]["data"][4]["photos"]["data"][1]["picture"]);
                        if($tempname4 == 1){
                            $name4 = $detailsjson["albums"]["data"][4]["name"];
                            if(empty($detailsjson["albums"]["data"][4]["photos"]["data"])){
                                echo "<p id='albumnames'>$name4</p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>"; 
                            } else {
                                echo "<p id='albumnames'><a align='center' href='#' onclick='showimage4();event.preventDefault();'>$name4</a></p>";
                                echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                            }
                        }
                        if($temppic14 == 1){
                            $pic14 = $detailsjson["albums"]["data"][4]["photos"]["data"][0]["picture"];
                            $id14 = $detailsjson["albums"]["data"][4]["photos"]["data"][0]["id"];
                            $largerimageurl14 = "https://graph.facebook.com/v2.8/$id14/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer4' >";
                            echo "<a class='cursorchange' href=$largerimageurl14 target='_blank'><img id='image14' src=$pic14 height='80' width='80'></a>&nbsp;";
                            echo "</div>";
                        }
                        if($temppic24 == 1){
                            $pic24 = $detailsjson["albums"]["data"][4]["photos"]["data"][1]["picture"];
                            $id24 = $detailsjson["albums"]["data"][4]["photos"]["data"][1]["id"];
                            $largerimageurl24 = "https://graph.facebook.com/v2.8/$id24/picture?access_token=$accessToken";
                            echo "<div class='imagecontainer4' >";
                            echo "<a class='cursorchange' href=$largerimageurl24 target='_blank'><img id='image24' src=$pic24 height='80' width='80'></a>";
                            echo "</div>";
                        }
                    echo "</div>";
                }
                    if(!isset($detailsjson["posts"])){
                        echo "<p align='center' id='noposts'>No Posts have been found</p>";
                    } else {
                    echo "<p id='posts' align='center'><a href='#' onclick='showpoststable();event.preventDefault();'>Posts</a></p>";
                    echo "<div id='poststable' >";
                    echo "<p id='msg'>Message</p>";
                    echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                    for($j=0;$j<5;$j++){
                        $temp = !empty($detailsjson["posts"]["data"][$j]["message"]);
                        if($temp == 1){
                            $message = $detailsjson["posts"]["data"][$j]["message"];
                            echo "<p id='postsmessage'>$message</p>";
                            echo "<hr style='margin-bottom: 0em;margin-top: 0em;'>";
                        }
                    }
                    echo "</div>";
                    }
                echo "</div>";
            } 
            if(array_key_exists('submit', $_POST)){
                $accessToken= "EAAV8pZCWcS3ABAFS8FRgHota6c9KZCoR41Hy9EfZCTbT1YWdGHpVGZCsguMAh8ZBgPJNlgVBkZBYUu0gdUg1Ps24UUBonZA7rO83WsR68SWz2ceoXo9dCpG2xNsPPjpS4aRwG37Dzfm7vAXWpy9NueVMr1DqzmCBOcZD";
                $googleapikey = "AIzaSyCAB7mqGX5M7ILc3_L0_yqwPQess_3ouZ8";
                $qkeywordwoencode = $_POST["keyword"];
                $qkeyword = urlencode($qkeywordwoencode);
                $typereturned = $_POST["types"];
                $addresswoencode = $_POST["location"];
                $address = urlencode($addresswoencode);
                $distancewoencode = $_POST["distance"];
                $distance = urlencode($distancewoencode);
                if($typereturned == "Users"){
                    $type = "user";
                }else if($typereturned == "Pages"){
                    $type = "page";
                }else if($typereturned == "Events"){
                    $type = "event";
                }else if($typereturned == "Groups"){
                    $type = "group";
                }else if($typereturned == "Places"){
                    $type = "place";
                }else{
                    $type = "";
                }
                //if type is places, get address in latitude and longitude
                if($typereturned == "Places"){
                    $addresswoencode = $_POST["location"];
                    // variable address is the one with url encoding
                    $address = urlencode($addresswoencode);
                    $distancewoencode = $_POST["distance"];
                    $distance = urlencode($distancewoencode);
                    if(empty($address)){
                        $fburl = "search?q=$qkeyword&type=$type&fields=id,name,picture.width(700).height(700)&access_token=$accessToken";    
                    } else {
                        $googleurl = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$googleapikey";
                        $googlejsonobject = file_get_contents($googleurl);
                        $googlejson = json_decode($googlejsonobject);
                        $lat = $googlejson->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                        $long = $googlejson->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                        //got latitude and longitude, use it to get info from FB
                        $fburl = "/search?q=$qkeyword&type=$type&center=$lat,$long&distance=$distance&fields=id,name,picture.width(700).height(700)&access_token=$accessToken";
                    }
                    $fbjsonobject = $fb->get($fburl);
                    $fbjson = $fbjsonobject->getDecodedBody();
                } else if ($typereturned == "Events"){
                    $fburl = "/search?q=$qkeyword&type=$type&fields=id,name,picture.width(700).height(700),place&access_token=$accessToken";
                    $fbjsonobject = $fb->get($fburl);
                    $fbjson = $fbjsonobject->getDecodedBody();
                }else {
                    $fburl = "/search?q=$qkeyword&type=$type&fields=id,name,picture.width(700).height(700)&access_token=$accessToken";
                    $fbjsonobject = $fb->get($fburl);
                    $fbjson = $fbjsonobject->getDecodedBody();
                }
               // echo "<pre>".print_r($fbjson)."</pre>";
                if(empty($fbjson["data"])){
                    echo "<p align='center' id='norecords'>No Records has been found</p>";
                } else {
                echo "<table id='maintable' align='center'><br />";
                
                if($typereturned != "Events"){
                    echo "<col width='180'><br />";
                    echo "<col width='430'><br />";
                    echo "<col width='90'><br />";
                    echo "<tr>";
                    echo "<th>Profile Photo</th><th>Name</th><th>Details</th>";
                    echo "</tr>";
                }else {
                    echo "<col width='30'><br />";
                    echo "<col width='450'><br />";
                    echo "<col width='220'><br />";
                    echo "<tr>";
                    echo "<th>Profile Photo</th><th>Name</th><th>Place</th>";
                    echo "</tr>";
                }
                foreach($fbjson["data"] as $item){
                    $name = $item["name"];
                    $url = $item["picture"]["data"]["url"];
                    if($typereturned != "Events"){
                        $details = $item["id"];
                        //echo "<pre>".print_r($detailsjson)."</pre>";
                        echo "<tr>";
                        echo "<td><img src=$url height='30' width='40' onclick='largeimage(this)'></td><td align=>$name</td><td><a href=search.php?id=$details&keyword=$qkeyword&type=$typereturned&locale=$address&dist=$distance>Details</a></td>";
                        echo "</tr>";
                    }else {
                        $place = $item["place"]["name"];
                        echo "<tr>";
                        echo "<td><img src=$url height='30' width='40' onclick='largeimage(this)'></td><td align=>$name</td><td>$place</td>";
                        echo "</tr>";
                        
                    }
                }
                echo "</table>";
              }
            }
        ?>
        </pre>
        <script>
            function addfields(value){
                if(value=="Places"){
                    document.getElementById('extrafields').style.display='block';
                    document.getElementById('buttons').style.marginTop='0.2em';
                    document.getElementById('extrafields').style.marginTop='0.2em';
                }else{
                    document.getElementById('extrafields').style.display='none';
                    document.getElementById('buttons').style.marginTop='1.5em';
                }
            }
            function clearfields(form){
                document.getElementById('extrafields').style.display='none';
                document.getElementById('buttons').style.marginTop='1.5em';
                location.href = "search.php";
            }
            function largeimage(img){
                 imgsrc = img.src;
                 viewwin = window.open(imgsrc,'viewwin', 'width=900,height=800', '_blank');
                 viewwin.focus();
            }
            function showimage0(){
                var img1 = document.getElementById('image10').style.display;
                if(!img1 || img1 == "none"){
                    document.getElementById('image10').style.display='block';
                }else {
                    document.getElementById('image10').style.display='none';
                }
                var img2 = document.getElementById('image20').style.display;
                if(!img2 || img2 == "none"){
                    document.getElementById('image20').style.display='block';
                }else {
                    document.getElementById('image20').style.display='none';
                }
            }
            function showimage1(){
                var img1 = document.getElementById('image11').style.display;
                if(!img1 || img1 == "none"){
                    document.getElementById('image11').style.display='block';
                }else {
                    document.getElementById('image11').style.display='none';
                }
                var img2 = document.getElementById('image21').style.display;
                if(!img2 || img2 == "none"){
                    document.getElementById('image21').style.display='block';
                }else {
                    document.getElementById('image21').style.display='none';
                }
            }
            function showimage2(){
                var img1 = document.getElementById('image12').style.display;
                if(!img1 || img1 == "none"){
                    document.getElementById('image12').style.display='block';
                }else {
                    document.getElementById('image12').style.display='none';
                }
                var img2 = document.getElementById('image22').style.display;
                if(!img2 || img2 == "none"){
                    document.getElementById('image22').style.display='block';
                }else {
                    document.getElementById('image22').style.display='none';
                }
                
            }
            function showimage3(){
                var img1 = document.getElementById('image13').style.display;
                if(!img1 || img1 == "none"){
                    document.getElementById('image13').style.display='block';
                }else {
                    document.getElementById('image13').style.display='none';
                }
                var img2 = document.getElementById('image23').style.display;
                if(!img2 || img2 == "none"){
                    document.getElementById('image23').style.display='block';
                }else {
                    document.getElementById('image23').style.display='none';
                }
            }
            function showimage4(){
                var img1 = document.getElementById('image14').style.display;
                if(!img1 || img1 == "none"){
                    document.getElementById('image14').style.display='block';
                }else {
                    document.getElementById('image14').style.display='none';
                }
                var img2 = document.getElementById('image24').style.display;
                if(!img2 || !img2 || img2 == "none"){
                    document.getElementById('image24').style.display='block';
                }else {
                    document.getElementById('image24').style.display='none';
                }
            }
            function showalbumtable(){
               var albumtable = document.getElementById('albumstable').style.display;
               if(!albumtable || albumtable == "none"){
                   document.getElementById('albumstable').style.display = 'block';
                   document.getElementById('poststable').style.display = 'none';
               } else {
                   document.getElementById('albumstable').style.display = 'none';
                   //document.getElementById('poststable').style.display = 'block';
               }
            }function showpoststable(){
               var poststable = document.getElementById('poststable').style.display;
               if(!poststable || poststable == "none"){
                   document.getElementById('poststable').style.display = 'block';
                   document.getElementById('albumstable').style.display = 'none';
               } else {
                   document.getElementById('poststable').style.display = 'none';
                   //document.getElementById('albumstable').style.display = 'block';
               }
            }
            
        </script>
    </body>
</html>
<?php

function f_dbConnect()
{
    
    //hostgator
    $dbhost = "localhost";
    $dbuser = "hangu_dish";
    $dbpwd = "dish2013";
    $dbname = "hangu_dish";
    
    //localhost db info
    //$dbhost = "localhost";
    //$dbuser = "root";
    //$dbpwd = "";
    //$dbname = "dish";
    
    //connect to databases
    $con = mysql_connect($dbhost, $dbuser, $dbpwd);
    if(!$con){die('Couldn\'t connect: '.mysql_error());}
    
    if(!(mysql_select_db($dbname, $con))) //Selecting the database under the login
    {
        die("Couldn\'t select database $dbname: " .mysql_error());
    }
}//End of f_dbConnect

function f_dbConnect_local()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpwd = "";
    $dbname = "dish";
    
    //connect to databases
    $con = mysql_connect($dbhost, $dbuser, $dbpwd);
    if(!$con){die('Couldn\'t connect: '.mysql_error());}
    
    if(!(mysql_select_db($dbname, $con))) //Selecting the database under the login
    {
        die("Couldn\'t select database $dbname: " .mysql_error());
    }
}//End of f_dbConnect

function f_userAuthen($user)
{
    $user = mysql_real_escape_string($user);
    $result = mysql_query("select * from user where username = '$user'") or die(mysql_error());
    
    if(mysql_num_rows($result) <= 0) //if no results return, meaning user doesn't exist in the user table, so return false
    {
        return false;
    }
    while($row = mysql_fetch_array($result))
    {
        return $row['uid'];
    }
    
} //End of f_userAuthen

function f_userCreate($user)
{
    mysql_query("insert into user (username) values('$user')") or die(mysql_error());
    return true;
}
//////////////////////////////////////////////////////////////////////

//displaySearch -- restaurant.php
//input:    search string,(can be restaurant name, address, city, state, or zip and uid
//output:   Echoing out html for each searched-out restaurant's accordion-group
//Take in restaurant name, search the database then displays all restaurants containing that name.
//Each restaurant will be displayed in its own accordion-group
function displaySearch($search,$uid)
{
    $rid_list = searchRestaurant($search); //getting an array of rids base on search string
    if(count($rid_list) == 0){ echo "<h1>No Restaurant Found</h1>";}
    foreach ($rid_list as $rid)
    {
        displaySingleRestaurant($rid,1,0,$uid); //echoing out each restaurant's accordion-group. 1 indicate that myspots,recommend and inquire buttons are needed
        //0 indicate that this is for display on restaurant.php.
    }
} //End of displaySearch

//displayMySpots -- restaurant.php
//input:    uid
//output:   Echoing out html for each of user's MySpots restaurant's accordion-group
//Each restaurant will be displayed in its own accordion-group
function displayMySpots($uid)
{
    $rid_list = getMySpots($uid); //getting an array of myspots rids base on user 
    if(count($rid_list) == 0){ echo "<h2>Come on, there must be at least 1 restaurant you like.</h2>";}
    foreach ($rid_list as $rid)
    {
        displaySingleRestaurant($rid,1,0,$uid); //echoing out each restaurant's accordion-group. 1 indicate that myspots,recommend and inquire buttons are needed
        //0 indicate that this is for display on restaurant.php.
    }
} //End of displayMySpots

//displayDishIn
//input:    uid
//output:   Echoing out html for each restaurant in user's dishin (inquiry table)
function displayDishIn($uid)
{
    $result = mysql_query("select rid from inquiry where uid = $uid and close = 0") or die(mysql_error());
    if(mysql_num_rows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
            $rid = $row['rid'];
            displaySingleRestaurant($rid,0,1,$uid); //button_set is 0 for home.php, section is 1 for dishin section
        }//End of while
    }//End of if
}//End of displayDishIn function



//displayDishOut
//input:    uid
//output:   Echoing out html for each restaurant in user's dishout (response table)
function displayDishOut($uid)
{
    $result = mysql_query("select rid from response where uid = $uid and dismiss = 0") or die(mysql_error());
    if(mysql_num_rows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
            $rid = $row['rid'];
            displaySingleRestaurant($rid,0,2,$uid); //button_set is 0 for home.php, section is 2 for dishout section
        }//End of while
    }//End of if
}//End of displayDishOut function

//displayRecommend
//input:    uid
//output:   Echoing out html for each restaurant in user's friends' recommendation (recommend table)
function displayRecommend($uid)
{
    $result = mysql_query("select rid from recommend where uid = $uid and dismiss = 0") or die(mysql_error());
    if(mysql_num_rows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
            $rid = $row['rid'];
            displaySingleRestaurant($rid,0,3,$uid); //button_set is 0 for home.php, section is 3 for dishin section
        }//End of while
    }//End of if
}//End of displayRecommend function

//displayRSinglestaurant
//input:    rid
//          uid -- the uid of the logged in user
//          button_set -- flag. 1 -> myspots,recommend and inquire buttons, only restaurant.php.  0 -> myspots and remove button, only home.php
//          section -- flag. 0->restaurant.php. 1->home.php dishin. 2->home.php dishout. 3->home.php recommend by friends.
//output:   returns nothing, but echoing out html for the requested restaurant's accordion-group
function displaySingleRestaurant($rid, $button_set,$section,$uid)
{
    $restaurant_obj = getSingleRestaurant($rid,$uid); //$restaurant_obj is an associative array containing rid, rname, myspots(flag: 0 not in myspot, 1 in myspot), address, city, zip and state of the restaurant
    $rname = $restaurant_obj['rname'];
    $inmyspots = $restaurant_obj['myspots']; //In order to know whether this restaurant is in a user's myspots, uid is required
    $address = $restaurant_obj['address'];
    $city = $restaurant_obj['city'];
    $zip = $restaurant_obj['zip'];
    $state = $restaurant_obj['state'];
    $complete_address = "$address $city, $state $zip";
    
    
    //MySpots button is always needed    
    //String that contains the tooltip for the MySpots Button. Either Add to MySpots, or Remove from MySpots
    //String that contains the icon classes for MySpots Button. Added -- 'icon-bookmark icon-white', btn-inverse. Unadded -- 'icon-bookmark'
    //URL that correspond myspot status, either remove it(myspotsremove.php?rid=$rid) or add it(myspotsadd.php?rid=$rid)
    if($inmyspots)
    {
        $myspots_button_tt = 'Remove from MySpots';
        $myspots_button_icon = 'icon-bookmark icon-white';
        $myspots_href = "myspotsaction.php?rid=$rid&button_size=$button_set&action=remove"; //button_size here is set to button_set since restaurant has btn-large, home.php has btn-small
        
    }
    else
    {
        $myspots_button_tt = 'Add to MySpots';
        $myspots_button_icon = 'icon-bookmark';
        $myspots_href = "myspotsaction.php?rid=$rid&button_size=$button_set&action=add";//button_size here is set to button_set since restaurant has btn-large, home.php has btn-small
    }
    
    if($button_set == 1) //Restaurant.php where recommend and inquire buttons are needed.
    {
        //Recommend button, and Inquire button -- no need to set different variable for these button, because they would always stay the same
        //MySpots button class has btn-large on restaurant.php (where recommend and inquire buttons are needed), but on home.php it's btn-small. Thus this is set within the if condition.
        //String containing the classes for the MySpots Button. i.e.'btn btn-large btn-inverse tt' for restaurants that are added, or 'btn btn-large tt' for unadded restaurants
        if($inmyspots){$myspots_button_class = "btn btn-large btn-inverse btn-myspots tt";} else{$myspots_button_class = "btn btn-large btn-myspots tt";}

        echo "
            <div class='accordion-group'> <!-- Single Restaurant -->
                <div class='accordion-heading lead'>
                    <div class='btn-group'>
                    <button class='btn btn-large top-expander expander' data-toggle='collapse' data-target='#$rid'>+</button>
                    <a class='$myspots_button_class' data-toggle='tooltip' data-placement='top'  title='' data-original-title='$myspots_button_tt' href='$myspots_href'><i class='$myspots_button_icon'></i></a>
                    <a class='btn btn-large tt btn-recommend' data-toggle='tooltip' data-placement='top'  title='' data-original-title='Recommend' href='recommend.php?rid=$rid'><i class='icon-glass'></i></a>
                    <a class='btn btn-large tt btn-inquire' data-container='body' data-toggle='tooltip' data-placement='top'  title='' data-original-title='Inquire' href='inquire.php?rid=$rid'><i class='icon-envelope'></i></a>
                    </div>
                    <span class='restaurant-name'>$rname</span>
                    <span class='address'>($complete_address)</span>
                </div>
                
                <div class='accordion-body collapse' id='$rid'>
                    <div class='accordion-inner'>
                        <table class='table table-hover table-condensed'>
                            <tbody>
        ";
                                displayAllDishes($rid,$uid); //Display html for all dishes.
        echo "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- End of a single restaurant -->
        ";
    }
    else //home.php where remove button is needed. 0 -> myspots and remove button (home.php)
    {
        //MySpots button class has btn-large on restaurant.php (where recommend and inquire buttons are needed), but on home.php it's btn-small. Thus this is set within the if condition.
        //String containing the classes for the MySpots Button. i.e.'btn btn-small btn-inverse tt' for restaurants that are added, or 'btn btn-small tt' for unadded restaurants
        if($inmyspots){$myspots_button_class = "btn btn-small btn-inverse btn-myspots tt";} else{$myspots_button_class = "btn btn-small btn-myspots tt";}
        
        //section -- flag. 0->restaurant.php. 1->home.php dishin. 2->home.php dishout. 3->home.php recommend by friends.
        //No need to have IF clause for $section = 0, since in the else clause, it's assume this is already only for home.php
        if($section == 1){$section_id = "in-$rid";}
        if($section == 2){$section_id = "out-$rid";}
        if($section == 3){$section_id = "rec-$rid";}
        
        echo "
            <div class='accordion-group '>
                <div class='accordion-heading'>
                    <div class='btn-group'>
                        <button class='btn btn-small expander small-expander' data-toggle='collapse' data-target='#$section_id'>+</button>                         
                        <a class='$myspots_button_class' data-toggle='tooltip' data-placement='right'  title='' data-original-title='$myspots_button_tt' href='$myspots_href'><i class='$myspots_button_icon'></i></a>
                    </div>
                    
                     <span class='restaurant-name'>$rname</span>
                     <span class='address'>$complete_address</span>
                     
                     <!-- The removal button would remove this restaurant from display via jquery, and also in database, so on page reload, it won't be displayed neither. Dishin, Dishout and Recommend is identified by var section-->
                     <a class='btn btn-small pull-right tt remove' data-toggle='tooltip' data-placement='right'  title='' data-original-title='Remove' href='removeDisplay.php?rid=$rid&section=$section'><i class='icon-remove'></i></a>
                    
                </div>
                <div class='accordion-body collapse' id='$section_id'>
                    <div class='accordion-inner'>
                        <table class='table table-hover table-condensed'>
                            <tbody>
        ";
                                displayAllDishes($rid,$uid); //Display html for all dishes.
        echo "                      
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        ";
    }
}

//displayAllDishes
//input:    rid
//          uid -- the uid of the logged in user
//output:   Returns nothing. Echoing html for all of requested restaurant's dishes. This function will be called within <tbody> tag
function displayAllDishes($rid,$uid)
{
    $did_list = getDishes($rid); // Getting a list of did base on the rid
    if(count($did_list) > 0) //Only move onto display dishes if there is actual DID returned from getDishes
    {
        foreach ($did_list as $did)
        {
            displaySingleDish($did,$uid); //This where single dishes get displayed. Essentially, everything inside/ncluding the <tr></tr>
        }//End of foreach
    }//End of if
}//End of displayAllDishes

//displaySingleDish
//input:    did
//          uid -- the uid of the logged in user
//output:   Returns nothing. Echo out html for each dish. Essentially, everything inside/ncluding the <tr></tr>
function displaySingleDish($did,$uid)
{
    $dish = getSingleDish($did,$uid); //$dish is an associative array, containing dish name, whether user has recommended it, and how many votes it currently has
    $dname = $dish['dname'];
    $d_recommend = $dish['recommend']; // This is just a flag. 1 - recommended. 0 - not recommended
    $d_vote_count = $dish['rec_count'];
    if($d_recommend)
    {
        $tt = 'Remove my vote';
        $href = "dishaction.php?did=$did&action=remove";
        $btn_class = 'btn btn-mini btn-inverse tt btn-dish-vote';
        $icon_class = 'icon-thumbs-up icon-white';
    }
    else
    {
        $tt = 'Thumbs up';
        $href = "dishaction.php?did=$did&action=recommend";
        $btn_class = 'btn btn-mini tt btn-dish-vote';
        $icon_class = 'icon-thumbs-up';
    }
    ///////////////////////////////////////////////////////////////
    echo "
        <tr>
            <td class='onedish'><a class='$btn_class' data-toggle='tooltip' data-placement='right'  title='' data-original-title='$tt' href='$href'><i class='$icon_class'></i></a>
                <span class='badge badge-info pull-right vote'>$d_vote_count</span>
                $dname     
            </td>
        </tr>
    ";
}

//getSingleDish
//input:    did, uid
//output:   return an associative array, containing dish name, whether user has recommended it. 1 - recommended, 0 - not recommended and how many votes it currently has
//          Element -- dname,recommend,rec_count
function getSingleDish($did,$uid)
{
    $dish = array(
        'dname' => '',
        'recommend' => 0,
        'rec_count' => 0);
    
    //Dname retrieval
    $result = mysql_query("select dname from dish where did = $did limit 1") or die(mysql_error());
    //If DID is not found, just return dish with dname set to unknown. This is indication of something going wrong.
    if(mysql_num_rows($result) <= 0) {$dish['dname'] = 'Unknown Dish'; return $dish;}
    else
    {
        while($row = mysql_fetch_array($result)) { $dish['dname'] = $row['dname']; }
    }
    
    //Recommend retrieval
    $result = mysql_query("select * from dish_recommend where did = $did and uid = $uid") or die(mysql_error());
    if(mysql_num_rows($result) > 0){$dish['recommend'] = 1;}
    
    //Rec_count retrieval
    $result = mysql_query("select count(*) as count from dish_recommend where did = $did") or die(mysql_error());
    while($row = mysql_fetch_array($result)) { $dish['rec_count'] = $row['count']; }
    
    return $dish;
} //End of getSingleDish


//getDishes
//input:    rid
//output:   retuns an array containing did for each dish under the rid. Elements - dname, recommend
function getDishes($rid)
{
    $did_list = array();
    $result = mysql_query("select did from dish where rid = $rid") or die(mysql_error());
    if(mysql_num_rows($result) <= 0){return $did_list;}//if there's no dish under a restaurant, return empty list
    
    while($row = mysql_fetch_array($result)) { $did_list[] = $row['did']; }//Put DIDs into the did_list
    return $did_list;
}//End of getDishes

//searchRestaurant
//input:    search string, can be restaurant name, address, city, state, or zip
//output:   Return an array of rid for those restaurants
//Take in restaurant name, search for all restaurants containing that name,
//and return an array of rid for those restaurants
function searchRestaurant($search)
{
    $rid_list = array();
    if ($search == 'showall'){$result = mysql_query("select rid from restaurant order by rname");}
    else
    {
        $result = mysql_query("select rid from restaurant where rname like '%$search%' or address like '%$search%' or city like '%$search%' or zip like '%$search%' or state like '%$search%' order by rname") or die(mysql_error());
    }
    while($row = mysql_fetch_array($result)) { $rid_list[] = $row['rid'];}
    return $rid_list;
}

//getSingleRestaurant
//input:    Rid, uid - In order to know whether this restaurant is in a user's myspots, uid is required
//output:   Return an associative array containing rid, rname, myspots(flag: 0 not in myspot, 1 in myspot), address, city, zip and state of the restaurant
function getSingleRestaurant($rid,$uid)
{
   $restaurant = array(
                       'rid' => $rid,
                       'rname' => '',
                       'address' => '',
                       'city' => '',
                       'zip' => '',
                       'state' => '',
                       'myspots' => 0);
   //Setting myspots -- as long as that user/rid combo exist in myspots table, then the restaurant is part of user's myspots
   $result = mysql_query("select * from myspots where uid = $uid and rid = $rid") or die(mysql_error());
   if(mysql_num_rows($result) > 0) {$restaurant['myspots'] = 1;}
   
   //Getting rest of restaurant's info
   $result = mysql_query("select * from restaurant where rid = $rid limit 1") or die(mysql_error());
   while($row = mysql_fetch_array($result))
   {
        $restaurant['rname'] = $row['rname'];
        $restaurant['address'] = $row['address'];
        $restaurant['city'] = $row['city'];
        $restaurant['state'] = $row['state'];
        $restaurant['zip'] = $row['zip'];
   }//End of while loop 
   return $restaurant;
}

//updateMySpots -- myspotsaction.php
//input:    uid, rid, action(add/remove)
//output:   No output, just update database for this user's action(add/remove) for this restaurant
function updateMySpots($uid,$rid,$action)
{
    if($action == 'add')
    {
        mysql_query("insert into myspots (uid,rid) values($uid,$rid)") or die(mysql_error());
    }
    else
    {
        mysql_query("delete from myspots where uid = $uid and rid = $rid") or die(mysql_error());
    }
} //End of updateMyspots

//updateDish -- dishsaction.php
//input:    uid, did, action(add/remove)
//output:   No output, just update database for this user's action(add/remove) for this dish
function updateDish($uid,$did,$action)
{
    if($action == 'recommend')
    {
        mysql_query("insert into dish_recommend (uid,did) values($uid,$did)") or die(mysql_error());
    }
    else
    {
        mysql_query("delete from dish_recommend where uid = $uid and did = $did") or die(mysql_error());
    }
} //End of updateDish


//updateRecommend -- recommend.php
//input:    uid, rid
//output:   return nothing. Just updated database for user's recommendation of a restaurant
//description:
//For all of current user's friends, enter a row in recommend table, where uid is friend's uid.
//Essentially, under a friend's uid, a new row with the recommended rid will be inserted, thus later displayed in FE
//KEY to REMEMBER:  Your friend's ID end up as $uid in the database, because this table contains what restaurant recommendation
//should be displayed for him/her.
function updateRecommend($uid,$rid)
{
    $friends_list = getFriends($uid);
    foreach($friends_list as $friend)
    {
        //check to see if the rid is already recommended to that friend, if so, do nothing, else insert a new row
        $result = mysql_query("select * from recommend where uid = $friend and rid = $rid") or die(mysql_error());
        if(mysql_num_rows($result) <= 0)
        {mysql_query("insert into recommend (uid,rid) values ($friend,$rid)") or die(mysql_error());}
        else
        {mysql_query("update recommend set dismiss = 0 where rid = $rid and uid = $friend") or die(mysql_error());}
    }//End of foreach
}//End of updateRecommend function

//updateInquire -- inquire.php
//input:    uid, rid
//output:   return nothing. Just updated database for user's inquiry of a restaurant
//Description:
//Keep in mind, even if there are multiple inquiries on the same restaurant from different people. The user's response
//section should only display that restaurant once.
function updateInquire($uid,$rid)
{
    //Check if there is closed inquiry on this restaurant, if so, unclose it and get the inqid. $affected = mysql_affected_rows();
    //that function would allow us to combine the check and update into one single query
    $result = mysql_query("select * from inquiry where uid = $uid and rid = $rid") or die(mysql_error());
    if(mysql_num_rows($result) <= 0)//If this inquiry doesnt exist, insert new row into inquiry table.
    {mysql_query("insert into inquiry (uid,rid) values ($uid,$rid)") or die(mysql_error());}
    else
    {mysql_query("update inquiry set close = 0 where uid = $uid and rid = $rid") or die(mysql_error());}
    
    
    //Get all of user's friends' uids.
    $friends_list = getFriends($uid); //Now we got a list of all of current user's friends uids
    //For every friend's uid, update response table. If rid already exists for that friend, just set dismiss to 0, so it displays
    //if inquiry doesn't yet exist, then insert a new row. Keep in mind, if another user inquired about the same restaurant,
    //then we also just set dismiss to 0.
    foreach($friends_list as $friend)
    {
        $result = mysql_query("select * from response where uid = $friend and rid = $rid") or die(mysql_error());
        if(mysql_num_rows($result) <= 0)//if there's no existing rows exist then just insert a new row
        {mysql_query("insert into response (rid,uid) values ($rid, $friend)") or die(mysql_error());}
        else
        {mysql_query("update response set dismiss = 0 where rid = $rid and uid = $friend") or die(mysql_error());}
    }//End of foreach
}//End of updateInquire function

//removeRestaurantDisplay -- removeDisplay.php
//input:    uid,rid,section -- 1->For home.php, dishin. 2->home.php dishout. 3->home.php recommend by friends.
//output:   return nothing. Just update the database to make sure this restaurant won't be displayed for specified section
function removeRestaurantDisplay($uid,$rid,$section)
{
    if($section == 1) //dishin, so inquiry table
    {mysql_query("update inquiry set close = 1 where uid = $uid and rid = $rid") or die(mysql_error());}
    
    if($section == 2) //dishout, so response table
    {mysql_query("update response set dismiss = 1 where uid = $uid and rid = $rid") or die(mysql_error());}
    
    if($section == 3) //dishin, so recommend table.
    {mysql_query("update recommend set dismiss = 1 where uid = $uid and rid = $rid") or die(mysql_error());}
}//End of removeRestaurantDisplay function

//getFriends
//input:    uid -- current user's uid
//output:   return an array containing all of current user's friends' uid. As of initial release, every user is considered a friend
function getFriends($uid)
{
    $friends_list = array();
    $result = mysql_query("select uid from user where uid != $uid") or die(mysql_error());
    while($row = mysql_fetch_array($result)) {$friends_list[] = $row['uid'];}
    
    return $friends_list;
}//End of getFriends function

//getMySpots
//input:    uid -- current user's uid
//output:   return an array containing all of current users MySpots restaurant rid.
function getMySpots($uid)
{
    $rid_list = array();
    $result = mysql_query("select rid from myspots where uid = $uid") or die(mysql_error());
    while($row = mysql_fetch_array($result)) {$rid_list[] = $row['rid'];}
    
    return $rid_list;
}//End of getFriends function

//updateFeedback
//input:    uid,comment
//output:   returns nothing, just update feedback table
function updateFeedback($uid,$comment)
{
    $comment = mysql_real_escape_string($comment);
    mysql_query("insert into feedback (uid,feedback) values ($uid,'$comment')") or die(mysql_error());
}
?>
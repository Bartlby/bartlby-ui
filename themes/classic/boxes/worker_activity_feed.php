<style>

.discussion {
  list-style: none;
  
  margin: 0;
  padding: 0 0 50px 0;
}
.discussion .avatar1  {
  display: block;
  width: 60px;
 
  
}
.discussion li {
  padding: 0.5rem;
  overflow: hidden;
  display: flex;
}


.self {
  justify-content: flex-end;
  align-items: flex-end;
}
.self .messages {
  order: 1;
  border-bottom-right-radius: 0;
}


.messages {
  background: white;
  padding: 10px;
  border-radius: 2px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  width: 100%;
}
.messages p {
  font-size: 0.8rem;
  margin: 0 0 0.2rem 0;
}
.messages time {
  font-size: 0.7rem;
  color: #ccc;
}


</style>

 <ol class="discussion">
<?
$st = new BArtlbyStorage("UserActivityFeed");
$db = $st->SQLDB($btl->UserActivityFeedDB);

$w = "";
if($plcs[worker] != "") {
	$w = "where user_id=" . $plcs[worker][worker_id];
}


if($db != false) {
	$r = $db->query("select * from UserActivityFeed  " . $w . " order by insert_date desc");
	foreach($r as $row) {
		$t = bartlby_get_worker_by_id($btl->RES, $row[user_id]);

		$em = $t[mail];
?>

<li class="other">
      <div class="avatar1">
      	<div class=avatar style='width: 40px; height:40px;'>
        	<img src='<?=$this->get_gravatar($em);?>'>

    	</div>

    
      </div>

      <div class="messages">
<?=$t[name]?>
       <p>
       	<?=$row[txt]?>
       </p>
        <?=$row[insert_date]?>
      </div>
    </li>	


<?



	}
} else {
	echo "for UserActivityFeed feature you require to install php5-sqlite package";
}


?>
</ol>
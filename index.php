<?php
    session_start();
    $title = "Tree Stories";
    include("libs/connections.php");
    include("libs/helpers.php");
    include("libs/main_header.php");
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) {
        // session started more than 30 minutes ago
        session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
        $_SESSION['CREATED'] = time();  // update creation time
    }
?>
<script>
    var logged_in = <?php
    $logged_in = ($_SESSION['fb_access_token'] != NULL ? 1 : 0) || ($_SESSION['google_access_token'] != NULL ? 1 : 0);
    if($logged_in){
        echo "true";
    } else {
        echo "false";
    }
    ?>;
</script>
<div id="map"></div>
<div class="destroyer" id="copyR">
    <div id="internal">
        <div id="header">
            <h1>Tree Stories</h1>
        </div>
        <div id="main-text">
            <iframe	src='https://climatecope.research.pdx.edu/csSS/csSS.html'></iframe>
        </div>
        <div id="footer">
            <button id="tell-my-story" onclick="destroyButton()">Tell My Story</button>
        </div>
    </div>
</div>
<!-- ALL TREE LOCATION DATA (ALONG WITH THE ATTRIBUTES THEREIN) ARE THE INTELLECTUAL PROPERTY OF THE SUPR LAB, WHICH HOLDS SOLE RIGHTS FOR ITS USE.-->
<?php
    $date = date("Y-m-d H:i:s", strtotime('-30 days'));
    $sql = "SELECT tree_location FROM posts WHERE created_on > ?";
    $query = $conn->prepare($sql);
    $query->bindParam(1, $date, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll();

    $sql = "SELECT tree_id, tree_location FROM posts WHERE created_on > ?";
    $query = $conn->prepare($sql);
    $query->bindParam(1, $date, PDO::PARAM_STR);
    $query->execute();

    $trees = $query->fetchAll();
?>
<script>
    function checkLogin(){
        try{
            if(logged_in == true){
                destroyButton();
            } else {
                $('.destroyer').css('visibility', 'visible');
            }
        } catch(ReferenceError){
            setTimeout(function(){
                checkLogin();
            }, 250);
        }
    }
    checkLogin();
    var updated = [];
    var updated_trees = [];
    <?php
        foreach($results as $result){
    ?>
    updated.push(<?php echo "'".$result["tree_location"]."'"; ?>);
    <?php
        }
    ?>

    <?php
        foreach($trees as $tree){
    ?>
        updated_trees.push([<?php echo $tree["tree_id"]; ?>,<?php echo '"'.$tree["tree_location"].'"';?>]);
    <?php
        }
    ?>
</script>
<?php
    include("libs/legend.php");
    include("libs/footer.php");
?>

<?php 
include("include/db.php");
include("include/utility.php");
include("include/session.php");
$page="index"
?>
<?php include("include/header.php");?>		
   <div class="page-header">
        <h1 class="title">Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Welcome to <?php echo $site_title?> Dashboard.</li>
        </ol>
        <!-- Start Page Header Right Div -->
        <div class="right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="<?php echo $site_url?>" class="btn btn-light" target="_blank" title="View Website">View Website</a>              
            </div>
        </div>
        <!-- End Page Header Right Div -->
    </div>
    <div class="container-widget row">
        <div class="col-md-12">
            <?php
            $res=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id where parent_id=0 and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' order by sortorder",$dblink);
            if(numrows($res)>0){
                while($rec=dofetch($res)){
                    ?>
                    <h2 class="title"><?php echo $rec["title"]?></h2>
                    <?php
                    $res1=doquery("select * from menu a inner join menu_2_admin_type b on a.id = b.menu_id where parent_id='".$rec["id"]."' and admin_type_id='".$_SESSION["logged_in_admin"]["admin_type_id"]."' order by sortorder",$dblink);
                    if(numrows($res1)>0){
                        ?>
                        <ul class="menu-boxes clearfix">
                            <?php
                            while($rec1=dofetch($res1)){
                                ?>
                                <li class="col-xs-6 col-md-2 col-sm-3">
                                    <a href="<?php echo $rec1["url"]?>">
                                        <span class="project-icon"><img width="40px" height="40px" title="<?php echo $rec1['title'];?>" alt="Menu Icon" src="<?php echo $file_upload_root?>menu/<?php echo $rec1["icon"]?>"></span>
                                        <span><?php echo $rec1["title"];?></span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
<?php include("include/footer.php");?>
	
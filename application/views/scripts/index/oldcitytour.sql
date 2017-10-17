<title>City Tour Package</title>
<div class="clearfix"></div>
<?php 
	$db = new Application_Model_DbTable_DbGlobal();
	$instruction = "citytourinstruction";
	$rows = $db->getAllParameter($instruction);
	
?>
<div class="clearfix"></div>
<section class="content">
    <div class="container">
         <?php echo $rows;?> 
    </div>
</section>
<section class="message-wrap margin-bottom-20">
    <div class="container">
        <div class="row">
            <h2 class="col-lg-9 col-md-8 col-sm-12 col-xs-12 xs-padding-left-15" style="color:red;"><strong>25-Provinces & Municipality </strong>Tour Services<span class="alternate-font"></span></h2>
            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 xs-padding-right-15"> </div>
        </div>
    </div>
    <div class="message-shadow"></div>
</section>
<section class="content">
    <div class="container">
      <div class="inner-page portfolio-container row">
    	  <div class="row">
    	  	<?php foreach ($this->province as $row){ ?>
	                	<div class="col-md-3 col-sm-6 col-xs-12 padding-right-5 padding-left-5" style="height:340px;">
			                    <div class="pricing_table" style="text-align: center;border:1px solid #ccc;">
			                        <div class="pricing-header" style="background: #FF0300;text-align: center;">
			                            <a style="color:#fff; text-align:center;"  target="_blank" href="<?php echo $this->baseUrl()."/index/tourlocation/id/".$row['id'];?>"><?php echo $row["province_name"];?></a>
			                        </div>
			                        <div class="main_pricing" style="background: #fff;">
			                        	<img style="border:none;padding-top:5px;" src="<?php echo $this->baseUrl()."/images/".$row['photo'];?>" alt="preview" />
			                        	<div style="clear:both;"></div>
			                        </div>
			                        <div style="clear:both;"></div>
			                        <div class="category_pricing">
			                         	<ul>
		                                </ul>
			                        </div>
			                        <div class="price-footer padding-vertical-15">
			                            <form method="post" action="#">
			                                <input  type="button"  value="Show Location" label="Show Location" onclick="window.location = '<?php echo $this->url(array('module'=>'default','controller'=>"index",'action'=>'tourlocation',"id"=>$row['id']),null,true); ?>';"/>
			                            </form>
			                        </div>
			                    </div>
			                </div>
             <?php }?>
             </div>
    	</div>
    </div>
</section>
<?php
include"inc/header.php";
?>
<div class="row">

    <div class="col-lg-12" id="adminform">
        <section class="panel">
            <header class="panel-heading">
                اضافه کردن محصول
            </header>
            <div class="panel-body">
                <div class="alert alert-danger" id="pru" style="display: none">
                    tamam fild ha por she
                </div>
                <div class="alert alert-success" id="prd" style="display: none">
                    ثبت با موفقیت انجام شد.
                </div>
                <select id="cat" onchange="getSubCat()">
                    <option selected disabled>دسته بندی خود را انتخاب کنید</option>
                    <?php
                    include "../class/dataBase.php";
                    $db = new dataBase();
                    $select = $db::Query("SELECT * FROM category");
                    while ($rowSelect = mysqli_fetch_assoc($select)){
                        ?>
                        <option value="<?php echo $rowSelect['catId']?>"><?php echo $rowSelect['catName']?></option>
                        <?php
                    }

                    ?>
                </select>
                <select id="subCat">
                    <option selected disabled>زیر دسته بندی خود را انتخاب کنید</option>
                    <option disabled>ابتدا دسته بندی را انتخاب کنید</option>

                </select>
                <div class="form-group">
                    <label for="exampleInputEmail1">نام محصول</label>
                    <input type="text" class="form-control" id="productName" placeholder="نام محصول ">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">مبلغ محصول</label>
                    <input type="text" class="form-control" id="productPrice" placeholder="مبلغ محصول">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">توضیحات محصول</label>
                    <input type="text" class="form-control" id="description" placeholder="توضیحات محصول">
                </div>

                <span class="btn btn-lg btn-login btn-block" onclick="submit()">ثبت</span>
            </div>
        </section>
    </div>

</div>
<!-- js placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="js/jquery.sparkline.js" type="text/javascript"></script>
<script src="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="js/owl.carousel.js" ></script>
<script src="js/jquery.customSelect.min.js" ></script>

<!--common script for all pages-->
<script src="js/common-scripts.js"></script>

<!--script for this page-->
<script src="js/sparkline-chart.js"></script>
<script src="js/easy-pie-chart.js"></script>
<script src="js/jquery.js"></script>

<script> 
    function getSubCat() {
         var cat = $("#cat").val();
        $.ajax({
            url:"request/getSubCat.php",
            data: {
                catId:cat
            },
            dataType:'json',
            type:'POST',
            success:function (data) {
                if(!data['error']){
                    $("#subCat").html(" <option selected disabled>زیر دسته بندی خود را انتخاب کنید</option>");
                    for (var i=0;i<data['subCat'].length;i++){
                        $("#subCat").append('<option value="'+data['subCat'][i]['id']+'">'+data['subCat'][i]['name']+'</option>');
                    }
                }
            }
        });
    }
</script>
<script>
    function submit() {
        var subCat = $("#subCat").val();
        var productName = $("#productName").val();
        var productPrice = $("#productPrice").val();
        var description = $("#description").val();
        $.ajax({
            url:"request/addProduct.php",
            data:{
                subCat:subCat,
                productName:productName,
                productPrice:productPrice,
                description:description
            },
            dataType:'json',
            type:'POST',
            success:function (data) {
                if (data["error"]){
                    $("#pru").show("fast").html(data['MSG']);


                }else  {
                    $("#prd").show("fast");
                }
            }
        });

    }
</script>
</body>
</html>

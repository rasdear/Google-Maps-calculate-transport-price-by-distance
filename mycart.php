<!DOCTYPE HTML>
<html>
<head>
    <?PHP include 'include/script.php'; ?>

    <style>
        #do_action .total_area, #do_action .chose_area {
            border: 1px solid #E6E4DF;
            color: #696763;
            padding: 30px 25px 30px 25px;
            margin-bottom: 80px;
        }

        .total_area ul li {
            background: #E6E4DF;
            color: #696763;
            margin-top: 10px;
            padding: 7px 20px;
        }

        .total_area span {
            float: right;
        }
    </style>

</head>
<body>

<?PHP include 'include/status.php'; ?>
<?PHP include 'include/menu.php'; ?>


<div class="login" style="min-height: 440px;">
    <div class="wrap">
        <h2 class="head">ตะกร้าสินค้า</h2>


        <table class="table table-bordered tb-all">
            <thead>
            <tr class="cart_menu">
				<td class="text-center" width="100">รหัส</td>
                <td class="text-center" width="150">รูปภาพ</td>
                <td class="text-center">รายการ</td>
                <td class="text-center" width="120">คงเหลือ</td>
                <td class="text-center" width="120">ราคา</td>
                <td class="text-center" width="120">จำนวน</td>
                <td class="text-center" width="120">รวม</td>
                <td class="text-center" width="70"></td>
            </tr>
            </thead>

            <?PHP
            $sum = 0;
			$delivery = 0;
            $total = 0
            ?>
            <?PHP if ($item = $_SESSION['cart']) { ?>
                <?PHP foreach ($item as $_item) { ?>
                    <?PHP
                    $total = $_item['price'] * $_item['qty'];
                    $sum = $sum + $total;
					$pid = $_item['pid'];
					
					$sql = "SELECT * FROM tb_product a INNER JOIN tb_category b ON a.category_id = b.category_id WHERE product_id = '$pid' ";
					$row = row_array($sql);
                    ?>
                    <tr>
						<td class="text-center">
							<p><?= $row['category_code']; ?>-<?= $row['product_code']; ?><p>
						</td>
                        <td class="text-center">
                            <a href=""><img src="uploads/<?= $_item['img'] ?>" width="110" height="110" alt=""/></a>
                        </td>
                        <td class="text-center">
                            <p><?= $_item['name'] ?></p>
                        </td>
                        <td class="text-center">
                            <p><?= $_item['total'] ?></p>
                        </td>
                        <td class="text-center">
                            <p><?= number_format ($_item['price'],2) ?></p>
                        </td>
                        <td class="text-center">
                            <div class="cart_quantity_button">
                                <select name="qty" class="form-control" id="<?PHP echo $_item['pid']; ?>">
                                    <?PHP for ($i = 1; $i <= $_item['total']; $i++) { ?>
                                        <option <?PHP echo $i == $_item['qty'] ? "selected" : ""; ?>
                                            value="<?PHP echo $i; ?>"><?PHP echo $i; ?></option>
                                    <?PHP } ?>
                                </select>
                            </div>
                        </td>
                        <td class="text-center">
                            <p class="cart_total_price"><?=number_format ($total,2); ?></p>
                        </td>
                        <td class="text-center">
                            <a href="process/del_cart.php?id=<?PHP echo $_item['pid']; ?>"
                               class="btn btn-danger btn-sm">ลบ</a>
                        </td>
                    </tr>
                <?PHP } ?>
            <?PHP } else { ?>
                <tr>
                    <td colspan="6" style="color: red; text-align: center; padding: 10px;">ไม่มีสินค้า</td>
                </tr>
            <?PHP } ?>


            </tbody>


        </table>

        <section id="do_action">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="chose_area" style="height: 260px;">
                            <h1 style="font-weight: bold;">เงื่อนไข / รายละเอียด</h1>

                            <p style="padding: 10px 20px; text-indent: 20px;">
                              <p>1.เข้าสู่ระบบ</p>
							  <p>2.เลือกสินค้าที่ต้องการ</p>
							  <p>3.กดปุ่มสั่งซื้อสินค้า</p>
							  <p>4.เลือกที่อยู่จัดส่ง กดคิดค่าขนส่ง และกดยืนยัน</p>
							  <p>5.ชำระเงิน</p>
							  <p>6.รอรับสินค้า</p>
							  
                            </p>
							
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="total_area" style="height: 260px;">
                            <ul>
                                <?PHP $_SESSION['cart_sum'] = $sum; ?>
                                <li>ราคารวม <span><?= number_format($sum, 2); ?> บาท</span></li>
                                <li>ค่าขนส่ง <span><p id="demo"><?= number_format($delivery, 2); ?> บาท</p> </span></li>
                                <li>รวมทั้งหมด <span><p id="demoprice" name="demoprice"><?= number_format($sum + 0, 2); ?> บาท</p>
												<input type="hidden" id="demop" name="demop" class="form-control" value="<?= $sum; ?>">
								</span></li>
                            </ul>
                            <div class="cart_bt" style="padding-top: 20px; text-align: right;">
                                <a class="btn btn-warning update" href="product.php">เลือกสินค้าเพิ่ม</a>
                                <button class="btn btn-success check_out" id="next_step">สั่งซื้อสินค้า</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<div id="result">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="form_all" id="show_step" style="overflow: hidden; height:0px;">
                    <?PHP
                    $name = "";
                    $address = "";
                    ?>
                    <?PHP if (check_session("member_id")) { ?>

                        <form id="distance_form "action="process/order_process.php" method="post">
                            <?PHP
                            $mid = check_session("member_id");
                            $sql = "SELECT * FROM tb_member WHERE member_id = '$mid'";
                            $row = row_array($sql);

                            $name = $row['member_name'] . " " . $row['member_lastname'];
                            $address = $row['member_address'];
                            ?>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="aaa" id="aaa" value="old" checked>
                                    จัดส่งที่อยู่เดิม
                                    &nbsp; &nbsp; &nbsp;
                                    <input type="radio" name="aaa" id="aaa" value="new">
                                    จัดส่งที่อยู่ใหม่
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">ชื่อ</label>
                                <input type="name" name="name" class="form-control" style="background: #ddd;"
                                       value="<?= $name; ?>" readonly placeholder="กรุณากรอกข้อมูล"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputtext1">ที่อยู่</label>
                                <input type="text" name="address" style="background: #ddd;" class="form-control"
                                       value="<?= $address; ?>" readonly placeholder="กรุณากรอกข้อมูล"
                                       required>
                            </div>

                            <div class="form-group">
                                <center><label><i class="icon-lock"></i> <b>เลือกพิกัดที่ต้องการส่ง</b></label></center>
								ค่าขนส่ง :<input id="textbox1" readonly class="form-control"/>
								<input type="hidden" id="dprice" name="dprice" class="form-control"></div>
                                <input type="text" id="to_places" name="location" style="margin-bottom: 10px;" class="form-control col-md-7 col-xs-12"
                                       required onfocus="alert('กรุณาเลือกตำแหน่งจากแผนที่!!'); $(this).blur();" />

                                <div id="dvMap" style="width: 100%; height: 300px;"></div>
								
                            </div>

                            <center>
                                <button type="button" id="prev_step" class="btn btn-warning">ย้อนกลับ</button>
								<button type="button" id="calpr" name="calpr" class="btn btn-primary" disabled>ยืนยันพิกัด</button>
                                <button type="submit" id="submitt" name="submitt" onclick="return confirm('ยืนยันการสั่งซื้อ');" class="btn btn-success" disabled>ยืนยันการสั่งซื้อ</button>
								
                            </center>
                        </form>
                    <?PHP } else { ?>
                        <p style="text-align: center; color: red; font-size: 20px;">
                            กรุณาเข้าสู่ระบบ
                            <br>
                            <br>
                            <button type="button" id="prev_step" class="btn btn-warning">ย้อนกลับ</button>
                        </p>
                    <?PHP } ?>				
                </div>
            </div>
        </div>
		
    </div>
	</div>
</div>

<?PHP include 'include/footer.php'; ?>

<script>
    $(document).ready(function () {

        $("input#aaa").change(function () {
            var data = $(this).val();
            var name = "<?=$name;?>";
            var address = "<?=$address;?>";

            if (data == "old") {
                $("input[name='name']").val(name);
                $("input[name='address']").val(address);
                $("input[name='name']").prop('readonly', true);
                $("input[name='address']").prop('readonly', true);

                $("input[name='name']").css('background-color', '#ddd');
                $("input[name='address']").css('background-color', '#ddd');
            } else {
                $("input[name='name']").val("");
                $("input[name='address']").val("");
                $("input[name='name']").prop('readonly', false);
                $("input[name='address']").prop('readonly', false);

                $("input[name='name']").css('background-color', '#fff');
                $("input[name='address']").css('background-color', '#fff');

            }
        });

        $("#next_step").click(function () {
            $("#show_step").css({"height":"auto"});
            $(".cart_bt").hide();
			window.scrollTo(0,document.body.scrollHeight);
        });

        $("#prev_step").click(function () {
            $("#show_step").css({"height":"0"});
            $(".cart_bt").show();
        });
		
		$("#calpr").click(function () {
		$('#submitt').attr('disabled', false);
		});
		
        $('select[name="qty"]').change(function () {
            var url = 'process/add_to_cart.php';
            var id = $(this).attr('id');
            var qty = $(this).val();
            window.location.href = url + '?id=' + id + '&qty=' + qty;
        });
		$('#save').attr('disabled', false);
    });
</script>
<script>
    $(function() {
        // add input listeners
        google.maps.event.addDomListener(window, 'load', function () {
            var to_places = document.getElementById('to_places').value;
        });
		
        // calculate distance
        function calculateDistance() {
			
			var input = document.getElementById('to_places').value;
			var latlngStr = input.split(',', 2);
			var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
			var origin = new google.maps.LatLng(18.560920,99.063794);
			var destination = new google.maps.LatLng(latlng);
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.IMPERIAL, // miles and feet.
                    // unitSystem: google.maps.UnitSystem.metric, // kilometers and meters.
                    avoidHighways: false,
                    avoidTolls: false
                }, callback);
        }
        // get distance results
        function callback(response, status) {
            if (status != google.maps.DistanceMatrixStatus.OK) {
                $('#result').html(err);
            } else {
                var origin = response.originAddresses[0];
                var destination = response.destinationAddresses[0];
                if (response.rows[0].elements[0].status === "ZERO_RESULTS") {
                    $('#result').html("Better get on a plane. There are no roads between "  + origin + " and " + destination);
                } else {
                    var distance = response.rows[0].elements[0].distance;
                    var duration = response.rows[0].elements[0].duration;
                    console.log(response.rows[0].elements[0].distance);
                    var distance_in_kilo = distance.value / 1000; // the kilom
                    var distance_in_mile = distance.value / 1609.34; // the mile
                    var duration_text = duration.text;
                    var duration_value = duration.value;
					if(distance_in_kilo < 20){
						var a = 200.00;
						var textbox1 = document.getElementById('textbox1');
						textbox1.value = a.toFixed(2) +" " + "บาท";
						dprice.value = a.toFixed(2);
						//$('#in_kilometre').value("100 Baht");
						//document.getElementById("demo").innerHTML = a +" "+ "บาท";
					}
					else{
						var a = (distance_in_kilo - 20) / (10) * (60) + 200;
						var textbox1 = document.getElementById('textbox1');
						textbox1.value = a.toFixed(2) +" " + "บาท";
						dprice.value = a.toFixed(2);
						//$('#in_kilometre').value(total.toFixed(2));
						
					}
					document.getElementById("demo").innerHTML = a.toFixed(2) +" "+ "บาท";
					
					var pp = document.getElementById('demop').value;
					var bb = document.getElementById('dprice').value;
					var mm = +pp + +bb;
					document.getElementById("demoprice").innerHTML = mm.toFixed(2) +" "+ "บาท";
					
                    $('#in_mile').text(distance_in_mile.toFixed(2));
                    $('#in_kilo').text(distance_in_kilo.toFixed(2));
                    $('#duration_text').text(duration_text);
                    $('#duration_value').text(duration_value);
                    $('#from').text(origin);
                    $('#to').text(destination);
                }
            }
        }
        // print results on submit the form
		$("#calpr").click(function(e) {
            e.preventDefault();
            calculateDistance();
			//document.body.scrollTop = document.documentElement.scrollTop = 0;
        });
        /*$('#distance_form').calpr(function(e){
            e.preventDefault();
            calculateDistance();
        });*/

    });

</script>

<script type="text/javascript">

    window.onload = function () {
		
        var mapOptions = {
            center: new google.maps.LatLng(18.560920,99.063794),
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var marker;
        var infoWindow = new google.maps.InfoWindow();
        var latlngbounds = new google.maps.LatLngBounds();
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        google.maps.event.addListener(map, 'click', function (e) {
            $("input[name='location']").val(e.latLng.lat() + "," + e.latLng.lng());
			$('#calpr').attr('disabled', false);
            var myLatLng = {lat: e.latLng.lat(), lng: e.latLng.lng()};

            if ( marker ) {
                marker.setPosition(myLatLng);
            } else {
                marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: 'จุดที่เลือก'
                });
            }
        });
    }


</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYgr4Io1KMLmr6OZ5RrsimQk7c9V7RAww&callback=initMap">
</script>

</body>
</html>

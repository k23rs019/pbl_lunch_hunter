<main>
    <h2>店舗登録</h2>
    <?php
    $error = $_SESSION['error'] ?? false;
    if ($error == true) {
        echo '<h2 style="color:red">必須項目が未入力です</h2>';
    }
    ?>
    <form action="?do=rst_save" method="post" enctype="multipart/form-data">

        <div class="registration-container">

            <div class="left-col">
                <div class="form-group">
                    <label for="store_name">店舗名</label>
                    <span class="required-star">*必須</span>
                    <input type="text" id="store_name" name="store_name" required>
                </div>

                <div class="form-group">
                    <label for="address">住所</label>
                    <span class="required-star">*必須</span>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label>定休日</label>
                    <span class="required-star">*必須</span><br>
                    <label><input type="checkbox" name="holiday[]" value=1> 日</label>
                    <label><input type="checkbox" name="holiday[]" value=2> 月</label>
                    <label><input type="checkbox" name="holiday[]" value=4> 火</label>
                    <label><input type="checkbox" name="holiday[]" value=8> 水</label>
                    <label><input type="checkbox" name="holiday[]" value=16> 木</label>
                    <label><input type="checkbox" name="holiday[]" value=32> 金</label>
                    <label><input type="checkbox" name="holiday[]" value=64> 土</label>
                    <label><input type="checkbox" name="holiday[]" value="年中無休"> 年中無休</label>
                    <label><input type="checkbox" name="holiday[]" value="未定"> 未定</label>
                </div>

                <div class="form-group">
                    <label>営業時間</label>
                    <span class="required-star">*必須</span><br>
                    <select name="open_time" required>
                        <option value="0:00">0:00</option>
                        <option value="0:30">0:30</option>
                        <option value="1:00">1:00</option>
                        <option value="1:30">1:30</option>
                        <option value="2:00">2:00</option>
                        <option value="2:30">2:30</option>
                        <option value="3:00">3:00</option>
                        <option value="3:30">3:30</option>
                        <option value="4:00">4:00</option>
                        <option value="4:30">4:30</option>
                        <option value="5:00">5:00</option>
                        <option value="5:30">5:30</option>
                        <option value="6:00">6:00</option>
                        <option value="6:30">6:30</option>
                        <option value="7:00">7:00</option>
                        <option value="7:30">7:30</option>
                        <option value="8:00">8:00</option>
                        <option value="8:30">8:30</option>
                        <option value="9:00" selected>9:00</option>
                        <option value="9:30">9:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                        <option value="22:00">22:00</option>
                        <option value="22:30">22:30</option>
                        <option value="23:00">23:00</option>
                        <option value="23:30">23:30</option>
                        <option value="24:00">24:00</option>
                    </select>

                    <select name="close_time" required>
                        <option value="0:00">0:00</option>
                        <option value="0:30">0:30</option>
                        <option value="1:00">1:00</option>
                        <option value="1:30">1:30</option>
                        <option value="2:00">2:00</option>
                        <option value="2:30">2:30</option>
                        <option value="3:00">3:00</option>
                        <option value="3:30">3:30</option>
                        <option value="4:00">4:00</option>
                        <option value="4:30">4:30</option>
                        <option value="5:00">5:00</option>
                        <option value="5:30">5:30</option>
                        <option value="6:00">6:00</option>
                        <option value="6:30">6:30</option>
                        <option value="7:00">7:00</option>
                        <option value="7:30">7:30</option>
                        <option value="8:00">8:00</option>
                        <option value="8:30">8:30</option>
                        <option value="9:00">9:00</option>
                        <option value="9:30">9:30</option>
                        <option value="10:00">10:00</option>
                        <option value="10:30">10:30</option>
                        <option value="11:00">11:00</option>
                        <option value="11:30">11:30</option>
                        <option value="12:00">12:00</option>
                        <option value="12:30">12:30</option>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                        <option value="17:00">17:00</option>
                        <option value="17:30">17:30</option>
                        <option value="18:00">18:00</option>
                        <option value="18:30">18:30</option>
                        <option value="19:00">19:00</option>
                        <option value="19:30">19:30</option>
                        <option value="20:00">20:00</option>
                        <option value="20:30">20:30</option>
                        <option value="21:00">21:00</option>
                        <option value="21:30">21:30</option>
                        <option value="22:00" selected>22:00</option>
                        <option value="22:30">22:30</option>
                        <option value="23:00">23:00</option>
                        <option value="23:30">23:30</option>
                        <option value="24:00">24:00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tel">電話番号</label>
                    <span class="required-star">*必須</span><br>
                    <input type="tel" name="tel_part1" size="3" required> -
                    <input type="tel" name="tel_part2" size="4" required> -
                    <input type="tel" name="tel_part3" size="4" required>
                </div>

                <div class="form-group">
                    <label>ジャンル</label>
                    <span class="required-star">*必須</span><br>
                    <label><input type="checkbox" name="genre[]" value="うどんラーメン"> うどん</label>
                    <label><input type="checkbox" name="genre[]" value="うどんラーメン"> ラーメン</label>
                    <label><input type="checkbox" name="genre[]" value="その他麺類"> その他麺類</label>
                    <label><input type="checkbox" name="genre[]" value="ファストフード"> ファストフード</label>
                    <label><input type="checkbox" name="genre[]" value="和食"> 和食</label>
                    <label><input type="checkbox" name="genre[]" value="洋食"> 洋食</label>
                    <label><input type="checkbox" name="genre[]" value="定食"> 定食</label>
                    <label><input type="checkbox" name="genre[]" value="焼肉"> 焼肉</label>
                    <label><input type="checkbox" name="genre[]" value="中華"> 中華</label>
                    <label><input type="checkbox" name="genre[]" value="カレー"> カレー</label>
                    <label><input type="checkbox" name="genre[]" value="その他"> その他</label>
                </div>

            </div>
            <div class="right-col">

                <div class="form-group">
                    <label>支払い方法</label>
                    <span class="required-star">*必須</span><br>
                    <label><input type="checkbox" name="payment[]" value="現金"> 現金</label>
                    <label><input type="checkbox" name="payment[]" value="QRコード"> QRコード</label>
                    <label><input type="checkbox" name="payment[]" value="電子マネー"> 電子マネー</label>
                    <label><input type="checkbox" name="payment[]" value="クレジットカード"> クレジットカード</label>
                </div>

                <div class="form-group">
                    <label for="url">URL</label>
                    <span class="optional-hash">#任意</span>
                    <input type="url" id="url" name="url">
                </div>

                <div class="form-group">
                    <label for="photo_file">写真</label>
                    <span class="optional-hash">#任意</span><br>
                    <input type="file" id="photo_file" name="photo_file" accept="image/*">
                </div>

                <div class="form-group" style="margin-top:10px;">
                    <img id="preview_img" src="" alt="選択した写真のプレビュー" style="max-width:200px; display:none; border:1px solid #ccc;" />
                    <button id="delete_btn" type="button" style="display:none; margin-left:10px;">削除</button>
                </div>

                <script>
                    const fileInput = document.getElementById("photo_file");
                    const previewImg = document.getElementById("preview_img");
                    const deleteBtn = document.getElementById("delete_btn");

                    fileInput.addEventListener("change", function(event) {
                        const file = event.target.files[0];
                        if (file && file.type.startsWith("image/")) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImg.src = e.target.result;
                                previewImg.style.display = "inline-block";
                                deleteBtn.style.display = "inline-block";
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    deleteBtn.addEventListener("click", function() {
                        previewImg.src = "";
                        previewImg.style.display = "none";
                        deleteBtn.style.display = "none";
                        fileInput.value = "";
                    });
                </script>

            </div>

            <button type="submit" name="register" style="float: right; margin-right: 10px;">登録</button>

    </form>

</main>
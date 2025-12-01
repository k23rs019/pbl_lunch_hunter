<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Lunch Hunter - 店舗編集・削除</title>
    </head>
<body>
    <?php
    require_once 'pg_header.php';
    ?>
    <main>
        <button onclick="location.href='store_list.php'">戻る</button>
        <h2>店舗詳細情報編集・削除</h2>
        
        <form action="edit_delete_store.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="store_id" value="<?php echo htmlspecialchars($store_data['id']); ?>">
            
            <section id="details">
                <div>
                    <label for="store_name">店舗名 *必須</label>
                    <input type="text" id="store_name" name="store_name" value="<?php echo htmlspecialchars($store_data['name']); ?>" required maxlength="30">
                </div>
                
                <div>
                    <label for="address">住所 *必須</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($store_data['address']); ?>" required>
                </div>

                <div>
                    <p>支払い方法 *必須</p>
                    <label><input type="checkbox" name="payment[]" value="cash" <?php echo in_array('cash', $store_data['payment']) ? 'checked' : ''; ?>> 現金</label>
                    <label><input type="checkbox" name="payment[]" value="card" <?php echo in_array('card', $store_data['payment']) ? 'checked' : ''; ?>> カード</label>
                    </div>
                
                <div>
                    <label for="photo">写真 (外部) #任意</label>
                    <input type="file" id="photo" name="photo">
                    </div>
            </section>
            
            <button type="submit" name="action" value="update">更新</button>
            
            <button type="submit" name="action" value="delete" onclick="return confirm('本当にこの店舗情報を削除しますか？');">削除</button>
        </form>
    </main>
</body>
</html>
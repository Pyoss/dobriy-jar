<?php
?>
<script lang="javascript" src="/local/templates/dobriy_jar_template/js/xlsx.mini.js"></script>
<script lang="javascript" src="/local/templates/dobriy_jar_template/js/FileSaver.min.js"></script>
<div class="center-content">
    <div>
    <label for="Text1">ID товаров:
    </label>
    <textarea id="Text1" name="Text1" cols="40" rows="5"></textarea>
    </div>
    <div>
    <label for="start">Дата выгрузки:</label>
    <input type="date" id="start" name="trip-start">
    </div>
    <div>

    <label for="mplc-choice">Выбор маркетплейса:</label>
    <select id="mplc-choice" name="mplc-choice">
        <option value="wb" name="mplc-choice">Вайлдберриз</option>
        <option value="oz" name="mplc-choice">Озон</option>
    </select>
    </div>
    <div>

    <button id="test_button">
        КНОПКА
    </button>
</div>

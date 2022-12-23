<template>
  <head>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
  </head>
  <div class="footer">
    <hr />
    <div class="footer container-fluid">
      <div class="row">
        <div class="col-lg-4 col-xs-12">
          <h4>Структура:</h4>
          <dl>
            <dd>Ректорат</dd>
            <dd>Вчена рада</dd>
            <dd>Департаменти</dd>
          </dl>
        </div>
        <div class="col-lg-4 col-xs-12">
          <h4>Можливості освіти:</h4>
          <dl>
            <dd>Дистанційна освіта</dd>
            <dd>Академічна мобільність</dd>
            <dd>Перепідготовка (друга вища освіта)</dd>
          </dl>
        </div>
        <div class="col-lg-4 col-xs-12">
          <h4>Для користувачів:</h4>
          <dl>
            <dd>Школяру</dd>
            <dd>Вступнику</dd>
            <dd>Першокурснику</dd>
          </dl>
        </div>
      </div>
      <div class="row justify-content-center bg">
        <div class="col-1">
          <i class="fa-solid fa-phone"></i>
          <a href="tel:+79150000000"> Our phone number</a>
        </div>
        <div class="col-1">
          <i class="fa-solid fa-envelope"></i>
          <a href="mailto:ruslana@gmail.com">E-mail</a>
        </div>
        <div class="col-1">
          <i class="fa-brands fa-telegram"></i>
          <a href="https://t.me/laparuslana">Telegram</a>
        </div>
        <div class="col-1">
          <i class="fa-brands fa-whatsapp"></i>
          <a href="https://wa.me/+79150000000">Whatsapp</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Footer-component",
};
</script>

<style>
i {
  color: black;
}
</style>
